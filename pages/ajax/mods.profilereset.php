<?php
check_session();
$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if (!$token || $token != session('changepw')) {
	die('لطفا صفحه خود را رفرش کنيد');
}
if (!chatroom::checkPermission('modprofile')) {
	exit('اين قابليت براي گروه کاربري شما غير فعال شده است');
}
if (session('admin') != '0') {
	$uid = isset($_POST['uid']) ? safe::number($_POST['uid']) : die('uid?');
	$action = isset($_POST['action']) ? safe::string($_POST['action']) : die('لطفا اول  وارد پروفایل کاربر شوید و از سمت چپ گوشه بالا رو مدیریت کلیک کنید');
	$token = isset($_POST['token']) ? safe::string($_POST['token']) != session('changepw') ? die('خطا') : '' : die('token?');
	$get = $sql->where('id', $uid)->getOne('users', array('name', 'type'));
	!CheckAdminType($get['type']) ? die('شما اجازه پاک کردن این پروفایل را ندارید') : '';
	switch ($action) {
		case 'avatar':
			$info = $sql->where('id', $uid)->getOne('profiles', array('avatar'));
			if ($info['avatar'] != 'photo.png') {
			//	unlink($options['avatar_dir'] . '/' . $info['avatar']);
			//	unlink($options['avatar_dir'] . '/' . str_replace('*', '-', $options['thumbavatarSize']) . '/' . $info['avatar']);
			}
			$sql->where('id', $uid)->update('profiles', array(
				'last_avatars' => json_encode($last_avatars)
			));
			$sql->where('id', $uid)->update('profiles', array(
				'avatar' => "photo.png",
				'cavatar' => "",
				'lduavatar' => ""
			));
			$sql->where('userid', $uid)->update('profile_counts', array(
				'lavatar' => '',
				'davatar' => ''
			));
			chatroom::addlog('5', "آواتار کاربر " . $get['name'] . " را پاک کرد");
			Users::alert($uid, 'آواتار شما توسط یکی از ناظرین یا معاونین پاک شد');
			exit('آواتار کاربر پاک شد');
			break;
		case 'cover':
			$info = $sql->where('id', $uid)->getOne('profiles', array('cover'));
			$sql->where('id', $uid)->update('profiles', array(
				'cover' => "cover.jpg",
				'ccover' => "",
				'lducover' => ""
			));
			if ($info['cover'] != 'cover.jpg') {
				if (is_file('theme/images/covers/' . $info['cover'])) {
					//@unlink('theme/images/covers/' . $info['cover']);
				}
			}
			chatroom::addlog('5', "کاور پروفایل کاربر " . $get['name'] . " را پاک کرد");
			Users::alert($uid, 'کاور پروفایل شما توسط یکی از ناظرین یا معاونین پاک شد');
			exit('کاور کاربر پاک شد');
			break;
		case 'shout':
			$sql->where('id', $uid)->update('profiles', array(
				'shout' => ""
			));
			chatroom::addlog('5', "فریاد کاربر " . $get['name'] . " را پاک کرد");
			Users::alert($uid, 'فریاد شما توسط یکی از ناظرین یا معاونین پاک شد');
			exit('فریاد کاربر پاک شد');
			break;
		case 'music':
			$sql->where('id', $uid)->update('profiles', array(
				'cmusic' => "",
				'ldumusic' => "",
				'dmusic' => "",
				'music' => ""
			));
			$sql->where('userid', $uid)->update('profile_counts', array(
				'dlmusic' => '',
				'lmusic' => '',
				'dmusic' => ''
			));
			if (is_file('theme/music/' . $uid . '.mp3')) {
				@unlink('theme/music/' . $uid . '.mp3');
			}
			require_once 'inc/classes/ftp.class.php';
			try {
				$ftp = new Ftp();
				$ftp->connect($ftp->config['host']);
				$ftp->login($ftp->config['user'], $ftp->config['pass']);
				$ftp->tryDelete('public_html/' . db_name . '/music/' . $uid . '.mp3');
				$ftp->close();
			} catch (FtpException $e) {
				//
			}
			chatroom::addlog('5', "اهنگ پروفایل کاربر " . $get['name'] . " را پاک کرد");
			Users::alert($uid, 'آهنگ پروفایل شما توسط یکی از ناظرین یا معاونین پاک شد');
			exit('آهنگ مورد علاقه کاربر پاک شد');
			break;
		case 'info':
			$sql->where('id', $uid)->update('profiles', array(
				'info' => "",
				'cinfo' => "",
				'lduinfo' => ""
			));
			chatroom::addlog('5', "توضیحات کاربر " . $get['name'] . " را پاک کرد");
			Users::alert($uid, 'آهنگ پروفایل شما توسط یکی از ناظرین یا معاونین پاک شد');
			exit('توضیحات کاربر پاک شد');
			break;
		case 'posts':
			$sql->where('userid', $uid)->delete('profile_posts');
			$sql->where('userid', $uid)->delete('profile_counts');
			chatroom::addlog('5', "تمام مطالب ارسالی کاربر " . $get['name'] . " را پاک کرد");
			Users::alert($uid, 'تمام مطالب ارسالی شما توسط یکی از ناظرین یا معاونین پاک شد');
			exit('تمامی مطالب ارسالی کاربر حذف شد');
			break;
		case 'enable':
			$sql->where('id', $uid)->update('profiles', array(
				'active' => "1"
			));
			chatroom::addlog('5', "پروفایل کاربر \"" . $get['name'] . "\" را فعال کرد");
			Users::alert($uid, 'پروفایل شما توسط یکی از ناظرین یا معاونین فعال شد');
			exit('enable');
			break;
		case 'disable':
			$sql->where('id', $uid)->update('profiles', array(
				'active' => "0"
			));
			chatroom::addlog('5', "پروفایل کاربر \"" . $get['name'] . "\" را غیرفعال کرد");
			Users::alert($uid, 'پروفایل شما توسط یکی از ناظرین یا معاونین غیرفعال شد');
			exit('disable');
			break;
		case 'all':
        	if(session('id')==56)
            {
              return "ss";
            }
			$site = str_replace(array('http://', 'www'), '', url());
			$info = $sql->where('id', $uid)->getOne('profiles', array('avatar', 'cover'));
			if ($info['avatar'] != 'photo.png') {
				if (is_file($options['avatar_dir'] . '/' . $info['avatar'])) {
					//@unlink($options['avatar_dir'] . '/' . $info['avatar']);
				}
				if (is_file($options['avatar_dir'] . '/' . str_replace('*', '-', $options['thumbavatarSize']) . '/' . $info['avatar'])) {
					//@unlink($options['avatar_dir'] . '/' . str_replace('*', '-', $options['thumbavatarSize']) . '/' . $info['avatar']);
				}
			}
			if ($info['cover'] != 'cover.jpg') {
				if (is_file('theme/images/covers/' . $info['cover'])) {
					//@unlink('theme/images/covers/' . $info['cover']);
				}
			}
			$sql->where('id', $uid)->update('profiles', array(
				'count' => '',
				'cover' => 'cover.jpg',
				'info' => '',
				'site' => $site,
				'avatar' => 'photo.png',
				'cinfo' => '',
				'lduinfo' => '',
				'ccover' => '',
				'lducover' => '',
				'cavatar' => '',
				'music' => '',
				'dmusic' => '',
				'ldumusic' => '',
				'cmusic' => ''
			));
			$sql->where('userid', $uid)->delete('profile_posts');
			$sql->where('userid', $uid)->delete('profile_counts');
			if (is_file('theme/music/' . $uid . '.mp3')) {
				@unlink('theme/music/' . $uid . '.mp3');
			}
			require_once 'inc/classes/ftp.class.php';
			try {
				$ftp = new Ftp();
				$ftp->connect($ftp->config['host']);
				$ftp->login($ftp->config['user'], $ftp->config['pass']);
				$ftp->tryDelete('public_html/' . db_name . '/music/' . $uid . '.mp3');
				$ftp->close();
			} catch (FtpException $e) {
				//
			}
			chatroom::addlog('5', "تمام اطلاعات پروفایل کاربر " . $get['name'] . " را پاک کرد");
			Users::alert($uid, 'تمام اطلاعات پروفایل شما توسط یکی از ناظرین یا معاونین پاک شد');
			exit('کل اطلاعات کاربر پاک شد');
			break;
		default:
			die('خطا');
			break;
	}
} else {
	echo 'permission!';
}
?>
