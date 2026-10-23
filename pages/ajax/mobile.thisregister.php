<?php

check_session();
$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if (!$token || $token != session('changepw')) {
    die('لطفا صفحه خود را رفرش کنيد');
}
    $admin = session('admin');
    if ($admin != '0') {
        $id = safe::number($_POST['user']);
        if (!Users::CheckId($id)) {
            echo('user');
            return;
        }
    } else {
        $id = session('id');
    }
    $type = $sql->where("id", $id)->getValue('onlines', 'type');
    if (!users::guestType($type)) {
        echo('این نام قبلا ثبت گردیده است');
        return;
    }
    $type = Users::lowestlevelregister();
    $sql->where("id", $id)->update('users', array(
        'password' => md5(base64_encode(md5(base64_encode($_REQUEST['password'])))),
        'type' => $type,
        'score' => 0
    ));
    $sql->where("id", $id)->update('onlines', array(
        'type' => $type,
        'score' => 0
    ));
    stats('register');
    Users::CheckProfile($id);
    echo('با موفقیت ثبت گردید از چت روم خارج شده و دوباره وارد شوید .');

?>