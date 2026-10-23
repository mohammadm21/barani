<?php

check_session();

$id = isset($_POST['user']) ? safe::number($_POST['user']) : false;

$sql->where("users", session('id'));
if ($sql->has('silents')) {
    die();
}
if (!session('buzz_time')) {
    session('buzz_time', 1);
}

$check_buzz_time = time() - session('buzz_time');
$inputs = Users::Inputs($id);

if (($id && !empty($id) && $check_buzz_time > 15 && $inputs['status_private'] == 'pmme') OR ( $id && session('admin') == '1' && $check_buzz_time > 15)) {
    if (!in_array(session('id'), $inputs['my_blacklist']) OR session('admin') == '1') {
        Message::add($id, "{BUZZ}", "-1", "pm");
        session('buzz_time', time());
    }
} else {
    echo 'false';
}
?>