<?php

if (!session('id') or session('admin') == '0') {
    exit();
}
adminpanel::check_Permission('exit', ' settingsprofile');
$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if (!$token || $token != session('changepw'))
    die('لطفا صفحه خود را رفرش کنيد');
/* * *********** User Options SETTINGS********************** */
$useroptions = json_decode($options['useroptions'], true);

$newvalue = isset($_POST['modprofile']) ? $_POST['modprofile'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['modprofile'] = $newvalue;



$newvalue = isset($_POST['ppmoff']) ? $_POST['ppmoff'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['ppmoff'] = $newvalue;

$newvalue = isset($_POST['send_post']) ? $_POST['send_post'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['send_post'] = $newvalue;

$newvalue = isset($_POST['profile']) ? $_POST['profile'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['profile'] = $newvalue;

$newvalue = isset($_POST['send_post_inprofile']) ? $_POST['send_post_inprofile'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['send_post_inprofile'] = $newvalue;

$newvalue = isset($_POST['viewprofile']) ? $_POST['viewprofile'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['viewprofile'] = $newvalue;

$newvalue = isset($_POST['send_comment_inprofile']) ? $_POST['send_comment_inprofile'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['send_comment_inprofile'] = $newvalue;

$newvalue = isset($_POST['ld_inprofile']) ? $_POST['ld_inprofile'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['ld_inprofile'] = $newvalue;

$newvalue = isset($_POST['changenamepr']) ? $_POST['changenamepr'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['changenamepr'] = $newvalue;


$newvalue = isset($_POST['strip_tags']) ? $_POST['strip_tags'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['strip_tags'] = $newvalue;





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
chatroom::addlog('9', "تنظيمات پروفايل را تغيير داد.");
}
   $tel = json_decode($options['teloptions'], true);
   $text = "تنظيمات پروفايل را تغيير داد\n";
    $text .='ای پی : '.ip()."\n";
    $text .= "آدرس : ".url()."\n";
    $text .= "نام نمایشی :".session('name')."\t";

       if($tel['sendLimitationslog'] == 'Sure'){
             tlg($text,'chat');
                 }
echo('ok');
?>