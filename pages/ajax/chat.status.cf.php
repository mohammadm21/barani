<?php
check_session();
$status = isset($_POST['status']) && safe::string($_POST['status']) == 'false' ? 'noreq' : 'reqme';
$val = Users::Inputs();
$val['status_cf'] = $status;
Users::Inputs(session('id'), $val);
echo $status == 'noreq' ? 'بسته شد' : 'باز شد';
?>