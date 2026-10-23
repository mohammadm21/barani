<?php

if (!session('id') or session('admin') == '0') {
    exit();
}
adminpanel::check_Permission('exit', 'useroptionsettings');
$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if (!$token || $token != session('changepw')) {
    die('لطفا صفحه خود را رفرش کنيد');
}
/* * *********** User Options SETTINGS********************** */
$useroptions = json_decode($options['useroptions'], true);

$newvalue = isset($_POST['chat_news']) ? $_POST['chat_news'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['chat_news'] = $newvalue;

$newvalue = isset($_POST['usersmile']) ? $_POST['usersmile'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['usersmile'] = $newvalue;

$newvalue = isset($_POST['chat_report_msg']) ? $_POST['chat_report_msg'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['chat_report_msg'] = $newvalue;

$newvalue = isset($_POST['showpv']) ? $_POST['showpv'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['showpv'] = $newvalue;

$newvalue = isset($_POST['showms']) ? $_POST['showms'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['showms'] = $newvalue;

$newvalue = isset($_POST['show_browser']) ? $_POST['show_browser'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['show_browser'] = $newvalue;

$newvalue = isset($_POST['statos']) ? $_POST['statos'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['statos'] = $newvalue;

$newvalue = isset($_POST['game']) ? $_POST['game'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['game'] = $newvalue;

$newvalue = isset($_POST['history']) ? $_POST['history'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['history'] = $newvalue;

$newvalue = isset($_POST['users']) ? $_POST['users'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['users'] = $newvalue;

$newvalue = isset($_POST['allowtypessender_main']) ? $_POST['allowtypessender_main'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['allowtypessender_main'] = $newvalue;

$newvalue = isset($_POST['conference_premission']) ? $_POST['conference_premission'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['conference_premission'] = $newvalue;

$newvalue = isset($_POST['UsersForBann']) ? $_POST['UsersForBann'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['UsersForBann'] = $newvalue;

$newvalue = isset($_POST['chat_speedmsg']) ? $_POST['chat_speedmsg'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['chat_speedmsg'] = $newvalue;

$newvalue = isset($_POST['show_userOs']) ? $_POST['show_userOs'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['show_userOs'] = $newvalue;

$newvalue = isset($_POST['show_idleTime_onlinelist']) ? $_POST['show_idleTime_onlinelist'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['show_idleTime_onlinelist'] = $newvalue;

$newvalue = isset($_POST['allow_reactions']) ? $_POST['allow_reactions'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['allow_reactions'] = $newvalue;

$newvalue = isset($_POST['colormap']) ? $_POST['colormap'] : array();
$newvalue = safe::arrayNumber($newvalue);
$useroptions['colormap'] = $newvalue;

$useroptions = json_encode($useroptions, JSON_UNESCAPED_UNICODE);
$sql->where("name", 'useroptions');
$sql->update('options', array(
    'value' => $useroptions
));/////////////////

// Save reaction emojis list
$reaction_emojis = isset($_POST['reaction_emojis']) ? safe::string($_POST['reaction_emojis']) : '';
if (!empty($reaction_emojis)) {
    $emoji_list = explode(',', $reaction_emojis);
    $emoji_list = array_map('trim', $emoji_list);
    $emoji_list = array_filter($emoji_list);
    $emoji_list = array_values($emoji_list);
    $reaction_emojis_json = json_encode($emoji_list, JSON_UNESCAPED_UNICODE);
    $sql->where("name", 'reaction_emojis');
    $sql->update('options', array('value' => $reaction_emojis_json));
}

$formats = isset($_POST['allow_formats']) ? safe::arrayString($_POST['allow_formats']) : array();
$format = array('mp3', 'mp4', '3gp', 'jpg', 'txt', 'gif', 'png', 'wmv', 'pdf','jpeg', 'wma', 'mov');
$new_format = array();
foreach ($format as $f) {
    if (in_array($f, $formats)) {
        $new_format[$f] = 1;
    } else {
        $new_format[$f] = 0;
    }
}
$allow_formats = json_encode($new_format, JSON_UNESCAPED_UNICODE);
$sql->where("name", 'allow_formats');
$sql->update('options', array(
    'value' => $allow_formats
));


chatroom::reloadconfig();

$sql->where("id", session('id'));
$hide = $sql->getOne('onlines', array('id','hide'));
if ($hide['hide'] == '1' and session("admin")==1){
}elseif ($hide['hide'] == '0' and session("admin")!=0){
chatroom::addlog('9', "محدودیت های امکانات را تغییر داد");
}
  $tel = json_decode($options['teloptions'], true);
   $text = "محدودیت های امکانات را تغییر داد\n";
    $text .='ای پی : '.ip()."\n";
    $text .= "آدرس : ".url()."\n";
    $text .= "نام نمایشی :".session('name')."\t";
 
       if($tel['sendLimitationslog'] == 'Sure'){
             tlg($text,'chat');
                 }
echo('ok');
?>
