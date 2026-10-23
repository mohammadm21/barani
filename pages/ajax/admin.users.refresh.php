<?php

if (!session('id') or session('admin') == '0') {
    exit();
}
adminpanel::check_Permission('exit', 'refreshusers');
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
if(!$user = $sql->getOne('users', array('id', 'username'))){
    echo('user');
    return false;
}
$sql->where("id", $user['id']);
$online = $sql->getOne('onlines', array('id'));
if ($online) {
    Users::refresh($online['id'], '1');
}

$sql->where("id", session('id'));
$hide = $sql->getOne('onlines', array('id','hide'));
if ($hide['hide'] == '1' and session("admin")==1){
}elseif ($hide['hide'] == '0' and session("admin")!=0){
chatroom::addlog('9', "کاربر   \"{$user['username']}\" را رفرش کرد");
}
$tel = json_decode($options['teloptions'], true);
  $text = "کاربر  \"{$user['username']}\" را رفرش کرد  \n";
    $text .='ای پی : '.ip()."\n";
    $text .= "آدرس : ".url()."\n";
    $text .= "نام نمایشی :".session('name')."\t";

       if($tel['senduserlog'] == 'Sure'){
             tlg($text,'chat');
                 }
echo('ok');
?>