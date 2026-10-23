<?php
/*
if (!session('id') or session('admin') == '0') {
    exit();
}
adminpanel::check_Permission('exit', 'delusers');
$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if (!$token || $token != session('changepw')) {
    die('لطفا صفحه خود را رفرش کنيد');
}

$id = isset($_POST['user']) ? safe::number($_POST['user']) : '';
if (session('id') != '1' && $id == '1') {
    echo 'این شخص صاحب چت روم است اجازه ویرایش اطلاعات را ندارید در صورتی که صاحب چت روم هستید برای تغییر رمز از ایکون چرخ دنده در داخل چت روم رمز خود را تغییر دهید';
    exit;
}
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

$sql->where("id", session('id'));
$hide = $sql->getOne('onlines', array('id','hide'));
if ($hide['hide'] == '1' and session("admin")==1){
}elseif ($hide['hide'] == '0' and session("admin")!=0){
chatroom::addlog('9', "کاربر \"{$user['username']}\" را حذف کرد");
}
 /* $text = "کاربر \"{$user['username']}\" را حذف کرد\n";
    $text .='ای پی : '.ip()."\n";
    $text .= "آدرس : ".url()."\n";
    $text .= "نام نمایشی :".session('name')."\t";

       if($tel['senduserlog'] == 'Sure'){
             tlg($text,'chat');
                 }*/
echo('ok');
*/
?>