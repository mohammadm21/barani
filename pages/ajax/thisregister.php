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
    session('type',$type);
    Users::CheckProfile($id);
    echo('ok');
} else {
    echo 'سیستم غیرفعال میباشد';
}
?>