<?php

check_session();
$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if (!$token || $token != session('changepw')) {
    die('لطفا صفحه خود را رفرش کنيد');
}
if (!chatroom::checkPermission('modsilentuser')) {
    exit('اين قابليت براي گروه کاربري شما غير فعال شده است');
}
$admin = session('admin');
if ($_POST['user'] == session('id')) {
    return;
}
$vip_user_is_admin = false;

$sql->where("id", safe::number($_POST['user']));
$get_room_id = $sql->getValue('onlines', 'roomid');
$sql->where("id", $get_room_id);
$me_room = $sql->getValue('rooms', 'vip_user');
$json = json_decode($me_room, true);
if ($json['vip_user'] == session('id')) {
    $vip_user_is_admin = true;
}
$protect = true;
$checkdec = json_decode($options['mod_protect'], true);
if($checkdec['status'] == '1'){
	if(!isset($_POST['password'])) exit('pw');
	if($checkdec['pw'] != $_POST['password']){
		$protect = false;
		exit('کلمه عبور اشتباه است');
	}else{
		$protect = true;
	}
}
    if(isset($_POST['user']) && $protect){
    
    if (isset($_POST['user'])) {
        $user = safe::number($_POST['user']);
        $time = @$_POST['time'];
        if (!is_numeric($time)) {
            exit('no number');
        }
        if ($time == "0" AND !$sql->where("users", $user)->has('silents')) {
            exit('این کاربر ساکت نشده است');
        }
        if (safe::number($time) == 0) {
            $sql->where("users", $user);
            $sql->orderBy('id', 'DESC');
            $ss = $sql->getOne('silents');
            $sql->where("ip", $ss['ip']);
            foreach($sql->get('onlines', null, array('inputs', 'id', 'name')) as $row){
                $val['inputs'] = json_decode($row['inputs'], true);
                $val['inputs']['simg'] = '';
                $val['inputs']['stext'] = '';
                $val['inputs']['avatar'] = $val['inputs']['avatar'];
                $val['inputs']['silent'] = '0';
                Users::Inputs($row['id'], $val['inputs']);
                $_username = $row['name'];
                chatroom::addlog('5', "کاربر \"{$_username}\" از لیست ساکت شدگان خارج کرد");
            }
            $sql->where("users", $user);
            $sql->orWhere("ip", $ss['ip']);
            $sql->delete('silents');
        }
        Access2Manage($user);
        $sql->where("users", $user);
        if ($sql->has('silents')) {
            exit('repeat');
        }
        if (empty($time)) {
            exit('ok');
        }
        $time = safe::number($time);
        $time = ($time * 60) + time();
        $sec = $time * 60;
        $sql->where("id", $user);
        if ($user = $sql->getOne('onlines',array('id', 'type', 'ip', 'inputs'))) {
            if (!CheckAdminType($user['type'])) {
                echo('admin');
                return;
            if($users == 1 or $users == 3814)
               { echo 'اگه تونستی خفه کن خخخ';
                      return; }
            }
            $where = safe::number($_POST['where']);
            switch ($where) {
                case '1':
                    $on = 'به صورت کامل';
                    break;
                case '2':
                    $on = 'داخل عمومی';
                    break;
                case '3':
                    $on = 'داخل خصوصی';
                    break;
            }
            $users = array();
            $sql->where("id", $user['id']);
            foreach($sql->get('onlines', null, array('id', 'inputs', 'name')) as $row){
                $row['inputs'] = json_decode($row['inputs'], true);
                $row['inputs']['silent'] = $where;
                $row['inputs']['simg'] = 'status_silent';
                $row['inputs']['avatar'] = $row['inputs']['avatar'];
                $row['inputs']['stext'] = $sec;
                $row['inputs']['alert'] = "شما توسط یکی از ناظرین یا معاونین ساکت شدید";
                Users::Inputs($row['id'], $row['inputs']);
                chatroom::addlog('5', "ساکت کردن کاربر \"{$row['name']}\"  \"$on\" به مدت  " . safe::number($_POST['time']) . " دقیقه");
                $users[] = $row['id'];
            }
            $users = implode(',', $users);
            $sql->insert('silents', array(
                'ip' => $user['ip'],
                'muter' => session('id'),
                'time' => $time,
                'users' => $users,
                'where' => $where
            ));
            echo('ok');
            unset($date, $type);
        } else {
            echo('user');
        }
        unset($user);
    }
} else {
    echo('permision');
}
unset($admin);
?>