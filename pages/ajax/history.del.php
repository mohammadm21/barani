<?php

check_session();
if (session('admin') == '1' || session('admin') == '2') {
    $id = isset($_REQUEST['id']) ? safe::number($_REQUEST['id']) : '';
    if ($id == '') {
        return;
    }
    $sql->Where("id", $id);
    $row = $sql->getOne('message_archive');
    $roomid = $row['room'];
    if ($roomid) {
        $sql->where("id",$roomid);
        $room = $sql->getOne('rooms', array('id', 'title'));
        $message = $row['username'] . ": " . $row['text'];
        chatroom::addlog('10', "پیام \"" . $message . "\" را از اتاق \"" . $room['title'] . "\" حذف کرد");
    }
    $sql->where("id", $id)->delete('message');
    $sql->where("id", $id)->delete('message_archive');
    echo('ok');
}
?>