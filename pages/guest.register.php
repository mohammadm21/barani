<?php

check_session();
// این اندپوینت فقط وقتی کار می‌کند که «عضویت اجباری مهمان» در تنظیمات اصلی روشن باشد.
// مقدار گزینه مستقیماً از دیتابیس خوانده می‌شود تا با توگلِ ذخیره‌شده همیشه هماهنگ باشد
// (نه فقط از کشِ inc/config.php که ممکن است قدیمی بماند).
$gReg = $sql->where('name', 'guest_must_register')->getValue('options', 'value');
if ($gReg != 'true') {
    echo('disabled');
    return;
}
if (!session('id')) {
    echo('login');
    return;
}
$pw = isset($_POST['password']) ? $_POST['password'] : '';
$rpw = isset($_POST['password2']) ? $_POST['password2'] : '';
if ($pw == '' || $pw !== $rpw) {
    echo('dontmatch');
    return;
}
$id = session('id');
$type = $sql->where("id", $id)->getValue('onlines', 'type');
if (!Users::guestType($type)) {
    echo('lastregister');
    return;
}
$type = Users::lowestlevelregister();
$sql->where("id", $id)->update('users', array(
    'password' => md5(base64_encode(md5(base64_encode($pw)))),
    'type' => $type,
    'score' => 0
));
$sql->where("id", $id)->update('onlines', array(
    'type' => $type,
    'score' => 0
));
stats('register');
Users::CheckProfile($id);
session('type', $type);
session('user', '1');
echo('ok');
