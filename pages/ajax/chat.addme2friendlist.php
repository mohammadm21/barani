<?php

check_session();
safe::number($_POST['id']) == '-1' ? die('system!') : '';
if (Users::guestType(session('type'))) {
    die('مهمان اجازه ندارد لطفا عضو شويد');
}

$id = isset($_POST['id']) ? safe::number($_POST['id']) : die('error');
$sql->where("id", $id);
$val = $sql->getOne('onlines', null, array('type', 'inputs', 'id'));
if ($val['type'] == '1') {
    die('به کاربر مهمان نمیتوانید درخواست دوستی بفرستید');
}
$friends = Users::get('friends', session('id'));
$friends = json_decode($friends, true);
$friends = is_array($friends)? array_values($friends): array();
$my_blacklist = $sql->where("user", $id)->getOne('my_blacklist', array('blacklist'));
$my_blacklist = json_decode($my_blacklist['blacklist'],true);
if (in_array(session('id'), $my_blacklist)) {
        die('کاربر مورد نظر شما را مسدود کرده است. شما نمیتوانید درخواست دوستی بدهید');
}
if(in_array($id,$friends)){
	echo('کاربر مورد نظر در لیست دوستان شما قرار دارد');
	return;
}else
if(in_array(session('id'),$friends)){
	echo('شما در لیست دوستان کاربر مورد نظر قرار دارید');
	return;
}
if ($val['id'] != '') {
	$val = json_decode($val['inputs'], true);
    if ($val['status_addfriend'] == 'reqme') {
        $val['addfriend'] = session('id');
        Users::Inputs($id, $val);
        echo('درخواست دوستي شما ارسال شد منتظر تاييد باشيد');
    } else {
        echo('کاربر مورد نظر درخواست دوستي خود را بسته است');
    }
} else {
    $sql->where("id", $id);
    $get = $sql->getOne('users', array('type', 'pending'));
    if (Users::guestType($get['type'])) {
        die('به کاربر مهمان نمیتوانید درخواست دوستی بفرستید');
    }
    $pending = $get['pending'];
    $json = json_decode($pending, true);
    $json[] = session('id');
    $json = array_unique($json);
    $json = array_values($json);
    $json = json_encode($json);
    $sql->where("id", $id)->update('users', array(
        'pending' => $json
    ));
    echo('درخواست دوستي شما ارسال شد');
}
?>