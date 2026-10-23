<?php

check_session();
$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if (!$token || $token != session('changepw')) {
    die('لطفا صفحه خود را رفرش کنيد');
}
$user = safe::number($_POST['user']);
$action = safe::string($_POST['action']);
$score = safe::number($_POST['score']);
if (session('admin') == 0) {
    exit('شما مدیر نیستید و نمیتوانید این در خواست را عملی کنید');
}
if (!chatroom::checkPermission('dendscore_premission')) {
    exit('اين قابليت براي گروه کاربري شما غير فعال شده است');
}
if (empty($user)) {
    exit('Error');
}
if (empty($action)) {
    exit('Error');
}
if (empty($score)) {
    exit('مقدار امتیاز وارد نشده است');
}
if ($action == 'del') {
    $sql->where("id", $user);
    $get = $sql->getOne('onlines');
    if ($get['score'] < 1) {
        $sql->where("id", $user)->update('users', array(
            'score' => 0
        ));
        $sql->where("id", $user)->update('onlines', array(
            'score' => 0
        ));
        echo('کاربر ' . Users::get('username', $user) . ' هیچ امتیازی ندارد');
    } else {
        $sql->where("id", $user)->update('users', array(
            'score' => $sql->dec($score),
            'onlinetime' => $sql->dec($score*60)
        ));
        $sql->where("id", $user)->update('onlines', array(
            'score' => $sql->dec($score)
        ));
        if ($online) {
            $sql->where("id", $user['id'])->update('onlines', array(
             'score' => $score
             ));
        }
	                 $txt = $score."امتیاز از شما کسر شد";	
	                     Users::warning($user,$txt);
        chatroom::addlog('9', 'مقدار' . $score . ' امتیاز از کاربر ' . Users::get('username', $user) . ' کسر کرد');
        echo($score . ' امتیاز از کاربر ' . Users::get('username', $user) . ' کسر شد');
    }
} elseif ($action == 'add') {
    $sql->where("id", $user)->update('users', array(
        'score' => $sql->inc($score),
        'onlinetime' => $sql->inc($score*60)
    ));
    $sql->where("id", $user)->update('onlines', array(
        'score' => $sql->inc($score)
    ));
    if ($online) {
    $sql->where("id", $user['id'])->update('onlines', array(
        'score' => $score
    ));
    }
	                 $txt = $score."امتیاز به شما تعلق گرفت پس از یک بار خروج و ورود به چت اعمال خواهد شد";	
	                     Users::warning($user,$txt);
    chatroom::addlog('9', 'مقدار' . $score . ' امتیاز به کاربر ' . Users::get('username', $user) . ' اضافه کرد');
    echo($score . ' امتیاز به کاربر ' . Users::get('username', $user) . ' اضافه شد');
} else {
    exit('Error');
}
?>