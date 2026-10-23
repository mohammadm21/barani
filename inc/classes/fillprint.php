<?php
/**
 * FillprintMatcher - device fingerprint similarity engine.
 * Adapted to BaraniChat's MysqliDb ($sql) instead of PDO.
 * Higher-weighted signals (hard to change) dominate the score.
 */
class FillprintMatcher {

    // Signal weights, out of 100.
    const WEIGHTS = [
        'canvas_fp'            => 20,
        'webgl_renderer'       => 18,
        'webgl_vendor'         => 6,
        'audio_fp'             => 15,
        'fonts'                => 15,
        'hardware_concurrency' => 6,
        'device_memory'        => 5,
        'platform'             => 5,
        'screen_width_height'  => 4,
        'timezone'             => 3,
        'user_agent_family'    => 3,
    ];

    public static function similarity(array $a, array $b) {
        $score = 0;
        $maxScore = 0;

        $maxScore += self::WEIGHTS['canvas_fp'];
        if (!empty($a['canvas_fp']) && $a['canvas_fp'] === $b['canvas_fp']) {
            $score += self::WEIGHTS['canvas_fp'];
        }

        $maxScore += self::WEIGHTS['webgl_renderer'];
        $gl = isset($b['webgl_renderer']) ? $b['webgl_renderer'] : null;
        if (!empty($a['webgl_renderer']) && $a['webgl_renderer'] === $gl
            && !in_array($a['webgl_renderer'], ['none', 'blocked', 'error'])) {
            $score += self::WEIGHTS['webgl_renderer'];
        }

        $maxScore += self::WEIGHTS['webgl_vendor'];
        if (!empty($a['webgl_vendor']) && $a['webgl_vendor'] === (isset($b['webgl_vendor']) ? $b['webgl_vendor'] : null)) {
            $score += self::WEIGHTS['webgl_vendor'];
        }

        $maxScore += self::WEIGHTS['audio_fp'];
        if (!empty($a['audio_fp']) && $a['audio_fp'] === (isset($b['audio_fp']) ? $b['audio_fp'] : null)
            && !in_array($a['audio_fp'], ['unsupported', 'timeout', 'error'])) {
            $score += self::WEIGHTS['audio_fp'];
        }

        $maxScore += self::WEIGHTS['fonts'];
        $fontsA = array_filter(explode(',', isset($a['fonts']) ? $a['fonts'] : ''));
        $fontsB = array_filter(explode(',', isset($b['fonts']) ? $b['fonts'] : ''));
        if (!empty($fontsA) && !empty($fontsB)) {
            $score += self::jaccardSimilarity($fontsA, $fontsB) * self::WEIGHTS['fonts'];
        }

        $maxScore += self::WEIGHTS['hardware_concurrency'];
        if (isset($a['hardware_concurrency']) && $a['hardware_concurrency'] == (isset($b['hardware_concurrency']) ? $b['hardware_concurrency'] : null)) {
            $score += self::WEIGHTS['hardware_concurrency'];
        }

        $maxScore += self::WEIGHTS['device_memory'];
        if (isset($a['device_memory']) && $a['device_memory'] == (isset($b['device_memory']) ? $b['device_memory'] : null)) {
            $score += self::WEIGHTS['device_memory'];
        }

        $maxScore += self::WEIGHTS['platform'];
        if (!empty($a['platform']) && $a['platform'] === (isset($b['platform']) ? $b['platform'] : null)) {
            $score += self::WEIGHTS['platform'];
        }

        $maxScore += self::WEIGHTS['screen_width_height'];
        if ((isset($a['screen_width']) ? $a['screen_width'] : null) == (isset($b['screen_width']) ? $b['screen_width'] : null)
            && (isset($a['screen_height']) ? $a['screen_height'] : null) == (isset($b['screen_height']) ? $b['screen_height'] : null)) {
            $score += self::WEIGHTS['screen_width_height'];
        }

        $maxScore += self::WEIGHTS['timezone'];
        if (!empty($a['timezone']) && $a['timezone'] === (isset($b['timezone']) ? $b['timezone'] : null)) {
            $score += self::WEIGHTS['timezone'];
        }

        $maxScore += self::WEIGHTS['user_agent_family'];
        if (self::uaFamily(isset($a['user_agent']) ? $a['user_agent'] : '') === self::uaFamily(isset($b['user_agent']) ? $b['user_agent'] : '')) {
            $score += self::WEIGHTS['user_agent_family'];
        }

        return $maxScore > 0 ? round(($score / $maxScore) * 100, 2) : 0;
    }

