<?php

adminpanel::check_Permission('notfound', 'adminpanel');
$el3 = safe::string(giu('el3'));
switch ($el3) {
    case('add'):include(theme::file('adminpanel.add.php'));
        break;
    case('edit'):include(theme::file('adminpanel.edit.php'));
        break;
    default:include(theme::file('adminpanel.php'));
        break;
}
?>