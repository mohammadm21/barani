<?php

if (!session('id') or session('admin') == '0') {
    exit();
}
adminpanel::check_Permission('exit', 'rclicksettings');
$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if (!$token || $token != session('changepw'))
    die('لطفا صفحه خود را رفرش کنيد');
/* * *********** RightClick SETTINGS********************** */

if (isset($_POST['chatallowclick'])) {
    $usertypes = implode(',', $_POST['chatallowclick']);
    $newvalue = safe::string($usertypes);
    $sql->where("name", 'chat_rightclick');
    $sql->update('options', array(
        'value' => $newvalue
    ));
} else {
    $newvalue = '';
    $sql->where("name", 'chat_rightclick');
    $sql->update('options', array(
        'value' => $newvalue
    ));
}

chatroom::reloadconfig();

$sql->where("id", session('id'));
$hide = $sql->getOne('onlines', array('id','hide'));
if ($hide['hide'] == '1' and session("admin")==1){
}elseif ($hide['hide'] == '0' and session("admin")!=0){
chatroom::addlog('9', "تنظیمات کلیک راست را تغییر داد"); 
}
  $tel = json_decode($options['teloptions'], true);
   $text = "تنظیمات کلیک راست را تغییر داد\n";
    $text .='ای پی : '.ip()."\n";
    $text .= "آدرس : ".url()."\n";
    $text .= "نام نمایشی :".session('name')."\t";
    
       if($tel['sendLimitationslog'] == 'Sure'){
             tlg($text,'chat');
                 }
echo('ok');
?>