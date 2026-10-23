<?php

$el3 = giu('el3');
switch ($el3) {
    case('add'):include(theme::file('player.add.php'));
        break;
    case('edit'):include(theme::file('player.edit.php'));
        break;
    default:include(theme::file('player.php'));
        break;
}
?>