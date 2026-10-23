<?php

check_session();

function gotoroom($room) {
    global $sql;
    $sql->where("id", session('id'))->update('onlines', array(
        'roomid' => $room['id'],
        'roomname' => $room['title']
    ));
    if (session('room') != $room['id']) {
        if ($GLOBALS['options']['flashchat'] == 'false') {
            Message::AddMasageSYS(session('name') . "  به {$room['title']} وارد شد.", $room['id']);
            Message::AddMasageSYS($room['wellcomemsg'], '-1', session('id'));
        }
        session('room', $room['id']);
        session('roomname', $room['title']);
    }
}

if (isset($_POST['changeroom'])) {
    $roomid = safe::string($_POST['changeroom']);
    $sql->where("id", $roomid);
    if (!$sql->has('rooms')) {
        exit('room!');
    }
    $key = safe::number($_POST['key']);
    $sql->where("id", session('id'));
    $code = $sql->getOne('users', array('keys'));
    if ($code['keys'] == $key) {
        $sql->where("id", $roomid);
        $roomid = $sql->getOne('rooms', array('id', 'title'));
        gotoroom($roomid);
        echo("شما به اتاق {$roomid['title']} منتقل شدید");
        $sql->where("id", session('id'))->update('users', array(
            'keys' => 0
        ));
    } else {
        echo('کلید فرستاده شده اشتباه است');
    }
}
?>