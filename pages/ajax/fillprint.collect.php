<?php
/**
 * fillprint.collect.php
 * Receives a device fingerprint from the client, stores it, scores it against
 * known-confirmed-abuser fingerprints, and applies the configured action.
 *
 * Actions (admin-configurable thresholds, percent similarity):
 *   score >= block_threshold            -> block (kick + prevent re-entry)
 *   private_threshold <= score < block   -> private chat only
 *   admin_threshold <= score < private   -> message admins only
 *   score < admin_threshold              -> normal (logged only)
 *
 * If notify_admin is enabled, a system PM is sent to online admins and an
 * admin_alerts row is written when a suspicious match is found.
 *
 * Request mode `fpCapture: true` is used right after an admin ban: the row is
 * stored directly as a CONFIRMED ABUSER so the returning same-device user is
 * caught on re-entry even if they had no stored fingerprint at ban time.
 */
$input = json_decode(file_get_contents('php://input'), true);
if (!$input || !is_array($input)) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array('status' => 'error', 'message' => 'invalid_input'), JSON_UNESCAPED_UNICODE);
    exit;
}

// ضبطِ هنگامِ بن: کلاینتِ اخراجی ممکن است با session پاک‌شده برسد (توسط benned.php)
// اما توکنِ یک‌بارمصرفِ ضبط (bannCaptureToken) را دارد که در ahyir_fp_capture ذخیره شده.
$bannCaptureUid = 0;
if (!session('id') && !empty($input['bannCaptureToken'])) {
    $ct = mb_substr((string)$input['bannCaptureToken'], 0, 40);
    try {
        $sql->where('capture_token', $ct);
        $pendUid = $sql->getValue('fp_capture', 'user_id');
        if ($pendUid) {
            $bannCaptureUid = (int)$pendUid;
        }
    } catch (Exception $e) {
        $bannCaptureUid = 0;
    }
}

// برای درخواستِ عادی session لازم است؛ اما ضبطِ هنگامِ بن با توکن مجاز است.
if (!session('id') && !$bannCaptureUid) exit('login');

$uid = $bannCaptureUid ? $bannCaptureUid : (int)session('id');
$ip = ip();
// برای ضبطِ هنگامِ بن، session پاک شده؛ اسم را از دیتابیس می‌گیریم تا پیامِ ادمین معنادار باشد.
$fpUserName = session('name') ? session('name') : '';
if ($bannCaptureUid) {
    try { $fpUserName = (string)$sql->where('id', $uid)->getValue('users', 'name'); } catch (Exception $e) { $fpUserName = ''; }
}

// در ضبطِ هنگامِ بن، سمتِ کلاینت همان نقشی را بازی می‌کند که capture عادی؛
// اما چون session ندارد، باید برداشت به‌صورت صریح انجام شود.
if ($bannCaptureUid) {
    $input['fpCapture'] = true;
}

