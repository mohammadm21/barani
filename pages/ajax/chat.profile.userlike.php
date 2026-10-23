<?php
check_session();
header::tojson();
if (Users::guestType(session('type'))) {
	return;
}
include 'inc/classes/profile.php';
Profile::CheckActiveMe();
$action = isset($_POST['action']) && safe::string($_POST['action']) == 'like' ? 'like' : 'dislike';
$id = isset($_POST['pid']) ? safe::number($_POST['pid']) : die('pid?');
$trust = isset($_POST['trust']) && safe::string($_POST['trust']) == 'true' ? true : false;
$location = isset($_POST['location']) ? safe::string($_POST['location']) : die('location');
/* check permissions */
$closed = json_encode(array(
	'text' => 'سیستم در حال ارتقا میباشد فعلا غیرفعال',
	'type' => 'error'
), JSON_UNESCAPED_UNICODE);
//die($closed);
$ld_score = $options['ld_score'];
$offline = json_encode(array(
	'text' => 'لایک و دیسلایک برای کل گروه های کاربری غیرفعال شده است',
	'type' => 'error'
), JSON_UNESCAPED_UNICODE);
$permission = json_encode(array(
	'text' => 'لایک و دیسلایک برای گروه کاربری شما غیرفعال شده است',
	'type' => 'error'
), JSON_UNESCAPED_UNICODE);
$scoretxt = json_encode(array(
	'text' => "شما امتیاز لازم برای لایک یا دیسلایک را ندارید. {$ld_score} امتیاز لازم دارید",
	'type' => 'error'
), JSON_UNESCAPED_UNICODE);
if ($options['ld_inprofile'] == 'false') {
	die($offline);
}
$userTypes = json_decode($options['useroptions'], true);
if (!in_array(session('type'), $userTypes['ld_inprofile'])) {
	die($permission);
}
if (session('score') < $ld_score) {
	die($scoretxt);
}
$myid = session('id');
if ($id == $myid) {
	die(json_encode(array(
		'text' => 'me',
		'type' => 'error'
	), JSON_UNESCAPED_UNICODE));
}
/* check permissions */
if ($action == 'like') {
	$check = LDUsers($id, 'like', $trust, $location);
	if ($location == 'avatar') {
		if (!$sql->where("userid", $id)->has('profile_counts')) {
			$sql->insert("profile_counts", array(
				'userid' => $id,
				'lavatar' => 1
			));
		} else {
			$sql->where("userid", $id)->update('profile_counts', array(
				'lavatar' => isset($check['dellike']) ? $sql->dec() : $sql->inc()
			));
		}
	}
	if ($location == 'music') {
		if (!$sql->where("userid", $id)->has('profile_counts')) {
			$sql->insert("profile_counts", array(
				'userid' => $id,
				'lmusic' => 1
			));
		} else {
			$sql->where("userid", $id)->update('profile_counts', array(
				'lmusic' => isset($check['dellike']) ? $sql->dec() : $sql->inc()
			));
		}
	}
	$text = $check['text'];
	ProfileSendMessage($id, $text, isset($check['dellike']) ? 'red' : 'green');
	echo json_encode(array(
		'text' => isset($check['dellike']) ? 'لایک شما پاک شد' : 'باموفقیت ثبت شد',
		'type' => 'success'
	), JSON_UNESCAPED_UNICODE);
} elseif ($action == 'dislike') {
	$check = LDUsers($id, 'dislike', $trust, $location);
	if ($location == 'avatar') {
		if (!$sql->where("userid", $id)->has('profile_counts')) {
			$sql->insert("profile_counts", array(
				'userid' => $id,
				'davatar' => 1
			));
		} else {
			$sql->where("userid", $id)->update('profile_counts', array(
				'davatar' => isset($check['deldislike']) ? $sql->dec() : $sql->inc()
			));
		}
	}
	if ($location == 'music') {
		if (!$sql->where("userid", $id)->has('profile_counts')) {
			$sql->insert("profile_counts", array(
				'userid' => $id,
				'dmusic' => 1
			));
		} else {
			$sql->where("userid", $id)->update('profile_counts', array(
				'dmusic' => isset($check['deldislike']) ? $sql->dec() : $sql->inc()
			));
		}
	}
	$text = $check['text'];
	ProfileSendMessage($id, $text, isset($check['deldislike']) ? 'green' : 'red');
	echo json_encode(array(
		'text' => isset($check['deldislike']) ? 'دیسلایک شما پاک شد' : 'باموفقیت ثبت شد',
		'type' => 'success'
	), JSON_UNESCAPED_UNICODE);
} else {
	echo json_encode(array(
		'text' => 'خطا',
		'type' => 'error'
	), JSON_UNESCAPED_UNICODE);
}
function LDUsers($id, $action, $trust, $location) {
	global $sql;
	if (!session('id')) {
		return;
	}
	$output = array();
	switch ($location) {
		case 'avatar':
			$get = $sql->where("id", $id)->getValue('profiles', 'lduavatar');
			$update = 'lduavatar';
			$textReportLike = 'آواتار شما را لایک کرد';
			$textReportDisLike = 'آواتار شما را دیسلایک کرد';
			$textReportDelLike = 'در قسمت آواتار شما لایک خود را پاک کرد';
			$textReportDelDisLike = 'در قسمت آواتار شما دیسلایک خود را پاک کرد';
			break;
		case 'cover':
			$get = $sql->where("id", $id)->getValue('profiles', 'lducover');
			$update = 'lducover';
			$textReportLike = 'کاور شما را لایک کرد';
			$textReportDisLike = 'کاور شما را دیسلایک کرد';
			$textReportDelLike = 'در قسمت کاور شما لایک خود را پاک کرد';
			$textReportDelDisLike = 'در قسمت کاور شما دیسلایک خود را پاک کرد';
			break;
		case 'info':
			$get = $sql->where("id", $id)->getValue('profiles', 'lduinfo');
			$update = 'lduinfo';
			$textReportLike = 'توضیحات شما را لایک کرد';
			$textReportDisLike = 'توضیحات شما را دیسلایک کرد';
			$textReportDelLike = 'در قسمت توضیحات شما لایک خود را پاک کرد';
			$textReportDelDisLike = 'در قسمت توضیحات شما دیسلایک خود را پاک کرد';
			break;
		case 'music':
			$get = $sql->where("id", $id)->getValue('profiles', 'ldumusic');
			$update = 'ldumusic';
			$textReportLike = 'آهنگ شما را لایک کرد';
			$textReportDisLike = 'آهنگ شما را دیسلایک کرد';
			$textReportDelLike = 'در قسمت آهنگ شما لایک خود را پاک کرد';
			$textReportDelDisLike = 'در قسمت آهنگ شما دیسلایک خود را پاک کرد';
			break;
		default:
			die('location?');
			break;
		}
	if (!$update) {
		return;
	}
	$json = json_decode($get, true);
	$json = is_array($json) ? $json : array();
	$myarray = $action == 'like' ? $json['likedusers'] : $json['dislikedusers'];
	$myid = session('id');
	if (in_array($myid, $myarray)) {
		if (!$trust) {
			die(json_encode(array(
				'text' => $action == 'like' ? 'شما قبلا این قسمت را لایک کرده اید. میخواهید پاک کنید؟' : 'شما قبلا این قسمت را دیسلایک کرده اید آیا میخواهید پاک کنید؟',
				'type' => 'errors'
			), JSON_UNESCAPED_UNICODE));
		}
		foreach ($json as $key => $val) {
			$y = 0;
			foreach ($val as $value) {
				if ($key == ($action == 'like' ? 'likedusers' : 'dislikedusers') && $myid == $value) {
					unset($json[$key][$y]);
				}
				$y++;
			}
		}
		if ($action == 'like') {
			$output['text'] = $textReportDelLike;
			$output['dellike'] = true;
		} else {
			$output['text'] = $textReportDelDisLike;
			$output['deldislike'] = true;
		}
	} else {
		if ($action == 'like') {
			$json['likedusers'][] = $myid;
			$json['likedusers'] = array_values($json['likedusers']);
		} else {
			$json['dislikedusers'][] = $myid;
			$json['dislikedusers'] = array_values($json['dislikedusers']);
		}
		if ($action == 'like') {
			$output['text'] = $textReportLike;
		} else {
			$output['text'] = $textReportDisLike;
		}
	}
	//if(!$trust)$json = array_merge($jsonx, $json);
	//$json = array_unique($json);
	$json = json_encode($json);
	$sql->where("id", $id)->update("profiles", array(
		$update => $json
	));
	return $output;
}
function ProfileSendMessage($id, $text, $textColor) {
	global $usertype, $sql;
	$type = $usertype[session('type')]['icon'];
	$sex = session('gender') == '2' ? theme::url('images/chat.user_comment_w.png') : theme::url('images/chat.user_comment_m.png');
	$color = $usertype[session('type')]['color'];
	$profile = '<a href="' . url("profile/" . session('id')) . '" target="_blank" title="پروفایل"><img src="' . theme::url("images/chat.pr.png") . '" /></a>';
	$note = '<table cellpadding="3" border="1" align="center">
			<tr><td><b>کاربر:</b></td><td><b><a href=javascript:void(0) onclick=changeUserEvent("privatwin_' . session('id') . '")><font color=' . $color . '>' . session('name') . '</font></a></b> <img src="/' . $type . '"><img src="' . $sex . '"></td></tr>
			<tr><td><b>متن:</b></td><td><b><font color=' . $textColor . '>' . $text . '</font></b></td></tr>
			<tr><td><b>آدرس پروفايل:</b></td><td>' . $profile . '</td></tr>
		</table><hr>';
	$sql->insert("message", array(
		'user' => -1,
		'username' => '<center>SYSTEMLKCM [ <b><font color=red>گزارش پروفایل شما</font></b> ]</center>',
		'color' => '000000',
		'room' => -1,
		'text' => $note,
		'date' => time(),
		'to' => $id,
		'into' => 'pm',
		'type' => session('admin')
	));
}
?>
