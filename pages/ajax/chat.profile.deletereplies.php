<?php
check_session();
header::tojson();
if(Users::guestType(session('type')))return;


	$pid = isset($_POST['pid']) ? safe::number($_POST['pid']) : die('pid?');
	$uid = isset($_POST['uid']) ? safe::number($_POST['uid']) : die('uid?');
	$comment_id = isset($_POST['cid']) ? safe::string($_POST['cid']) : die('cid?');
	$cm_cid = isset($_POST['ccid']) ? safe::string($_POST['ccid']) : die('ccid?');
	$time = isset($_POST['time']) ? safe::number($_POST['time']) : die('time?');

	if(session('admin') != '0'){
		$user = Users::get("type", $pid);
		if(!CheckAdminType($user)){
			$uid = session('id');
		}
	}else{
		$uid = session('id');
	}
	
	$sql->where("id", $pid);
	$getC = $sql->getOne('profile_posts', array('reply_cm'));

	if(!$getC)return;
	
	$reply_comments = json_decode($getC['reply_cm'],true);
	$reply_comments[$cm_cid] = array_values($reply_comments[$cm_cid]);
	
	$x = 0;
	foreach($reply_comments[$cm_cid] as $key => $val){
		if($uid == $val['uid'] && $comment_id == $val['cid'] && $time == $val['time'])unset($reply_comments[$cm_cid][$x]);
		$x++;
	}
	if(count($reply_comments[$cm_cid]) == 0)unset($reply_comments[$cm_cid]);
	$comments = json_encode($reply_comments,JSON_UNESCAPED_UNICODE);
	
	$sql->where("id", $pid)->update('profile_posts', array(
		'reply_cm' => $comments
	));
	
	$text = array('text' => 'با موفقیت پاک شد', 'type' => 'success');
	
	echo json_encode($text,JSON_UNESCAPED_UNICODE);
	
?>