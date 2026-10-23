<?php

check_session();
$myid = isset($_POST['id']) ? safe::number($_POST['id']) : die('id?');
$cfid = isset($_POST['cfid']) ? safe::number($_POST['cfid']) : die('cfid?');
$action = isset($_POST['action']) && safe::string($_POST['action']) == 'invite' ? 'invite' : 'join';
$channelid = isset($_POST['channelid']) ? safe::string($_POST['channelid']) : die('channelid?');
$roomid = isset($_POST['roomid']) ? safe::string($_POST['roomid']) : die('roomid?');
((session('id') == $cfid) ? false : !allow_cf($cfid)) ? die('شما حذف شده اید') : '';

$sql->where("creator", $cfid);
$sql->where("joiner", $myid, '!=');
foreach($sql->get('cf_users') as $row){
    $val = Users::Inputs($row['joiner']);
    $val['cvc'] = $cfid;
    $val['cvc_chid'] = $channelid;
    $val['cvc_rid'] = $roomid;
    Users::Inputs($row['joiner'], $val);
}
if ($cfid != session('id')) {
    $v = Users::Inputs($cfid);
    $v['cvc'] = $cfid;
    $v['cvc_chid'] = $channelid;
    $v['cvc_rid'] = $roomid;
    Users::Inputs($cfid, $v);
}
echo('فرستاده شد');
?>