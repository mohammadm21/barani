<?php
check_session();
header::tojson();
if(Users::guestType(session('type')))return;

	$pid = isset($_POST['pid']) ? safe::number($_POST['pid']) : die('pid?');
	$action = isset($_POST['action']) && safe::string($_POST['action'])  == 'add' ? 'add' : 'del';
	
	$sql->join('profiles p', 'p.id=u.id', 'LEFT');
	$sql->where("u.id", $pid);
	$user = $sql->getOne('users u', array(
		'u.blacklist',
		'p.followers',
		'u.type'
	));
	if($usertype[$user['type']]['admin'] != '0' && session('admin') == '0'){
		$text = array('text' => 'شما نمیتوانیید با مدیران و ناظران قطع ارتباط کنید', 'type' => 'error');
		die(json_encode($text, JSON_UNESCAPED_UNICODE));
	}elseif($usertype[$user['type']]['admin'] != '0' && session('admin') != '0'){
		if(!CheckAdminType($user['type'])){
			$text = array('text' => 'شما نمیتوانیید با این کاربر قطع ارتباط کنید', 'type' => 'error');
			die(json_encode($text, JSON_UNESCAPED_UNICODE));
		}
	}
	
	
	$myid = session('id');
	
	if($pid == $myid)die();
	
	$sql->join('profiles p', 'p.id=u.id', 'LEFT');
	$sql->where("u.id", $myid);
	$get = $sql->getOne('users u', array(
		'u.blacklist',
		'p.followers'
	));
        $blacklist = json_decode($get['blacklist'], true);
	
	$blacklist = ArrayUnsetValue($blacklist, $pid);
	if($action == 'add')$blacklist[] = $pid;
	$blacklist = array_unique($blacklist);
	$blacklist = array_values($blacklist);
	$blacklist = json_encode($blacklist);
	$sql->where("id", $myid)->update('users', array(
		'blacklist' => $blacklist
	));
	
	if($action == 'add'){
		$myjson = json_decode($get['followers'], true);
		$userjson = json_decode($user['followers'], true);
		
		
		$myjson['users'] = ArrayUnsetValue($myjson['users'], $pid);
		
		$myjson['users'] = array_values($myjson['users']);
		$myjson = json_encode($myjson);
		
		$sql->where("id", $myid)->update('profiles', array(
			'followers' => $myjson
		));
		
		$userjson['me'] = ArrayUnsetValue($userjson['me'], $myid);
		
		$userjson['me'] = array_values($userjson['me']);
		$userjson = json_encode($userjson);
		
		$sql->where("id", $pid)->update('profiles', array(
			'followers' => $userjson
		));
	}	
	
	if($action == 'add'){
		$txt = 'قطع ارتباط با موفقیت انجام شد';
	}else{
		$txt = 'کاربر از لیست قطع ارتباط شده ها پاک شد';
	}
	
	$text = array('text' => $txt, 'type' => 'success');
	
	echo json_encode($text, JSON_UNESCAPED_UNICODE);


?>