    private static function jaccardSimilarity(array $setA, array $setB) {
        $intersection = count(array_intersect($setA, $setB));
        $union = count(array_unique(array_merge($setA, $setB)));
        return $union > 0 ? $intersection / $union : 0;
    }

    private static function uaFamily($ua) {
        $ua = (string)$ua;
        $browser = 'unknown';
        $os = 'unknown';
        if (stripos($ua, 'Firefox') !== false) $browser = 'Firefox';
        elseif (stripos($ua, 'Edg') !== false) $browser = 'Edge';
        elseif (stripos($ua, 'Chrome') !== false) $browser = 'Chrome';
        elseif (stripos($ua, 'Safari') !== false) $browser = 'Safari';

        if (stripos($ua, 'Windows') !== false) $os = 'Windows';
        elseif (stripos($ua, 'Mac OS') !== false) $os = 'macOS';
        elseif (stripos($ua, 'Android') !== false) $os = 'Android';
        elseif (stripos($ua, 'iPhone') !== false || stripos($ua, 'iPad') !== false) $os = 'iOS';
        elseif (stripos($ua, 'Linux') !== false) $os = 'Linux';
        return "$browser|$os";
    }

    /**
     * Best similarity of a new record against known abusers.
     * Uses the global MysqliDb ($sql). Returns array(score, id).
     */
    public static function findBestMatch($sql, array $newRecord) {
        $candidates = $sql
            ->where('is_confirmed_abuser', 1)
            ->orderBy('created_at', 'DESC')
            ->get('fingerprints', 500);

        $best = array('score' => 0, 'id' => null);
        foreach ($candidates as $candidate) {
            $score = self::similarity($newRecord, $candidate);
            if ($score > $best['score']) {
                $best = array('score' => $score, 'id' => $candidate['id']);
            }
        }
        return $best;
    }
}

/**
 * FillprintNotify - pushes a system PM about a suspicious user to online admins.
 * Mirrors the "پلیس مخفی" admin-notification pattern in message.php.
 */
class FillprintNotify {

    public static function toAdmins($userName, $ip, $score, $action) {
        global $sql, $options, $defualtfontname, $defualtfontsize, $systemname;

        $allow_admins = isset($options['useroptions'])
            ? json_decode($options['useroptions'], true)
            : array();

        $actions = array(
            'blocked'    => 'اخراج و مسدود شد',
            'private'    => 'محدود به چت خصوصی',
            'admin_only' => 'محدود به پیام به مدیر',
            'none'       => 'در حال مشاهده',
        );
        $actionText = isset($actions[$action]) ? $actions[$action] : $action;

        $note = sprintf(
            '<b><font color=red>کاربر مشکوک</font></b> تشخیص داده شد.<br>کاربر: %s<br>IP: %s<br>شباهت: %s ٪<br>اقدام: %s',
            htmlspecialchars($userName, ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($ip, ENT_QUOTES, 'UTF-8'),
            $score,
            $actionText
        );

        $admins = $sql->where('admin', '0', '!=')->get('onlines', null, array('id', 'type', 'roomid'));
        foreach ($admins as $row) {
            if ($row['roomid'] == '1') {
                $row['roomid'] = '-1';
            }
            $perm = isset($allow_admins['send_robo2private_admins'])
                && is_array($allow_admins['send_robo2private_admins'])
                ? $allow_admins['send_robo2private_admins'] : array();
            if (in_array($row['type'], $perm)) {
                $sql->insert('message', array(
                    'user' => -1,
                    'username' => 'SYSTEM [ <b><font color=red>اثر انگشت</font></b> ]',
                    'color' => 'defaultcolor()',
                    'fontname' => $defualtfontname,
                    'fontsize' => $defualtfontsize,
                    'room' => $row['roomid'],
                    'text' => $note,
                    'date' => $_SERVER['REQUEST_TIME'],
                    'to' => $row['id'],
                    'into' => 'pm',
                    'type' => 'system',
                ));
            }
        }
    }
}
