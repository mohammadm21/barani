<?php

check_session();
$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if (!$token || $token != session('changepw')) {
    die('لطفا صفحه خود را رفرش کنيد');
}
if (session('admin') != '0') {
    $user = isset($_POST['user']) ? safe::number($_POST['user']) : die('user!');
    $action = isset($_POST['action']) ? safe::string($_POST['action']) : die('action!');
    Users::get('type', $user) == '1' ? die('به کاربر مهمان نمیتوانید دسترسی بدهید') : '';
    echo _insert_into_permission($user, $action);
}

function _insert_into_permission($user, $action) {
    $uname = Users::get('name', $user);
    switch ($action) {
        case 'profile':
            $alert = 'دسترسی موقت ویرایش پروفایل به شما داده شد هم اکنون میتوانید پروفایل خود را ویرایش کنید';
            $unalert = 'دسترسی موقت ویرایش پروفایل از شما گرفته شد';
            $log = "به کاربر $uname دسترسی موقت ویرایش پروفایل را داد";
            $unlog = "از کاربر $uname دسترسی موقت ویرایش پروفایل را گرفت";
            break;
        case 'uploadfile':
            $alert = 'دسترسی موقت آپلود فایل در خصوصی به شما داده شد هم اکنون میتوانید در خصوصی فایل ارسال کنید';
            $unalert = 'دسترسی موقت آپلود فایل در خصوصی از شما گرفته شد';
            $log = "به کاربر $uname دسترسی موقت آپلود فایل در خصوصی را داد";
            $unlog = "از کاربر $uname دسترسی موقت آپلود فایل در خصوصی را گرفت";
            break;
        case 'c2c':
            $alert = 'دسترسی موقت چت تصویری به شما داده شد هم اکنون میتوانید در خصوصی چت تصویری کنید';
            $unalert = 'دسترسی موقت چت تصویری در خصوصی از شما گرفته شد';
            $log = "به کاربر $uname دسترسی موقت چت تصویری در خصوصی را داد";
            $unlog = "از کاربر $uname دسترسی موقت چت تصویری در خصوصی را گرفت";
            break;
        case 'history':
            $alert = 'دسترسی موقت خواندن تاریخچه به شما داده شد هم اکنون میتوانید تاریخچه را بخوانید';
            $unalert = 'دسترسی موقت خواندن تاریخچه از شما گرفته شد';
            $log = "به کاربر $uname دسترسی موقت خواندن تاریخچه را داد";
            $unlog = "از کاربر $uname دسترسی موقت خواندن تاریخچه را گرفت";
            break;
        default:
            $alert = 'خطا در دسترسی گرفتن';
            $unalert = 'خطا';
            $log = "خطا";
            $unlog = "خطا";
            break;
    }
    $inputs = Users::Inputs($user);
    if (!in_array($action, $inputs['permission'])) {
        $inputs['permission'][] = $action;
        $inputs['alert'] = $alert;
        $inputs['permission'] = array_unique($inputs['permission']);
        $txt = 'دسترسی داده شد';
        chatroom::addlog('5', $log);
    } elseif (in_array($action, $inputs['permission'])) {
        $inputs['permission'] = array_diff($inputs['permission'], array($action));
        $inputs['alert'] = $unalert;
        $txt = 'دسترسی برداشته شد';
        chatroom::addlog('5', $unlog);
    } else {
        $txt = 'خطا';
    }
    if ($txt != 'خطا') {
        Users::Inputs($user, $inputs);
    }
    return $txt;
}

?>