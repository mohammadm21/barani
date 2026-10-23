<?php
check_session();
if(Users::guestType(session('type')))return;

	//if(session('id') != '3232257')exit('به زودی .... ! ');
	
	
	$pid = isset($_POST['pid']) ? safe::number($_POST['pid']) : die('pid?');
	$comment = isset($_POST['comment']) ? safe::string($_POST['comment']) : die('comment?');
	$comment_id = isset($_POST['cid']) ? safe::string($_POST['cid']) : die('cid?');
	
	$sql->where("id", $pid);
	$oldComments = $sql->getOne('profile_posts', array('reply_cm', 'userid', 'disabled'));
	
	$jdecode = json_decode($oldComments['reply_cm'], true);
	$oldComments = is_array($jdecode) ? $jdecode : array();
	
	$this_time = time();
	$reply_id = pwcreator::start().'-'.$this_time;

	$jdecode[$comment_id][] = array('cid' => $reply_id, 'uid' => session('id'), 'comment' => $comment, 'time' => $this_time);

	$data = json_encode($jdecode, JSON_UNESCAPED_UNICODE);
	
	$sql->where("id", $pid)->update('profile_posts',array(
		'reply_cm' => $data 
	));

	echo('ارسال شد');
?>