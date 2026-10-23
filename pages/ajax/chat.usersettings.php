<?php

check_session();
$status = isset($_POST['status']) && safe::string($_POST['status']) == 'false' ? 'noreq' : 'reqme';
$action = isset($_POST['action']) ? safe::string($_POST['action']) : die('action?');
if ($action == 'onlinelist') {
    if ($status == 'noreq') {
        $status = 'default';
    } else {
        $status = 'likeline';
    }
    //if($status == 'noreq')$status = session('thmonlinelist','default'); else $status = session('thmonlinelist','likeline');
    echo $status == 'likeline' ? 'ln' : 'def';
}
$val = Users::Inputs();
$val[$action] = $status;
Users::Inputs(session('id'), $val);
if ($action != 'onlinelist') {
    echo ($status == 'noreq') ? 'بسته شد' : 'باز شد';
}
?>