<?php
$el3 = giu('el3');
switch($el3){
    case('add'):include(theme::file('visualuser.add.php'));break;
	case('edit'):include(theme::file('visualuser.edit.php'));break;
	case('group'):include(theme::file('visualuser.group.php'));break;
	case('random'):include(theme::file('visualuser.random.php'));break;
    default:include(theme::file('visualuser.php'));break;
}
?>