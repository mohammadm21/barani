<?php

$id = session('id');
if (!$id) {
    exit('login');
}
$sql->where("userid", session('id'));
echo $sql->getValue('offline_message', "count(*)");
?>