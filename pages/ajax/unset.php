<?php

$el3 = safe::string(giu('el3'));
if ($el3 == 'admin') {
    if (session('id') && session('admin') != '0') {
        session('adminpanelogin', 'false');
        echo('ok');
    }
}
?>