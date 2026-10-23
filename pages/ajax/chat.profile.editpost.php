<?php
check_session();
header::tojson();
if(Users::guestType(session('type')))return;

include('inc/classes/profile.php');
Profile::CheckActiveMe();

$id = isset($_POST['pid']) ? safe::number($_POST['pid']) : die('pid?');

if(!isset($_POST['action']))$_POST['action'] = 'get';

$user_id = session('id');

	$sql->where("id", $id);
	$sql->where("userid", $user_id);
	$post = $sql->getOne("profile_posts", array('userid', 'content'));
	
	if($post['userid'] != $user_id)die(json_encode(array('text' => 'شما ارسال کننده این پست نیستید.', 'type' => 'error'), JSON_UNESCAPED_UNICODE));
	if($_POST['action'] == 'get'){
		$content = str_replace("<br>",'\n',$post['content']);
		echo(json_encode(array('text' => $content, 'type' => 'success'), JSON_UNESCAPED_UNICODE));
	}
	if($_POST['action'] == 'update'){
		$content = isset($_POST['post']) ? safe::string($_POST['post']) : die('post content?');
		$content = str_replace("\n",'<br>',$content);
		$sql->where("id", $id)->update('profile_posts', array(
			'content' => $content
		));
		echo(json_encode(array('text' => "با موفقیت به روز رسانی شد.", 'html' => $content, 'type' => 'success'), JSON_UNESCAPED_UNICODE));
	}
?>