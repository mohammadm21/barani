<?php

$el3 = giu('el3');
switch ($el3) {
    case('add'):adminpanel::check_Permission('notfound', 'addroom');
        include(theme::file('rooms.add.php'));
        break;
    case('edit'):adminpanel::check_Permission('notfound', 'editroom');
        include(theme::file('rooms.edit.php'));
        break;
    case('delete'):adminpanel::check_Permission('notfound', 'deleteroom');
        include(theme::file('rooms.delete.php'));
        break;
    default:adminpanel::check_Permission('notfound', 'rooms');
        include(theme::file('rooms.php'));
        break;
}
?>