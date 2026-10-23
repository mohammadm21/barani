<?php
check_session();
$message = isset($_POST['message']) && safe::string($_POST['message']) == 'false' ? 'nopm' : 'pmme';
$val = Users::Inputs();
$val['status_private'] = $message;
Users::Inputs(session('id'), $val);
$message == 'nopm' ? session('privat_msgs','close') : sessionunset('privat_msgs');
echo $message == 'nopm' ? 'خصوصي شما بسته شد' : 'خصوصي شما باز شد';
?>