<?php
header::toJson();
$myid = (int)session('id');
if (!$myid) { echo(json_encode(array('status' => 'error'))); exit; }

function lasthist_isSystemRow($row) {
    // SYSTEM usernames are not shown (user=-1 already excluded at query level)
    if (stripos($row['username'], 'SYSTEM') !== false) return true;
    // Special JSON/system placeholders
    $txt = $row['text'];
    if ($txt == 'request' || $txt == 'false' || $txt == 'true') return true;
    return false;
}

function lasthist_publicRows($sql, $table, $limit) {
    // Non-system, public (to == -1) rows, newest first.
    // Fetch extra rows so filtering system ones still fills the limit.
    $all = array();
    $result = $sql
        ->where('to', '-1')
        ->where('user', '-1', '!=')
        ->orderBy('id', 'DESC')
        ->get($table, $limit * 2, array('id', 'user', 'username', 'color', 'room', 'text', 'date', 'into', 'type'));
    foreach ($result as $row) {
        if (lasthist_isSystemRow($row)) continue;
        $all[] = $row;
        if (count($all) >= $limit) break;
    }
    return $all;
}

// Collect up to 50 public, non-system messages (message first, then archive)
$rows = lasthist_publicRows($sql, 'message', 50);
$need = 50 - count($rows);
if ($need > 0) {
    $archive = lasthist_publicRows($sql, 'message_archive', $need);
    $rows = array_merge($rows, $archive);
}

// Fetch avatars for all users in one go (real avatar lives in `profiles`)
$uids = array();
foreach ($rows as $row) { if ((int)$row['user'] > 0) $uids[] = (int)$row['user']; }
$uids = array_unique($uids);
$avatar_map = array();
$gender_map = array();
if (!empty($uids)) {
    // profiles.avatar = real uploaded avatar (e.g. 1_1786299735.jpg)
    $sql->where('id', $uids, 'in');
    $profiles = $sql->get('profiles', null, array('id', 'avatar', 'gender'));
    foreach ($profiles as $p) {
        $gender_map[(int)$p['id']] = $p['gender'];
        $av = $options['defaultavatar'];
        if (!empty($p['avatar']) && $p['avatar'] != 'photo.png' && is_file('theme/images/avatars/' . $p['avatar'])) {
            $av = $p['avatar'];
        } else {
            $av = ($p['gender'] == '1') ? 'maledefualt.png' : 'femaledefualt.jpg';
        }
        $avatar_map[(int)$p['id']] = $av;
    }
    // Fallback: users without a profiles row
    $missing = array();
    foreach ($uids as $uid) {
        if (!isset($avatar_map[$uid])) $missing[] = $uid;
    }
    if (!empty($missing)) {
        $sql->where('id', $missing, 'in');
        $onlines = $sql->get('onlines', null, array('id', 'gender', 'type', 'inputs'));
        foreach ($onlines as $ol) {
            $g = isset($gender_map[(int)$ol['id']]) ? $gender_map[(int)$ol['id']] : $ol['gender'];
            $av = $options['defaultavatar'];
            $ol_inp = json_decode($ol['inputs'], true);
            if (is_array($ol_inp) && isset($ol_inp['avatar']) && $ol_inp['avatar'] != 'photo.png' && is_file('theme/images/avatars/' . $ol_inp['avatar'])) {
                $av = $ol_inp['avatar'];
            } else {
                $av = ($g == '1') ? 'maledefualt.png' : 'femaledefualt.jpg';
            }
            $avatar_map[(int)$ol['id']] = $av;
        }
    }
}

$messages = array();
foreach ($rows as $row) {
    $avatar = isset($avatar_map[(int)$row['user']]) ? $avatar_map[(int)$row['user']] : $options['defaultavatar'];
    $messages[] = array(
        'id' => (int)$row['id'],
        'user_id' => (int)$row['user'],
        'username' => $row['username'],
        'text' => $row['text'],
        'color' => $row['color'],
        'avatar' => $avatar,
        'time' => jdate::start('H:i', $row['date']),
        'date' => jdate::start('Y/m/d', $row['date']),
        'isSystem' => 0
    );
}
$messages = array_reverse($messages); // oldest first

// پینِ فعال فعلی برای نمایش بنرِ استیکی در بالای چت عمومی به کاربرِ تازه‌وارد.
$current_pin = 0;
try {
    $pin_row = $sql->where('active', 1)->getOne('pinned', array('id','username','name','message'));
    if ($pin_row) {
        $current_pin = array('id'=>(int)$pin_row['id'], 'username'=>$pin_row['username'], 'name'=>$pin_row['name'], 'message'=>$pin_row['message']);
    }
    unset($pin_row);
} catch (Exception $e) {
    // جدول pinned هنوز ساخته نشده (migration اعمال نشده)؛ چت عادی بدون پین ادامه می‌یابد.
    $current_pin = 0;
}

echo(json_encode(array('status' => 'ok', 'messages' => $messages, 'pin' => $current_pin), JSON_UNESCAPED_UNICODE));
?>
