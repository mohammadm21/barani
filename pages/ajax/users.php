<?php

$el3 = giu('el3');
switch ($el3) {
    case('search'):include('users.search.php');
        break;
    default:header::Go(url('users'));
        break;
}
?>