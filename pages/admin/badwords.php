<?php

$el3 = giu('el3');
switch ($el3) {
    case('add'):adminpanel::check_Permission('notfound', 'badwords');
        include(theme::file('badwords.add.php'));
        break;
    case('edit'):adminpanel::check_Permission('notfound', 'editbadwords');
        include(theme::file('badwords.edit.php'));
        break;
    case('setting'):adminpanel::check_Permission('notfound', 'badwordsettings');
        include(theme::file('badwords.setting.php'));
        break;
    default:adminpanel::check_Permission('notfound', 'badwords');
        include(theme::file('badwords.php'));
}
?>