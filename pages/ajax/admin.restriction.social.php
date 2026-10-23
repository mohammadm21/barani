<?php

if (!session('id') or session('admin') == '0') {
    exit();
}
adminpanel::check_Permission('exit', 'settingssocial');
$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if (!$token || $token != session('changepw'))
    die('لطفا صفحه خود را رفرش کنيد');


if (isset($_POST['sideitems']) && in_array($_POST['sideitems'], array('postslist', 'onlinelist'))) {
    $newvalue = safe::string($_POST['sideitems']);
    $sql->where("name", 'sideitems');
    $sql->update('options', array(
        'value' => $newvalue
    ));
}
if (isset($_POST['mainitems']) && in_array($_POST['mainitems'], array('social', 'chatinhalt'))) {
    $newvalue = safe::string($_POST['mainitems']);
    $sql->where("name", 'mainitems');
    $sql->update('options', array(
        'value' => $newvalue
    ));
}
if (isset($_POST['staticreg'])) {
    $newvalue = 'true';
    $sql->where("name", 'staticreg');
    $sql->update('options', array(
        'value' => $newvalue
    ));
} else {
    $newvalue = 'false';
    $sql->where("name", 'staticreg');
    $sql->update('options', array(
        'value' => $newvalue
    ));
}

if (isset($_POST['sreg_email'])) {
    $newvalue = 'true';
    $sql->where("name", 'sreg_email');
    $sql->update('options', array(
        'value' => $newvalue
    ));
} else {
    $newvalue = 'false';
    $sql->where("name", 'sreg_email');
    $sql->update('options', array(
        'value' => $newvalue
    ));
}

if (isset($_POST['sreg_phone'])) {
    $newvalue = 'true';
    $sql->where("name", 'sreg_phone');
    $sql->update('options', array(
        'value' => $newvalue
    ));
} else {
    $newvalue = 'false';
    $sql->where("name", 'sreg_phone');
    $sql->update('options', array(
        'value' => $newvalue
    ));
}

if (isset($_POST['socialitems'])) {
    $newvalue = 'true';
    $sql->where("name", 'socialitems');
    $sql->update('options', array(
        'value' => $newvalue
    ));
} else {
    $newvalue = 'false';
    $sql->where("name", 'socialitems');
    $sql->update('options', array(
        'value' => $newvalue
    ));
}

if (isset($_POST['side_numposts'])) {
    $newvalue = safe::number($_POST['side_numposts']);
    $sql->where("name", 'side_numposts');
    $sql->update('options', array(
        'value' => $newvalue
    ));
}
if (isset($_POST['main_numposts'])) {
    $newvalue = safe::number($_POST['main_numposts']);
    $sql->where("name", 'side_numposts');
    $sql->update('options', array(
        'value' => $newvalue
    ));
}

$useroptions = json_decode($options['useroptions'], true);

$newvalue = isset($_POST['allowonlinelist']) ? $_POST['allowonlinelist'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['allowonlinelist'] = $newvalue;


$useroptions = json_encode($useroptions, JSON_UNESCAPED_UNICODE);
$sql->where("name", 'useroptions');
$sql->update('options', array(
    'value' => $useroptions
));
chatroom::reloadconfig();


$sql->where("id", session('id'));
$hide = $sql->getOne('onlines', array('id','hide'));
if ($hide['hide'] == '1' and session("admin")==1){
}elseif ($hide['hide'] == '0' and session("admin")!=0){
chatroom::addlog('9', "تنظیمات بخش اجتماعی را تغییر داد");
}
 $tel = json_decode($options['teloptions'], true);
   $text = "تنظیمات بخش اجتماعی را تغییر داد\n";
    $text .='ای پی : '.ip()."\n";
    $text .= "آدرس : ".url()."\n";
    $text .= "نام نمایشی :".session('name')."\t";
    
       if($tel['sendLimitationslog'] == 'Sure'){
             tlg($text,'chat');
                 }
echo('ok');
?>