<?php
$el3 = safe::string(giu('el3'));
switch($el3){
    case('add'):adminpanel::check_Permission('notfound', 'addavatar');include(theme::file('avatars.add.php'));break;
    case('edit'):adminpanel::check_Permission('notfound', 'editavatar');include(theme::file('avatars.edit.php'));break;
    default:adminpanel::check_Permission('notfound', 'avatars');include(theme::file('avatars.php'));break;
}
?>