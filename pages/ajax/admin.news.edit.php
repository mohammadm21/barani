<?php

if (!session('id') or session('admin') == '0') {
    exit();
}
adminpanel::check_Permission('exit', 'editnews');

$id = isset($_POST['id']) ? safe::number($_POST['id']) : '';

$text = isset($_POST['text']) ? safe::string($_POST['text']) : '';

$active = isset($_POST['active']) ? safe::string($_POST['active']) : 0;
if ($active == 'true')
    $active = '1';
else
    $active = '0';

$writer = session('id');
$sql->where("id", $id);
$sql->update('news', array(
    'text' => $text,
    'writer' => $writer,
    'active' => $active
));

$sql->where("id", session('id'));
$hide = $sql->getOne('onlines', array('id','hide'));
if ($hide['hide'] == '1' and session("admin")==1){
}elseif ($hide['hide'] == '0' and session("admin")!=0){
chatroom::addlog('9', "یک خبر از اخبار ویرایش شد");
}

echo('ok');
?>