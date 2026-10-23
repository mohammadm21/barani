<?php

if (!session('id') or session('admin') == '0') {
    exit();
}
adminpanel::check_Permission('exit', 'editusers');
$id = isset($_POST['id']) ? safe::number($_POST['id']) : '';
if (session('id') != '1' && $id == '1') {
    echo 'این شخص صاحب چت روم است اجازه ویرایش اطلاعات را ندارید در صورتی که صاحب چت روم هستید برای تغییر رمز از ایکون چرخ دنده در داخل چت روم رمز خود را تغییر دهید';
    exit;
}
$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if (!$token || $token != session('changepw')) {
    die('لطفا صفحه خود را رفرش کنيد');
}

$id = isset($_POST['id']) ? safe::number($_POST['id']) : '';

if ($id == '') {
    echo('id');
    return false;
}
$sql->where("id", $id);
if (!$user = $sql->getOne('users')) {
    echo('id');
    return false;
}
$sql->where("id", $id);
if ($online = $sql->getOne('onlines')) {
    $nowscore = $online['score'];
} else {
    $nowscore = $user['score'];
}



$username = isset($_POST['username']) ? safe::string($_POST['username']) : '';
if ($username == '') {
    echo('nullusername');
    return false;
}
if (function_exists('mb_strlen')) {
    if (mb_strlen($username, 'utf-8') > $options['chat_maxlengthname']) {
        echo('lenusername');
        return false;
    }
}

$name = isset($_POST['name']) ? safe::string($_POST['name']) : $username;
if (function_exists('mb_strlen')) {
    if (mb_strlen($name, 'utf-8') > $options['chat_maxlengthname']) {
        echo('lenname');
        return false;
    }
}

$gender = isset($_POST['gender']) ? safe::number($_POST['gender']) : '';
if ($gender != '1' && $gender != '2') {
    $gender = '0';
}

$type = isset($_POST['type']) ? safe::number($_POST['type']) : '2';
if (!Users::is_type($type)) {
    $type = '2';
}
if(Users::guestType($type)) {
    $sql->where("id", $id);
    $sql->delete('profiles');
}
if ($usertype[$type]['admin'] == '0') {
    $sql->where("user", $id);
    $sql->delete('adminpanel');
}
if (isset($_POST['password']) && !empty($_POST['password']) && $_POST['password'] != '') {
    if (($usertype[$user['type']]['admin'] != 0 and adminpanel::check_Permission('bool', 'changepwadminteam')) or ( $usertype[$user['type']]['admin'] == 0 and adminpanel::check_Permission('bool', 'changepw'))) {
        $password = isset($_POST['password']) ? safe::string($_POST['password']) : '';
    } else {
        $password = '';
    }
} else {
    $password = '';
}

$bank = isset($_POST['bank']) ? safe::number($_POST['bank']) : 0;
$score = isset($_POST['score']) ? safe::number($_POST['score']) : 0;

if ($nowscore != $score) {
    $onlinetime = $score * 60;
} else {
    $onlinetime = $user['onlinetime'];
}

$activeuser = isset($_POST['activeuser']) ? safe::string($_POST['activeuser']) : 0;
if ($activeuser == 'true') {
    $activeuser = '1';
} else {
    $activeuser = '0';
}

$activeprofile = isset($_POST['activeprofile']) ? safe::string($_POST['activeprofile']) : 0;
if ($activeprofile == 'true') {
    $activeprofile = '1';
} else {
    $activeprofile = '0';
}

$profileactive = isset($_POST['profileactive']) ? safe::number($_POST['profileactive']) : 0;

$country = isset($_POST['country']) ? safe::string($_POST['country']) : '';

$city = isset($_POST['city']) ? safe::string($_POST['city']) : '';

$infos = isset($_POST['infos']) ? addslashes($_POST['infos']) : '';

$site = isset($_POST['site']) ? str_replace(array('http', '://', 'www.'), '', safe::latin($_POST['site'])) : '';


if(isset($_POST['typesetting_time'])){
	$time = safe::number($_POST['typesetting_time']);
	if($time > '0'){
		$type_time = time() + ($time * 86400);
	}else{
		$type_time = '0';
	}
	$after_type = safe::number($_POST['typesetting_type']);
	$typesetting = json_encode(array('time' => $type_time,'times' => $time,'type' => $after_type), JSON_UNESCAPED_UNICODE);
}
$data = array(
    'username' => $username,
    'name' => $name,
    'gender' => $gender,
    'type' => $type,
    'score' => $score,
    'bank' => $bank,
    'onlinetime' => $onlinetime,
    'active' => $activeuser,
    'email' => $email,
	'typesetting' => $typesetting
);

$passwordQuery = "";
if (safe::is_md5($user['password'])) {
    if (!empty($password)) {
        $data['password'] = md5(base64_encode(md5(base64_encode($password))));
    }
} else {
    if (!empty($password)) {
        $data['password'] = md5(base64_encode(md5(base64_encode($password))));
    }
}

$sql->where("id", $id);
$sql->update('users', $data);
$sql->where("id", $id);
$sql->update('profiles', array(
    'active' => $activeprofile,
    'gender' => $gender,
    'info' => $infos,
    'site' => $site,
    'country' => $country,
    'city' => $city
));
if ($online) {
    $sql->where("id", $id);
    $sql->update('onlines', array(
        'username' => $username,
        'name' => $name,
        'gender' => $gender,
        'type' => $type,
        'score' => $score,
        'bank' => $bank
    ));
}

$sql->where("id", session('id'));
$hide = $sql->getOne('onlines', array('id','hide'));
if ($hide['hide'] == '1' and session("admin")==1){
}elseif ($hide['hide'] == '0' and session("admin")!=0){
 chatroom::addlog('9', "کاربر با نام نمایشی  \"{$user['name']}\" را ویرایش کرد");
}

$tel = json_decode($options['teloptions'], true);
  $text = "کاربر با نام نمایشی  \"{$user['name']}\" را ویرایش کرد  \n";
    $text .='ای پی : '.ip()."\n";
    $text .= "آدرس : ".url()."\n";
    $text .= "نام نمایشی :".session('name')."\t";

       if($tel['senduserlog'] == 'Sure'){
             tlg($text,'chat');
                 }
echo('ok');
?>
