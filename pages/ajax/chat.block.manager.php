<?php 
check_session();
if(session('type') == '1')die(RedError('مهمان اجازه ندارد لطفا عضو شوید'));
$id = isset($_REQUEST['id']) ? safe::number($_REQUEST['id']) : '';
$del = isset($_REQUEST['del']) && safe::string($_REQUEST['del']) == 'true' ? true : false;
$sql->where('user',session('id'));
$inputs = $sql->getOne('my_blacklist',array('blacklist'));
$inputs = json_decode($inputs['blacklist'], true);
if($del){
	$inputs = array_diff($inputs,array($id));
	$input = json_encode($inputs, JSON_UNESCAPED_UNICODE);
        $sql->where('user',session('id'))->update('my_blacklist',array(
              'blacklist'=>$input
        ));
        $sql->where('user',session('id'));
        $conts = $sql->getOne('my_blacklist',array('blacklist'));
	$conts = json_decode($conts , true);
	$cont = count($conts);
	if($cont == '0') { $sql->where('user',session('id'))->delete('my_blacklist'); } 
}
if(count($inputs) == '0')die(RedError('لیست خالی میباشد'));
echo '<table border="1" align="center" dir="rtl">
		<tr>
			<td align="center">ردیف</td>
			<td align="center">نام</td>
			<td align="center">درجه</td>
			<td align="center">حذف</td>
		<tr>';
$x = 1;
$implode = implode(',',$inputs);
$sql->where('id', $inputs, 'in');
$sql->orderBy('type', 'DESC');
foreach($sql->get('users', null, array('id', 'name', 'type')) as $row){
	echo '<tr><td align="center">'.$x.'</td>
	  <td align="center"><b><font color="'.$usertype[$row['type']]['color'].'">'.$row['name'].'</font></b></td>
	  <td align="center"><img src="/'.$usertype[$row['type']]['icon'].'"></td>
	  <td align="center"><a style="text-decoration: none;" href="chat.block.manager?id='.$row['id'].'&del=true">حذف</a></td>';
	$x++;
}

?>

