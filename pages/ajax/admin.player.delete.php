<?php

if (!session('id') or session('admin') == '0') {
    exit();
}
adminpanel::check_Permission('exit', 'delplayer');
$music = isset($_POST['id']) ? safe::number($_POST['id']) : '';
if ($music == '') {
    echo('id');
    return;
}
$sql->where("id", $music);
if ($music = $sql->getOne('player')) {
    if (is_file($music['src']))
        unlink($music['src']);
    $sql->where("id", $music['id']);
    $sql->delete('player');

$sql->where("id", session('id'));
$hide = $sql->getOne('onlines', array('id','hide'));
if ($hide['hide'] == '1' and session("admin")==1){
}elseif ($hide['hide'] == '0' and session("admin")!=0){
chatroom::addlog('9', "آهنگ \"{$music['title']}\" را حذف کرد");
}
    echo('ok');
}
?>