<?php

if (!session('id') or session('admin') == '0') {
    exit();
}
adminpanel::check_Permission('exit', 'sendedimages');
include(theme::file('sendedimages.php'));
?>