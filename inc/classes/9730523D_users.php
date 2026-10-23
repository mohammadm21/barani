<?php

class Users {

    static function setOnline($time = null, $lastcheck = null, $inputs = null) {
        if ($time == null) {
            $time = $_SERVER['REQUEST_TIME'];
        } else {
            $time = "'$time'";
        }
        if ($lastcheck != null) {
            $lastcheck = ", `lastcheck`='$lastcheck'";
        }
        if ($inputs != null) {
            $inputs = json_encode($inputs, JSON_UNESCAPED_UNICODE);
            $inputs = ", `inputs`='$inputs'";
        }
        db::update('onlines', "`time`=$time, `ip`='" . ip() . "'$lastcheck $inputs", "`id`='" . session('id') . "'");
        db::close();
    }

    static function addOnline($id, $username, $name, $gender, $type, $hide, $roomid, $roomname, $visualuser = false) {
        if ($visualuser == false) {
            $time = $_SERVER['REQUEST_TIME'];
            $ip=ip();
        } else {
            $time = "'0'";
            $ip="0";
        }
        if (db::num('onlines', "`id`='$id'") < 1) {
            db::insert('onlines', '`id`, `username`, `name`, `gender`, `time`, `type`, `hide`, `roomid`, `roomname`, `ip`', "'$id', '$username', '$name', '$gender', $time, '$type', '$hide', '$roomid', '$roomname', '".$ip."'");
            self::Inputs($id, 'newuser');
        } else {
            db::update('onlines', "`time`=$time", "`id`='$id'");
        }
        db::close();
    }

    static function CheckBann() {
        return(db::num('banns', "`ip`='" . ip() . "'") > 0 ? true : false);
    }

    static function CheckProfile($id = null) {
        if ($id == null) {
            $id = session('id');
        }
        if (db::num('profiles', "`id`='$id'") == 0) {
            if ($user = db::get('users', "*", "`id`='$id'")) {
                db::insert('profiles', "`id`, `gender`, `birthdate`, `registerdate`, `ip`, `avatar`, `info`, `site`, `lastview`, `online`, `country`, `city`, `active`", "'$id', '{$user['gender']}', '0|1', UNIX_TIMESTAMP(), '{$user['ip']}', 'photo.png', '', '{$GLOBALS['site_url'][0]}', UNIX_TIMESTAMP(), '1', 'iran', '', '1'");
            }
        }
        db::close();
    }

    static function CheckId($id) {
        return(db::num('users', "`id`='$id'") > 0 ? true : false);
    }

    static function Checkrunning($name, $rename = false) {
        global $options;
        $column = $rename ? 'name' : 'username';
        return(db::num('onlines', "`$column`='$name'") or db::num('users', "`$column`='$name' and `type`!='{$options['defaultusertype']}'") ? true : false);
    }

    static function addUser($username, $name, $type, $gender, $email = '', $password = '', $mob = '') {
        db::insert('users', '`username`, `name`, `type`,`active`, `ip`, `gender`, `online`, `lastview`, `lastlogin`, `email`, `password` , `mobile`', "'$username', '$name', '$type', '1', '{$_SERVER["REMOTE_ADDR"]}', '$gender', '1', UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), '$email', '$password' , '$mob'");
        $user = db::$id;
        // db::insert('profiles', "`id`, `gender`, `birthdate`, `registerdate`, `ip`, `avatar`, `info`, `site`, `lastview`, `online`, `country`, `city`", "'$user', '$gender', '0|1', UNIX_TIMESTAMP(), '{$_SERVER["REMOTE_ADDR"]}', 'photo.png', '', '{$GLOBALS['site_url'][0]}', UNIX_TIMESTAMP(), '1', 'ایران', ''");
        db::close();
        return($user);
    }

