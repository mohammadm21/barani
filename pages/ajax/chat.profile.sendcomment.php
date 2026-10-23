<?php
check_session();
header::tojson();
if(Users::guestType(session('type')))return;

include('inc/classes/profile.php');
include('inc/classes/bbcode.php');
Profile::CheckActiveMe();

	$pid = isset($_POST['pid']) ? safe::number($_POST['pid']) : die('pid?');
	$comment = isset($_POST['comment']) ? safe::string($_POST['comment']) : die('comment?');
	$action = isset($_POST['action']) ? safe::string($_POST['action']) : die('action?');
	
	$offline = json_encode(array('text' => 'ارسال نظر برای کل گروه های کاربری غیرفعال شده است', 'type' => 'error'), JSON_UNESCAPED_UNICODE);
	$permission = json_encode(array('text' => 'ارسال نظر برای گروه کاربری شما غیرفعال شده است', 'type' => 'error'), JSON_UNESCAPED_UNICODE);
	$disabled = json_encode(array('text' => 'این مطلب قفل شده است شما اجازه ارسال نظر ندارید', 'type' => 'error'), JSON_UNESCAPED_UNICODE);
	$active = json_encode(array('text' => 'پروفايل کاربر توسط مدیران غیرفعال شده است', 'type' => 'error'), JSON_UNESCAPED_UNICODE);
	$closed = json_encode(array('text' => 'به دلیل بروز رسانی پروفايل تا اطلاع ثانوی امکان ارسال نظر وجود ندارد', 'type' => 'error'), JSON_UNESCAPED_UNICODE);
	$success = json_encode(array('text' => 'ok', 'type' => 'success'), JSON_UNESCAPED_UNICODE);
	
	if(session('admin') == '0'){
		if(Message::Profile_Checker($comment))
                 {
                   $filtertext = "در متن نظر شما کلمه مورد دار یا تبلیغی وجود دارد";
                   die(json_encode(array('text' => $filtertext, 'type' => 'error'), JSON_UNESCAPED_UNICODE));
		  // $comment = Message::badwords($comment,'profile');
                 }
		}	
	
	if($options['send_comment_inprofile'] == 'false')die($offline);
	
	$userTypes = json_decode($options['useroptions'], true);
	if(!in_array(session('type'), $userTypes['send_comment_inprofile']))die($permission);
	
	switch($action){
		case 'avatar':
			$oldComments = db::get('profiles','`cavatar`,`active`',"`id`='$pid'");
			$table  = 'profiles';
			$update = 'cavatar';
			$text   = 'یک نظر در قسمت آواتار پروفايل شما ارسال کرد';
		break;
		case 'cover':
			$oldComments = db::get('profiles','`ccover`,`active`',"`id`='$pid'");
			$table  = 'profiles';
			$update = 'ccover';
			$text   = 'یک نظر در قسمت کاور پروفايل شما ارسال کرد';
		break;
		case 'info':
			$oldComments = db::get('profiles','`cinfo`,`active`',"`id`='$pid'");
			$table  = 'profiles';
			$update = 'cinfo';
			$text   = 'یک نظر در قسمت توضیحات پروفایل شما ارسال کرد';
		break;	
		case 'music':
			$oldComments = db::get('profiles','`cmusic`,`active`',"`id`='$pid'");
			$table  = 'profiles';
			$update = 'cmusic';
			$text   = 'یک نظر در قسمت آهنگ مورد علاقه پروفايل شما ارسال کرد';
		break;
		default:
			$oldComments = db::get('profile_posts','`comments`,`userid`, `disabled`', "`id`='$pid'");
			$active_profile = db::get('profiles','`active`', "`id`='".$oldComments['userid']."'");
			$pathp = $oldComments['userid'];
			$table = 'profile_posts';
			$update = 'comments';
		break;
	}
	if(!$oldComments)return;
	if(!$update)return;
	if(empty($pid) || empty($comment))die();
	if($oldComments['disabled'] == '1')die($disabled);
	if($active_profile[0] == '0')die($active);
	if($oldComments['active'] == '0')die($active);
	
	$comment = str_replace("\n",'<br>',$comment);

	$jdecode = json_decode($oldComments[0], true);
	$oldComments = is_array($jdecode) ? $jdecode : array();
	$this_time = time();
	$comment_id = pwcreator::start().'-'.$this_time;
	$NewComments = array_merge($oldComments, array(session('id') => array('cid' => $comment_id,'uid' => session('id'), 'comment' => $comment, 'time' => $this_time)));
	
	$data = json_encode($NewComments, JSON_UNESCAPED_UNICODE);
	
	db::update($table,"`$update`='$data'", "`id`='$pid'");
	
	echo($success);
	if($table == 'profile_posts'){
		ProfileSend2Friends($pathp,$pid,$comment);
	}elseif($pid != session('id')){
		ProfileSendMessage($pid,$text,$comment);
	}
	
