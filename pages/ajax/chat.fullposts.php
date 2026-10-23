<?php
/*
header::tojson();
include 'inc/classes/bbcode.php';
$limit = isset($options['main_numposts']) && $options['main_numposts'] > 0 ? $options['main_numposts'] : "5";
$lastime = isset($_POST['time']) ? safe::number($_POST['time']) : 0;
$myposts = myposts(0, $limit, $lastime);
echo json_encode(array(
	'myposts' => is_array($myposts) ? $myposts : array()
), JSON_UNESCAPED_UNICODE);
function myposts($start = 0, $limit = 5, $lastime = 0) {
	global $usertype, $options;
	$profile = $sql->join("profiles p", "p.id=u.id", "LEFT")->where("u.id", session('id'))->getOne('users u', array('u.friends', 'p.followers'));
	$friends = array_values(json_decode($profile['friends'], true));
	$followers = json_decode($profile['followers'], true);
	$friends_posts = "";
	$follows_posts = "";
	if ($lastime) {
		$sql->where('p.time', $lastime, '>');
	}
	if (count($friends) != 0) {
		$sql->orWhere('p.userid', $friends, 'in');
	}
	if (count($followers['me']) != 0) {
		$sql->orWhere('p.userid', $followers['me'], 'in');
	}
	$sql->orWhere("p.userid", session('id'));
	$sql->orderBy('id', 'DESC');
	$sql->join('users u', 'u.id = p.userid', 'LEFT');
	$sql->join('onlines o', 'o.id = p.userid', 'LEFT');
	$sql->join('profiles pr', 'pr.id = p.userid', 'LEFT');
	$uposts = array();
	foreach ($sql->get('profile_posts p', array($start, $limit), array('p.*', 'u.name', 'u.type', 'u.gender', 'pr.avatar', 'o.id as online')) as $row) {
		$avatar = '/avatars/' . (is_file('theme/images/avatars/' . $row['avatar']) ? $row['avatar'] : ($row['gender'] == '1' ? 'no_avatar_male.png' : 'no_avatar_female.jpg')) . '/50x50';
		$for = "me";
		if (count($friends) != 0 and in_array($row['userid'], $friends)) {
			$for = "fr";
		}
		if (count($followers['me']) != 0 && in_array($row['userid'], $followers['me'])) {
			$for = "fl";
		}
		$uposts[] = array(
			'pid' => $row['id'],
			'id' => $row['userid'],
			'name' => $row['name'],
			'color' => $for,
			'avatar' => $avatar,
			'likes' => count(array_values(json_decode($row['likedusers'], true))),
			'dislikes' => count(array_values(json_decode($row['dislikedusers'], true))),
			'comments' => count(json_decode($row['comments'], true)),
			'content' => bbcode::tohtml($row['content'], true),
			'time' => time_ago($row['time']),
			'rtime' => $row['time']
		);
	}
	return $uposts;
}*/
?>