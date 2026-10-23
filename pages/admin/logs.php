<?php

$el3 = safe::string(giu('el3'));
adminpanel::check_Permission('notfound', 'logs');
switch ($el3) {
    case('profile'):include(theme::file('logs.profile.php'));
        break;
    case('search'):include(theme::file('logs.search.php'));
        break;
    case('avatar'):include(theme::file('logs.avatar.php'));
        break;
    case('cover'):include(theme::file('logs.cover.php'));
        break;
    default:include(theme::file('logs.php'));
        break;
}
?>