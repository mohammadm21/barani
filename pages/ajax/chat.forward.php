<?php
// DEBUG: Check if PHP runs at all
file_put_contents(__DIR__ . '/../../debug.log', date('Y-m-d H:i:s') . " chat.forward.php loaded\n", FILE_APPEND);

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../../php-errors.log');

$id = session('id');
if (!$id) {
    echo json_encode(array('error' => 'login'));
    exit();
}
header('Content-Type: application/json; charset=utf-8');

if ($options['forwardtext'] != 'true') {
    echo json_encode(array('error' => 'forward disabled'));
    exit();
}

$useroptions = json_decode($options['msgoptions'], true);
if (!in_array(session('type'), $useroptions['allowforward'])) {
    echo json_encode(array('error' => 'no access'));
    exit();
}

$input = Users::Inputs();
if ($input['silent'] == '1') {
    echo json_encode(array('error' => 'silent'));
    exit();
}

$msg = safe::string(Message::badwords(str_replace(array('<b>', '</b>', '<i>', '</i>'), "", $_POST['text']), 'main'));
$name = safe::string($_POST['name']);
$rooms = isset($_POST['room']) ? safe::number($_POST['room']) : session('room');
$regex = "#([<]span)(.*)([<]/span[>])#";
$msg = preg_replace($regex, '', $msg);

$to = isset($_POST['id']) ? safe::number($_POST['id']) : '';
if ($to == '') {
    $to = '-1';
    $into = 'main';
} else {
    $into = 'pm';
}

$data = array('action' => 'forward', 'msg' => $msg, 'name' => $name);
if (strstr($msg, 'img width=50 height=50 id=nocheck src=')) {
    $data['isimg'] = true;
    $data['msg'] = str_replace('img width=50 height=50 id=nocheck src=', '', $msg);
}
$msg = json_encode($data, JSON_UNESCAPED_UNICODE);

$result = Message::add($to, $msg, $rooms, $into);
echo $result;
