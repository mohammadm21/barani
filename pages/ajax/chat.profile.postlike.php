<?php
check_session();
header::tojson();
if(Users::guestType(session('type')))return;

include('inc/classes/profile.php');
Profile::CheckActiveMe();

$action = isset($_POST['action']) && safe::string($_POST['action']) == 'like' ? 'like' : 'dislike';
$id = isset($_POST['pid']) ? safe::number($_POST['pid']) : die('pid?');
$trust = isset($_POST['trust']) && safe::string($_POST['trust']) == 'true' ? true : false;

if(session('type') == '1')die('مهمان اجازه ندارد');

if($action == 'like'){
	$check = ProfileLikedUsers($id,'like',$trust);;
	$sql->where("id", $id);
	$likes = $sql->getOne('profile_posts',array('likes'));
	$likes = isset($check['dellike']) ?  $likes['likes'] - 1 : $likes['likes'] + 1;
	$sql->where("id", $id)->update('profile_posts', array(
		'likes' => $likes
	));

	$text = $check['text'];
	//ProfileSendMessage($id,$text,isset($check['dellike']) ? 'red' : 'green');
	
	echo json_encode(array('text' => isset($check['dellike']) ? 'لایک شما پاک شد' : 'باموفقیت ثبت شد', 'type' => 'success'), JSON_UNESCAPED_UNICODE);
}elseif($action == 'dislike'){
	$check = ProfileLikedUsers($id,'dislike',$trust);
	$sql->where("id", $id);
	$dislikes = $sql->getOne('profile_posts',array('dislikes'));
	$dislikes = isset($check['deldislike']) ? $dislikes['dislikes'] - 1 : $dislikes['dislikes'] + 1;
	$sql->where("id",$id)->update('profile_posts', array(
		'dislikes' => $dislikes
	));
	$text = $check['text'];
	//ProfileSendMessage($id,$text,isset($check['deldislike']) ? 'green' : 'red');
	
	echo json_encode(array('text' => isset($check['deldislike']) ? 'دیسلایک شما پاک شد' : 'باموفقیت ثبت شد', 'type' => 'success'), JSON_UNESCAPED_UNICODE);
}else{
	echo(json_encode(array('text' => 'خطا', 'type' => 'error'), JSON_UNESCAPED_UNICODE));
}


function ProfileLikedUsers($id,$action,$trust){
	global $sql;
	if(!session('id'))return;
	$output  = array();
        if( $action == 'like' ) $friends = $sql->where('id', $id)->getOne('profile_posts', array('likedusers', 'userid'));
        if( $action == 'dislike' ) $friends = $sql->where('id', $id)->getOne('profile_posts', array('dislikedusers', 'userid'));
	if(session('id') == $friends['userid'])die(json_encode(array('text' => 'me', 'type' => 'error'), JSON_UNESCAPED_UNICODE));
        if( $action == 'like' ) $json = json_decode($friends['likedusers'],true);
        if( $action == 'dislike' ) $json = json_decode($friends['dislikedusers'],true);
	$myid = session('id');
	if(in_array($myid, $json)){
		if(!$trust)die(json_encode(array('text' => $action == 'like' ? 'شما قبلا این پست را لایک کرده اید. میخواهید پاک کنید؟' : 'شما قبلا این پست را دیسلایک کرده اید آیا میخواهید پاک کنید؟',
					'type' => 'error'
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
	$sql->where("id", $id)->update('profile_posts', array(
		($action == 'like' ? 'likedusers' : 'dislikedusers') => $json
	));
	return $output;
}
function ProfileSendMessage($id,$text,$textColor){
	global $usertype, $sql;
	$type = url('theme/images/user-types/'.session('type').'.png');
	$sex = session('gender') == '2' ? theme::url('images/chat.user_comment_w.png') : theme::url('images/chat.user_comment_m.png');
	$color = $usertype[session('type')]['color'];
	$profile = '<a href="'.url("profile/".session('id')).'" target="_blank" title="پروفایل"><img src="'.theme::url("images/chat.pr.png").'" /></a>';
	$note = '<table cellpadding="3" border="1" align="center">
			<tr><td><b>کاربر:</b></td><td><b><a href=javascript:void(0) onclick=changeUserEvent("privatwin_'.session('id').'")><font color='.$color.'>'.session('name').'</font></a></b> <img src="'.$type.'"><img src="'.$sex.'"></td></tr>
			<tr><td><b>متن:</b></td><td><b><font color='.$textColor.'>'.$text.'</font></b></td></tr>
			<tr><td><b>آدرس پروفايل:</b></td><td>'.$profile.'</td></tr>
		</table><hr>';


	$sql->insert('message', array(
		'user' => '-1',
		'username' => '<center>SYSTEMLKCM [ <b><font color=red>گزارش پروفایل شما</font></b> ]</center>',
		'color' => '000000',
		'room' => '-1',
		'text' => $note,
		'date' => time(),
		'to' => $id,
		'into' => 'pm',
		'type' => session('admin')
	));
}
?>