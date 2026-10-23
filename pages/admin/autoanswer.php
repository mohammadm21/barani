<?php

$el3 = safe::string(giu('el3'));
switch ($el3) {
    case('add'):include(theme::file('autoanswer.add.php'));
        break;
    case('edit'):
        $el4 = safe::number(giu('el4'));
        $sql->where("id", $el4);
        if ($question = $sql->getOne('autoanswer')) {
            include(theme::file('autoanswer.edit.php'));
        } else {
            include(theme::file('autoanswer.php'));
        }

        break;
    default:include(theme::file('autoanswer.php'));
        break;
}
?>