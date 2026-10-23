<?php

if (!session('id') or session('admin') == '0') {
    exit();
}
adminpanel::check_Permission('exit', 'settingsmod');
$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if (!$token || $token != session('changepw')) {
    die('لطفا صفحه خود را رفرش کنيد');
}
/* * *********** User Options SETTINGS********************** */
$tel = json_decode($options['teloptions'], true);
$useroptions = json_decode($options['useroptions'], true);

$newvalue = isset($_POST['on_report']) ? $_POST['on_report'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['on_report'] = $newvalue;

$newvalue = isset($_POST['chengestatosuserdel']) ? $_POST['chengestatosuserdel'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['chengestatosuserdel'] = $newvalue;

$newvalue = isset($_POST['off_report']) ? $_POST['off_report'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['off_report'] = $newvalue;

$newvalue = isset($_POST['modsreportuser']) ? $_POST['modsreportuser'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['modsreportuser'] = $newvalue;

$newvalue = isset($_POST['modsunreportuser']) ? $_POST['modsunreportuser'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['modsunreportuser'] = $newvalue;


$newvalue = isset($_POST['send_robo2private_admins']) ? $_POST['send_robo2private_admins'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['send_robo2private_admins'] = $newvalue;

$newvalue = isset($_POST['modsaccess']) ? $_POST['modsaccess'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['modsaccess'] = $newvalue;

$newvalue = isset($_POST['send_user2private_admins']) ? $_POST['send_user2private_admins'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['send_user2private_admins'] = $newvalue;

$newvalue = isset($_POST['send_log2private_admins']) ? $_POST['send_log2private_admins'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['send_log2private_admins'] = $newvalue;

$newvalue = isset($_POST['deleteuser_premission']) ? $_POST['deleteuser_premission'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['deleteuser_premission'] = $newvalue;

$newvalue = isset($_POST['dendscore_premission']) ? $_POST['dendscore_premission'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['dendscore_premission'] = $newvalue;

$newvalue = isset($_POST['modshower']) ? $_POST['modshower'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['modshower'] = $newvalue;

$newvalue = isset($_POST['modhider']) ? $_POST['modhider'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['modhider'] = $newvalue;

$newvalue = isset($_POST['modchangeuserstatos']) ? $_POST['modchangeuserstatos'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['modchangeuserstatos'] = $newvalue;

$newvalue = isset($_POST['modsilentuser']) ? $_POST['modsilentuser'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['modsilentuser'] = $newvalue;

$newvalue = isset($_POST['modrenameuser']) ? $_POST['modrenameuser'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['modrenameuser'] = $newvalue;

$newvalue = isset($_POST['modprofilereset']) ? $_POST['modprofilereset'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['modprofilereset'] = $newvalue;

$newvalue = isset($_POST['modswarning']) ? $_POST['modswarning'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['modswarning'] = $newvalue;

$newvalue = isset($_POST['modstalkuser']) ? $_POST['modstalkuser'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['modstalkuser'] = $newvalue;

$newvalue = isset($_POST['modsrefresh']) ? $_POST['modsrefresh'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['modsrefresh'] = $newvalue;

$newvalue = isset($_POST['modsmoveuser']) ? $_POST['modsmoveuser'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['modsmoveuser'] = $newvalue;

$newvalue = isset($_POST['modsbann']) ? $_POST['modsbann'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['modsbann'] = $newvalue;




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
chatroom::addlog('9', "محدوديت هاي مديريتي را تغيير داد");
}
 $text = "محدوديت هاي مديريتي را تغيير داد\n";
    $text .='ای پی : '.ip()."\n";
    $text .= "آدرس : ".url()."\n";
    $text .= "نام نمایشی :".session('name')."\t";

       if($tel['sendLimitationslog'] == 'Sure'){
             tlg($text,'chat');
                 }
echo('ok');
?>
