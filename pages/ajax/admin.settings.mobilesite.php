<?php
if (!session('id') or session('admin') == '0') {
    exit('No hack AND bots Ye Babye :D sheytooni mamnoo :D');
}
adminpanel::check_Permission('exit', 'settingsmobilesite');
$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if (!$token || $token != session('changepw')) {
    die('لطفا صفحه خود را رفرش کنيد');
}
if (isset($_POST['mobilesite_mode']) && in_array($_POST['mobilesite_mode'], array('off', 'auto', 'link'))) {
    $newvalue = safe::string($_POST['mobilesite_mode']);
    $sql->where("name", "mobilesite_mode");
    $sql->update("options", array('value' => $newvalue));
}
chatroom::reloadconfig();
echo('ok');
?>
