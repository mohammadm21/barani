<?php
$el3 = giu('el3');
switch($el3){
    case('add'):include(theme::file('question.add.php'));break;
    default:include(theme::file('question.php'));break;
}
?>