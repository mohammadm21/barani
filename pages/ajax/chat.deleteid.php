
<?php

$id = session('id');
if (!$id) {
    exit('login');
}
if (!chatroom::checkPermission('delete_uname')) {
    exit('اين قابليت براي گروه کاربري شما غير فعال شده است');
}
$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if (!$token || $token != session('changepw')) {
    die('لطفا صفحه خود را رفرش کنيد');
}
if (empty($_POST['password'])) {
    exit('کلمه عبور وارد نشده است');
}
$sql->where("id", $id);
$password = $sql->getOne("users");
if ($password['password'] != chatroom::password_encode($_POST['password'])) {
    exit('کلمه عبور وارد شده معتبر نمی باشد');
} elseif ($password['password'] == chatroom::password_encode($_POST['password'])) {
    $sql->where("id", $id)->delete('users');
}


echo('ایدی با موفقیت حذف شد');
Users::exitt($id, 'true')

?>