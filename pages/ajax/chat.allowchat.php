<?php
check_session();
if (!isset($_REQUEST['action'])) {
    header::tojson();
    $inputs = Users::Inputs();
    echo json_encode(array('allowchat' => $inputs['allowchat']), JSON_UNESCAPED_UNICODE);
} else {
    if ($_REQUEST['action'] == 'allowchat') {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : die('خطا');
        $id = safe::number($id);
        $private = Users::Inputs($id);
        $myFriends = Users::get("friends", $id);
        $jsonFriends = isset($myFriends) ? json_decode($myFriends, true) : array();
        if ($private['status_private'] != 'nopm' || in_array(session('id'), $jsonFriends)) {
            $sql->insert('message', array(
                'user' => session('id'),
                'username' => session('name'),
                'color' => session('color'),
                'room' => session('room'),
                'text' => 'request',
                'date' => time(),
                'to' => $id,
                'into' => 'allowchat',
                'type' => session('admin')
            ));
            echo "ok";
        } else {
            echo "کاربر مورد نظر خصوصی خود را بسته است";
        }
    }
    if ($_REQUEST['action'] == 'answering') {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : die('خطا');
        $id = safe::number($id);
        $answer = isset($_POST['answer']) && $_POST['answer'] == 'true' ? "true" : "false";
        $req_allow = json_decode(session('req_allow'), true);
        $req_allow = array_values($req_allow);
        if (in_array($id, $req_allow)) {
            die('خطا');
        } else {
            $req_allow = ArrayUnsetValue($req_allow, $id);
            session('req_allow', json_decode(session($req_allow)));
        }
        if ($answer == "true") {
            $myinputs = Users::Inputs($id);
            if (!in_array(session('id'), $myinputs['allowchat'])) {
                $myinputs['allowchat'][] = session('id');
                $myinputs['allowchat'] = array_unique($myinputs['allowchat']);
                $myinputs['allowchat'] = array_values($myinputs['allowchat']);
                Users::Inputs($id, $myinputs);
            }
            $uinputs = Users::Inputs();
            if (!in_array($id, $uinputs['allowchat'])) {
                $uinputs['allowchat'][] = $id;
                $uinputs['allowchat'] = array_unique($uinputs['allowchat']);
                $uinputs['allowchat'] = array_values($uinputs['allowchat']);
                Users::Inputs(session('id'), $uinputs);
            }
        }
        $sql->insert('message', array(
            'user' => session('id'),
            'username' => session('name'),
            'color' => session('color'),
            'room' => session('room'),
            'text' => $answer,
            'date' => time(),
            'to' => $id,
            'into' => 'allowchat',
            'type' => session('admin')
        ));
        echo "ok";
    }
}
?>