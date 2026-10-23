<?php
check_session();
if(Users::guestType(session('type')))return;

$user_id = session('id');
$user = mysqli_fetch_array(db::query("SELECT u.`username`, p.`music`, p.`info`, p.`active`, u.`question` FROM {prefix}users u LEFT JOIN {prefix}profiles p ON p.`id` = u.`id` WHERE u.`id` = '$user_id'"));
if($user['active'] == '0')exit('پروفايل شما غیرفعال شده است و اجازه ویرایش پروفايل خود را ندارید');
$name = isset($_REQUEST['name']) ? safe::string($_REQUEST['name']) : $user['username'];
//$name = mb_substr($name, 0, (int)$options['chat_maxlengthname'], 'utf-8');
$name = mb_substr($name, 0, 50, 'utf-8');
if(!$name)$name = $user['username'];
if(db::num('onlines', "`username`='$name' and `id`!='$user_id'") or db::num('users', "`username`='$name' and `type`!='{$options['defaultusertype']}' and `id`!='$user_id'")){
    exit('name');
}
$berthday = explode('/', $_REQUEST['berthday']);
if(substr($berthday[0], 0, 2) != '13' or count($berthday) != 3)return;
$berthday = jdate::jmaketime( safe::number($berthday[0]), safe::number($berthday[1]), safe::number($berthday[2]));
$typeberthday = isset($_REQUEST['typeberthday']) ?  (int)safe::number($_REQUEST['typeberthday']) : 1;
if($typeberthday > 7 or $typeberthday < 1)return;
$gender = (int)safe::number($_REQUEST['gender']);
if($gender > 2 or $gender < 0)return;
//--------------------------------effect------
$effect = (int)safe::number($_REQUEST['effect']);
if($effect > 1 or $effect < 0)return;
//---------------------------------------------

$info = $_REQUEST['info'];
$info = addslashes($info);
if(session('admin') != '1')$info = strip_tags($info);
$site = safe::url($_REQUEST['site']);

$email = $_REQUEST['email'];
if($email == '1')$email = null;
if(!empty($email)){
    $email = safe::is_email($_REQUEST['email']);
    if(!$email){
        echo('email');
        return;
    }else{
        $email = $_REQUEST['email'];
    }
}
/*
$setting_myfriends = safe::string($_REQUEST['setting_myfriends']);
$setting_mylastview = safe::string($_REQUEST['setting_mylastview']);
$ldconfig = isset($_REQUEST['ldconfig']) && $_REQUEST['ldconfig'] == '1' ? '1' : '0';
$notification = isset($_REQUEST['notification']) && $_REQUEST['notification'] == '1' ? '1' : '0';
*/

$settings = json_encode(safe::arrayString($_REQUEST['settings']));

$shout = safe::string($_REQUEST['shout']);
if(mb_strlen($shout, 'UTF-8') > 170)die('تعداد حرف ها نباید بیشتر از 170 حرف باشد');
$country = safe::string($_REQUEST['country']);
$city = safe::string($_REQUEST['city']);
$music_title = safe::string($_REQUEST['music_title']);
$music_info = safe::string($_REQUEST['music_info']);
$music_info = str_replace(array('\n','\r','<br>'),'<br />',$music_info);
$music = safe::string($_REQUEST['music']);
$security_question = trim(safe::string($_REQUEST['security_question']));
$security_answer = trim(safe::string($_REQUEST['security_answer']));

$security = $user['question'];

if($options['security_question'] == 'true'){
	$security_question_groups = json_decode($options['useroptions'], true);
	if(in_array(session('type'), $security_question_groups['security_question'])){
		if(trim($security_question) == ''){
			die('سئوال امنیتی نباید خالی باشد');
		}
		if(trim($security_answer) == ''){
			die('جواب سئوال امنیتی نباید خالی باشد');
		}
		if(!empty($security_question) && !empty($security_answer)){
			$security = array("question" => $security_question, "answer" => base64_encode($security_answer));
			$security = json_encode($security, JSON_UNESCAPED_UNICODE);
		}
	}
}

//if(!empty($music && !empty($music_title))){
    if((!empty($music)) && (!empty($music_title))){
	$checkmusic = json_decode($user['music'], true);
	$_music = json_encode(array('music_title' => $music_title, 'music' => $music, 'music_info' => $music_info), JSON_UNESCAPED_UNICODE);
	if($checkmusic['music'] != $music){
		$music = ", `music`='$_music', `cmusic`='', `ldumusic`=''";
	}else{
		$music  = ", `music`='$_music'";
	}
}else{
	$music  = '';
}
if(trim($info) != $user['info']){
	$cinfo = ', `cinfo` = "", `lduinfo` = ""';
}else{
	$cinfo = '';
}

db::update('users', "`name`='$name', `email`='$email', `gender`='$gender', `question` = '$security'", "`id`='$user_id'");
db::update('onlines', "`name`='$name', `gender`='$gender'", "`id`='$user_id'");
db::update('profiles', "`birthDate`='$berthday|$typeberthday', `gender`='$gender',`effect`='$effect', `country`='$country', `city`='$city', `info`='$info', `shout`='$shout', `settings`='$settings', `site`='$site' $music $cinfo", "`id`='$user_id'");

$checkerSite = parse_url($site);
$check_site = str_replace(array('http://','www.'),'',$checkerSite['path']) != str_replace(array('http://','www.'),'',url())  ? $site : '';
$text = array('info' => $info, 'site' => $check_site);

mysqli_free_result($user);

include('inc/classes/profile.php');
Profile::Send2Friends('پروفايل خود را بروز کرد');

Message::Profile_Checker($text);

echo('ok');
?>