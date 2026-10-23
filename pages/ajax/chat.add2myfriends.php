<?php

check_session();
$id = isset($_POST['id']) ? safe::number($_POST['id']) : die('error');
$reject = isset($_POST['reject']) && safe::string($_POST['reject']) == 'true' ? 'true' : 'false';
$usex = session('gender') == '1' ? 'مرد' : 'زن';
if ($reject == 'true') {
    $errotext = "نام: " . session('name') . " || جنسیت: " . $usex . " || درجه: " . $usertype[session('type')]['title'] . " || درخواست دوستی شما را تایید نکرد !!!!";
    Users::alert($id, $errotext);
    exit;
}
$meid = session('id');
if (session('type') != '1') {
    $friends = Users::get('friends', $meid);
    $json = json_decode($friends, true);
    $json[] = $id;
    $json = array_unique($json);
    $json = array_values($json);
    $json = json_encode($json);
    $sql->where("id", $meid)->update('users', array(
        'friends' => $json
    ));
    unset($json);
}
if (Users::get('type', $id) != '1') {
    $friends = Users::get('friends', $id);
    $json = json_decode($friends, true);
    $json[] = $meid;
    $json = array_unique($json);
    $json = array_values($json);
    $json = json_encode($json);
    $sql->where("id", $id)->update('users', array(
        'friends' => $json
    ));
    $successText = "نام: " . session('name') . " || جنسیت: " . $usex . " || درجه: " . $usertype[session('type')]['title'] . " || درخواست دوستی شما را تایید کرد";
    Users::alert($id, $successText);
}
if (session('type') != '1') {
    echo('به ليست دوستانتان اضافه شد');
} else {
    echo('شما به لیست دوستانشان اضافه شدید');
}
?>