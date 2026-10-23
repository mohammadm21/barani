<?php
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

if ($options['reply'] != 'true') {
    echo json_encode(array('error' => 'reply disabled'));
    exit();
}

$useroptionss = json_decode($options['useroptions'], true);
if (!in_array(session('type'), $useroptionss['reply'])) {
    echo json_encode(array('error' => 'no access'));
    exit();
}

$input = Users::Inputs();
if ($input['silent'] == '1') {
    echo json_encode(array('error' => 'silent'));
    exit();
}

$umsg = safe::string(Message::badwords(str_replace(array('<b>', '</b>', '<i>', '</i>'), "", $_POST['umsg']), 'main'));
$uname = safe::string($_POST['uname']);
$mymsg = safe::string(Message::badwords(str_replace(array('<b>', '</b>', '<i>', '</i>'), "", $_POST['mymsg']), 'main'));
$msgid = safe::string($_POST['msgid']);

$regex = "#([<]span)(.*)([<]/span[>])#";
$umsg = preg_replace($regex, '', $umsg);
$mymsg = preg_replace($regex, '', $mymsg);

if ($msgid == 'myself') {
    $uname = session('name');
} else {
    $msgid = safe::number($msgid);
    if ($msgid == '') {
        echo json_encode(array('error' => 'nomsgid'));
        exit();
    }
    $sql->where("id", $msgid);
    $org_msg = $sql->getOne('message', array('username', 'text'));
    if (!$org_msg) {
        echo json_encode(array('error' => 'no original msg'));
        exit();
    }
    $jmsg = str_replace(array('<b>', '</b>', '<i>', '</i>'), "", $org_msg['text']);
    if (isJson($jmsg)) {
        $jd = json_decode($jmsg, true);
        if (isset($jd['action'])) {
            if ($jd['action'] == 'reply') {
                $org_msg['text'] = Message::badwords($jd['mymsg'], 'main');
            } elseif ($jd['action'] == "forward") {
                $org_msg['text'] = Message::badwords($jd['msg'], 'main');
            }
        }
    }
    $umsg = safe::string(str_replace(array('<b>', '</b>', '<i>', '</i>'), "", $org_msg['text']));
    $uname = safe::string($org_msg['username']);
}

$to = isset($_POST['to']) ? safe::number($_POST['to']) : '';
$where = safe::string($_POST['where']);

if ($where != 'main' AND empty($to)) {
    echo json_encode(array('error' => 'no recipient'));
    exit();
}

$data = array('action' => 'reply', 'umsg' => $umsg, 'uname' => $uname, 'mymsg' => $mymsg);
if (strstr($umsg, 'img width=50 height=50 id=nocheck src=')) {
    $data['isimg'] = true;
    $data['umsg'] = str_replace('img width=50 height=50 id=nocheck src=', '', $umsg);
}
$msg = json_encode($data, JSON_UNESCAPED_UNICODE);

switch ($where) {
    case 'main':
        $id = '-1';
        $zone = 'main';
        break;
    case 'private':
    case 'pm':
        $id = $to;
        $zone = 'pm';
        break;
    case 'cf':
        $id = $to;
        $zone = $to;
        break;
    default:
        echo json_encode(array('error' => 'invalid where'));
        exit();
}

$result = Message::add($id, $msg, session('room'), $zone);
echo $result;
