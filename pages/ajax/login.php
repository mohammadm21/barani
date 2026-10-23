<?php
$el3 = giu('el3');
switch($el3){
    case('guests'):include('login.guests.php');break;
    case('users'):include('login.users.php');break;
    case('multi'):include('login.multi.php');break;
    case('admins'):include('login.admins.php');break;
    default:header::Go(url('login'));break;
}
?>