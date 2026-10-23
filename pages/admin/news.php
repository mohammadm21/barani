<?php

$el3 = giu('el3');
switch ($el3) {
    case('add'):include(theme::file('news.add.php'));
        break;
    case('edit'):include(theme::file('news.edit.php'));
        break;
    default:include(theme::file('news.php'));
        break;
}
?>