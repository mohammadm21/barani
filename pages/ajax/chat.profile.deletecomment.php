<?php
check_session();
header::tojson();
if(Users::guestType(session('type')))return;

include('inc/classes/profile.php');
Profile::CheckActiveMe();

	$pid = isset($_POST['pid']) ? safe::number($_POST['pid']) : die('pid?');
	$uid = isset($_POST['uid']) ? safe::number($_POST['uid']) : die('uid?');
	$comment_id = isset($_POST['cid']) ? safe::string($_POST['cid']) : die('cid?');
	$time = isset($_POST['time']) ? safe::number($_POST['time']) : die('time?');
	$action = isset($_POST['action']) ? safe::string($_POST['action']) : die('action?');
	
		if(session('admin') != '0'){
		$user = Users::get("type", $uid);
		if(!CheckAdminType($user)){
			$uid = session('id');
		}
	}else{
		$uid = session('id');
	}
	
	switch($action){
		case 'avatar':
			$sql->where("id", $pid);
			$getC = $sql->getValue('profiles', 'cavatar');
			$table = 'profiles';
			$column = 'cavatar';
		break;	
		case 'cover':
			$sql->where("id", $pid);
			$getC = $sql->getValue('profiles', 'ccover');
			$table = 'profiles';
			$column = 'ccover';
		break;	
		case 'info':
			$sql->where("id", $pid);
			$getC = $sql->getValue('profiles', 'cinfo');
			$table = 'profiles';
			$column = 'cinfo';
		break;
		case 'music':
			$sql->where("id", $pid);
			$getC = $sql->getValue('profiles', 'cmusic');
			$table = 'profiles';
			$column = 'cmusic';
		break;	
		default:
			$sql->where("id", $pid);
			$getC = $sql->getValue('profile_posts', 'comments');
			$table = 'profile_posts';
			$column = 'comments';
		break;
	}
	if(!$getC)return;
	$comments = json_decode($getC,true);
	
	$x = 0;
	foreach($comments as $key => $val){
		if($uid == $val['uid'] && $comment_id == $val['cid'] && $time == $val['time'])unset($comments[$x]);
		$x++;
	}
	$comments = array_merge(array(), $comments);
	$comments = json_encode($comments,JSON_UNESCAPED_UNICODE);
	
	$sql->where("id", $pid)->update($table, array(
		$column => $comments
	));
	
	
	$text = array('text' => 'با موفقیت پاک شد', 'type' => 'success');
	
	echo json_encode($text,JSON_UNESCAPED_UNICODE);
	
?>