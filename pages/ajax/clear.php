<?php

$admin = session('admin');
$id = isset($_POST['id']) ? safe::number($_POST['id']) : '';
$msgid = isset($_POST['msgid']) ? safe::string($_POST['msgid']) : '';
$action = isset($_POST['action']) ? safe::string($_POST['action']) : '';
        $sql->where("id", $id);
        $name = $sql->getOne('users', array('username'));
if ($admin != '0') {
    if ($id != '' and $msgid !="" and $action == 'delall') {
        $data = array('action' => 'delete', 'msgid' => $msgid);
        $text = json_encode($data, JSON_UNESCAPED_UNICODE);
        $sql->insert('message', array(
            'user' => -1,
            'username' => $systemname,
            'color' => $systemcolor,
            'fontname' => $defualtfontname,
            'fontsize' => $defualtfontsize,
            'room' => -1,
            'text' => $text,
            'date' => time(),
            'to' => -1,
            'into' => 'deletmsg',
            'type' => 0
        ));
        chatroom::addlog('7', " پاک کردن تمامی پیام های کاربر \"{$name['username']}\" از عمومی");
        echo('ok');
    }elseif ($id != '' and $msgid !="" and $action == 'delone') {
        $data = array('action' => 'delete', 'msgid' => $msgid);
        $text = json_encode($data, JSON_UNESCAPED_UNICODE);
	$sql->insert('message', array(
            'user' => -1,
            'username' => $systemname,
            'color' => $systemcolor,
            'fontname' => $defualtfontname,
            'fontsize' => $defualtfontsize,
            'room' => -1,
            'text' => $text,
            'date' => time(),
            'to' => -1,
            'into' => 'deletmsg',
            'type' => 0
        ));
        chatroom::addlog('7', " پاک کردن پیام کاربر \"{$name['username']}\" از عمومی");
		echo('ok');
    }  else {
        $sql->where("roomid", session('room'));
        foreach($sql->get('onlines', null, array('id', 'inputs', 'time')) as $row){
            if ($row['time'] == '0' or $row['id'] == session('id')) {
                continue;
            }
            $inputs = json_decode($row['inputs'], true);
            $inputs['clear'] = '1';
            Users::Inputs($row['id'], $inputs);
        }
        chatroom::addlog('7', "پاک کردن صفحه همه کاربران");
        echo('ok');
    }
}
unset($admin);
?>