<?php
/**
 * Server-Sent Events (SSE) endpoint for real-time chat messages.
 * Must be accessed directly: /stream.php?room=1
 * NOT through index.php (which outputs HTML before SSE headers).
 */

date_default_timezone_set('Asia/Tehran');
session_start();
require_once('inc/config.php');
require_once('inc/function.php');
require_once('inc/classes.php');

$id = session('id');
if (!$id) {
    http_response_code(401);
    exit();
}

// SSE headers - must be set before ANY output
header('Content-Type: text/event-stream; charset=utf-8');
header('Cache-Control: no-cache');
header('Connection: keep-alive');
header('X-Accel-Buffering: no');
header('Access-Control-Allow-Origin: *');

// Disable output buffering
@ini_set('output_buffering', 'Off');
@ini_set('zlib.output_compression', 'False');
if (function_exists('apache_setenv')) {
    apache_setenv('no-gzip', '1');
}
while (ob_get_level() > 0) {
    ob_end_flush();
}
if (function_exists('ob_implicit_flush')) {
    ob_implicit_flush(true);
}

$sql = new MysqliDb(db_server, db_user, db_pass, db_name);
$sql->setPrefix(db_prefix);

$room = isset($_GET['room']) ? intval($_GET['room']) : session('room');
$last = isset($_GET['last']) ? intval($_GET['last']) : 0;
$timeout = 30;
$start = time();

// Send initial connection event
echo "event: connected\ndata: {\"status\":\"ok\"}\n\n";
@flush();

