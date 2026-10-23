<?php

check_session();
$admin = session('admin');
if ($admin == '1' or $admin == '2' or $admin == '3' or $admin == '4' or $admin == '5' or $admin == '6' or $admin == '7') {
    if ($admin == '1' or $admin == '2') {
        if ($options['adminhidden'] == 'false') {
            die('امکان مخفی شدن حذف شده');
        }
    }
    if ($admin == '3' or $admin == '4' or $admin == '5' or $admin == '6') {
        if ($options['modhidden'] == 'false') {
            die('امکان مخفی شدن برای ناظران حذف شده است.');
        }
    }
    session('hide', '1');
    Users::hide(session('id'));
}
?>