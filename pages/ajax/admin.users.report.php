<?php
if(!session('id') or session('admin') == '0')exit();
adminpanel::check_Permission('exit', 'delreport');

$id = isset($_POST['id']) ? safe::number($_POST['id']) : '';
if($id == ''){echo('id');return false;}

$sql->where("user", $id);
if (!$user = $sql->getOne('report_users')) {echo('nouser');return false;}

$sql->where("id", $id);
$name = $sql->getOne('users', array('id','username'));

$sql->where("user", $user['user'])->delete('report_users');
$sql->where("id", $user['user'])->update('users',array('report'=>'0'));

$sql->where("id", session('id'));
$hide = $sql->getOne('onlines', array('id','hide'));
if ($hide['hide'] == '1' and session("admin")==1){
}elseif ($hide['hide'] == '0' and session("admin")!=0){
chatroom::addlog('9', "کاربر \"{$name['username']}\" را از ریپورتی خارج کرد");
}

echo('ok');
?>