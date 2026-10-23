<?php

if (!session('id') or session('admin') == '0') {
    exit();
}
adminpanel::check_Permission('exit', 'pays');
$id = safe::number($_REQUEST['link']);
$sql->where("id", $id);
$sql->delete('pays');
echo('ok');
?>