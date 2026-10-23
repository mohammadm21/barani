<?php
check_session();
if(session('admin')=='0' or !session('id'))exit("شما دسترسی لازم برای این کار را دارا نمیباشد");
$userid = isset($_POST['userid']) ? safe::number($_POST['userid']) : exit("ای دی کاربر معتبر نمیباشد"); 
$action = isset($_POST['action']) ? safe::string($_POST['action']) : exit("عملیات مورد نظر مشخص نشده است");
$usersoptions = json_decode($options['useroptions'],true);
if($action == "block"){
	if(in_array(session('type'),$usersoptions['off_report'])){
	$inputs = Users::Inputs($userid);
	if($inputs['reportstatus'] == 'stop'){
		echo("ارسال گزارش این کاربر قبلا مسدود شده است");return;
	}
    $inputs['reportstatus'] = 'stop';
    Users::Inputs($userid, $inputs);
	if($options['score_report'] != '0' and $options['score_report'] != ''){
	$mo = $userid;
	$usermo = $sql->where('username', $mo)->getOne('users', array('id', 'score'));
	if ($sql->where('id', $mo)->getValue('onlines', 'count(*)') > 0) {
        $sql->where("id", $mo);
        $oscor = $sql->get('onlines', null, array('id','score'));
    $newscore = $oscor['score'] - $options['report_score'];
        $sql->where("id", $mo)->update('users',array('score' => $newscore));
        $sql->where("id", $mo)->update('onlines',array('score' => $newscore));
} else {
    $newscore = $usermo['score'] - $options['report_score'];
        $sql->where("id", $mo)->update('users',array('score' => $newscore));
    }
	$txt = "کاربر گرامی به دلیل ارسال گزارش پیاپی و بی مورد ارسال گزارش شما مسدود و {$score} امتیاز از شما کسر شد";
	}else{
	$txt = "کاربر گرامی به دلیل ارسال گزارش پیاپی و بی مورد ارسال گزارش شما مسدود شد";	
	}
	Users::warning($userid,$txt);
	$username = Users::get('username',$userid);
	chatroom::addlog('7', " ارسال گزارش کاربر {$username} را مسدود کرد");
	echo('ارسال گزارش کاربر مورد نظر با موفقیت مسدود شد');
 }else{
	echo('شما دسترسی لازم برای مسدود کردن گزارش کاربر را دارا نمیباشد');
	}
}
if($action == "onblock"){
	if(in_array(session('type'),$usersoptions['on_report'])){
	$inputs = Users::Inputs($userid);
	if($inputs['reportstatus'] == 'on'){
		echo("ارسال گزارش این کاربر هم اکنون باز میباشد");return;
	}
    $inputs['reportstatus'] = 'on';
    Users::Inputs($userid, $inputs);
	$txt = "کاربر گرامی ارسال گزارش شما هم اکنون باز شد";	
	Users::warning($userid,$txt);
	$username = Users::get('username',$userid);
	chatroom::addlog('7', " ارسال گزارش کاربر {$username} را باز کرد");
	echo('ارسال گزارش کاربر مورد نظر با موفقیت باز شد');
}else{
	echo('شما دسترسی لازم برای باز کردن گزارش کاربر را دارا نمیباشد');
	}
}
?>