<?php

check_session();
$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if (!$token || $token != session('changepw')) {
    die('لطفا صفحه خود را رفرش کنيد');
}
if (!chatroom::checkPermission('modswarning')) {
    exit('اين قابليت براي گروه کاربري شما غير فعال شده است');
}
$admin = session('admin');
$vip_user_is_admin = false;
$sql->where("id", safe::number($_POST['user']));
$get_room_id = $sql->getValue('onlines', 'roomid');
$sql->where("id", $get_room_id['roomid']);
$me_room = $sql->getOne("rooms", 'vip_user');
$json = json_decode($me_room['vip_user'], true);
if ($json['vip_user'] == session('id')) {
    $vip_user_is_admin = true;
}
if (Users::AdminsType(session('type')) or $vip_user_is_admin) {
    $user = safe::number($_POST['user']);
    Access2Manage($user);
    if (Users::CheckId($user)) {
        $text = safe::string($_REQUEST['txt']);
        Users::warning($user, $text);
        echo('ok');
    } else {
        echo('user');
    }
    unset($user);
} else {
    echo('permision');
}
unset($admin);
?>