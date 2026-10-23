<?php
check_session();
$action = isset($_POST['action']) && $_POST['action'] == 'true' ? true : false;
if ($action) {
	header::tojson();
	$lfriends = Users::get('friends', session('id'));
	$jdecode = json_decode($lfriends, true);
	$j = array();
	foreach ($jdecode as $key => $val) {
		$j[] = $val;
	}
	echo json_encode($j);
	exit;
} else {
	Users::guestType(session('type')) ? die(RedError('مهمان اجازه ندارد لطفا عضو شويد')) : '';
	$id = isset($_REQUEST['id']) ? safe::number($_REQUEST['id']) : '';
	$del = isset($_REQUEST['del']) && $_REQUEST['del'] == 'true' ? true : false;
	$isprofile = isset($_REQUEST['location']) && $_REQUEST['location'] == 'profile' ? true : false;
	$meid = session('id');
	$friends = Users::get('friends', $meid);
	$jfriends = json_decode($friends, true);
	if ($del) {
		$query = $sql->where('id', $meid)->orWhere('id', $id)->get('users', null, array('id', 'friends'));
		foreach ($query as $row) {
			$jsondecode = json_decode($row['friends'], true);
			if ($row['id'] == $meid) {
				$jfriends = DelteFriend($jsondecode, $meid, $id);
			} else {
				DelteFriend($jsondecode, $id, $meid);
				$usex = session('gender') == '1' ? 'مرد' : 'زن';
				$text = "نام: " . session('name') . " || جنسیت: " . $usex . " || درجه: " . $usertype[session('type')]['title'] . " || شما را از لیست دوستان خود پاک کرد !!!!";
				Users::alert($id, $text);
			}
		}
		if ($isprofile) {
			exit('با موفقیت پاک شد');
		}
	}
	if (count($jfriends) == '0') {
		die(RedError('ليست خالي ميباشد'));
	}
	echo '<table dir="rtl" border="1" align="center">
			<tr>
				<td align="center">#</td>
				<td align="center">نام</td>
				<td align="center">درجه</td>
				<td align="center">وضعیت</td>
				<td align="center">حذف</td>
			<tr>';
	$x = 1;
	$query = $sql->where('u.id', $jfriends, 'in')->join('onlines as o', 'o.id = u.id', 'LEFT')->get('users as u', null, array('u.id', 'u.name', 'u.type', 'u.online', 'o.id as online'));
	foreach ($query as $row) {
		$hide = $sql->where('id', $row['id'])->getOne('onlines', array('hide'));
		$onof = $row['online'] == '0' ? 'offline_pro.png' : ($hide == '0' ? 'online.png' : 'offline_pro.png');
		echo '<tr><td align="center">' . $x . '</td>
	  <td align="center"><b><font color="' . $usertype[$row['type']]['color'] . '">' . $row['name'] . '</font></b></td>
	  <td align="center"><img style="height: 30px; width: 30px;" src="/' . $usertype[$row['type']]['icon'] . '"></td>
	  <td align="center"><img style="height: 20px; width: 20px;" src="' . theme::url('theme/images/chat/' . $onof) . '"></td>
	  <td align="center"><a style="text-decoration: none;" href="chat.showmyfriends?id=' . $row['id'] . '&del=true">حذف</a></td>';
		$x++;
	}
}
function DelteFriend($jfriends, $id, $delid) {
	global $sql;
	$array = ArrayUnsetValue($jfriends, $delid);
	$json = json_encode($array);
	$sql->where('id', $id)->update('users', array(
		'friends' => $json
	));
	return $array;
}
?>
