<?php
if(!session('id') or session('admin') == '0')exit();
adminpanel::check_Permission('exit', 'addusertypes');

$sorts = $_POST['sort_values'];
$admin_level = $_POST['admin_level'];

if(is_array($sorts)){
	foreach($sorts as $id => $sortVal){
		$id = safe::number($id);
		$sortVal = safe::number($sortVal);
        $sql->where("id", $id);
        $sql->update('usertypes', array(
            'sort' => $sortVal
        ));
	}
}
if(is_array($admin_level)){
	foreach($admin_level as $id => $level){
		$id = safe::number($id);
		$level = safe::number($level);
        $sql->where("id", $id);
        $sql->update('usertypes', array(
            'admin' => $level
        ));
	}
}
chatroom::reloadconfig();

$sql->where("id", session('id'));
$hide = $sql->getOne('onlines', array('id','hide'));
if ($hide['hide'] == '1' and session("admin")==1){
}elseif ($hide['hide'] == '0' and session("admin")!=0){
chatroom::addlog('9', "تنظیمات الویت های سطح کاربری را ویرایش کرد");
}
echo('ok');


?>