function ProfileSend2Friends($pid,$postid,$comment){
	global $usertype;
	
	$get = db::get('users', '`name`, `type`, `gender`', "`id`='$pid'");
	$myid = session('id');
	$type = $usertype[session('type')]['icon'];
	$sex = session('gender') == '2' ? theme::url('images/chat.user_comment_w.png') : theme::url('images/chat.user_comment_m.png');
	$usex = $get['gender'] == '2' ? theme::url('images/chat.user_comment_w.png') : theme::url('images/chat.user_comment_m.png');
	$color = $usertype[session('type')]['color'];
	$profile = '<a href="/profile/'.$pid.'/'.$postid.'" target="_blank" title="پروفایل"><img src="'.theme::url("images/chat.pr.png").'" /></a>';
	$note = '<table cellpadding="3" border="1" align="center">
			<tr><td><b>کاربر:</b></td><td><b><a href=javascript:void(0) onclick=changeUserEvent("privatwin_'.$myid.'")><font color='.$color.'>'.session('name').'</font></a></b> <img src="/'.$type.'"><img src="'.$sex.'"></td></tr>
			<tr><td><b>پروفايل:</b></td><td><b><a href=javascript:void(0) onclick=changeUserEvent("privatwin_'.$pid.'")><font color='.$usertype[$get['type']]['color'].'>'.$get['name'].'</font></a></b> <img src="/'.$usertype[$get['type']]['icon'].'"><img src="'.$usex.'"></td></tr>
			<tr><td><b>آدرس مستقیم مطلب:</b></td><td>'.$profile.'</td></tr>
			<tr><td><b>نظر:</b></td><td><b><font color=green>'.bbcode::SmileComment($comment).'</font></b></td></tr>
		</table><hr>';
	$myfriends = Users::get('friends',$myid);
	$myfriends = json_decode($myfriends,true);
	if($myid != $pid && !in_array($pid,$myfriends)){
		$myfriends[] = $pid;
	}
	if(count($myfriends) == '0')return;
	$implode = implode(',', $myfriends);
	$query = db::query("SELECT u.`id`,p.`settings` FROM {prefix}users u LEFT JOIN {prefix}profiles p ON p.`id` = u.`id` WHERE u.`id` IN($implode)");
	while($row = mysqli_fetch_array($query)){
		$u_setting = json_decode($row['settings'], true);
		if($u_setting['notification'] == '0')continue;
		$text = $pid == $row['id'] ? 'گزارش پروفايل شما' : 'ارسال نظر در پروفايل';
		db::insert('message', '`user`, `username`, `color`, `room`, `text`, `date`, `to`, `into`, `type`',
		 "'-1', '<center>SYSTEMLKCM [ <b><font color=red>".$text."</font></b> ]</center>',
		 '000000', '-1', '".$note."', '".time()."', '".$row['id']."', 'pm', '".session('admin')."'");
	}
	mysqli_free_result($query);
}
function ProfileSendMessage($id,$text,$comment){
	global $usertype;
	$type = $type = $usertype[session('type')]['icon'];
	$sex = session('gender') == '2' ? theme::url('images/chat.user_comment_w.png') : theme::url('images/chat.user_comment_m.png');
	$color = $usertype[session('type')]['color'];
	$profile = '<a href="/profile/'.session('id').'" target="_blank" title="پروفایل"><img src="'.theme::url("images/chat.pr.png").'" /></a>';
	$note = '<table cellpadding="3" border="1" align="center">
			<tr><td><b>کاربر:</b></td><td><b><a href=javascript:void(0) onclick=changeUserEvent("privatwin_'.session('id').'")><font color='.$color.'>'.session('name').'</font></a></b> <img src="/'.$type.'"><img src="'.$sex.'"></td></tr>
			<tr><td><b>متن:</b></td><td><b><font color=green>'.$text.'</font></b></td></tr>
			<tr><td><b>نظر:</b></td><td><b><font color=green>'.bbcode::SmileComment($comment).'</font></b></td></tr>
			<tr><td><b>آدرس پروفايل:</b></td><td>'.$profile.'</td></tr>
		</table><hr>';
	$user_s  = mysqli_fetch_array(db::query("SELECT p.`settings` FROM {prefix}users u LEFT JOIN {prefix}profiles p ON p.`id` = u.`id` WHERE u.`id` = '$id'"));
	$u_setting = json_decode($user_s['settings'], true);
	if($u_setting['notification'] == '1'){
		db::insert('message', '`user`, `username`, `color`, `room`, `text`, `date`, `to`, `into`, `type`',
		 "'-1', '<center>SYSTEMLKCM [ <b><font color=red>گزارش پروفایل شما</font></b> ]</center>',
		 '000000', '-1', '".$note."', '".time()."', '".$id."', 'pm', '".session('admin')."'");
	}
	mysqli_free_result($user_s);
}

?>