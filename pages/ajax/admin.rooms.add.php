<?php

if (!session('id') or session('admin') == '0') {
    exit();
}
adminpanel::check_Permission('exit', 'addroom');
$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if (!$token || $token != session('changepw'))
    die('لطفا صفحه خود را رفرش کنيد');

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

$id = $sql->insert('rooms', array(
    'title' => $newName,
    'password' => $password,
    'wellcomemsg' => $wellcomemsg,
    'capacity' => $cap,
    'type' => $type,
    'timeroom' => $timeroom
));
if ($defaultroom) {
    $sql->where("name", 'chat_defaultroom');
    $sql->update('options', array(
        'value' => $id
    ));
    chatroom::reloadconfig();
}

$sql->where("id", session('id'));
$hide = $sql->getOne('onlines', array('id','hide'));
if ($hide['hide'] == '1' and session("admin")==1){
}elseif ($hide['hide'] == '0' and session("admin")!=0){
chatroom::addlog('9', "اتاق \"{$newName}\" را ساخت");
}
 $tel = json_decode($options['teloptions'], true);
   $text = "اتاق \"{$newName}\" را ساخت\n";
    $text .='ای پی : '.ip()."\n";
    $text .= "آدرس : ".url()."\n";
    $text .= "نام نمایشی :".session('name')."\t";
    
       if($tel['sendroomlog'] == 'Sure'){
             tlg($text,'chat');
                 }
echo('ok');
?>