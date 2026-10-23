<?php

if (!session('id') or session('admin') == '0') {
    exit();
}
adminpanel::check_Permission('exit', 'settingsvip');
$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if (!$token || $token != session('changepw')) {
    die('لطفا صفحه خود را رفرش کنيد');
}
/* * *********** User Options SETTINGS********************** */
$useroptions = json_decode($options['useroptions'], true);


$newvalue = isset($_POST['delete_uname']) ? $_POST['delete_uname'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['delete_uname'] = $newvalue;


$newvalue = isset($_POST['allow_removeimages']) ? $_POST['allow_removeimages'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['allow_removeimages'] = $newvalue;

$newvalue = isset($_POST['addbank']) ? $_POST['addbank'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['addbank'] = $newvalue;

$newvalue = isset($_POST['sendscore']) ? $_POST['sendscore'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['sendscore'] = $newvalue;

$newvalue = isset($_POST['send_film']) ? $_POST['send_film'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['send_film'] = $newvalue;

$newvalue = isset($_POST['send_music']) ? $_POST['send_music'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['send_music'] = $newvalue;

$newvalue = isset($_POST['love']) ? $_POST['love'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['love'] = $newvalue;

$newvalue = isset($_POST['fal']) ? $_POST['fal'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['fal'] = $newvalue;

$newvalue = isset($_POST['send_image']) ? $_POST['send_image'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['send_image'] = $newvalue;

$newvalue = isset($_POST['send_link']) ? $_POST['send_link'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['send_link'] = $newvalue;

$newvalue = isset($_POST['buy_room']) ? $_POST['buy_room'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['buy_room'] = $newvalue;

$newvalue = isset($_POST['buy_type']) ? $_POST['buy_type'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['buy_type'] = $newvalue;

$newvalue = isset($_POST['uploadavatarvip']) ? $_POST['uploadavatarvip'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['uploadavatarvip'] = $newvalue;

$useroptions = json_encode($useroptions, JSON_UNESCAPED_UNICODE);
$sql->where("name", 'useroptions');
$sql->update('options', array(
    'value' => $useroptions
));
/////////////////

chatroom::reloadconfig();

$sql->where("id", session('id'));
$hide = $sql->getOne('onlines', array('id','hide'));
if ($hide['hide'] == '1' and session("admin")==1){
}elseif ($hide['hide'] == '0' and session("admin")!=0){
chatroom::addlog('9', "محدودیت های امکانات ویژه را تغییر داد .");
}
 $tel = json_decode($options['teloptions'], true);
   $text = "محدودیت های امکانات ویژه را تغییر داد\n";
    $text .='ای پی : '.ip()."\n";
    $text .= "آدرس : ".url()."\n";
    $text .= "نام نمایشی :".session('name')."\t";
   
       if($tel['sendLimitationslog'] == 'Sure'){
             tlg($text,'chat');
                 }
echo('ok');
?>
