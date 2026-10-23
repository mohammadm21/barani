<?php

$el3 = giu('el3');
adminpanel::check_Permission('notfound', "rollcall");
if ($el3 == 'top') {
    include(theme::file('rollcall.top.php'));
} else {
    include(theme::file('rollcall.php'));
}
?>