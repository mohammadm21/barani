<?php
check_session();
header::tojson();
if(Users::guestType(session('type')))return;

$if = '1';
if(isset($_REQUEST['term']) and !empty($_REQUEST['term'])){
    $name = safe::string(urldecode($_REQUEST['term']));
    $sql->where("u.name", '%'.$name.'%','LIKE');
    $sql->where("u.type", 1,'!=');
    $sql->orderBy('u.type', 'DESC');
}else{
    exit();
}
$rows = array();
$sql->join('users u', 'u.id=p.id', 'LEFT');
foreach($sql->get('profiles p', null, array('p.id', 'u.name', 'u.type', 'u.gender')) as $row){
	$sex = $row['gender'] == '1' ? 'مرد' : 'زن';
    $rows[] = array('id' =>$row['id'], 'label' => "{$row['name']}({$usertype[$row['type']]['title']})($sex)", 'value' => $row['name']);
}
echo(json_encode($rows, JSON_UNESCAPED_UNICODE));

?>