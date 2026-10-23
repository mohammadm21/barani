<?php
check_session();
header::tojson();
if(Users::guestType(session('type')))return;

include('inc/classes/profile.php');
Profile::CheckActiveMe();

	$pid = isset($_POST['pid']) ? safe::number($_POST['pid']) : die('pid?');
	$action = isset($_POST['action']) ? safe::string($_POST['action']) : die('action?');
	
	$myid = session('id');
	
	if($pid == $myid && $action == 'add')die(json_encode(array('text' => 'شما نمیتوانید پروفایل خود را دنبال کنید', 'type' => 'error'),JSON_UNESCAPED_UNICODE));
	
	$sql->where("id", $myid);
	$myget = $sql->getOne('profiles',array('followers'));
	$sql->where("id", $pid);
	$userget = $sql->getOne('profiles',array('followers'));
	
	if(!$myget)return;
	if(!$userget)return;
	
	$myjson = json_decode($myget['followers'], true);
	$userjson = json_decode($userget['followers'], true);
	if($action == 'add' || $action == 'del'){
		$myjson['me'] = ArrayUnsetValue($myjson['me'], $pid);
		
		if($action == 'add')$myjson['me'][] = $pid;
		
		$myjson['me'] = array_values($myjson['me']);
		$myjson = json_encode($myjson);
		
		$sql->where('id', $myid)->update('profiles', array(
			'followers' => $myjson
		));
		
		$userjson['users'] = ArrayUnsetValue($userjson['users'], $myid);
		
		if($action == 'add')$userjson['users'][] = $myid;
		
		$userjson['users'] = array_values($userjson['users']);
		$userjson = json_encode($userjson);
		
		$sql->where('id', $pid)->update('profiles', array(
			'followers' => $userjson
		));
		
		$text = array('text' => $action == 'add' ? 'با موفقیت اضافه شدید' : 'با موفقیت از لیست دنبال کنندگان حذف شدید', 'type' => 'success');
		
		echo json_encode($text, JSON_UNESCAPED_UNICODE);
		
	}else{
		if(count($userjson['users']) == '0')return;
		if(!empty($userjson['users'])){
		//	$json = array_slice($json, -12, 12);
			$implode = implode(',',$userjson['users']);
			echo '<script type="text/javascript">
				$(function () {
				  $(\'[data-toggle="tooltip"]\').tooltip();
				});</script>';

			$sql->join('profiles p','p.id=u.id', 'LEFT');
			$sql->join('onlines o','o.id=u.id', 'LEFT');
			$sql->where("u.id", $userjson['users'], 'in');
			$sql->orderBy('u.id', 'DESC');
			foreach($sql->get('users u', null, array('u.name', 'u.id', 'u.type', 'u.gender', 'p.avatar', 'o.id as online')) as $row){
				$avatar = Profile::ShowResizedAvatar(is_file('theme/images/avatars/'.$row['avatar']) ? $row['avatar'] : ($row['gender'] == '1' ? 'no_avatar_male.png' : 'no_avatar_female.jpg'));
				$on = $row['online'] ? 'on' : '';
				echo('<li class="list-group-item '.$on.'" data-toggle="tooltip" data-placement="bottom" title="'.$row['name'].' - '.$usertype[$row['type']]['title'].'"><a target="_blank" href="/profile/'.$row['id'].'"><img src="'.$avatar.'"><span>'.$row['name'].'</span></a></li>');
			}
		}
	}

?>