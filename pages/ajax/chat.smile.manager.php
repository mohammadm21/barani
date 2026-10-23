<?php
check_session();
if (session('type') == '1') {
	die(RedError('مهمان اجازه ندارد لطفا عضو شوید'));
}
$id = session('id');
$useroptions = json_decode($options['useroptions'], true);
if (!in_array(session('type'), $useroptions['usersmile'])) {
	die(RedError('برای گروه کاربری شما محدودیت قرار داده شده است'));
}
$del = isset($_REQUEST['del']) && safe::string($_REQUEST['del']) == 'true' ? true : false;
$ids = isset($_REQUEST['ids']) ? safe::number($_REQUEST['ids']) : '';
$smile_users = $sql->getOne('user_smile', array('user'));
if ($del) {
	$sql->where('id', $ids)->delete('user_smile');
}
if (count($smile_users) == '0') {
	die(RedError('لیست خالی میباشد'));
}
echo '<table border="1" align="center" dir="rtl">
		<tr>
			<td align="center">ردیف</td>
			<td align="center">تصویر</td>
			<td align="center">وضعیت</td>
			<td align="center">حذف</td>
		<tr>';
$x = 1;
$query = $sql->where('user', $id)->get('user_smile', null, array('id', 'smilecode', 'active'));
foreach ($query as $row) {
	?>
	<tr><td align="center"><?php echo $x;?></td>
	  <td align="center"><img src="<?php echo url($options['smilies_dir'] . '/' . $row['smilecode'] . '.gif');?>"/></td>
	  <td align="center"><?php
	if ($row['active'] == "0") {
		echo "در انتظار تایید";
	} else {
		echo "تاییده شده";
	}
	?></td>
	  <td align="center"><a style="text-decoration: none;" href="chat.smile.manager?ids='<?php echo $row['id'];?>'&del=true">حذف</a>
	  </td>
	  <?php
	$x++;
}
?>
