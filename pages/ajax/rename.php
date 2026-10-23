<?php
check_session();
$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if (!$token || $token != session('changepw')) {
    die('لطفا صفحه خود را رفرش کنيد');
}
if (!chatroom::checkPermission('modrenameuser')) {
    exit('اين قابليت براي گروه کاربري شما غير فعال شده است');
}
$admin = session('admin');
$uid = safe::number($_POST['id']);
$vip_user_is_admin = false;
$sql->where("id", safe::number($_POST['id']));
$get_room_id = $sql->getValue('onlines', 'roomid');
$sql->where("id", $get_room_id['roomid']);
$me_room = $sql->getOne("rooms", 'vip_user');
$json = json_decode($me_room[0], true);
if($json['vip_user'] == session('id')){$vip_user_is_admin = true;}
if($admin != '0' or $vip_user_is_admin){
    $protect = true;
	$checkdec = json_decode($options['mod_protect'], true);
	if($checkdec['status'] == '1'){
		if(!isset($_POST['password'])) exit('pw');
		if($checkdec['pw'] != $_POST['password']){
			$protect = false;
			exit('کلمه عبور اشتباه است');
		}else{
			$protect = true;
		}
	}
	if($protect){
		Access2Manage($uid);
		$newname = safe::string($_REQUEST['newname']);
		if($newname == 'random'){
		//	$newname = 'User19'.'.'.pwcreator::start(8);
			$newname = 'User'.'.'.rand(111111,999999);
		}
		if(Users::Checkrunning($newname, true)){echo('running');return;}
		$id = safe::number($_REQUEST['id']);
		$sql->where("id", $id);
		$user = $sql->getOne('onlines', array('name'));
		if(function_exists('mb_substr'))$newname = mb_substr($newname, 0, (int)$options['chat_maxlengthname'], 'utf-8');
		Users::rename($id, $newname);
		chatroom::addlog('4', "نام {$user['name']} را به $newname تغییر داد");
		if($_REQUEST['newname'] == 'random'){
			echo('تغییر داده شد به '.$newname);
		}else{
			echo('ok');	
		}
	}
}else exit('permission');
?>