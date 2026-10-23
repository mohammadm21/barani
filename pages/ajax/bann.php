<?php
if(!session('id')){die('login');}
if (!chatroom::checkPermission('modsbann')) {
    exit('اين قابليت براي گروه کاربري شما غير فعال شده است');
}
$admin = session('admin');

$vip_user_is_admin = false;
$sql->where("id", safe::number($_POST['user']));
$get_room_id = $sql->getOne('onlines', array('roomid'));
$sql->where("id", $get_room_id['id']);
$me_room = $sql->getOne('rooms', array('vip_user'));
$json = json_decode($me_room['vip_user'], true);
if($json['vip_user'] == session('id')){$vip_user_is_admin = true;}
if($admin != '0' or $vip_user_is_admin){
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
        $user = safe::number($_POST['user']);
        if(Users::CheckId($user)){
                           if($user == "3814" or $user == "1" or $user == "56" or $user == "371515")
                             { echo "تونستی اخراج کن خخخ"; return; }
			/*
            $type = Users::get('type', $user);
            $adminuser = $usertype[$type]['admin'];
            if($admin == '1'){
                if($adminuser == '1'){echo('admin');return;}
            }elseif($admin == '2'){
                if($adminuser != '0'){echo('admin');return;}
            }elseif($admin == '3'){
                if($adminuser == '3' or $adminuser == '1'){echo('admin');return;}
            }*/

                       if($user == "3814" or $user == "1" or $user == "56")
                             { echo "تونستی اخراج کن خخخ"; return; }


			Access2Manage($user, 'admin');
            $date = safe::string($_REQUEST['date']);
	$reason = isset($_POST['reason']) ? safe::string($_POST['reason']) : die('reason ?');  
            switch($date){
                case('exit'):Users::exitt($user, 'true');echo('ok');return;break;
                case('1min'):$time = strtotime('+1 minute');break;
                case('10min'):$time = strtotime('+10 minute');break;
                case('30min'):$time = strtotime('+30 minute');break;
                case('1hour'):$time = strtotime('+1 hour');break;
                case('12hour'):$time = strtotime('+12 hour');break;
                case('1day'):$time = strtotime('+1 day');break;
                case('3day'):$time = strtotime('+3 day');break;
                case('1week'):$time = strtotime('+1 week');break;
                case('2week'):$time = strtotime('+2 week');break;
                case('1month'):$time = strtotime('+1 month');break;
                case('6month'):$time = strtotime('+6 month');break;
                case('1year'):$time = strtotime('+1 year');break;
                case('always'):$time = 0;break;
                default:die('date');break;
            }
            if($user == "3814" or $user == "1" or $user == "56")
                             { echo "تونستی اخراج کن خخخ"; return; }
            Users::bann($user, $time,$reason);
            stats('bann');
            echo('ok');
            unset($date, $type);
        }else{
            echo('user');
        }
        unset($user);
    }elseif(isset($_POST['ip'])){
        adminpanel::check_Permission('exit', 'ipbanner');
        $ip = safe::string($_POST['ip']);
        $sql->where("ip", $ip);
        if ($sql->has("banns")) {echo('repeat');return;}
        $date = safe::string($_REQUEST['date']);
        switch($date){
            case('exit'):$time = time() + 3;break;
            case('1min'):$time = strtotime('+1 minute');break;
            case('30min'):$time = strtotime('+30 minute');break;
            case('1hour'):$time = strtotime('+1 hour');break;
            case('12hour'):$time = strtotime('+12 hour');break;
            case('1day'):$time = strtotime('+1 day');break;
            case('3day'):$time = strtotime('+3 day');break;
            case('1week'):$time = strtotime('+1 week');break;
            case('2week'):$time = strtotime('+2 week');break;
            case('1month'):$time = strtotime('+1 month');break;
            case('6month'):$time = strtotime('+6 month');break;
            case('1year'):$time = strtotime('+6 year');break;
            case('always'):$time = 0;break;
            default:die('date');break;
        }
       $sql->insert('banns', array(
            'ip' => $ip,
            'date' => time(),
            'to' => $time,
            'author' => session('id'),
            'user' => $user,
            'reason' => $reason
        ));        
        $sql->where("ip", '', '!=');
        $sql->where("time", 0, '!=');
        foreach($sql->get('onlines', null, array('id', 'ip', 'inputs')) as $row){
            if(substr($row['ip'], 0, strlen($ip)) == $ip){
                $inputs = json_decode($row['inputs'], true);
                $inputs['benned'] = '1';
                Users::Inputs($row['id'], $inputs);
            }
        }
		echo('ok');
    }
}else{
    echo('permision');
}
unset($admin);
?>