while (true) {
    if (connection_aborted()) break;

    if ((time() - $start) >= $timeout) {
        echo "event: heartbeat\ndata: {}\n\n";
        @flush();
        $start = time();
    }

    // Query for new messages
    $sql->where("id", $last, '>');
    $sql->where("user", $id, '!=');
    $sql->where("room", array(-1, $room), 'in');
    $sql->orderBy('id', "ASC");
    $result = $sql->get('message', null, array('id', 'user', 'username', 'color', 'fontname', 'fontsize', 'room', 'text', 'date', 'to', 'into', 'type'));

    if (!empty($result)) {
        // Batch-fetch user info
        $user_ids = array();
        foreach ($result as $row) {
            if ($row['to'] == '-1' || $row['to'] == $id) {
                $user_ids[] = $row['user'];
            }
        }
        $user_ids = array_unique($user_ids);
        $online_map = array();
        if (!empty($user_ids)) {
            $sql->where("id", $user_ids, 'in');
            $online_rows = $sql->get('onlines', null, array('id', 'gender', 'type', 'inputs'));
            foreach ($online_rows as $orow) {
                $online_map[$orow['id']] = $orow;
            }
        }

        $messages = array();
        foreach ($result as $row) {
            if ($row['to'] != '-1' && $row['to'] != $id) continue;

            $online = isset($online_map[$row['user']]) ? $online_map[$row['user']] : null;
            $avatar = $options['defaultavatar'];
            $sex = isset($online['gender']) ? $online['gender'] : $options['defaultusertype'];
            $priv = isset($online['type']) ? $online['type'] : $options['defaultusertype'];

            if (isset($online['inputs'])) {
                $inputs = json_decode($online['inputs'], true);
                if (isset($inputs['avatar']) && $inputs['avatar'] != 'photo.png') {
                    if (is_file('theme/images/avatars/' . $inputs['avatar'])) {
                        $avatar = $inputs['avatar'];
                    } else {
                        $avatar = ($online['gender'] == '1') ? 'maledefualt.png' : 'femaledefualt.jpg';
                    }
                } else {
                    $avatar = ($online['gender'] == '1') ? 'maledefualt.png' : 'femaledefualt.jpg';
                }
            }

            $messages[] = array(
                'id' => $row['id'], 'user' => $row['username'], 'user_id' => $row['user'],
                'isSystem' => (int)$row['user'] === -1 ? 1 : 0,
                'message' => $row['text'], 'time' => date('H:i', $row['date']),
                'privat' => ($row['to'] == '-1') ? '0' : $row['user'],
                'css' => "color:#{$row['color']};font-family:{$row['fontname']};font-size:{$row['fontsize']}",
                'priv' => $priv, 'sex' => $sex, 'type' => 'get',
                'cf' => ($row['into'] == 'pm' || $row['into'] == 'main' || $row['into'] == 'allowchat') ? '0' : '1',
                'avatar' => $avatar, 'into' => $row['into'], 'msgid' => $row['id'],
            );
            $last = max($last, $row['id']);
        }

        // Batch-fetch reactions for SSE messages
        if (!empty($messages)) {
            $sids = array();
            foreach ($messages as $m) { $sids[] = (int)$m['msgid']; }
            $sids = array_unique($sids);
            $sql->where('message_id', $sids, 'in');
            $rrows = $sql->get('reactions', null, array('message_id', 'user_id', 'reaction'));
            $rmap = array();
            $all_ruids = array();
            foreach ($rrows as $rr) {
                $mid = (int)$rr['message_id'];
                if (!isset($rmap[$mid])) $rmap[$mid] = array('counts' => array(), 'users' => array());
                $em = $rr['reaction'];
                if (!isset($rmap[$mid]['counts'][$em])) $rmap[$mid]['counts'][$em] = 0;
                $rmap[$mid]['counts'][$em]++;
                $rmap[$mid]['users'][(int)$rr['user_id']] = $em;
                $all_ruids[] = (int)$rr['user_id'];
            }
            // Build user id => (name, avatar) map for the tooltip
            $ruser_map = array();
            $all_ruids = array_unique($all_ruids);
            if (!empty($all_ruids)) {
                $sql->where('id', $all_ruids, 'in');
                $rusers = $sql->get('onlines', null, array('id', 'name', 'gender', 'inputs'));
                foreach ($rusers as $ru) {
                    $av = $options['defaultavatar'];
                    $rinp = json_decode($ru['inputs'], true);
                    if (is_array($rinp) && isset($rinp['avatar']) && $rinp['avatar'] != 'photo.png') {
                        $av = is_file('theme/images/avatars/' . $rinp['avatar']) ? $rinp['avatar'] : (($ru['gender'] == '1') ? 'maledefualt.png' : 'femaledefualt.jpg');
                    } else {
                        $av = ($ru['gender'] == '1') ? 'maledefualt.png' : 'femaledefualt.jpg';
                    }
                    $ruser_map[(int)$ru['id']] = array('name' => $ru['name'], 'avatar' => $av);
                }
            }
            $uid = (int)$id;
            foreach ($messages as &$msg) {
                $mid = (int)$msg['msgid'];
                if (isset($rmap[$mid])) {
                    $msg['reactions'] = array();
                    foreach ($rmap[$mid]['counts'] as $emoji => $cnt) {
                        $usrs = array();
                        foreach (array_keys($rmap[$mid]['users'], $emoji) as $uu) {
                            $info = isset($ruser_map[$uu]) ? $ruser_map[$uu] : array('name' => '', 'avatar' => $options['defaultavatar']);
                            $usrs[] = array('id' => $uu, 'name' => $info['name'], 'avatar' => $info['avatar']);
                        }
                        $msg['reactions'][] = array('reaction' => $emoji, 'count' => $cnt, 'users' => $usrs);
                    }
                    $msg['my_reaction'] = isset($rmap[$mid]['users'][$uid]) ? $rmap[$mid]['users'][$uid] : null;
                } else {
                    $msg['reactions'] = array();
                    $msg['my_reaction'] = null;
                }
            }
            unset($msg);
        }

        if (!empty($messages)) {
            $data = array('data' => $messages, 'mid' => $last);
            echo "event: messages\ndata: " . json_encode($data, JSON_UNESCAPED_UNICODE) . "\n\n";
            @flush();
        }

        $sql->where("id", $id);
        $sql->update('onlines', array('lastcheck' => $last, 'time' => time()));
    }

    sleep(1);
}
