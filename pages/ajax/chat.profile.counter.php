<?php
check_session();
if(Users::guestType(session('type')))return;

include('inc/classes/profile.php');
Profile::CheckActiveMe();

	$pid = isset($_POST['pid']) ? safe::number($_POST['pid']) : die('pid?');
	if($pid != session('id')){
		$sql->where("id", $pid);
		$users = $sql->getValue('profiles', 'dmusic');
		//if(!$users)return;
		$users = json_decode($users, true);
		
		if(!in_array(session('id'), $users)){
			$users[] = session('id');
			$users = array_values($users);
			$users = json_encode($users);
			$sql->where("id", $pid)->update('profiles', array(
				'dmusic' => $users
			));
			$sql->where("userid", $pid)->update('profile_counts', array(
				'dlmusic' => $sql->inc()
			));
		}

	}
	
?>
