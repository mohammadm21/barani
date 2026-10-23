<?php

$el3 = safe::string(giu('el3'));
switch ($el3) {
    case('add'):adminpanel::check_Permission('notfound', 'addstatos');
        include(theme::file('statos.add.php'));
        break;
    default:adminpanel::check_Permission('notfound', 'statos');
        include(theme::file('statos.php'));
        break;
}
?>