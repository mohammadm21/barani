<?php
set_time_limit(0);
check_session();
if(Users::guestType(session('type')))return;

include('inc/classes/profile.php');
Profile::CheckActiveMe();

$output_dir = 'theme/music/';
$music_name = session('id').'.mp3';
$myid = session('id');
$extension = end(explode(".", strtolower($_FILES["myfile"]["name"]))) != 'mp3' ? die(RedError('در صورتی که این پیامو مشاهده کردید  یعنی فرمت فایل شما mp3 نیست یا اینکه حجم فایل شما زیاد است')) : '';
@unlink('theme/music/'.$music_name);
move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$music_name);
	$music = array();
	$music['music_title'] = safe::string($_FILES["myfile"]["name"]);
	$music['music'] = '/theme/music/'.$music_name.'?rand='.rand(1111,9999);
	$music['music_info'] = safe::string($_POST['music_info']);
	$encode = json_encode($music, JSON_UNESCAPED_UNICODE);
	$sql->where("id", $myid)->update('profiles', array(
		'music' => $encode,
		'dmusic' => '',
		'cmusic' => '',
		'ldumusic' => ''
	));

	$sql->where("userid", $myid)->update('profile_counts', array(
		'dlmusic' => '',
		'lmusic' => '',
		'dmusic' => ''
	));

	Profile::Send2Friends('آهنگ پروفايل خود را عوض کرد');

	echo(RedError('آهنگ شما با موفقیت آپلود شد<br>حالا کافیست پروفایل خود را ذخیره کنید.','green'));
?>