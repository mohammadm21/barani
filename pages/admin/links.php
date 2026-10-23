<?php

$el3 = giu('el3');
switch ($el3) {
    case('add'):include(theme::file('links.add.php'));
        break;
    case('edit'):include(theme::file('links.edit.php'));
        break;
    default:include(theme::file('links.php'));
        break;
}
?>