<?php

if (!session('id') or session('admin') == '0') {
    exit('No hack AND bots Ye Babye :D sheytooni mamnoo :D');
}
adminpanel::check_Permission('exit', 'fillprint');

$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if (!$token || $token != session('changepw')) {
    die('لطفا صفحه خود را رفرش کنيد');
}

$fp_id = isset($_POST['fp_id']) ? (int)$_POST['fp_id'] : 0;
if ($fp_id <= 0) {
    die('اثر انگشت نامعتبر');
}

$fp = $sql->where('id', $fp_id)->getOne('fingerprints', array('id'));
if (!$fp) {
    die('اثر انگشت یافت نشد');
}

$sql->where('id', $fp_id)->update('fingerprints', array('is_confirmed_abuser' => 1));

echo('ok');
?>
