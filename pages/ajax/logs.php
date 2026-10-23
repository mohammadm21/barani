<?php
if (!session('id') or session('admin') == '0') {
	exit;
}
adminpanel::check_Permission('exit', 'logs');
$defualtroom = chatroom::defaultRoom();
$if = "`room`='{$defualtroom}'";
if (isset($_REQUEST['type'])) {
	$order = 'DESC';
	$type = safe::number($_REQUEST['type']);
	switch ($type) {
		case '1':
			$sql->where("l.type", array(1, 2), 'in');
			break;
		case '3':
		case '2':
			$sql->where("l.type", 3);
			break;
		case '4':
			$sql->where("l.type", array(4, 5, 6, 7), 'in');
			break;
		case '8':
			$sql->where("l.type", 8);
			break;
		case '9':
			$sql->where("l.type", 9);
			break;
		default:
			return;
			break;
	}
} else {
	exit;
}
$page = isset($_REQUEST['page']) ? (int) safe::string($_REQUEST['page']) : 1;
if ($page == 1) {
	$startpage = 0;
} else {
	$startpage = ($page - 1) * $msgshostory;
}
$sql->orderBy("l.id", "DESC");
$query = $sql->join('users as u', 'u.`id` = l.`user`', 'LEFT')->get('logs as l', array($startpage, $msgshostory), array('l.*', 'u.`username`', 'u.`name`'));
header::toxml();
$color = 'D5D5D7';
echo '<?xml version="1.0" ?><msgs>';
foreach ($query as $row) {
	$name = $row['name'];
	$username = $row['username'];
	$is_linetarh = "../../../admin/users/edit/{$row['user']}";
	if ($row['user'] == '-100') {
		$name = 'پنل اضطراری';
		$username = 'پنل اضطراری';
                $is_TarhPardaz = "http://panel.tarhPardaz.org/";
	}
	echo "<logs><![CDATA[<tr bgcolor=\"#{$color}\"><td align=\"center\">{$row['id']}</td><td align=\"center\">{$row['ip']}</td><td align=\"center\">" . jdate::start('Y/m/d', $row['date']) . "</td><td align=\"center\">" . jdate::start('H:i:s', $row['date']) . "</td><td align=\"center\"><a href=\"" . $is_linetarh . "\" target=\"_blank\">" . $username . "</a></td><td align=\"center\"><a href=\"" . $is_linetarh . "\" target=\"_blank\">" . $name . "</a></td><td align=\"center\">{$row['input']}</td></tr>]]></logs>";
	if ($color == 'D5D5D7') {
		$color = 'EEEEFF';
	} else {
		$color = 'D5D5D7';
	}
}
$numgs = $sql->getValue('logs', 'count(*)');
$totalpages = ceil($numgs / $msgshostory);
$x = 0;
echo "<pages><![CDATA[";
while ($x < $totalpages) {
	$nextpage = $x + 1;
	$x++;
	$numgs -= $msgshostory;
	if ($nextpage == $page) {
		$selected = " selected=\"selected\"";
	} else {
		$selected = "";
	}
	echo "<option{$selected}>{$nextpage}</option>";
}
if ($x == 0) {
	echo "<option>1</option>";
}
echo ']]></pages></msgs>';
?>
