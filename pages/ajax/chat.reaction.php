<?php
/**
 * Message Reactions API
 * Uses existing session auth, permission system, and DB classes
 */
if (!session('id')) exit();

$action = isset($_POST['action']) ? safe::string($_POST['action']) : exit();
$msg_id = isset($_POST['msg_id']) ? safe::number($_POST['msg_id']) : 0;
$reaction = isset($_POST['reaction']) ? safe::string($_POST['reaction']) : '';
$user_id = session('id');

// Permission check using existing system
if (!chatroom::checkPermission('allow_reactions')) {
    header('Content-Type: application/json; charset=utf-8');
    die(json_encode(array('status' => 'error', 'message' => 'no_permission'), JSON_UNESCAPED_UNICODE));
}

// Validate reaction against configured emojis
$allowed = json_decode($options['reaction_emojis'], true);
if (!$allowed) $allowed = array('👍','❤️','😂','😮','😢','😡','👎');
if (!in_array($reaction, $allowed)) {
    header('Content-Type: application/json; charset=utf-8');
    die(json_encode(array('status' => 'error', 'message' => 'invalid_emoji'), JSON_UNESCAPED_UNICODE));
}

// Fetch message to verify it exists and check privacy
$sql->where('id', $msg_id);
$message = $sql->getOne('message', array('id', 'user', 'to'));

if (!$message) {
    header('Content-Type: application/json; charset=utf-8');
    die(json_encode(array('status' => 'error', 'message' => 'msg_not_found'), JSON_UNESCAPED_UNICODE));
}

// System messages (user = -1) are not reactionable
if ((int)$message['user'] === -1) {
    header('Content-Type: application/json; charset=utf-8');
    die(json_encode(array('status' => 'error', 'message' => 'system_msg'), JSON_UNESCAPED_UNICODE));
}

// Privacy: private messages only reactable by sender/receiver
$to_user = (int)$message['to'];
if ($to_user > 0 && $user_id != $to_user && $user_id != (int)$message['user']) {
    header('Content-Type: application/json; charset=utf-8');
    die(json_encode(array('status' => 'error', 'message' => 'no_access'), JSON_UNESCAPED_UNICODE));
}

switch ($action) {
    case 'toggle':
        $sql->where('message_id', $msg_id);
        $sql->where('user_id', $user_id);
        $existing = $sql->getOne('reactions', array('id', 'reaction'));

        if ($existing) {
            if ($existing['reaction'] === $reaction) {
                $sql->where('id', $existing['id']);
                $sql->delete('reactions');
                $added = false;
            } else {
                $sql->where('id', $existing['id']);
                $sql->update('reactions', array(
                    'reaction' => $reaction,
                    'created_at' => time()
                ));
                $added = true;
            }
        } else {
            $sql->insert('reactions', array(
                'message_id' => $msg_id,
                'user_id' => $user_id,
                'reaction' => $reaction,
                'created_at' => time()
            ));
            $added = true;
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array(
            'status' => 'ok',
            'added' => $added,
            'reactions' => getReactions($msg_id, $user_id)
        ), JSON_UNESCAPED_UNICODE);
        
        // Broadcast to other users: bump reaction_broadcast timestamp
        // This tells polling clients to re-check reactions for visible messages
        $sql->where('name', 'reaction_broadcast');
        $sql->update('options', array('value' => time()), 1);
        break;

    default:
        header('Content-Type: application/json; charset=utf-8');
        die(json_encode(array('status' => 'error'), JSON_UNESCAPED_UNICODE));
}

function getReactions($msg_id, $uid) {
    global $sql;
    global $options;
    $sql->where('message_id', $msg_id);
    $rows = $sql->get('reactions', null, array('reaction', 'user_id'));

    $counts = array();
    $users_by_emoji = array();
    $my_r = null;
    $all_user_ids = array();
    foreach ($rows as $r) {
        $e = $r['reaction'];
        if (!isset($counts[$e])) { $counts[$e] = 0; $users_by_emoji[$e] = array(); }
        $counts[$e]++;
        $users_by_emoji[$e][] = (int)$r['user_id'];
        $all_user_ids[] = (int)$r['user_id'];
        if ((int)$r['user_id'] === (int)$uid) $my_r = $e;
    }

    // Map user_id => display name + avatar
    $avatar_map = array();
    $all_user_ids = array_unique($all_user_ids);
    if (!empty($all_user_ids)) {
        $sql->where('id', $all_user_ids, 'in');
        $profiles = $sql->get('onlines', null, array('id', 'name', 'gender', 'inputs'));
        foreach ($profiles as $p) {
            $av = $options['defaultavatar'];
            $inputs = json_decode($p['inputs'], true);
            if (is_array($inputs) && isset($inputs['avatar']) && $inputs['avatar'] != 'photo.png') {
                if (is_file('theme/images/avatars/' . $inputs['avatar'])) {
                    $av = $inputs['avatar'];
                } else {
                    $av = ($p['gender'] == '1') ? 'maledefualt.png' : 'femaledefualt.jpg';
                }
            } else {
                $av = ($p['gender'] == '1') ? 'maledefualt.png' : 'femaledefualt.jpg';
            }
            $avatar_map[(int)$p['id']] = array('name' => $p['name'], 'avatar' => $av);
        }
    }

    $list = array();
    foreach ($counts as $emoji => $cnt) {
        $usrs = array();
        foreach ($users_by_emoji[$emoji] as $uu) {
            $info = isset($avatar_map[$uu]) ? $avatar_map[$uu] : array('name' => '', 'avatar' => $options['defaultavatar']);
            $usrs[] = array('id' => $uu, 'name' => $info['name'], 'avatar' => $info['avatar']);
        }
        $list[] = array('reaction' => $emoji, 'count' => $cnt, 'users' => $usrs);
    }
    return array('list' => $list, 'user_reaction' => $my_r);
}
