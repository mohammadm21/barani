<?php
/*
check_session();
$token = isset($_POST['token']) ? $_POST['token'] : false;
if (!$token || $token != session('changepw')) {
    die('لطفا صفحه خود را رفرش کنيد');
}
$user = safe::number($_POST['user']);
$token = isset($_POST['token']) ? $_POST['token'] : false;
if (session('admin') == 0) {
    exit('Permissions');
}
if (!chatroom::checkPermission('deleteuser_premission')) {
    exit('اين قابليت براي گروه کاربري شما غير فعال شده است');
}
if (empty($user)) {
    exit('Error');
}
$id = isset($_POST['user']) ? safe::number($_POST['user']) : '';
if ($id == '') {
    echo('id');
    return false;
}
$sql->where("id", $id);
if (!$user = $sql->getOne('users', array('id', 'username'))) {
    echo('user');
    return false;
}
$sql->where("id", $user['id']);
$online = $sql->getOne('onlines', array('id'));

$sql->where("user", $user['id']);
$sql->delete('adminpanel');
$sql->where("user", $user['id']);
$sql->delete('banns');
$sql->where("user", $user['id']);
$sql->delete('logs');
$sql->where("user", $user['id']);
$sql->orwhere("to", $user['id']);
$sql->delete('message');
$sql->where("user", $user['id']);
$sql->orwhere("to", $user['id']);
$sql->delete('message_archive');
$sql->where("id", $user['id']);
$sql->delete('profiles');
$sql->where("userid", $user['id']);
$sql->delete('profile_posts');
$sql->where("userid", $user['id']);
$sql->delete('profile_counts');
$sql->where("id", $user['id']);
$sql->delete('letters');
$sql->where("user", $user['id']);
$sql->delete('stats');
$sql->where("id", $user['id']);
$sql->delete('users');


if ($online) {
    $sql->where("id", $user['id']);
    $sql->delete('onlines');
}
chatroom::addlog('9', "کاربر \"{$user['username']}\" را حذف کرد");
echo('کاربر با موفقیت حذف شد');
*/
?>