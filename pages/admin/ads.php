<?php

$el3 = giu('el3');
switch ($el3) {
    case('add'):include(theme::file('ads.add.php'));
        break;
    case('edit'):include(theme::file('ads.edit.php'));
        break;
    default:include(theme::file('ads.php'));
}
?>