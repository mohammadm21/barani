<?php 
check_session();
header::tojson();

include('inc/classes/profile.php');
Profile::CheckActiveMe();

	$id = isset($_POST['id']) ? safe::number($_POST['id']) : die('id?');
	$pid = isset($_POST['pid']) ? safe::number($_POST['pid']) : die('pid?');
	$action = isset($_POST['action']) && safe::string($_POST['action']) == '1' ? '1' : '0';
	
	$myid = session('id');
	if(session('admin') != '0'){
		$admin = '';
		if($id != $myid){
			$user = Users::get('name', $id);
			$profile = '<a href="/profile/'.$id.'/'.$pid.'" target="_blank" title="آدرس مستقیم مطلب"><img src="'.theme::url("images/chat.pr.png").'" /></a>';
			if($action == '1'){
				$log = "غیرفعال کردن نظرات مطلب کاربر $user $profile";
			}else{
				$log = "فعال کردن نظرات مطلب کاربر $user $profile";
			}
			chatroom::addlog('5', $log);
		}
	}else{
		$sql->where("userid", $myid);
	}
	$sql->where("id", $pid)->update('profile_posts', array(
		'disabled' => $action
	));
	$text = $action == '1' ? 'غیرفعال شد' : 'فعال شد';
	echo json_encode(array('text' => $text, 'type' => 'success'), JSON_UNESCAPED_UNICODE);
	
?>