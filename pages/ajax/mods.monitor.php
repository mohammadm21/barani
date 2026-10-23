<?php

check_session();
$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if (!$token || $token != session('changepw')) {
    die('لطفا صفحه خود را رفرش کنيد');
}
$id = isset($_POST['id']) ? safe::number($_POST['id']) : die('id?');
$reason = isset($_POST['reason']) ? safe::string($_POST['reason']) : die('reason?');
$action = isset($_POST['action']) ? safe::string($_POST['action']) : die('action?');

if (empty($reason))
    die('دلیل نباید خالی باشد');
$admin = session('id');
$time = time();
$info = $sql->where("id",$id)->getOne('users',array('name','type'));
if ($usertype[$info['type']]['admin'] == '0' || $usertype[$info['type']]['admin'] == '1') {
    echo('این کاربر سطح مدیریتی ندارد');
    exit;
}
if ($action == 'score' && session('admin') == '1') {
	if ($sql->where('mod', $id)->getValue('mods_activity', 'count(*)') == 0) {
        $admins = array('id' => session('id'), 'reason' => $reason, 'time' => $time);
        $admins = array_merge(array(), array(session('id') => $admins));
        $admins = json_encode($admins, JSON_UNESCAPED_UNICODE);;
        $sql->insert('mods_activity', array(
            'admins' => $admins,
            'mod' => $id,
            'score' => 1,
            'lastpoint' => $time
        ));
        chatroom::addlog('5', "کاربر \"{$info['name']}\" به دلیل \"$reason\" یک امتیاز مثبت گرفت");
        echo('ثبت شد');
    } else {
        $get = $sql->where("mod",$id)->getOne('mods_activity',array('admins','lastpoint'));
        if (($time - $get['lastpoint']) > 86400) {
            $admin = json_decode($get['admins'], true);
            $admin = is_array($admin) ? $admin : array();
            $admins = array('id' => session('id'), 'reason' => $reason, 'time' => $time);
            $admins = array_merge($admin, array(session('id') => $admins));
            $admins = json_encode($admins, JSON_UNESCAPED_UNICODE);
            $sql->where('mod', $id)->update("mods_activity", array(
                'admins' => $admins,
                'score' => $sql->inc(),
                'lastpoint' => $time
            ));
            chatroom::addlog('5', "کاربر \"{$info['name']}\" به دلیل \"$reason\" یک امتیاز مثبت گرفت");
            echo('ثبت شد');
        } else {
            $timeleft = (int) (24 - ($time - $get['lastpoint']) / 3600);
            echo("تا $timeleft ساعت دیگر امکان ثبت امتیاز برای این کاربر وجود ندارد");
        }
    }
} elseif ($action == 'warning' && session('admin') == '1') {
	if ($sql->where('mod', $id)->getValue('mods_activity', 'count(*)') == 0) {
        $admins = array('id' => session('id'), 'reason' => $reason, 'time' => $time);
        $admins = array_merge(array(), array(session('id') => $admins));
        $admins = json_encode($admins, JSON_UNESCAPED_UNICODE);
        $sql->insert('mods_activity', array(
            'admins' => $admins,
            'mod' => $id,
            'warning' => 1,
            'lastpoint' => $time
        ));
        chatroom::addlog('5', "کاربر \"{$info['name']}\" به دلیل \"$reason\" یک امتیاز منفی گرفت");
        echo('ثبت شد');
    } else {
        $get = $sql->where("mod",$id)->getOne('mods_activity',array('admins','lastpoint'));
        if (($time - $get['lastpoint']) > 86400) {
            $admin = json_decode($get['admins'], true);
            $admin = is_array($admin) ? $admin : array();
            $admins = array('id' => session('id'), 'reason' => $reason, 'time' => $time);
            $admins = array_merge($admin, array(session('id') => $admins));
            $admins = json_encode($admins, JSON_UNESCAPED_UNICODE);
            $sql->where("mod", $id)->update('mods_activity', array(
                'admins' => $admins,
                'warning' => $sql->inc(),
                'lastpoint' => $time
            ));
            chatroom::addlog('5', "کاربر \"{$info['name']}\" به دلیل \"$reason\" یک امتیاز منفی گرفت");
            echo('ثبت شد');
        } else {
            $timeleft = (int) (24 - ($time - $get['lastpoint']) / 3600);
            echo("تا $timeleft ساعت دیگر امکان ثبت امتیاز برای این کاربر وجود ندارد");
        }
    }
} else {
    echo('خطا');
}
?>