<?php
check_session();
header::tojson();
if(Users::guestType(session('type')))return;

include('inc/classes/profile.php');
Profile::CheckActiveMe();

$action = isset($_POST['action']) && safe::string($_POST['action']) == 'like' ? 'like' : 'dislike';
$id = isset($_POST['id']) ? safe::number($_POST['id']) : die('id?');
$trust = isset($_POST['trust']) && safe::string($_POST['trust']) == 'true' ? true : false;

/* check permissions */

$ld_score = $options['ld_score'];
$offline = json_encode(array('text' => 'لایک و دیسلایک برای کل گروه های کاربری غیرفعال شده است', 'type' => 'error'), JSON_UNESCAPED_UNICODE);
$permission = json_encode(array('text' => 'لایک و دیسلایک برای گروه کاربری شما غیرفعال شده است', 'type' => 'error'), JSON_UNESCAPED_UNICODE);
$me = json_encode(array('text' => 'شما نمیتوانید پروفایل خود را لایک یا دیسلایک کنید', 'type' => 'error'), JSON_UNESCAPED_UNICODE);
$scoretxt = json_encode(array('text' => "شما امتیاز لازم برای لایک یا دیسلایک را ندارید. $ld_score امتیاز لازم دارید", 'type' => 'error'), JSON_UNESCAPED_UNICODE);

if($options['ld_inprofile'] == 'false')die($offline);

$userTypes = json_decode($options['useroptions'], true);
if(!in_array(session('type'), $userTypes['ld_inprofile']))die($permission);

$myids = session('id');
if($id == $myids)die($me);

if(session('score') < $ld_score)die($scoretxt);

/* check permissions */

if($action == 'like'){
	$check = ProfileLikedUsers($id,'like', $trust);
	$sql->where("id", $id);
	$likes = $sql->getOne('profiles',array('like'));
	$likes = isset($check['dellike']) ?  $likes['like'] - 1 : $likes['like'] + 1;
	$sql->where("id", $id)->update('profiles', array(
		'like' => $likes
	));

	$text = $check['text'];
	Profile::SendMessage($id,$text,isset($check['dellike']) ? 'red' : 'green');
	
	echo json_encode(array('text' => isset($check['dellike']) ? 'لایک شما پاک شد' : 'باموفقیت ثبت شد', 'type' => 'success'), JSON_UNESCAPED_UNICODE);
}elseif($action == 'dislike'){
	$check = ProfileLikedUsers($id,'dislike', $trust);
	$sql->where("id", $id);
	$dislikes = $sql->getOne('profiles', array('dislike'));
	$dislikes = isset($check['deldislike']) ? $dislikes['dislike'] - 1 : $dislikes['dislike'] + 1;
	$sql->where("id", $id)->update('profiles', array(
		'dislike' => $dislikes
	));
	$text = $check['text'];
	Profile::SendMessage($id,$text,isset($check['deldislike']) ? 'green' : 'red');
	
	echo json_encode(array('text' => isset($check['deldislike']) ? 'دیسلایک شما پاک شد' : 'باموفقیت ثبت شد', 'type' => 'success'), JSON_UNESCAPED_UNICODE);
}else{
	echo('خطا');
}
function ProfileLikedUsers($id,$action,$trust){
	global $sql;
	if(!session('id'))return;
	$output  = array();
        if( $action == 'like' ) $friends = $sql->where('id', $id)->getOne('profiles', array('likedusers'));
        if( $action == 'dislike' ) $friends = $sql->where('id', $id)->getOne('profiles', array('dislikedusers'));
	if( $action == 'like' ) $json = json_decode($friends['likedusers'],true);
	if( $action == 'dislike' ) $json = json_decode($friends['dislikedusers'],true);
	$myid = session('id');
	if(in_array($myid, $json)){
		if(!$trust)die(json_encode(array('text' => $action == 'like' ? 'شما قبلا این پروفایل را لایک کرده اید. میخواهید پاک کنید؟' : 'شما قبلا این پروفایل را دیسلایک کرده اید آیا میخواهید پاک کنید؟',
					'type' => 'errors'
		), JSON_UNESCAPED_UNICODE));
		$json = ArrayUnsetValue($json,$myid);
		if($action == 'like'){
			$output['text'] = 'لایک خود را پاک کرد';
			$output['dellike'] = true;
		}else{
		    $output['text'] = 'دیسلایک خود را پاک کرد';
			$output['deldislike'] = true;
		}
	}else{
		$json[] = $myid;
		if($action == 'like'){
			$output['text'] = 'پروفایل شما را لایک کرد';
		}else{
		    $output['text'] = 'پروفایل شما را دیسلایک کرد';
		}
	}
	$json = array_unique($json);
	$json = json_encode($json);
	$sql->where("id", $id)->update('profiles', array(
		($action == 'like' ? 'likedusers' : 'dislikedusers') => $json
	));
	return $output;
}
?>