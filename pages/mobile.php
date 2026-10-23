<?php

//if (is_mobile_detect()) {
    $theme = 'mobile';
    $id = session('id');
    $sql->where("id",$id);
    $me = $sql->getOne('onlines', array('id', 'type', 'gender', 'score', 'type', 'inputs', 'name', 'bank'));
    if (!$me) {
        $sql->where("id", $id);
        if($user = $sql->getOne("users", array('id', 'username', 'name', 'type', 'gender', 'type'))){
            $room = session('room');
            Users::addOnline($id, $user['username'], $user['name'], $user['gender'], $user['type'], session('hide'), $room, chatroom::getNameRoom($room));
        } else {
            header::Go(url('login'));
            exit();
        }
        $sql->where("id", $id);
        $me = $sql->getOne('onlines', array('id', 'type', 'gender', 'score', 'type', 'inputs', 'name', 'bank'));
    }
    $temp = json_decode($me['inputs'], true);
    unset($temp['score']);
    $me += $temp;
    include(theme::file('mobile.php'));
/* else {
    $chat = $options['chat_prefix'];
    include("pages/$chat.php");
}*/
?>
