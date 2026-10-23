<?php 
check_session();
$action = isset($_POST['action']) ? safe::string($_POST['action']) : exit();
$id     = isset($_POST['id']) ? safe::number($_POST['id']) : '';
$type   = isset($_POST['type']) && safe::number($_POST['type']) == '1' ? 'close' : 'open';
if($action == "show_msgs" and $type == "close"){
  session("msgactions","close");
}elseif($action == "show_msgs" and $type == "open"){
  sessionunset("msgactions");
}
if($action == "privat_perm"){
	$inputs = Users::Inputs(session('id'));
	if($inputs['closepv'] == 'close' and $type == "close"){
		echo("خصوصی شما قبلا به حالت تاییده در امده است");return;
	}
    $inputs['closepv'] = $type;
    session('privat_perm',$type);
    Users::Inputs(session('id'), $inputs);
    echo("انجام شد");
}
?>