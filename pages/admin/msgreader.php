<?php

if (!session('id') or session('admin') == '0') {
    exit();
}
adminpanel::check_Permission('exit', 'msgreader');
include(theme::file('msgreader.php'));
?>