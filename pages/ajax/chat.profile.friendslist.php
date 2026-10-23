<?php
check_session();
if(Users::guestType(session('type')))return;

include('inc/classes/profile.php');
Profile::CheckActiveMe();

$id = isset($_POST['userid']) ? safe::number($_POST['userid']) : die('id?');
ProfileShowFriends($id);

function ProfileShowFriends($id){
	global $usertype, $options, $sql;
	if(!session('id'))return;
	$sql->where("id", $id);
	$user = $sql->getOne('users',array('friends'));
	$jdc = json_decode($user['friends'], true);
	if(empty($jdc))die('یافت نشد.');
	$user_friends = implode(',',$jdc);
	echo '<script type="text/javascript">
		$(function () {
		  $(\'[data-toggle="tooltip"]\').tooltip();
		});</script>';
	$sql->join('profiles p','p.id=u.id', 'LEFT');
	$sql->join('onlines o','o.id=u.id', 'LEFT');
	$sql->where("u.id", $jdc, 'in');
	$sql->orderBy('u.id', 'DESC');
	foreach($sql->get('users u', null, array('u.name', 'u.id', 'u.type', 'u.gender', 'p.avatar', 'o.id as online')) as $row){
		$avatar = Profile::ShowResizedAvatar(is_file('theme/images/avatars/'.$row['avatar']) ? $row['avatar'] : ($row['gender'] == '1' ? 'no_avatar_male.png' : 'no_avatar_female.jpg'));
		$on = $row['online'] ? 'on' : '';
		echo('<li class="list-group-item '.$on.'" data-toggle="tooltip" data-placement="bottom" title="'.$row['name'].' - '.$usertype[$row['type']]['title'].'"><a target="_blank" href="/profile/'.$row['id'].'"><img src="'.$avatar.'"><span>'.$row['name'].'</span></a></li>');
	}
}
?>