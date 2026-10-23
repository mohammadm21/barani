<?php
check_session();
header::tojson();

if(Users::guestType(session('type')))exit('guest !');

if(!chatroom::checkPermission('profile'))exit(json_encode(array('text' => 'اين قسمت براي گروه کاربري شما غيرفعال شده است', 'type' => 'error'), JSON_UNESCAPED_UNICODE));


include('inc/classes/profile.php');
Profile::CheckActiveMe();

	$shout = isset($_POST['action']) ? safe::string($_POST['content']) : exit('content?');
	$action = safe::string($_POST['action']);
	$text = array('text' => 'خطا', 'type' => 'error');
	
	if($action == 'shout'){
		if(mb_strlen($shout, 'UTF-8') > 170)exit(json_encode(array('text' => 'تعداد حرف ها نبايد بيشتر از 170 حرف باشد', 'type' => 'error'), JSON_UNESCAPED_UNICODE));
	$sql->where("id", session('id'))->update('profiles', array(
		'shout' => $shout
	));
		$text = array('text' => 'ok', 'type' => 'success');
	}
	
	echo json_encode($text, JSON_UNESCAPED_UNICODE);
	
	if(!empty($shout)){
		Profile::Send2Friends('فریاد خود را ویرایش کرد');
	}

?>