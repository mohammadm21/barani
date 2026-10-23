<?php

check_session();
$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if (!$token || $token != session('changepw')) {
    die('لطفا صفحه خود را رفرش کنيد');
}
if (!chatroom::checkPermission('modstalkuser')) {
    exit('اين قابليت براي گروه کاربري شما غير فعال شده است');
}
if (session('admin') != '0') {
    $user = isset($_POST['user']) ? safe::number($_POST['user']) : die('user!');
    $is_user = Users::get('type', $user) == Users::lowestlevelregister();
    if (!$is_user) {
        die('اين قسمت مخصوص کاربران عضو ميباشد');
    }
    $score = $options['scoretosendmsg'];
    $sql->where("id", $user);
    $info = $sql->getOne('onlines', array('score'));
    if ($score < $info['score']) {
        die('امتیاز کاربر برای حرف زدن در عمومی کافی میباشد');
    }
    $sql->where("id", $user)->update('onlines', array(
        'score' => $score
    ));
    $sql->where("id", $user)->update('users', array(
        'score' => $score
    ));
    chatroom::addlog('5', "به کاربر " . Users::get('name', $user) . " اجازه حرف زدن در عمومی را داد");
      $txt = " شما بعد از 15 ثانیه اجازه صحبت در عمومی رو دارید";	
	                     Users::warning($user,$txt);
    echo('انجام شد');
} else {
    echo('permission');
}
?>