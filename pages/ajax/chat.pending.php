<?php

check_session();
header('Content-Type: text/html; charset=utf-8');
$action = isset($_POST['action']) && safe::string($_POST['action']) == 'true' ? true : false;

Users::guestType(session('type')) ? die(RedError('مهمان اجازه ندارد لطفا عضو شويد')) : '';
$id = isset($_REQUEST['id']) ? safe::number($_REQUEST['id']) : '';
$accept = isset($_REQUEST['accept']) && safe::string($_REQUEST['accept']) == 'true' ? true : false;
$del = isset($_REQUEST['del']) && safe::string($_REQUEST['del']) == 'true' ? true : false;

$meid = session('id');
$pending = Users::get('pending', $meid);
$jpending = json_decode($pending, true);
if ($accept) {
    $sql->where("id", $meid);
    $sql->orWhere("id", $id);
    foreach($sql->get('users', null, array('id', 'friends','pending')) as $row){
        $jsondecode = json_decode($row['friends'], true);
		$jsonpending = json_decode($row['pending'], true);
        if ($row['id'] == $meid) {
			if(!in_array($id,$jsonpending) || !in_array($id,$jpending)){
				die(RedError('شما درخواست دوستی از کاربر مورد نظر ندارید'));
			}
            $jpending = Add2MyFriends($jsondecode, $jpending, $meid, $id);
        } else {
			$p = Users::get('pending', $id);
            $j = json_decode($pending, true);
			if(!in_array($meid,$jsonpending) || !in_array($meid,$j)){
				die(RedError('شما درخواست دوستی از کاربر مورد نظر ندارید'));
			}
            Add2MyFriends($jsondecode, $jpending, $id, $meid);
            $usex = session('gender') == '1' ? 'مرد' : 'زن';
            $text = "نام: " . session('name') . " || جنسیت: " . $usex . " || درجه: " . $usertype[session('type')]['title'] . " || درخواست دوستی شما را قبول کرد.";
            Users::alert($id, $text);
        }
    }
}
if ($del) {
    $sql->where("id", $meid);
    $delpending = $sql->getOne("users", array('pending'));
    $jsondecode = json_decode($delpending['pending'], true);
    $jpending = DelteInPending($jsondecode, $meid, $id);
    $usex = session('gender') == '1' ? 'مرد' : 'زن';
    $text = "نام: " . session('name') . " || جنسیت: " . $usex . " || درجه: " . $usertype[session('type')]['title'] . " || درخواست دوستی شما را قبول نکرد !!!";
    Users::alert($id, $text);
}
if (count($jpending) == '0')
    die(RedError('ليست خالي ميباشد'));
echo '<table border="1" align="center">
			<tr>
				<td align="center">#</td>
				<td align="center">نام</td>
				<td align="center">درجه</td>
				<td align="center">وضعیت</td>
				<td align="center">قبول</td>
				<td align="center">حذف</td>
			<tr>';
$x = 1;
$implode = implode(',', $jpending);

$sql->join('onlines o', 'o.id=u.id', 'LEFT');
$sql->where("u.id", $jpending, 'in');
$sql->orderBy('o.id', "DESC");
foreach($sql->get('users u', null, array('u.id', 'u.name', 'u.type', 'u.online', 'o.id as online')) as $row){
    $onof = $row['online'] ? 'online.png' : 'offline_pro.png';
    echo '<tr><td align="center">' . $x . '</td>
		  <td align="center"><b><font color="' . $usertype[$row['type']]['color'] . '">' . $row['name'] . '</font></b></td>
		  <td align="center"><img src="/' . $usertype[$row['type']]['icon'] . '"></td>
		  <td align="center"><img src="' . theme::url('theme/images/chat/' . $onof) . '"></td>
		  <td align="center"><a style="text-decoration: none;" href="chat.pending?id=' . $row['id'] . '&accept=true">قبول</a></td>
		  <td align="center"><a style="text-decoration: none;" href="chat.pending?id=' . $row['id'] . '&del=true">حذف</a></td>';
    $x++;
}

function Add2MyFriends($jfriends, $pending, $id, $add) {
    global $sql;
    $jfriends[] = $add;
    $array = array_unique($jfriends);
    $array = array_values($jfriends);
    $json = json_encode($array);
    $sql->where("id", $id)->update('users', array(
        'friends' => $json
    ));
    $array = DelteInPending($pending, $id, $add);

    return $array;
}

function DelteInPending($jfriends, $id, $delid) {
    global $sql;
    $array = ArrayUnsetValue($jfriends, $delid);
    $json = json_encode($array);
    $sql->where("id", $id)->update('users', array(
        'pending' => $json
    ));
    return $array;
}

function DelteFriend($jfriends, $id, $delid) {
    global $sql;
    $array = ArrayUnsetValue($jfriends, $delid);
    $json = json_encode($array);
    $sql->where("id", $id)->update('users', array(
        'friends' => $json
    ));
    return $array;
}
?>