    static function get($re, $id = '') {
        if ($id == '0') {
            return;
        }
        if ($id == '') {
            $id = session('id');
        }
        if ($re != '*') {
            $col = "`$re`";
        } else {
            $col = '*';
        }
        $result = db::get('users', $col, "`id`='$id'");
        db::close();
        return $result[0];
    }

    static function getOnlines($re, $id = '') {
        if ($id == '0') {
            return;
        }
        if ($id == '') {
            $id = session('id');
        }
        if ($re != '*') {
            $col = "`$re`";
        } else {
            $col = '*';
        }
        $result = db::get('onlines', $col, "`id`='$id'");
        return $result;
    }

    static function getByName($re, $name = '') {
        if ($name == '0') {
            return;
        }
        if ($re != '*') {
            $col = "`$re`";
        } else {
            $col = '*';
        }
        $result = db::get('users', $col, "`username`='$name'");
        db::close();
        return $result[0];
    }

    static function bann($id, $date, $reason) {
        db::delete('banns', "`user`='$id'");
        $user = db::get('users', '`name`,`ip`', "`id`='$id'");
        $ip = $user['ip'];
        $name = $user['name'];
        db::insert('banns', '`ip`, `date`, `to`, `author`, `user`, `reason`', "'$ip', UNIX_TIMESTAMP(), '$date', '" . session('id') . "', '$id', '$reason'");
        $inputs = self::Inputs($id);
        $inputs['benned'] = '1';
        self::Inputs($id, $inputs);
        db::update('users', "`active`='0',`online`='0'", "`id`='$id'");
        db::update('profiles', "`active`='0'", "`id`='$id'");
        // Flag this user's latest device fingerprint as a confirmed abuser so
        // returning devices are detected even under a new account.
        try {
            global $sql;
            $fp = $sql->where('session_ref', (string)$id)->orderBy('id', 'DESC')->getOne('fingerprints', array('id'));
            if ($fp) {
                $sql->where('id', $fp['id'])->update('fingerprints', array('is_confirmed_abuser' => 1));
            }
        } catch (Exception $e) {
            // table may not exist yet / offline path; ban itself still succeeds
        }
        // Ask this user's device to immediately send its fingerprint so we always
        // have a confirmed-abuser row to match a same-device re-entry against —
        // even if no fingerprint was stored at ban time (e.g. banned while on the
        // mobile /phone theme).
        //
        // The directive is persisted in TWO places:
        //   1. onlines.inputs.fp_capture  -> read by the still-open client poll.
        //   2. ahyir_fp_capture row       -> DURABLE. benned.php DELETEs the
        //      onlines row, so #1 alone would be destroyed before the client
        //      could act on it. The table row survives the delete AND a re-login
        //      with a new IP, so the directive is re-served on the next poll.
        try {
            global $sql;
            $inputs = self::Inputs($id);
            if (is_array($inputs)) {
                $inputs['fp_capture'] = '1';
                self::Inputs($id, $inputs);
            }
            if (!$sql->tableExists('fp_capture')) {
                $sql->rawQuery("CREATE TABLE IF NOT EXISTS `ahyir_fp_capture` (
                  `id` INT AUTO_INCREMENT PRIMARY KEY,
                  `user_id` INT NOT NULL,
                  `capture_token` VARCHAR(40) DEFAULT '',
                  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                  UNIQUE KEY `uq_user` (`user_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
            }
            try {
                $cols2 = $sql->rawQuery("SHOW COLUMNS FROM `ahyir_fp_capture` LIKE 'capture_token'");
                if (empty($cols2)) {
                    $sql->rawQuery("ALTER TABLE `ahyir_fp_capture` ADD COLUMN `capture_token` VARCHAR(40) DEFAULT ''");
                }
            } catch (Exception $e) {
                // ignore
            }
            $cToken = md5(uniqid('fpcap', true) . (string)$id);
            $sql->rawQuery("INSERT INTO `ahyir_fp_capture` (`user_id`, `capture_token`) VALUES ('" . (int)$id . "', '" . $cToken . "')
                ON DUPLICATE KEY UPDATE `capture_token` = '" . $cToken . "', `created_at` = CURRENT_TIMESTAMP");
            // توکنِ ضبط را برای صفحهٔ بن (benned.php) هم ذخیره کن تا کلاینتِ اخراجی
            // بدونِ نگرانی از پاک‌شدنِ session، اثر انگشتش را ارسال کند.
            // توجه: باید همیشه هم‌زمان با ردیفِ fp_capture باشد؛ اگر کاربر چند بار اخراج
            // شود و این توکن جدا بماند، banned.php توکنِ کهنه می‌فرستد که با ردیفِ تازه
            // نمی‌خواند و ضبطِ بن شکست می‌خورد. پس همیشه بازنویسی کن.
            session('bann_capture_token', $cToken);
            // توکنِ دستگاهِ این حساب را به بن متصل کن تا اگر با اکانتی نو/مهمان برگشت
            // (همان مرورگر) شناخته و بلاک شود. ستون fp_token را self-heal می‌کنیم.
            try {
                $cols = $sql->rawQuery("SHOW COLUMNS FROM `ahyir_banns` LIKE 'fp_token'");
                if (empty($cols)) {
                    $sql->rawQuery("ALTER TABLE `ahyir_banns` ADD COLUMN `fp_token` VARCHAR(40) DEFAULT ''");
                }
            } catch (Exception $e) {
                // ignore
            }
            $lastFp = $sql->where('session_ref', (string)$id)->orderBy('id', 'DESC')->getOne('fingerprints', array('device_token'));
            if ($lastFp && !empty($lastFp['device_token'])) {
                $sql->where('user', $id)->update('banns', array('fp_token' => $lastFp['device_token']));
            }
        } catch (Exception $e) {
            // ignore; ban itself already registered
        }
        chatroom::addlog('8', "کاربر " . $name . " را تا " . jdate::start('Y/m/d H:i:s', $date) . " اخراج نمود");
        db::close();
    }

    static function setIP($id) {
        db::update('users', "`ip`='{$_SERVER["REMOTE_ADDR"]}', `lastlogin`= '" . $_SERVER['REQUEST_TIME'] . "', `online`='1'", "`id`='$id'");
        db::close();
    }

    static function hide($id) {
        db::update('onlines', "`hide`='1'", "`id`='$id'");
        db::close();
    }

    static function show($id) {
        db::update('onlines', "`hide`='0'", "`id`='$id'");
        db::close();
    }

    static function AdminsType($type = null) {
        global $usertype;
        $types = array();
        foreach ($usertype as $val) {
            if ($val['admin'] != '0') {
                $types[] = $val['id'];
            }
        }

        if ($type != null) {
            foreach ($types as $t) {
                if ($type == $t) {
                    return true;
                }
            }
            return false;
        } else {
            return $types;
        }
    }

    static function is_admin($type) {
        global $usertype;
        $types = array();
        foreach ($usertype as $val) {
            if ($val['admin'] != '0') {
                $types[] = $val['id'];
            }
        }

        foreach ($types as $t) {
            if ($type == $t) {
                return true;
            }
        }
        return false;
    }

    static function is_type($type) {
        global $usertype;
        return isset($usertype[$type]);
    }

    static function guestType($type = null) {
        global $usertype;
        $types = array();
        foreach ($usertype as $val) {
            if ($val['guest'] == 1) {
                $types[] = $val['id'];
            }
        }
        if ($type != null) {
            foreach ($types as $t) {
                if ($type == $t) {
                    return true;
                }
            }
            return false;
        } else {
            return $types;
        }
    }

    static function Offline($id) {
        global $options;
        if ($online = db::get('onlines', '`name`, `type`, `time`, `inputs`, `score`, `bank`', "`id`='$id'")) {
            if ($online['time'] != 0) {
                $user = db::get('users', "`score`, `bank`, `lastlogin`, `onlinetime`, `type`", "`id`='$id'");
                if ($options['getscoretype'] == 'login') {
                    global $pscores;
                    $onlinetime = $online['time'] - $user['lastlogin'];
                    $score = round($onlinetime / $pscores);
                    $score += $user['score'];
                    if ($options['update_bank'] == "true") {
                        $bank = round($onlinetime / ($options['time_bank'] * 60));
                        $bank += $user['bank'];
                    } else {
                        $bank = $user['bank'];
                    }
                    $onlinetime += $user['onlinetime'];
                    $type = self::changetype($user['type'], $score);
                } elseif ($options['getscoretype'] == 'ajax') {
                    $score = (int) $online['score'];
                    $bank = (int) $online['bank'];
                    $onlinetime = $online['time'] - $user['lastlogin'];
                    $onlinetime += $user['onlinetime'];
                    $type = self::changetype($online['type'], (int) $score);
                }
                db::update('users', "`onlinetime` = '$onlinetime', `online`='0', `lastview`='" . $online['time'] . "', `score`='$score', `bank`='$bank', `type`='$type'", "`id`='$id'");
                db::delete('onlines', "`id`='$id'");
                Message::AddMasageSYS($online['name'] . " از چت روم خارج شد.", '-1');
                chatroom::addlog('2', $online['name'] . " از چت روم خارج شد.", $id);
            }
        }
        db::close();
    }

    static function changetype($type, $score) {

        if (!self::guestType($type) and ! self::AdminsType($type)) {
            global $usertype;
            global $options;
            $types = array();
            foreach ($usertype as $val) {
                if ($val['guest'] == 1) {
                    continue;
                }
                if ($val['admin'] == '1') {
                    continue;
                }
                if ($options['automod'] == 'false' and $val['admin'] != '0') {
                    continue;
                }
                $types[$val['id']] = (int) $val['score'];
            }
            arsort($types);

            foreach ($types as $id => $scoree) {
                if ($score > $scoree) {
                    $type = $id;
                    break;
                }
            }
        }
        return $type;
    }

    static function Checkregister($id) {
        $type = db::get('users', 'type', "`id`='$id'");
        db::close();
        return(self::guestType($type) ? false : true);
    }

    static function lowestlevelregister() {
        global $usertype;
        $types = array();
        foreach ($usertype as $val) {
            if ($val['guest'] == 1) {
                continue;
            }
            if ($val['admin'] != '0') {
                continue;
            }
            $types[$val['id']] = (int) $val['score'];
        }
        asort($types);

        foreach ($types as $key => $val) {
            $val = $usertype[$key];
            if ($val['admin'] == '0' and $val['guest'] == '0' and $val['score'] == '0') {
                return($key);
            }
        }
    }

    static function CheckAdmin($id) {
        $type = db::get('users', 'type', "`id`='$id'");
        db::close();
        return(self::AdminsType($type) ? true : false);
    }

    static function rename($id, $newname) {
        if (!session('id')) {
            die();
        }
        // $carrentName = db::get('users', 'name', "`id`='$id'");
        db::update('users', "`name`='$newname'", "`id`='$id'");
        db::update('onlines', "`name`='$newname'", "`id`='$id'");
        $inputs = self::Inputs($id);
        $inputs['alert'] = 'نام شما توسط یکی از مدیران یا ناظرین عوض شد';
        self::Inputs($id, $inputs);
        //Message::AddMasageSYS("$carrentName نام خود را به $newname تغیر داد", '-1');
        db::close();
    }

    static function autoOffline() {
        global $offlinetime;
        $result = db::query("SELECT `id` FROM `{prefix}onlines` WHERE (`time` < (UNIX_TIMESTAMP()-$offlinetime)) and `time` != '0'");
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            self::Offline($row['id']);
        }
        mysqli_free_result($result);
   