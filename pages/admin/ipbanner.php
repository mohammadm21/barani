<?php

$el3 = giu('el3');
switch ($el3) {
    case('add'):include(theme::file('ipbanner.add.php'));
        break;
    case('edit'):include(theme::file('ipbanner.edit.php'));
        break;
    default:include(theme::file('ipbanner.php'));
        break;
}
?>