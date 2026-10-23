<?php

adminpanel::check_Permission('notfound', 'usertypes');
$el3 = giu('el3');
switch ($el3) {
    case('edit'):include(theme::file('usertypes.edit.php'));
        break;
    case('add'):include(theme::file('usertypes.add.php'));
        break;
    case('delete'):include(theme::file('usertypes.delete.php'));
        break;
    default:include(theme::file('usertypes.php'));
        break;
}
?>