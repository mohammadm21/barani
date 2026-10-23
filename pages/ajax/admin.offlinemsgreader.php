<?php
if(!session('id') or session('admin') == '0'){exit();}
adminpanel::check_Permission('exit', 'msgreader');
header::toxml();
$color = 'D5D5D7';
$page = isset($_POST['page']) ? ((int)safe::number($_POST['page'])) : 1;
if($page == 1)$startpage=0;else $startpage = ($page-1)*$msgshostory;
if(isset($_POST['keyword']) && !empty($_POST['keyword'])){
$keyword = safe::string($_POST['keyword']);
$if = true;
$limit = '';
}else{
$if = false;
$limit = "LIMIT $startpage, $msgshostory";
}
$name_sender1 = 'all';
$name_reciver1 = 'all';
if(isset($_POST['name_sender1'])){
	if(!empty($_POST['name_sender1']))$name_sender1 = safe::string($_POST['name_sender1']);else $name_sender1 = 'all';
}
if(isset($_POST['name_reciver1'])){
	if(!empty($_POST['name_reciver1']))$name_reciver1 = safe::string($_POST['name_reciver1']);else $name_reciver1 = 'all';
}

echo('<?xml version="1.0" ?><msgs>');
$Y = 1;
$sql->orderBy('id', 'DESC');
foreach($sql->get('offline_message', array($startpage, $msgshostory)) as $row){
	if($row['userid'] == '-1' || $row['sender_id'] == '-1')continue;
	$text_array = explode(',',$row['text']);
	foreach($text_array as $text){
		if($if && !preg_match("/$keyword/i",$text))continue;
		$name_reciver = Users::get('name',$row['userid']); 
		$name_sender = Users::get('name',$row['sender_id']);
		if($name_sender1 != 'all' && !preg_match("/$name_sender1/i",$name_sender))continue;
		if($name_reciver1 != 'all' && !preg_match("/$name_reciver1/i",$name_reciver))continue;
		$random = rand(11111111,999999999);
		    echo("<msg><![CDATA[
        <tr id=\"msg-".$random."\" bgcolor=\"#$color\">
            <td width=\"20\" align=\"center\">$Y</td>
            <td width=\"180\" align=\"center\"><a href=\"../../../admin/users/edit/$row[sender_id]\" target=\"_blank\">.: $name_sender :.</a></td>
            <td width=\"180\" align=\"center\"><a href=\"../../../admin/users/edit/$row[userid]\" target=\"_blank\">.: $name_reciver :.</a></td>
            <td width=\"180\" align=\"center\">$text</td>
            <td width=\"20\" align=\"center\"><a href=\"javascript:del_offline_message('".$row['id']."','".$text."','".$random."');\">X</a></td>
        </tr>
    ]]></msg>");
	$Y++;
	}

	
}
$numgs = $sql->getValue('offline_message', 'count(*)');
$totalpages = ceil($numgs / $msgshostory);
$x=0;
echo("<pages><![CDATA[");
while($x < $totalpages ){
    $nextpage = $x+1;
    $x++;
    $numgs -= $msgshostory;
    if($nextpage == $page){$selected = " selected=\"selected\"";}else{ $selected = "";}
    echo("<option$selected>$nextpage</option>");
}
if($x == 0){echo("<option>1</option>");}
echo(']]></pages></msgs>');
?>