<?php

check_session();
$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if (!$token || $token != session('changepw')) {
    die('لطفا صفحه خود را رفرش کنيد');
}
if (!session('id')) {
    die('login');
}
if (!chatroom::checkPermission('modsmoveuser')) {
    exit('اين قابليت براي گروه کاربري شما غير فعال شده است');
}
$admin = session('admin');
$vip_user_is_admin = false;

$sql->where("id", safe::number($_POST['user']));
$get_room_id = $sql->getValue('onlines', 'roomid');
$sql->where("id", $get_room_id['roomid']);
$me_room = $sql->getValue('rooms', 'vip_user');

$json = json_decode($me_room['vip_user'], true);
if ($json['vip_user'] == session('id')) {
    $vip_user_is_admin = true;
}
if ($admin != '0' or $vip_user_is_admin) {
    $user = safe::number($_POST['user']);
    $roomid = safe::number($_POST['roomid']);
    $availabes = array();
    foreach($sql->get('rooms') as $row){
        if (empty($row['password'])) {
            $availabes[] = $row['id'];
        }
    }
    if ($vip_user_is_admin && $admin == '0') {
        if (!in_array($roomid, $availabes)) {
            exit('permission');
        }
    }
    $sql->where("id", $roomid);
    $get = $sql->getOne('rooms', array('id', 'title'));
    if (!$get['id']) {
        exit('این اتاق وجود ندارد');
    }
    $sql->where("id", $user);
    $inputs = $sql->getOne('onlines', array('inputs', 'name'));
    $uname = $inputs['name'];
    $inputs = json_decode($inputs['inputs'], true);
    $code = rand(9999, 9999999);
    $val = Users::Inputs();
    $val['move'] = $roomid;
    $val['move_key'] = $code;
    $val['avatar'] = $inputs['avatar'];
    Users::Inputs($user, $val);
    $sql->where("id", $user)->update('users', array(
        'keys' =>$code
    ));
    chatroom::addlog('5', "کاربر \"{$uname}\" را به اتاق {$get['title']} انتقال داد");
    echo 'کاربر انتقال داده شد';
} else {
    exit('permission');
}
?>