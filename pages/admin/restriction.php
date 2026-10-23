<?php

$el3 = giu('el3');
switch ($el3) {
	case('login'):include(theme::file('restriction.login.php'));
        break;
	case('rightclick'):include(theme::file('restriction.rightclick.php'));
        break;
    case('useroptions'):include(theme::file('restriction.useroptions.php'));
        break;
	case('profile'):include(theme::file('restriction.profile.php'));
        break;
    case('social'):include(theme::file('restriction.social.php'));
        break;
    case('mod'):include(theme::file('restriction.mod.php'));
        break;
    case('private'):include(theme::file('restriction.private.php'));
        break;
    case('vip'):include(theme::file('restriction.vip.php'));
        break;
}
?>