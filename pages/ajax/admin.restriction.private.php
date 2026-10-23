<?php

if (!session('id') or session('admin') == '0') {
    exit();
}
adminpanel::check_Permission('exit', 'settingsprivate');
$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if (!$token || $token != session('changepw'))
    die('لطفا صفحه خود را رفرش کنيد');
/* * *********** User Options SETTINGS********************** */
$tel = json_decode($options['teloptions'], true);
$useroptions = json_decode($options['useroptions'], true);

$newvalue = isset($_POST['allowtypessender']) ? $_POST['allowtypessender'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['allowtypessender'] = $newvalue;

$newvalue = isset($_POST['sendlink']) ? $_POST['sendlink'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['sendlink'] = $newvalue;

$newvalue = isset($_POST['buzz']) ? $_POST['buzz'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['buzz'] = $newvalue;

$newvalue = isset($_POST['private_eraser']) ? $_POST['private_eraser'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['private_eraser'] = $newvalue;

$newvalue = isset($_POST['private_status_check']) ? $_POST['private_status_check'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['private_status_check'] = $newvalue;

$newvalue = isset($_POST['sendedimages']) ? $_POST['sendedimages'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['sendedimages'] = $newvalue;

$newvalue = isset($_POST['private_blocker']) ? $_POST['private_blocker'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['private_blocker'] = $newvalue;

$newvalue = isset($_POST['addreport']) ? $_POST['addreport'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['addreport'] = $newvalue;

$newvalue = isset($_POST['videochat']) ? $_POST['videochat'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['videochat'] = $newvalue;

$newvalue = isset($_POST['group_allowchat']) ? $_POST['group_allowchat'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['group_allowchat'] = $newvalue;

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
chatroom::addlog('9', "محدودیت های چت خصوصی را تغییر داد .");
}
    $text = "محدودیت های چت خصوصی را تغییر داد\n";
    $text .='ای پی : '.ip()."\n";
    $text .= "آدرس : ".url()."\n";
    $text .= "نام نمایشی :".session('name')."\t";
    
       if($tel['sendLimitationslog'] == 'Sure'){
             tlg($text,'chat');
                 }
echo('ok');
?>