// ---- خوددرمانی: اگه جدول‌های اثر انگشت هنوز ساخته نشده باشند، همین‌جا بساز ----------
try {
    if (!$sql->tableExists('fingerprints')) {
        $sql->rawQuery("CREATE TABLE IF NOT EXISTS `ahyir_fingerprints` (
          `id` INT AUTO_INCREMENT PRIMARY KEY,
          `session_ref` VARCHAR(64) NOT NULL DEFAULT '',
          `ip` VARCHAR(45) NOT NULL DEFAULT '',
          `user_agent` TEXT, `language` VARCHAR(50) DEFAULT '', `languages` VARCHAR(255) DEFAULT '',
          `platform` VARCHAR(100) DEFAULT '', `hardware_concurrency` INT DEFAULT 0, `device_memory` FLOAT DEFAULT 0,
          `max_touch_points` INT DEFAULT 0, `timezone` VARCHAR(100) DEFAULT '', `timezone_offset` INT DEFAULT 0,
          `screen_width` INT DEFAULT 0, `screen_height` INT DEFAULT 0, `screen_color_depth` INT DEFAULT 0, `pixel_ratio` FLOAT DEFAULT 1,
          `canvas_fp` VARCHAR(100) DEFAULT '', `webgl_vendor` VARCHAR(255) DEFAULT '', `webgl_renderer` VARCHAR(255) DEFAULT '',
          `audio_fp` VARCHAR(100) DEFAULT '', `fonts` TEXT, `cookies_enabled` TINYINT DEFAULT 0,
          `do_not_track` VARCHAR(20) DEFAULT '', `plugins` TEXT,
          `browser_hint` VARCHAR(50) DEFAULT '', `browser_signals` VARCHAR(255) DEFAULT '',
          `is_confirmed_abuser` TINYINT DEFAULT 0, `is_flagged` TINYINT DEFAULT 0, `match_score` FLOAT DEFAULT 0,
          `matched_fingerprint_id` INT DEFAULT NULL, `action_taken` VARCHAR(20) DEFAULT '',
          `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
          INDEX `idx_canvas` (`canvas_fp`), INDEX `idx_webgl` (`webgl_renderer`(100)),
          INDEX `idx_confirmed` (`is_confirmed_abuser`), INDEX `idx_user` (`session_ref`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }
    if (!$sql->tableExists('admin_alerts')) {
        $sql->rawQuery("CREATE TABLE IF NOT EXISTS `ahyir_admin_alerts` (
          `id` INT AUTO_INCREMENT PRIMARY KEY, `fingerprint_id` INT NOT NULL, `user_id` INT DEFAULT 0,
          `match_score` FLOAT NOT NULL, `matched_fingerprint_id` INT DEFAULT NULL, `action_taken` VARCHAR(20) DEFAULT '',
          `message` TEXT, `is_read` TINYINT DEFAULT 0, `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
          INDEX `idx_fp` (`fingerprint_id`), INDEX `idx_read` (`is_read`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }
    if (!$sql->tableExists('fp_capture')) {
        $sql->rawQuery("CREATE TABLE IF NOT EXISTS `ahyir_fp_capture` (
          `id` INT AUTO_INCREMENT PRIMARY KEY,
          `user_id` INT NOT NULL,
          `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
          UNIQUE KEY `uq_user` (`user_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }
    // ستون device_token (شناختِ میان‌اکانتیِ دستگاه) را به‌صورت self-heal اضافه کن
    fp_ensure_col($sql, 'ahyir_fingerprints', 'device_token', "ALTER TABLE `ahyir_fingerprints` ADD COLUMN `device_token` VARCHAR(40) DEFAULT ''");
    fp_ensure_col($sql, 'ahyir_banns', 'fp_token', "ALTER TABLE `ahyir_banns` ADD COLUMN `fp_token` VARCHAR(40) DEFAULT ''");
    // ستون‌های تشخیصِ مرورگرِ واقعی (Brave/Tor/UA جعلی/ربات) — فقط اطلاعاتی‌اند،
    // در امتیازدهیِ FillprintMatcher استفاده نمی‌شوند (چون کاربر می‌تواند بین
    // مرورگرها جابه‌جا شود و این باعثِ عدم‌تطبیقِ کاذب می‌شد).
    fp_ensure_col($sql, 'ahyir_fingerprints', 'browser_hint', "ALTER TABLE `ahyir_fingerprints` ADD COLUMN `browser_hint` VARCHAR(50) DEFAULT ''");
    fp_ensure_col($sql, 'ahyir_fingerprints', 'browser_signals', "ALTER TABLE `ahyir_fingerprints` ADD COLUMN `browser_signals` VARCHAR(255) DEFAULT ''");
} catch (Exception $e) {
    // اگر حق ساخت جدول نبود، ادامه می‌دهیم و مسیرِ خودش خطا را مدیریت می‌کند
}

/**
 * Self-heal: اگر ستونِ خواسته‌شده روی جدول نبود، اضافه کن (MySQL 8.0.29+ یا fallback).
 */
function fp_ensure_col($sql, $table, $col, $alterSql) {
    try {
        $cols = $sql->rawQuery("SHOW COLUMNS FROM `$table` LIKE '" . addslashes($col) . "'");
        if (empty($cols)) {
            $sql->rawQuery($alterSql);
        }
    } catch (Exception $e) {
        // ignore
    }
}

$enabled = (isset($options['fillprint_enabled']) && $options['fillprint_enabled'] == 'true');
$isCapture = !empty($input['fpCapture']);

// ---- ساختِ $record زودتر از قبل ----------------------------------------
// نکته مهم: $record باید قبل از بلوکِ تشخیصِ promoteAsAbuser ساخته شود، چون
// بندِ (ج) به $record['device_token'] نیاز دارد. قبلاً این بلوک قبل از تعریفِ
// $record اجرا می‌شد و $record['device_token'] همیشه خالی بود؛ در نتیجه بندِ
// (ج) -یعنی دقیقاً همان مسیری که قرار بود برگشتِ کاربر با اکانت/کوکیِ تازه را
// از روی توکنِ دستگاه شناسایی کند- هیچ‌وقت اجرا نمی‌شد.
$record = array(
    'session_ref'          => (string)$uid,
    'ip'                   => $ip,
    'user_agent'           => isset($input['userAgent']) ? mb_substr($input['userAgent'], 0, 500) : '',
    'language'              => isset($input['language']) ? mb_substr($input['language'], 0, 50) : '',
    'languages'            => isset($input['languages']) ? mb_substr($input['languages'], 0, 255) : '',
    'platform'             => isset($input['platform']) ? mb_substr($input['platform'], 0, 100) : '',
    'hardware_concurrency' => (int)(isset($input['hardwareConcurrency']) ? $input['hardwareConcurrency'] : 0),
    'device_memory'        => (float)(isset($input['deviceMemory']) ? $input['deviceMemory'] : 0),
    'max_touch_points'     => (int)(isset($input['maxTouchPoints']) ? $input['maxTouchPoints'] : 0),
    'timezone'             => isset($input['timezone']) ? mb_substr($input['timezone'], 0, 100) : '',
    'timezone_offset'      => (int)(isset($input['timezoneOffset']) ? $input['timezoneOffset'] : 0),
    'screen_width'         => (int)(isset($input['screenWidth']) ? $input['screenWidth'] : 0),
    'screen_height'        => (int)(isset($input['screenHeight']) ? $input['screenHeight'] : 0),
    'screen_color_depth'   => (int)(isset($input['screenColorDepth']) ? $input['screenColorDepth'] : 0),
    'pixel_ratio'          => (float)(isset($input['pixelRatio']) ? $input['pixelRatio'] : 1),
    'canvas_fp'            => mb_substr(isset($input['canvasFp']) ? $input['canvasFp'] : '', 0, 100),
    'webgl_vendor'         => mb_substr(isset($input['webglVendor']) ? $input['webglVendor'] : '', 0, 255),
    'webgl_renderer'       => mb_substr(isset($input['webglRenderer']) ? $input['webglRenderer'] : '', 0, 255),
    'audio_fp'             => mb_substr(isset($input['audioFp']) ? $input['audioFp'] : '', 0, 100),
    'fonts'                => mb_substr(isset($input['fonts']) ? $input['fonts'] : '', 0, 2000),
    'cookies_enabled'      => !empty($input['cookiesEnabled']) ? 1 : 0,
    'do_not_track'         => mb_substr(isset($input['doNotTrack']) ? $input['doNotTrack'] : '', 0, 20),
    'plugins'              => mb_substr(isset($input['plugins']) ? $input['plugins'] : '', 0, 2000),
    'device_token'         => isset($input['fpToken']) ? mb_substr($input['fpToken'], 0, 40) : '',
    'browser_hint'         => mb_substr(isset($input['browserHint']) ? $input['browserHint'] : '', 0, 50),
    'browser_signals'      => mb_substr(isset($input['browserSignals']) ? $input['browserSignals'] : '', 0, 255),
);

// ---- ضربتِ تضمینی abuser: این دستگاه/حساب در صفِ ضبطِ پس از اخراج است؟ ---------
// پس از اخراج، بنِ همان حساب هنوز فعال است (banns.user) یا ردیفِ ahyir_fp_capture
// برای این کاربر در انتظار ضبط‌دستیِ مدیر است. در این صورت، همین collect به‌عنوان
// ردیفِ abuser ثبت می‌شود (بدونِ وابستگی به threshold)، فرقی با لایهٔ click نیست.
// این، حفرهٔ دورزدنِ «ایپی عوض کن و برگرد» را می‌بندد: اولین ورودِ مجدد همان حساب،
// ردیفِ confirmed abuser را می‌سازد و بنِ جلسه اعمال می‌شود (bind به حساب، نه IP).
// نکته: این بلوک باید بعد از ساختِ کاملِ $record بیاید چون بندِ (ج) به
// $record['device_token'] نیاز دارد (پیش‌تر این ترتیب برعکس بود و device_token
// همیشه خالی می‌رسید، پس بندِ (ج) هیچ‌وقت واقعاً اجرا نمی‌شد).
$promoteAsAbuser = $isCapture;
$pendingAbuser = false;
if ($enabled && !$promoteAsAbuser) {
    try {
        // الف) بنِ فعالِ همین حساب (اکانت یکسان، ای‌پی متفاوت)
        $activeBan = $sql->where('user', $uid)->where("(`to` = '0' OR `to` > " . time() . ")")->getOne('banns', array('id'));
        if (!$activeBan) {
            // ب) ردیفِ ماندگارِ در انتظارِ ضبط اشاره به همین اکانت دارد
            //    توجه: tableExists رشته‌ی where را به $_where اضافه می‌کند؛ پس آن را
            //    قبل از where بگیر تا با where قبلیِ کاربر نجوشد و insert را خراب نکند.
            if ($sql->tableExists('fp_capture')) {
                $sql->where('user_id', $uid);
                $pend = $sql->getOne('fp_capture', array('id'));
                if ($pend) {
                    $activeBan = array('id' => $pend['id']);
                }
            }
        }
        if (!$activeBan && !empty($record['device_token'])) {
            // ج) میان‌اکانتی: توکنِ دستگاهِ این collect با بنِ فعالی که قبلاً همان توکن را
            //    گرفته مطابقت دارد (کاربرِ اخراجی با اکانتی نو/مهمان برگشته — همان مرورگر).
            //    این بند حالا واقعاً اجرا می‌شود چون $record از قبل کامل ساخته شده.
            $dtBan = $sql->where('fp_token', $record['device_token'])
                ->where("(`to` = '0' OR `to` > " . time() . ")")
                ->getOne('banns', array('id'));
            if ($dtBan) {
                $activeBan = array('id' => $dtBan['id']);
            }
        }
        if ($activeBan) {
            $promoteAsAbuser = true;
            $pendingAbuser = true;
        }
    } catch (Exception $e) {
        $promoteAsAbuser = false;
    }
}
$isCapture = $promoteAsAbuser ? true : $isCapture;

// ---- امتیازدهی فقط وقتی فعال باشد و این نه capture باشد ----------
$bestScore = 0.0;
$bestId = null;
$action = 'none';
if ($enabled && !$isCapture) {
    try {
        $match = FillprintMatcher::findBestMatch($sql, $record);
        $bestScore = (float)$match['score'];
        $bestId = $match['id'] !== null ? (int)$match['id'] : null;

        $blockT = (float)(isset($options['fillprint_block_threshold']) ? $options['fillprint_block_threshold'] : 90);
        $privT  = (float)(isset($options['fillprint_private_threshold']) ? $options['fillprint_private_threshold'] : 75);
        $adminT = (float)(isset($options['fillprint_admin_threshold']) ? $options['fillprint_admin_threshold'] : 50);

        if ($bestScore >= $blockT) {
            $action = 'blocked';
        } elseif ($bestScore >= $privT) {
            $action = 'private';
        } elseif ($bestScore >= $adminT) {
            $action = 'admin_only';
        }
    } catch (Exception $e) {
        $bestScore = 0.0;
        $action = 'none';
    }
}

// ---- store record ----
// در حالت capture، ردیف مستقیماً به‌عنوان abuser ثبت می‌شود (دستور ادمین).
$rec = $record;
$rec['is_confirmed_abuser'] = $isCapture ? 1 : 0;
$rec['is_flagged'] = ($isCapture || $bestScore >= (float)(isset($options['fillprint_admin_threshold']) ? $options['fillprint_admin_threshold'] : 50)) ? 1 : 0;
$rec['match_score'] = $bestScore;
$rec['matched_fingerprint_id'] = $bestId;
$rec['action_taken'] = $isCapture ? 'blocked' : $action;
$fpId = 0;
try {
    $fpId = $sql->insert('fingerprints', $rec);
    $fpId = $fpId ? $sql->getInsertId() : 0;
} catch (Exception $e) {
    // اگر ذخیره نشد، ساکت نمان؛ حداقل روی error log بیفتد تا برای مدیر قابل‌ردیابی باشد
    error_log('fillprint.collect: insert failed: ' . $e->getMessage());
    $fpId = 0;
}

// ---- پاک‌کردن فلگ captureِ درخواستیِ ادمین روی inputs کاربر (که دیگر خودش را پس داده) ----
if ($isCapture && $fpId) {
    try {
        if (class_exists('Users')) {
            $cur = Users::Inputs($uid);
            if (is_array($cur)) {
                unset($cur['fp_capture']);
                Users::Inputs($uid, $cur);
            }
        }
        // ردیفِ ماندگارِ در انتظارِ ضبط را هم پس از ضبط پاک کن.
        if ($sql->tableExists('fp_capture')) {
            $sql->where('user_id', $uid)->delete('fp_capture');
        }
        // توکنِ دستگاهِ ضبط‌شده را به بنِ فعالِ همین کاربر متصل کن تا بازگشتِ
        // میان‌اکانتیِ این دستگاه (توکن یکسان) در collectهای بعدی شناخته شود.
        if (!empty($record['device_token'])) {
            try {
                $sql->where('user', $uid)->where("(`to` = '0' OR `to` > " . time() . ")")
                    ->update('banns', array('fp_token' => $record['device_token']));
            } catch (Exception $e) {
                // ignore
            }
        }
    } catch (Exception $e) {
        // ignore
    }
}

// ---- ورودِ مجددِ یک حسابِ اخراجی (pending abuser): فوراً همین جلسه را بیرون بینداز
if ($pendingAbuser && $fpId) {
    try {
        if (class_exists('Users')) {
            // بنِ همان حساب (که هنوز فعال است) را به‌روز کن تا اخراجِ جدید اعمال شود
            $sql->where('user', $uid)->delete('banns');
            Users::bann($uid, 0, 'اثر انگشت یک دستگاه اخراجی دوباره وارد شد');
        }
    } catch (Exception $e) {
        // ignore; ردیفِ abuser قبلاً ذخیره شده و بنِ همان حساب هنوز در banns فعال است
    }
}

// ---- enforcement (فقط برای ورودِ عادیِ فعال، نه capture) ----------
if ($enabled && !$isCapture && $action == 'blocked') {
    try {
        // hard block: prevent re-entry. Put the culprit's latest fingerprint into
        // the reference pool too, and issue a permanent ban.
        if ($fpId) {
            $sql->where('id', $fpId)->update('fingerprints', array('is_confirmed_abuser' => 1));
        }
        if (class_exists('Users')) {
            $banned = $sql->where('id', $uid)->getValue('users', 'id');
            if ($banned) {
                Users::bann($uid, 0, 'اثر انگشت مشکوک (شباهت ' . $bestScore . '٪)');
            }
        }
    } catch (Exception $e) {
        // ignore; alert below still reports
    }
}

// ---- mirror restriction flag into onlines.inputs for message gate ----
if ($enabled && !$isCapture && ($action == 'private' || $action == 'admin_only')) {
    try {
        if (class_exists('Users')) {
            $cur = Users::Inputs($uid);
            if (is_array($cur)) {
                $cur['fp_restrict'] = $action;
                Users::Inputs($uid, $cur);
            }
        }
    } catch (Exception $e) {
        // ignore; fingerprint action_taken remains the source of truth
    }
}

// ---- notify admins + write admin alert ----------
// در حالت capture هم به ادمین اطلاع بده که دستگاهِ اخراجی دوباره آمده.
$notify = (isset($options['fillprint_notify_admin']) && $options['fillprint_notify_admin'] == 'true');
$isFlagged = $isCapture || $bestScore >= (float)(isset($options['fillprint_admin_threshold']) ? $options['fillprint_admin_threshold'] : 50);
if ($isFlagged) {
    try {
        $sql->insert('admin_alerts', array(
            'fingerprint_id'          => $fpId,
            'user_id'                 => $uid,
            'match_score'             => $bestScore,
            'matched_fingerprint_id'  => $bestId,
            'action_taken'            => $isCapture ? 'blocked' : $action,
            'message'                 => 'کاربر مشکوک (اثر انگشت): ' . $fpUserName . '، IP: ' . $ip . '، شباهت: ' . $bestScore . '٪، اکشن: ' . ($isCapture ? 'blocked' : $action),
        ));
    } catch (Exception $e) {
        // admin_alerts optional
    }
    // ---- throttle نوتیفیکیشن: چون اثر انگشت حالا هر چند دقیقه یک‌بار (heartbeat)
    // هم ارسال می‌شود، بدون این throttle یک کاربرِ مشکوکِ آنلاین هر بار یک پیامِ
    // سیستمیِ تازه به مدیرها می‌فرستاد. اینجا فقط اگر در ۱۵ دقیقه‌ی اخیر برای همین
    // uid هشداری ثبت نشده باشد، نوتیفیکیشن واقعی ارسال می‌شود؛ ثبت در admin_alerts
    // (بالا) برای لاگ/تاریخچه همچنان هر بار انجام می‌شود.
    $shouldNotify = true;
    if ($notify) {
        try {
            $recentAlert = $sql->where('user_id', $uid)
                ->where('created_at', date('Y-m-d H:i:s', time() - 15 * 60), '>')
                ->orderBy('id', 'DESC')
                ->getOne('admin_alerts', array('id'));
            if ($recentAlert) {
                $shouldNotify = false;
            }
        } catch (Exception $e) {
            $shouldNotify = true; // اگر چک نشد، برای احتیاط اطلاع بده
        }
    }
    if ($notify && $shouldNotify) {
        try {
            FillprintNotify::toAdmins($fpUserName, $ip, $bestScore, $isCapture ? 'blocked' : $action);
        } catch (Exception $e) {
            // optional
        }
    }
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode(array(
    'status'  => 'ok',
    'flagged' => ($isCapture || $bestScore >= (float)(isset($options['fillprint_admin_threshold']) ? $options['fillprint_admin_threshold'] : 50)) ? 1 : 0,
    'score'   => $bestScore,
    'action'  => $isCapture ? 'blocked' : $action
), JSON_UNESCAPED_UNICODE);