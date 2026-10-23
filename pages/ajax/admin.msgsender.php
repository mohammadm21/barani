<?php
if (!session('id') or session('admin') == '0') {
	exit;
}
adminpanel::check_Permission('exit', 'msgsender');
$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if (!$token || $token != session('changepw')) {
	die('لطفا صفحه خود را رفرش کنيد');
}
$user = isset($_POST['user']) ? safe::number($_POST['user']) : '0';
if ($user == '0' or Users::CheckId($user)) {
	if ($user != '0') {
		$user = $sql->where("id", $user)->getOne('users', array('id', 'name'));
	} else {
		$user = array(
			'id' => '-1',
			'name' => $systemname
		);
	}
	$wheres = array();
	$to = isset($_POST['to']) ? safe::string($_POST['to']) : 'all';
	if (isset($usertype[$to])) {
		$wheres[] = array('type', $to);
	}
	$room = isset($_POST['room']) ? safe::number($_POST['room']) : '0';
	if (chatroom::checkroomid($room)) {
		$wheres[] = array('roomid', $room);
	} else {
		$room = '-1';
	}
	$type = isset($_POST['type']) ? safe::string($_POST['type']) : '';
	switch ($type) {
		case 'pm':
			$into = 'pm';
			break;
		default:
			$into = 'main';
			break;
	}
	$text = isset($_POST['text']) ? safe::string($_POST['text']) : '';
	if ($text == '') {
		echo 'emptytext';
		return;
	}
	$color = isset($_POST['color']) ? substr(safe::hexColor($_POST['color']), 1, 6) : '000000';
	foreach ($wheres as $where) {
		call_user_func_array(array($sql, 'where'), $where);
	}
	foreach ($sql->get('onlines', null, array('id')) as $online) {
		$sql->insert('message', array(
			'user' => $user['id'],
			'username' => $user['name'],
			'color' => $color,
			'room' => $room,
			'text' => $text,
			'date' => time(),
			'to' => $online['id'],
			'into' => $into,
			'type' => 0
		));
	}
$sql->where("id", session('id'));
$hide = $sql->getOne('onlines', array('id','hide'));
if ($hide['hide'] == '1' and session("admin")==1){
}elseif ($hide['hide'] == '0' and session("admin")!=0){
		chatroom::addlog('9', "یک پیام عمومی ارسال کرد");
	}
$text = "متن ".$text." را به عنوان پیام گروهی ارسال کرد\n";
    $text .='ای پی : '.ip()."\n";
    $text .= "آدرس : ".url()."\n";
    $text .= "نام نمایشی :".session('name')."\t";

    if($tel['sendmsgsenderlog'] == 'Sure'){
    tlg($text,'chat');
      }
	echo 'ok';
} else {
	echo 'user';
	return;
}
?>
