<?php

check_session();
$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if (!$token || $token != session('changepw')) {
    die('لطفا صفحه خود را رفرش کنيد');
}
if (!session('id')) {
    die('login');
}
if (!chatroom::checkPermission('modhider')) {
    exit('اين قابليت براي گروه کاربري شما غير فعال شده است');
}
$admin = session('admin');
$vip_user_is_admin = false;
$get_room_id = $sql->where("id",safe::number($_POST['user']))->getOne('onlines','roomid');
$me_room = $sql->where("id",$get_room_id['roomid'])->getOne('rooms','vip_user');
$json = json_decode($me_room[0], true);
if ($json['vip_user'] == session('id')) {
    $vip_user_is_admin = true;
}
if ($admin != '0' or $vip_user_is_admin) {
    $user = safe::number($_POST['user']);
    if (Users::CheckId($user)) {
        $sql->where("id", $user)->update('onlines', array(
            'hide' => 1
        ));
        $text = 'شما به حالت مخفی در آمدید';
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
