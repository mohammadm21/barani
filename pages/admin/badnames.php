<?php

$el3 = giu('el3');
switch ($el3) {
    case('add'):adminpanel::check_Permission('notfound', 'badnames');
        include(theme::file('badnames.add.php'));
        break;
    case('edit'):
        include(theme::file('badnames.edit.php'));
        break;
    case('setting'):
        include(theme::file('badnames.setting.php'));
        break;
    default:adminpanel::check_Permission('notfound', 'badnames');
        include(theme::file('badnames.php'));
}
?>