<?php

if (!session('id') || session('admin') == '0') {
    exit();
}
adminpanel::check_Permission('exit','editfilemanager');
$password = isset($_POST['password']) ? safe::string($_POST['password']) : '';

if ($options['filemanager_password'] == $password) {
	session("filemanager_password", $password);
    echo('ok');
} else {
    session('filemanager_password', '');
    echo('invalid');
}
?>
