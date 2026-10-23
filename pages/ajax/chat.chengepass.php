<?php

if (!session('id')) {
    exit();
}
if (!session('changepw')) {
    exit();
}
if (!isset($_POST['token'])) {
    exit('لطفا صفحه خود را رفرش نمایید');
}
if ($_POST['token'] != session('changepw')) {
    exit('لطفا صفحه خود را رفرش نمایید');
}
if (empty($_POST['password'])) {
    die('فیلد خالی میباشد لطفا پر کنید');
}
$sql->where("id", session('id'));
if ($me = $sql->getOne("users", array('type'))) {
    if (($options['advcreg'] == '1' and $me['type'] != $options['defaultusertype']) or $options['advcreg'] == '0') {
        if ($_POST['user'] == session('id')) {
            Users::repass(session('id'), md5(base64_encode(md5(base64_encode($_POST['password'])))));
        } else {
            if (session('admin') != '1') {
                echo('nopermision');
                return;
            }

            if (Users::Checkregister($_POST['user'])) {
                echo('noregister');
                return;
            }
            if (Users::CheckAdmin($_POST['user'])) {
                echo('nopermision');
                return;
            }
        }
        echo('ok');
    }
}
?>