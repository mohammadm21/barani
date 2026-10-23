<?php
check_session();

$check_block_priv = isset($_POST['block_priv']) && safe::number($_POST['block_priv']) == '-1' ? die('خطا') : safe::number($_POST['block_priv']); 
$check_block_all = isset($_POST['block_all']) && safe::number($_POST['block_all']) == '-1' ? die('خطا') : safe::number($_POST['block_all']); 

$check_type = Users::get('type',$check_block_priv);

$is_admin = $usertype[$check_type]['admin'];
$is_admin2 = $usertype[$check_type]['admin'];
if($is_admin == '1')die('شما نمیتوانید مدیر را مسدود کنید');
if($is_admin2 == '1')die('شما نمیتوانید مدیر را مسدود کنید');


if(isset($_POST['block_all'])){
	$user = safe::number($_POST['block_all']);
	$txt = _insert_into_myblacklist($user);
    echo($txt);
}elseif(isset($_POST['block_priv'])){
	$user = safe::number($_POST['block_priv']);
	$txt = _insert_into_myblacklist($user);
    echo($txt);
}else{
	echo('خطا');
}
function reportusers($user,$num) {
    global $usertype,$options, $sql;
    $allow_admins = json_decode($options['useroptions'], true);
    $user_user = array('name' => Users::get("name",$user), 'type' => Users::get("type",$user), 'gender' => Users::get("gender",$user));
    $type_user = $usertype[$user_user['type']]['icon'];
    $sex_user = $user_user['gender'] == '2' ? theme::url('theme/images/chat/chat.user_comment_w.png') : theme::url('theme/images/chat/chat.user_comment_m.png');
    $user_color = $usertype[$user_user['type']]['color'];
    $note = '<table dir="rtl" cellpadding="3" border="1" align="center">
					<tr><td><img style="cursor:pointer;" src="' . theme::url('theme/images/chat/chat.wand.png') . '" onclick=changeUserEvent("adminu_' . $user . '")><b>کاربر:</b></td><td><b><a href=javascript:void(0) onclick=changeUserEvent("privatwin_' . $user . '")><font color=' . $sender_color . '>' . $user_user['name'] . '</font></a></b> <img src="/' . $type_user . '"><img src="' . $sex_user . '"></td></tr>
					<tr><td><b>متن گزارش:</b></td><td>این کاربر توسط '.$num.' کاربر بلاک و ریپورت شده است</td>
				</table><hr>';
    foreach($sql->get('onlines', null, array('id', 'type', 'roomid')) as $row){
        if ($row['roomid'] == '1') {
            $row['roomid'] = '-1';
        }
        if (in_array($row['type'], $allow_admins['send_user2private_admins'])) {
           $sql->insert('message', array(
                'user' => -1,
                'username' => '<center>SYSTEM [ <b><font color=red>ریپورت کاربران</font></b> ]</center>',
                'color' => '000000',
                'room' => $row['roomid'],
                'text' => $note,
                'date' => time(),
                'to' => $row['id'],
                'into' => 'pm',
                'type' => session('admin')
            ));
        }
    }
        if (!$sql->where("user", $user)->has('report_users')) { 
	$reptime = ($options['reportBlock_user_time'] * 86400) + time();
        $sql->where('id',$user);
        $ip = $sql->getOne('onlines',array('ip'));
           $sql->insert('report_users', array(
                'user' => $user,
                'ip' => $ip['ip'],
                'time' => $reptime
            ));
   }
}

function _insert_into_myblacklist($user){
    global $usertype,$options, $sql;
        $sql->where('id',$user);
        $reports = $sql->getOne('users',array('report'));
        $sql->where('user',session('id'));
        $inputs = $sql->getOne('my_blacklist',array('blacklist'));
        $inputs = json_decode($inputs['blacklist'], true);
        if(!in_array($user,$inputs)){
	$inputs[] = $user;
	$inputs = array_unique($inputs);
	$inputs = json_encode($inputs, JSON_UNESCAPED_UNICODE);
	$report = $reports['report'];
	$report+=1;
	$txt = 'مسدود شد';
}elseif(in_array($user,$inputs)){
	$inputs = ArrayUnsetValue($inputs,$user);
	$inputs = json_encode($inputs, JSON_UNESCAPED_UNICODE);
	$report = $reports['report'];
	$report-=1;
	if($report < 0)$report=0;
	$txt = 'آزاد شد';
}else{
	$txt = 'خطا';
}
	if($txt != 'خطا')
        if (!$sql->where("user", session('id'))->has('my_blacklist')) { 
        $sql->insert('my_blacklist', array(
            'user' => session('id'),
            'blacklist' => $inputs
        ));
        $sql->where('id',$user)->update('users',array(
              'report'=> $report
        ));
	}else{	
        $sql->where('user',session('id'))->update('my_blacklist',array(
              'blacklist'=> $inputs
        ));
        $sql->where('id',$user)->update('users',array(
              'report'=> $report
        ));
        $sql->where('user',session('id'));
        $conts = $sql->getOne('my_blacklist',array('blacklist'));
	$conts = json_decode($conts['blacklist'] , true);
	$conts = count($conts);
        if($conts == '0') { $sql->where('user',session('id'))->delete('my_blacklist'); } 
	}
        if (!$sql->where("user", $user)->has('report_users')) { 
		if($options['reportBlock_user'] == 'true') {
                    if($options['reportBlock_user_num'] == $report or $options['reportBlock_user_num'] < $report){
	 	reportusers($user,$report);
	  }	
         }
	}
	return $txt;
}
?>