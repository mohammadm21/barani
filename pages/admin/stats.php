<?php

adminpanel::check_Permission('notfound', 'stats');
$el3 = giu('el3');
switch ($el3) {
    case('list'):include(theme::file('stats.list.php'));
        break;
    default:include(theme::file('stats.php'));
        break;
}
?>