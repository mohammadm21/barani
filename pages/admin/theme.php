<?php

$el3 = safe::string(giu('el3'));
switch ($el3) {
    case('chat'):include(theme::file('theme.chat.php'));
        break;
    case('admin-icons'):include(theme::file('theme.admin-icons.php'));
        break;
    case('headers'):include(theme::file('theme.headers.php'));
        break;
    case('code'):include(theme::file('theme.code.php'));
        break;
    case('codeedit'):include(theme::file('theme.codeedit.php'));
        break;
    case('winstyle'):include(theme::file('theme.winstyle.php'));
        break;
 case('logintheme'):include(theme::file('theme.logintheme.php'));
        break;
    case('themeselect'):include(theme::file('theme.themeselect.php'));
        break;
    case('fontsadd'):adminpanel::check_Permission('notfound', 'addfonts');
        include(theme::file('theme.fontsadd.php'));
        break;
    case('fonts'):adminpanel::check_Permission('notfound', 'fonts');
        include(theme::file('theme.fonts.php'));
        break;
}
?>