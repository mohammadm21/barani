<?php
//---------security---------
$id = session('id');
if (!$id) {
    echo json_encode(array('error' => 'login'));
    exit();
}
header('Content-Type: application/json; charset=utf-8');

check_session();
if (Users::guestType(session('type'))) { echo json_encode(array('error' => 'guest')); exit(); }

if ($options['forwardtext'] == 'true') {
    $useroptions = json_decode($options['msgoptions'], true);
    if (!is_array($useroptions)) {
        $useroptions = array();
    }
    if (in_array(session('type'), $useroptions['allowforward'] ?? array())) {
        $input = Users::Inputs();
        if (!$input || $input['silent'] == '1') {
            echo json_encode(array('error' => 'silent'));
            exit();
        }
        $msg = safe::string(Message::badwords(str_replace(array('<b>', '</b>', '<i>', '</i>'), "", $_POST['text'] ?? '')));
        $name = safe::string($_POST['name'] ?? '');
        $msgid = safe::string($_POST['msg'] ?? '');
        $rooms = !empty($_POST['room']) ? safe::number($_POST['room']) : null;
        $regex = "#([<]span)(.*)([<]/span[>])#";
        $msg = preg_replace($regex, '', $msg);
        if (!empty($rooms)) {
            $room = $rooms;
        } else {
            $room = session('room');
        }
        $to = isset($_POST['id']) ? safe::number($_POST['id']) : '';
        if ($to == '') {
            $to = '-1';
            $into = 'main';
        } elseif ($to != '') {
            $to = $to;
            $into = 'pm';
        }
        $data = array('action' => 'forward', 'msg' => $msg, 'name' => $name);
        if (strstr($msg, 'img width=50 height=50 id=nocheck src=')) {
            $data['isimg'] = true;
            $data['msg'] = str_replace('img width=50 height=50 id=nocheck src=', '', $msg);
        }
        $msg = json_encode($data, JSON_UNESCAPED_UNICODE);

        $result = Message::add($to, $msg, $room, $into);
        echo $result;
    } else {
        echo json_encode(array('error' => 'no access'));
        exit();
    }
} else {
    echo json_encode(array('error' => 'forward disabled'));
    exit();
}
?>
