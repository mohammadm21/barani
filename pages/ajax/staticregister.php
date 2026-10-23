<?php

check_session();
if ($options['advcreg'] == '0' || session('admin') != '0') {
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
if ($options['sreg_email'] == 'true') {
        $cellphone = isset($_POST['cellphone']) ? safe::string($_POST['cellphone']) : '';
        if (!safe::is_cellphone($cellphone)) {
            echo('phone');
            return;
        }
    }
    if ($options['sreg_email'] == 'true') {
        $email = isset($_POST['email']) ? safe::string($_POST['email']) : '';
        if (!safe::is_email($email)) {
            echo('email');
            return;
        }
    }
    $type = $sql->where("id", $id)->getValue('onlines', 'type');
    if (!users::guestType($type)) {
        echo('lastregister');
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
    echo('ok');
} else {
    echo 'سیستم غیرفعال میباشد';
}
?>