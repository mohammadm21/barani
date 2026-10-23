<?php
check_session();

$userid = isset($_POST['userid']) ? safe::number($_POST['userid']) : exit("ای دی کاربر معتبر نمیباشد"); 
$action = isset($_POST['action']) ? safe::string($_POST['action']) : exit("عملیات مورد نظر مشخص نشده است");
$allow_admins = json_decode($options['useroptions'], true);
$check_type = Users::get('type',$userid);
$is_admin = $usertype[$check_type]['admin'];
if($is_admin == '1')die('شما نمیتوانید مدیر را ریپورت کنید');
if($action == "report"){
if(in_array(session('type'),$allow_admins['modsreportuser'])){
if ($sql->where('user', $userid)->getValue('report_users', 'count(*)') == 0) {
	echo(reportusers($userid,$action));
	 Users::warning($userid,'شما ریپورت شدید');
}else{
     echo('این کاربر هم اکنون ریپورت میباشد');
}
 }else{
	 echo("شما دسترسی لازم برای ریپورت کاربر را ندارید");
 } 
}elseif($action == "delreport"){
if(in_array(session('type'),$allow_admins['modsunreportuser'])==true){
  if ($sql->where('user', $userid)->getValue('report_users', 'count(*)') != 0) {
	echo(reportusers($userid,$action));
   	 Users::warning($userid,'شما از ریپورت خارج شدید');
}else{
     echo('این کاربر در لیست ریپورت شده ها نمیباشد');
}
 }else{
	 echo("شما دسترسی لازم برای  ان ریپورت کاربر را ندارید");
 }     
}
function reportusers($user,$action) {
    global $usertype,$options, $sql;
    $allow_admins = json_decode($options['useroptions'], true);
    $user_user = array('name' => Users::get("name",$user), 'type' => Users::get("type",$user), 'gender' => Users::get("gender",$user));
    $type_user = $usertype[$user_user['type']]['icon'];
    $sex_user = $user_user['gender'] == '2' ? theme::url('theme/images/chat/chat.user_comment_w.png') : theme::url('theme/images/chat/chat.user_comment_m.png');
    $user_color = $usertype[$user_user['type']]['color'];
	$msg = $action == "report" ? '<td><b>متن گزارش:</b></td><td>این کاربر توسط '.session('name').' ریپورت شده است</td>' : '<td><b>متن گزارش:</b></td><td>این کاربر توسط  '.session('name').'از ریپورت خارج شده است</td>';
	
    $note = '<table dir="rtl" cellpadding="3" border="1" align="center">
					<tr><td><img style="cursor:pointer;" src="' . theme::url('theme/images/chat/chat.wand.png') . '" onclick=changeUserEvent("adminu_' . $user . '")><b>کاربر:</b></td><td><b><a href=javascript:void(0) onclick=changeUserEvent("privatwin_' . $user . '")><font color=' . $sender_color . '>' . $user_user['name'] . '</font></a></b> <img src="/' . $type_user . '"><img src="' . $sex_user . '"></td></tr>
					'.$msg.'
				</table><hr>';
    $query = $sql->get('onlines', null, array('id', 'type', 'roomid'));
    foreach ( $query as $row ) { 
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
	if ($action == "report" and $sql->where('user', $user)->getValue('report_users', 'count(*)') == 0) {
	$reptime = ($options['reportBlock_user_time'] * 86400) + time();
        $sql->where('id',$user);
       $ip = $sql->getOne('onlines', array('ip'));
          $sql->insert('report_users', array(
            'user' => $user,
            'ip' => $ip['ip'],
            'time' => $reptime
          ));
	return "کاربر مورد نظر به لیست ریپورت شده ها اضافه شد";

   }else{  
        $sql->where('user',$user)->delete('report_users');
	   return "کاربر با موفقیت از لیست ریپورت حذف شد";

   }
}

?>