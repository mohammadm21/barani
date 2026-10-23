<?php
check_session();
if ($options['videochat'] != 'true') {
    die('این قسمت توسط مدیریت غیرفعال شده است');
}
$myid = session('id');
$reject = isset($_POST['reject']) && safe::string($_POST['reject']) == 'true' ? false : true;
$action = isset($_POST['action']) && safe::string($_POST['action']) == 'invite' ? 'invite' : 'get';
if (!isset($_POST['reject'])) {
    $channelid = isset($_POST['channelid']) ? safe::string($_POST['channelid']) : die('channelid!');
    $roomid = isset($_POST['roomid']) ? safe::string($_POST['roomid']) : die('roomid!');
}
$to = isset($_POST['to']) ? safe::number($_POST['to']) : die('to!');
$val = Users::Inputs($to);
if ($val['status_c2c'] != 'reqme' && $action == 'invite') {
    die('کاربر مورد نظر درخواست چت تصویری خود را بسته است');
}

$usex = session('gender') == '1' ? 'مرد' : 'زن';
if (!isset($_POST['reject'])) {
    $val['vc'] = $myid;
    $val['vc_chid'] = $channelid;
    $val['vc_rid'] = $roomid;
} elseif (!$reject) {
    $val['alert'] = "نام: " . session('name') . " || جنسیت: " . $usex . " || درجه: " . $usertype[session('type')]['title'] . " || درخواست چت تصویری شما را قبول نکرد !!!!";
} else {
    $val['alert'] = "نام: " . session('name') . " || جنسیت: " . $usex . " || درجه: " . $usertype[session('type')]['title'] . " || درخواست چت تصویری شما را قبول کرد";
}
Users::Inputs($to, $val);
/* @var $_POST type */
if (!isset($_POST['reject'])) {
    echo('منتظر تاييد باشيد');
}
?>