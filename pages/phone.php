<?php

if (!session('id')) {
    header::Go(url('login'));
    exit();
}
session('showonlines', 4);
header("Content-Type: text/html;charset=utf-8");

if (session('banned')) {
    header::Go(url('benned'));
    exit();
}
if (isset($_COOKIE['ChatBenn'])) {
    $bann = safe::number($_COOKIE['ChatBenn']);
    $sql->where("id", $bann);
    if ($bann = $sql->getOne('banns')) {
        if ($bann['to'] > time()) {
            header::Go(url('benned'));
            exit();
        }
    }
}
$sql->where("`to`", time(), '>');
$sql->orWhere('`to`', 0);
foreach ($sql->get('banns') as $row) {
    if (substr(ip(), 0, strlen($row['ip'])) == $row['ip']) {
        header::Go(url('benned'));
        exit();
    }
}

if (is_dir(theme::file('theme/js/temp/'))) {
    $temps = file::listDirectory(theme::file('theme/js/temp/'));
    foreach ($temps as $temp) {
        $t = fileatime($temp);
        if (time() - $t > 60)
            unlink($temp);
    }
}
$id = session('id');
$sql->where("id", $id);
$me = $sql->getOne('onlines', array('id', 'type', 'gender', 'score', 'inputs', 'name', 'bank'));
if (!$me) {
    $sql->where("id", $id);
    if ($user = $sql->getOne("users", array('id', 'username', 'name', 'type', 'gender'))) {
        $room = session('room');
        Users::addOnline($id, $user['username'], $user['name'], $user['gender'], $user['type'], session('hide'), $room, chatroom::getNameRoom($room));
    } else {
        header::Go(url('login'));
        exit();
    }
    $sql->where("id", $id);
    $me = $sql->getOne('onlines', array('id', 'type', 'gender', 'score', 'inputs', 'name', 'bank'));
}

$temp = json_decode($me['inputs'], true);
unset($temp['score']);
unset($temp['bank']);
$me += $temp;

$theme = 'phone';
include(theme::file('phone.php'));
