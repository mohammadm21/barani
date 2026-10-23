<?php

check_session();
echo '<ul>';
$reg = isset($_POST['search']) ? safe::string($_POST['search']) : die();
$sql->where("id", session('id'), '!=');
if(session('admin') != '1'){
    $sql->where("hide", 0);
}
$sql->where("name", '%'.$reg.'%','LIKE');
$sql->orderBy('type', 'DESC');
foreach($sql->get('onlines', null, array('id', 'name', 'type', 'gender')) as $row){
	if(Users::is_admin(session('type'))){
	$admins = '<img style="cursor:pointer" src="/theme/images/chat/chat.wand.png" id="adminu_'.$row['id'].'">';
	}
  echo '<li id="' . $row['id'] . '">' . $row['name'] . ' ' .$admins.'  <img src="' . ($row['gender'] == '1' ? theme::url('theme/images/chat/chat.user_comment_m.png') : theme::url('theme/images/chat/chat.user_comment_w.png')) . '"><img height="20" width="50" src="/' . $usertype[$row['type']]['icon'] . '"></li>';
}
echo '</ul>';

?>