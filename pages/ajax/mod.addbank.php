<?php

check_session();
$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if (!$token || $token != session('changepw')) {
    die('لطفا صفحه خود را رفرش کنيد');
}
$user = safe::number($_POST['user']);
$action = safe::string($_POST['action']);
$bank = safe::number($_POST['bank']);
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
if (empty($bank)) {
    exit('مقدار امتیاز وارد نشده است');
}
if ($action == 'del') {
	$get = $sql->where('id', $user)->getOne('onlines');
	if ($get['bank'] < 1) {
        $sql->where("id", $user)->update('users', array(
            'bank' => 0
        ));
        $sql->where("id", $user)->update('onlines', array(
            'bank' => 0
        ));
        echo('کاربر ' . Users::get('username', $user) . ' هیچ امتیاز بانکی ندارد');
    } else {
        $sql->where("id", $user)->update('users', array(
            'bank' => $sql->dec($bank)
        ));
        $sql->where("id", $user)->update('onlines', array(
            'bank' => $sql->dec($bank)
        ));
        if ($online) {
			$sql->where('id', $user)->update('onlines', array(
				'bank' => $sql->dec($bank)
			));
        }
	                 $txt = $bank."امتیاز بانکی از شما کسر شد";	
	                     Users::warning($user,$txt);
        chatroom::addlog('9', 'مقدار' . $bank . ' امتیاز بانکی از کاربر ' . Users::get('username', $user) . ' کسر کرد');
        echo($bank . ' امتیاز بانکی از کاربر ' . Users::get('username', $user) . ' کسر شد');
    }
} elseif ($action == 'add') {
    $sql->where("id", $user)->update('users', array(
        'bank' => $sql->inc($bank)
    ));
    $sql->where("id", $user)->update('onlines', array(
        'bank' => $sql->inc($bank)
    ));
    if ($online) {
		$sql->where('id', $user)->update('onlines', array(
			'bank' => $sql->inc($bank)
		));
    }
	                 $txt = $bank."امتیاز بانکی به شما تعلق گرفت ";	
	                     Users::warning($user,$txt);
    chatroom::addlog('9', 'مقدار' . $bank . ' امتیاز بانکی به کاربر ' . Users::get('username', $user) . ' اضافه کرد');
    echo($bank . ' امتیاز بانکی به کاربر ' . Users::get('username', $user) . ' اضافه شد');
} else {
    exit('Error');
}
?>