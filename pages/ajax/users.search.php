<?php
if (!chatroom::checkPermission("users")) {
	exit("perm");
}
if (isset($_REQUEST['username'])) {
	$username = safe::string($_REQUEST['username']);
	$sql->where("name", "%" . $username . "%", "like");
	$sql->orderBy("type", "DESC");
}
$query = $sql->get('users', null, array('id', 'name','gender', 'type', 'score'));
header::toxml();
echo '<?xml version="1.0" ?><users>';
foreach ($query as $row) {
                        switch($row['gender']){
                            case '1':$gender = 'm';break;
                            case '2':$gender = 'w';break;
                            case '0':$gender = 'n';break;
                             }
	$type = users::GetType($row['type']);
	echo "<user><![CDATA[<tr><td align=\"center\">{$row['id']}</td><td align=\"center\">{$row['name']}</td><td align=\"center\"><a href=\"/profile/" . $row['id'] . "\"><img src=\"" . $usertype[$row['type']]['icon'] . "\"></a></td><td align=\"center\"><img src=\"" . url('/theme/images/chat/chat.user_comment_' . $gender . '.png') . "\"></td><td align=\"center\">{$row['score']}</td><td align=\"center\"><a href=\"" . url("profile/{$row['id']}") . "\"><img src=\"" . url('theme/ver1/images/profile.png') . "\"></a></td></tr>]]></user>";
}
echo '</users>';
?>
