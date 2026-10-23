<?php

$admin = session('admin');
if ($admin != '0') {
    session('hide', '0');
    Users::show(session('id'));
}
?>