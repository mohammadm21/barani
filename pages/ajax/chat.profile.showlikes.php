<?php
check_session();

include('inc/classes/profile.php');
Profile::CheckActiveMe();

$action = isset($_REQUEST['action']) && safe::string($_REQUEST['action']) == 'liked' ? 'liked' : 'disliked';
$location = isset($_REQUEST['loc']) ? safe::string($_REQUEST['loc']) : 'main';
$id = isset($_REQUEST['id']) ? safe::number($_REQUEST['id']) : die('id?');
$more = isset($_REQUEST['more']) ? safe::number($_REQUEST['more']) : '20';
$sort = isset($_REQUEST['sort']) && safe::string($_REQUEST['sort']) == 'ASC' ? 'ASC' : 'DESC';

ProfileLikedUsers($id,$action,$more,$sort,$location);

function ProfileLikedUsers($id,$action,$more,$sort,$location){
	if(!session('id'))return;
	global $usertype, $sql;
	switch ($location) {
		case 'posts':
		//	$likedusers = $action == 'liked' ? $sql->where('id', $id)->getOne('profile_posts', array('likedusers')) : $sql->where('id', $id)->getOne('profile_posts', array('dislikedusers'));
			
       			if( $action == 'liked' ) $likedusers = $sql->where('id', $id)->getOne('profile_posts', array('likedusers'));
     			if( $action == 'disliked' ) $likedusers = $sql->where('id', $id)->getOne('profile_posts', array('dislikedusers'));
			if( $action == 'liked' ) $json = json_decode($likedusers['likedusers'], true);
   			if( $action == 'disliked' ) $json = json_decode($likedusers['dislikedusers'], true);
			break;
		case 'avatar':
			$likedusers = $sql->where('id', $id)->getOne('profiles', array('lduavatar'));
			$json = json_decode($likedusers['lduavatar'], true);
			$json = $action == 'liked' ? $json['likedusers'] : $json['dislikedusers'];
			break;
		case 'cover':
			$likedusers = $sql->where('id', $id)->getOne('profiles', array('lducover'));
			$json = json_decode($likedusers['lducover'], true);
			$json = $action == 'liked' ? $json['likedusers'] : $json['dislikedusers'];
			break;
		case 'info':
			$likedusers = $sql->where('id', $id)->getOne('profiles', array('lduinfo'));
			$json = json_decode($likedusers['lduinfo'], true);
			$json = $action == 'liked' ? $json['likedusers'] : $json['dislikedusers'];
			break;
		case 'music':
			$likedusers = $sql->where('id', $id)->getOne('profiles', array('ldumusic'));
			$json = json_decode($likedusers['ldumusic'], true);
			$json = $action == 'liked' ? $json['likedusers'] : $json['dislikedusers'];
			break;
		default:
			//$likedusers = $action == 'liked' ? $sql->where('id', $id)->getValue('profiles', 'likedusers') : $sql->where('id', $id)->getValue('profiles', 'dislikedusers');
			//$json = json_decode($likedusers, true);
       			if( $action == 'liked' ) $likedusers = $sql->where('id', $id)->getOne('profiles', array('likedusers'));
     			if( $action == 'disliked' ) $likedusers = $sql->where('id', $id)->getOne('profiles', array('dislikedusers'));
			if( $action == 'liked' ) $json = json_decode($likedusers['likedusers'], true);
   			if( $action == 'disliked' ) $json = json_decode($likedusers['dislikedusers'], true);
			break;
		}
	
		$count = count($json);
		if($count == '0')return;
		if($count < $more)$more = $count;
		if($sort == 'DESC'){
			//$json = array_slice($json, -$more, $more);
		}else{
		//	$json = array_slice($json, 0, $more);
		}
		$json = array_slice($json, -55, 55);
		$implode = implode(',',$json);
		echo '<script type="text/javascript">
			$(function () {
			  $(\'[data-toggle="tooltip"]\').tooltip();
			});</script>';
		$x = 1;
	        $query = $sql->where("u.id", $json, 'in')->join('profiles as p', 'p.`id` = u.`id`', 'LEFT')->join('onlines as o', 'o.`id` = u.`id`', 'LEFT')->get('users as u', null, array('u.`id`', 'u.`name`', 'u.`type`', 'u.`gender`', 'p.`avatar`', 'o.`id` as online'));
		$sort_sort = $sort == 'ASC' ? 'DESC' : 'ASC';
		if($action == 'liked'){
			$text = $sort == 'DESC' ? 'از اولین لایک کننده تا آخرین' : 'از آخرین لایک کننده تا اولین';
		}else{
			$text = $sort == 'DESC' ? 'از اولین دیسلایک کننده تا آخرین' : 'از آخرین دیسلایک کننده تا اولین';
		}
	        foreach ($query as $row) {
			$avatar = Profile::ShowResizedAvatar(is_file('theme/images/avatars/'.$row['avatar']) ? $row['avatar'] : ($row['gender'] == '1' ? 'no_avatar_male.png' : 'no_avatar_female.jpg'));
			$on = $row['online'] ? 'on' : '';
			echo('<li class="list-group-item '.$on.'" data-toggle="tooltip" data-placement="bottom" title="'.$row['name'].' - '.$usertype[$row['type']]['title'].'"><a target="_blank" href="/profile/'.$row['id'].'"><img src="'.$avatar.'"><span>'.$row['name'].'</span></a></li>');

			$x++;
		}
}
?>