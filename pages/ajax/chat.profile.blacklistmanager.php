<?php 
check_session();
if(Users::guestType(session('type')))return;

$id = isset($_REQUEST['id']) ? safe::number($_REQUEST['id']) : '';
$del = isset($_REQUEST['del']) && $_REQUEST['del'] == 'true' ? true : false;

$meid = session('id');
if($del){

	$sql->join('profiles p','p.id = u.id', 'LEFT');
	$sql->where("u.id", $meid);
	$get = $sql->getOne("users u", array(
		'u.blacklist',
		'p.followers'
	));

	$sql->join('profiles p','p.id = u.id', 'LEFT');
	$sql->where("u.id", $myid);
	$user = $sql->getOne("users u", array(
		'u.blacklist',
		'p.followers'
	));
	$get = json_decode($get['blacklist'], true);
	$get = array_values($get);
	$get = ArrayUnsetValue($get,$id);
	$farray = json_encode(array_values($get));
	$sql->where("id", $meid)->update('users', array(
		'blacklist' => $farray
	));
	
	$myjson = json_decode($get['followers'], true);
	$userjson = json_decode($user['followers'], true);
	
	$myjson['users'] = ArrayUnsetValue($myjson['users'], $pid);
	
	$myjson['users'] = array_values($myjson['users']);
	$myjson = json_encode($myjson);
	
	$sql->where("id", $myid)->update('profiles', array(
		'followers' => $myjson
	));
	
	$userjson['me'] = ArrayUnsetValue($userjson['me'], $myid);
	
	$userjson['me'] = array_values($userjson['me']);
	$userjson = json_encode($userjson);
	
	$sql->where("id", $pid)->update('profiles', array(
		'followers' => $userjson
	));
	
}
$blacklist = Users::get('blacklist',$meid);
$blacklist = json_decode($blacklist, true);
if(count($blacklist) == '0')die(RedError('لیست خالی میباشد'));
echo '<table border="1" align="center">
		<tr>
			<td align="center">#</td>
			<td align="center">نام</td>
			<td align="center">درجه</td>
			<td align="center">وضعیت</td>
			<td align="center">حذف</td>
		<tr>';
$x = 1;
$implode = implode(',',$blacklist);
$sql->join('onlines o', 'o.id=u.id', 'LEFT');
$sql->where("u.id", $blacklist, 'in');
$sql->orderBy('o.id', 'DESC');
foreach($sql->get('users u', array(
	'u.id',
	'u.name',
	'u.type',
	'o.id as online'
)) as $row){
	$onof = $row['online'] ? 'online.png' : 'offline_pro.png';
	echo '<tr><td align="center">'.$x.'</td>
	  <td align="center"><b><font color="'.$usertype[$row['type']]['color'].'">'.$row['name'].'</font></b></td>
	  <td align="center"><img src="/'.$usertype[$row['type']]['icon'].'"></td>
	  <td align="center"><img src="'.theme::url('images/'.$onof).'"></td>
	  <td align="center"><a style="text-decoration: none;" href="chat.profile.blacklistmanager?id='.$row['id'].'&del=true">حذف</a></td>';
	$x++;
}
?>

