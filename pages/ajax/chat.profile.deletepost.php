<?php
check_session();
header::tojson();
if(Users::guestType(session('type')))return;

include('inc/classes/profile.php');
Profile::CheckActiveMe();

	$pid = isset($_POST['pid']) ? safe::number($_POST['pid']) : die('pid?');
	
	$myid = session('id');
	$access2del = false;
	
	$sql->join('users u', 'u.id=p.userid', 'LEFT');
	$sql->where("p.id", $pid);
	$get = $sql->getOne('profile_posts p', array(
		'u.name',
		'u.type',
		'p.userid'
	));
	
	$admin = $usertype[$get['type']]['admin'];
	
	if(session('admin') != '0' && $get['userid'] != $myid){
		
		$meadmin = session('admin');
$and = '';
			/*
		if($meadmin == '1' && $admin != '1')$access2del = true;
		
		if($meadmin == '2' && $admin == '0')$access2del = true;

		if($meadmin == '3' && $admin != '1' && $admin != '3')$access2del = true;
		*/
		if(CheckAdminType($get['type'])){
			$access2del = true;
		}else{
			$access2del = false;
		}
		if($access2del){
			chatroom::addlog('5', "مطلب ارسالی کاربر {$get['name']} در پروفایل را حذف کرد");
		}
	}else{
		//$and = "AND `userid` = '$myid'";
		$sql->where("userid", $myid);
		$access2del = true;
	}
	if($access2del){
		
		$uid = $get['userid'];
		$sql->where("id", $pid)->delete('profile_posts');
		$sql->where("userid", $uid)->update('profile_counts', array(
			'posts' => $sql->dec()
		));
		
	}

	if($access2del){
		$text = array('text' => 'با موفقیت پاک شد', 'type' => 'success');
	}else{
		$text = array('text' => 'ِشما اجازه پاک کردن این مطلب را ندارید', 'type' => 'error');
	}
	
	
	echo(json_encode($text, JSON_UNESCAPED_UNICODE));
?>