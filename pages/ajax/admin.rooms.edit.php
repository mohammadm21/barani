<?php

if (!session('id') or session('admin') == '0') {
    exit();
}
adminpanel::check_Permission('exit', 'editroom');
$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if (!$token || $token != session('changepw'))
    die('لطفا صفحه خود را رفرش کنيد');

$room = isset($_POST['room']) ? safe::number($_POST['room']) : '';
if ($room == '' or ! chatroom::checkroomid($room)) {
    echo('roomid');
    return;
}

$newName = isset($_POST['title']) ? safe::string($_POST['title']) : '';
if ($newName == "") {
    echo('title');
    return;
}


$password = isset($_POST['password']) ? $_POST['password'] : '';
switch ($password) {
    case(''):case('7'):case('8'):case('78'):break;
    default:$password = base64_encode($password);
        break;
}
$cap = isset($_POST['cap']) ? safe::number($_POST['cap']) : '';
$type = isset($_POST['permission']) ? safe::string($_POST['permission']) : '';

$wellcomemsg = isset($_POST['wellcomemsg']) ? $_POST['wellcomemsg'] : '';
$addallowtags = '<p><a><span><ul><ol><b><li><img><div><b><strong><br /><br><center><i><h1><h2><h3><h4><h5><h6>';
$wellcomemsg = strip_tags($wellcomemsg, $addallowtags);
$wellcomemsg = trim($wellcomemsg);


$defaultroom = isset($_POST['defaultroom']) ? safe::string($_POST['defaultroom']) : false;
if ($defaultroom == 'true') {
    $defaultroom = true;
} else {
    $defaultroom = false;
}
if ($defaultroom) {
    $password = '';
}

if(isset($_POST['timeroom'])){
	$time = safe::number($_POST['timeroom']);
	if($time > '0'){
		$timer = time() + ($time * 86400);
	}else{
		$timer = '0';
	}
	$timeroom = json_encode(array('time' => $timer,'times' => $time), JSON_UNESCAPED_UNICODE);
}

$vip_user_isset = isset($_POST['vip_user']) && isset($_POST['vip_time']) ? true : false;
if ($vip_user_isset) {
    $sql->where("id", $room);
    $vip_db_get = $sql->getOne('rooms', array('vip_user'));
    $vip_db = json_decode($vip_db_get[0], true);
    if (isset($vip_db['vip_user'])) {
        $is_admin = safe::string($_POST['is_admin']) == '1' ? '1' : '0';
        $vip_room = array('vip_user' => safe::number($_POST['vip_user']), 'vip_time' => safe::number($_POST['vip_time']), 'is_admin' => $is_admin);
        $j = json_encode($vip_room, JSON_UNESCAPED_UNICODE);
    }
}

$vip_r = $vip_user_isset ? $j : '0';
$sql->where("id", $room);
$sql->update('rooms', array(
    'title' => $newName,
    'password' => $password,
    'wellcomemsg' => $wellcomemsg,
    'capacity' => $cap,
    'type' => $type,
    'vip_user' => $vip_r,
    'timeroom' => $timeroom
));
if ($defaultroom) {
    $sql->where("name", 'chat_defaultroom');
    $sql->update('options', array(
        'value' => $room
    ));
    chatroom::reloadconfig();
}

$sql->where("id", session('id'));
$hide = $sql->getOne('onlines', array('id','hide'));
if ($hide['hide'] == '1' and session("admin")==1){
}elseif ($hide['hide'] == '0' and session("admin")!=0){
chatroom::addlog('9', "اتاق \"{$newName}\" را ویرایش کرد"); 
}
$tel = json_decode($options['teloptions'], true);
   $text = "اتاق \"{$newName}\" را ویرایش کرد\n";
    $text .='ای پی : '.ip()."\n";
    $text .= "آدرس : ".url()."\n";
    $text .= "نام نمایشی :".session('name')."\t";
   
       if($tel['sendroomlog'] == 'Sure'){
             tlg($text,'chat');
                 }
echo('ok');
?>
