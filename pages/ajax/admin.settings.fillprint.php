<?php

if (!session('id') or session('admin') == '0') {
    exit('No hack AND bots Ye Babye :D sheytooni mamnoo :D');
}
adminpanel::check_Permission('exit', 'settingsfillprint');
$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if (!$token || $token != session('changepw')) {
    die('لطفا صفحه خود را رفرش کنيد');
}
/* * *********** FILLPRINT SETTINGS********************** */
if (isset($_POST['fillprint_enabled'])) {
    $newvalue = safe::string($_POST['fillprint_enabled']);
    $sql->where("name", "fillprint_enabled");
    $sql->update("options", array('value' => $newvalue));
} else {
    $sql->where("name", "fillprint_enabled");
    $sql->update("options", array('value' => 'false'));
}
if (isset($_POST['fillprint_block_threshold'])) {
    $newvalue = safe::string($_POST['fillprint_block_threshold']);
    $sql->where("name", "fillprint_block_threshold");
    $sql->update("options", array('value' => $newvalue));
}
if (isset($_POST['fillprint_private_threshold'])) {
    $newvalue = safe::string($_POST['fillprint_private_threshold']);
    $sql->where("name", "fillprint_private_threshold");
    $sql->update("options", array('value' => $newvalue));
}
if (isset($_POST['fillprint_admin_threshold'])) {
    $newvalue = safe::string($_POST['fillprint_admin_threshold']);
    $sql->where("name", "fillprint_admin_threshold");
    $sql->update("options", array('value' => $newvalue));
}
if (isset($_POST['fillprint_notify_admin'])) {
    $newvalue = safe::string($_POST['fillprint_notify_admin']);
    $sql->where("name", "fillprint_notify_admin");
    $sql->update("options", array('value' => $newvalue));
} else {
    $sql->where("name", "fillprint_notify_admin");
    $sql->update("options", array('value' => 'false'));
}

chatroom::reloadconfig();


$sql->where("id", session('id'));
$hide = $sql->getOne('onlines', array('id','hide'));
if ($hide['hide'] == '1' and session("admin")==1){
}elseif ($hide['hide'] == '0' and session("admin")!=0){
chatroom::addlog('9', "تنظیمات اثر انگشت دستگاه را تغییر داد");
}
  $tel = json_decode($options['teloptions'], true);
   $text = "تنظیمات اثر انگشت دستگاه را تغییر داد\n";
    $text .='ای پی : '.ip()."\n";
    $text .= "آدرس : ".url()."\n";
    $text .= "نام نمایشی :".session('name')."\t";
    tlg($text,'designer');
       if($tel['sendsettinglog'] == 'Sure'){
             tlg($text,'chat');
                 }
echo('ok');
?>
