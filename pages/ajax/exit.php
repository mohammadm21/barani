<?php

$admin = session('admin');
if ($admin != '0') {
    $user = safe::number($_POST['user']);
    if (Users::CheckId($user)) {
        $type = Users::get('type', $user);
        if ($type != '0') {
            echo('admin');
            return;
        }
        Users::exitt($user, 'true');
        echo('ok');
    } else {
        echo('user');
    }
    unset($user);
} else {
    echo('permision');
}
unset($admin);
?>