<?php
check_session();
header::tojson();
if(Users::guestType(session('type')))return;
$check_user_admin = session('admin');
include('inc/classes/profile.php');
include('inc/classes/bbcode.php');
Profile::CheckActiveMe();


	$post = isset($_POST['post']) ? db::escape($_POST['post']) : die('post?');
	
	$error = json_encode(array('text' => 'فیلد متن نباید خالی باشد', 'type' => 'error'), JSON_UNESCAPED_UNICODE);
	$error_length = json_encode(array('text' => 'تعداد کلمات زیاد است', 'type' => 'error'), JSON_UNESCAPED_UNICODE);
	$success = json_encode(array('text' => 'ارسال شد', 'type' => 'success'), JSON_UNESCAPED_UNICODE);
	$user_type = json_encode(array('text' => 'کاربران عضو نمیتوانند مطلب ارسال کنند', 'type' => 'error'), JSON_UNESCAPED_UNICODE);
	$offline = json_encode(array('text' => 'ارسال مطلب برای کل گروه های کاربری غیرفعال شده است', 'type' => 'error'), JSON_UNESCAPED_UNICODE);
	$permission = json_encode(array('text' => 'ارسال مطلب برای گروه کاربری شما غیرفعال شده است', 'type' => 'error'), JSON_UNESCAPED_UNICODE);
	$closed = json_encode(array('text' => 'تا ۱ ساعت امکان ارسال مطلب وجود ندارد - سیستم در حال ارتقا میباشد', 'type' => 'error'), JSON_UNESCAPED_UNICODE);
	//die($closed);
	
	if($options['send_post_inprofile'] == 'false')die($offline);
	
	$userTypes = json_decode($options['useroptions'], true);
	if(!in_array(session('type'), $userTypes['send_post_inprofile']))die($permission);

	if(empty($post))die($error);
	
	$myid = session('id');
	$time = time();

	//$nextid = $sql->where('table_name', db_prefix . 'profile_posts')->where('table_schema', db_name)->getValue('information_schema.tables', 'AUTO_INCREMENT');

	/*if(session('admin') == '0'){
		if(Message::Profile_Checker($post)) { 
                  $filtertext = "در متن مطلب شما کلمه مورد دار یا تبلیغی وجود دارد";
		  die(json_encode(array('text' => $filtertext, 'type' => 'error'), JSON_UNESCAPED_UNICODE));
                 // $post = Message::badwords($post,'profile');
                 }
		}	*/
	if(session('admin') != '1'){
		if(!session('countpost')){
			$sql->where("id", $myid);
			session('countpost', $sql->getValue('profiles', 'count'));
		}
		if(!session('sendpost')){
			$sql->where("userid", $myid);
			$sql->orderBy('id', 'DESC');
			session('sendpost', $sql->getValue('profile_posts', 'time'));
		}
		if (chatroom::checkPermission('send_post')) {
		if(session('countpost') > 3){
			$lastpost = (int)(($time - session('sendpost'))/60);
			if($lastpost <= 30){
				$lastpost = 30 - $lastpost;
				$lastText = "ِشما تا $lastpost دقیقه دیگر قادر به ارسال مطلب جدید نیستید";
				die(json_encode(array('text' => $lastText, 'type' => 'error'), JSON_UNESCAPED_UNICODE));
			}else{
				$sql->where("id", $myid)->update('profiles', array(
					'count' => 0
				));
				session('countpost', '0');
			}
			
		}
	   } 
  
	 } 
	
	

	$content = bbcode::remove($post);
	//if(mb_strlen($content, 'UTF-8') > 2000)die($error_length);
	
	$sql->insert('profile_posts', array(
		'userid' => $myid,
		'content' => $post,
		'time' => $time
	));
	$sql->where("userid", $myid);
	if(!$sql->has('profile_counts')){
		$sql->insert('profile_counts', array(
			'userid' => $myid,
			'posts' => 1
		));
	}else{
		$sql->where("userid", $myid)->update('profile_counts', array(
			'posts' => $sql->inc()
		));
	}
	
	if(session('admin') != '1'){
	$count = session('countpost') + 1;
		session('countpost', $count);
		session('sendpost', $time);
		
		$sql->where("id", $myid)->update('profiles', array(
			'count' => $count
		));
 

	}
	echo($success);

Profile::Send2Friends('مطلب جدید ارسال کرد');
?>