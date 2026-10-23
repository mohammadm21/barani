<?php

if (!session('id')) {
    exit();
}
$admin = session('admin');
if ($admin == '1') {
    adminpanel::check_Permission('exit', 'adminpanel');
    $action = isset($_POST['action']) ? safe::string($_POST['action']) : '';
    if ($action == 'add') {
        $user = safe::number($_POST['user']);
        if (Users::CheckId($user)) {
            if (!Users::Checkregister($user)) {
                echo('notrigis');
                return;
            }
            if (Users::CheckAdmin($user)) {
                echo('lastadmin');
                return;
            }
            Users::setAdmin($user);
            $username = Users::get('username', $user);
            chatroom::addlog('9', "کاربر \"$username\" را به جمع مدیران اضافه کرد");
            echo('ok');
        } else {
            echo('user');
        }
        unset($user);
    }
} else {
    echo('permision');
}
unset($admin);
?>