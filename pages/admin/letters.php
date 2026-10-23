<?php

$letter = safe::number(giu('el3'));
$sql->where('id', $letter);
$sql->where('to', session('id'));
if($letter = $sql->getOne('letters')){
    include('letters.view.php');
} else {
    include(theme::file('letters.php'));
}
?>