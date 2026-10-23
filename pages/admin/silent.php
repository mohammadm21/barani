<?php

adminpanel::check_Permission('notfound', "silent");
$el3 = giu('el3');
switch ($el3) {
    default:include(theme::file('silent.php'));
        break;
}
?>