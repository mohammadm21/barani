<?php
check_session();
header::tojson();

include('inc/classes/profile.php');
Profile::CheckActiveMe();

	$pid = isset($_POST['pid']) ? safe::number($_POST['pid']) : die('pid?');
	$proid = isset($_POST['proid']) ? safe::number($_POST['proid']) : die('proid?');
	
	$text = array('text' => 'گزارش شما ارسال شد', 'type' => 'success');
	echo json_encode($text, JSON_UNESCAPED_UNICODE);
	
	if($proid == session('id')){
		$sql->where("id", $pid);
		$proid = $sql->getValue('profile_posts', 'userid');
	}
	
	Profile::Report2Admins($proid, $pid);
	
	
?>