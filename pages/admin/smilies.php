<?php
$el3 = safe::string(giu('el3'));
switch($el3){
    case('add'):adminpanel::check_Permission('notfound', 'addsmily');include(theme::file('smilies.add.php'));break;
    case('edit'):adminpanel::check_Permission('notfound', 'editsmily');include(theme::file('smilies.edit.php'));break;
    case('bracelet'):adminpanel::check_Permission('notfound', 'editsmily');include(theme::file('smilies.bracelet.php'));break;

   
    default:adminpanel::check_Permission('notfound', 'smilies');include(theme::file('smilies.php'));break;
}
?>