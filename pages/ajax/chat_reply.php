<?php
$id = session('id');
if (!$id) {
    echo json_encode(array('error' => 'login'));
    exit();
}
check_session();
if (Users::guestType(session('type'))) { echo json_encode(array('error' => 'guest')); exit(); }
header('Content-Type: application/json; charset=utf-8');

$time = time();
$check_user_admin = session('admin');
$check_user_type = session('type');
$myOptions = json_decode($options['useroptions'], true);
$runAutoanswer = 0;
if ($options['scoretosendmsg'] == '0') {
    $options['scoretosendmsg'] = '1';
}
/*
$actions = $sql->where("id", $id)->getOne('onlines', array("id", "username", "name", "gender", "type", "time", "hide", "roomid", "roomname", "lastcheck", "ip", "inputs", "score"));
if (!$actions) {
    if ($user = $sql->where("id", $id)->getOne('users', array('username', 'name', 'gender', 'type'))) {
        $room = session('room');
        Users::addOnline($id, $user['username'], $user['name'], $user['gender'], $user['type'], session('hide'), $room, chatroom::getNameRoom($room));
	$actions = $sql->where("id", $id)->getOne('onlines', array("id", "username", "name", "gender", "type", "time", "hide", "roomid", "roomname", "lastcheck", "ip", "inputs", "score"));
    } else {
        exit();
    }
}
if (!$actions) {
    exit();
}
*/
 /*----check request for antirobots #by mamali------  
  	  if($options['attackmod'] =='true'){

      	 if($check_user_admin != '1'){
         	if ($actions['type'] == '1' || $actions['type'] == '2') {
            exit(0);
            }
		}
     }
  ----------------------------------------*/

if ($options['reply'] == 'true') {
    $useroptionss = json_decode($options['useroptions'], true);
    if (!is_array($useroptionss)) {
        $useroptionss = array();
    }
    if (in_array(session('type'), $useroptionss['reply'] ?? array())) {
        $input = Users::Inputs();
        if (!$input || $input['silent'] == '1') {
            echo json_encode(array('error' => 'silent'));
            exit();
        }
        $umsg = safe::string(Message::badwords(str_replace(array(
            '<b>',
            '</b>',
            '<i>',
            '</i>'
        ), "", $_POST['umsg'] ?? '')));
        $uname = safe::string($_POST['uname'] ?? '');
        $mymsg = safe::string(str_replace(array(
            '<b>',
            '</b>',
            '<i>',
            '</i>'
        ), "", $_POST['mymsg'] ?? ''));
        $msgid = safe::string($_POST['msgid'] ?? '');
        $os = safe::string($_POST['os'] ?? '');
        $regex = "#([<]span)(.*)([<]/span[>])#";
        $umsg = preg_replace($regex, '', $umsg);
        $mymsg = preg_replace($regex, '', $mymsg);
        if ($msgid == 'myself') {
            $uname = session('name');
        } else {
            $msgid = safe::number($msgid);
            if ($msgid == '') {
                echo json_encode(array('error' => 'nomsgid'));
                exit();
            }
            $sql->where("id", $msgid);
            $org_msg = $sql->getOne('message', array(
                'username',
                'text'
            ));
            if (!$org_msg || !isset($org_msg['text'])) {
                echo json_encode(array('error' => 'no original msg'));
                exit();
            }
            $jmsg = str_replace(array(
                '<b>',
                '</b>',
                '<i>',
                '</i>'
            ), "", $org_msg['text'] ?? '');
            if (isJson($jmsg)) {
                $jd = json_decode($jmsg, true);
                if (is_array($jd) && isset($jd['action'])) {
                    if ($jd['action'] == 'reply') {
                        $org_msg['text'] = Message::badwords($jd['mymsg']);
                    } elseif ($jd['action'] == "forward") {
                        $org_msg['text'] = Message::badwords($jd['msg']);
                    }
                }
            }
            $umsg = safe::string(str_replace(array(
                '<b>',
                '</b>',
                '<i>',
                '</i>'
            ), "", $org_msg['text']));
            $uname = safe::string($org_msg['username']);
        }
        $to = isset($_POST['to']) ? safe::number($_POST['to']) : '';
        $where = safe::string($_POST['where'] ?? '');
        if ($where != 'main' AND empty($to)) {
            echo json_encode(array('error' => 'no recipient'));
            exit();
        }
        if ($where != 'main' AND $to) {
            $user = Users::Inputs($to);
            if (!$user) {
                echo json_encode(array('error' => 'invalid user'));
                exit();
            }
            $my_blacklist_row = $sql->where("user", $to)->getOne('my_blacklist', array('blacklist'));
            $my_blacklist = ($my_blacklist_row && isset($my_blacklist_row['blacklist']))
                ? json_decode($my_blacklist_row['blacklist'], true)
                : array();
            if (!is_array($my_blacklist)) {
                $my_blacklist = array();
            }
            if (in_array(session('id'), $my_blacklist) AND ($where == 'private' || $where == 'pm')) {
                echo json_encode(array('error' => 'blacklisted'));
                exit();
            }
            if (($user['status_private'] ?? '') == 'nopm' AND $where == 'private') {
                $friends = Users::get('friends', $to);
                $u_friends = json_decode($friends, true);
                if (!is_array($u_friends)) {
                    $u_friends = array();
                }
                if (!in_array(session('id'), $u_friends)) {
                    echo json_encode(array('error' => 'nopm'));
                    exit();
                }
            }
            if ($where == 'cf' AND $to != session('id')) {
                if (!allow_cf($to)) {
                    echo json_encode(array('error' => 'no cf access'));
                    exit();
                } else {
                    $sql->where('creater', $to);
                    $sql->where('joiner', session('id'));
                    $cf_check_silent = $sql->getOne('cf_users', array(
                        'silent'
                    ));
                    if (($cf_check_silent['silent'] ?? '0') == '1') {
                        echo json_encode(array('error' => 'cf silent'));
                        exit();
                    }
                }
            }
        }
        $data = array(
            'action' => 'reply',
            'umsg' => $umsg,
            'uname' => $uname,
            'mymsg' => $mymsg
        );
        if (strstr($umsg, 'img width=50 height=50 id=nocheck src=')) {
            $data['isimg'] = true;
            $data['umsg'] = str_replace('img width=50 height=50 id=nocheck src=', '', $umsg);
        }
        $msg = json_encode($data, JSON_UNESCAPED_UNICODE);
        switch ($where) {
            case 'main':
                $id = '-1';
                $zone = 'main';
                break;
            case 'private':
                $id = $to;
                $zone = 'pm';
                break;
			case 'pm':
                $id = $to;
                $zone = 'pm';
                break;
            case 'cf':
                $id = $to;
                $zone = $to;
                break;
            default:
                echo json_encode(array('error' => 'invalid where'));
                exit();
                break;
        }
        $result = Message::add($id, $msg, session('room'), $zone);
        echo $result;
    } else {
        echo json_encode(array('error' => 'no access'));
        exit();
    }
} else {
    echo json_encode(array('error' => 'reply disabled'));
    exit();
}

?>
