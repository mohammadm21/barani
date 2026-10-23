<?php

if (!session('id') or session('admin') == '0') {
    exit();
}
adminpanel::check_Permission('exit', 'news');
$id = safe::number($_POST['new']);
$sql->where("id", $id);
if(!$sql->has('news')){
    exit('nonew');
}
$sql->where("id", $id);
if($post = $sql->getOne('news', array('id'))){
    $sql->where("id", $post['id']);
    $sql->delete('news');
    echo('ok');
}

$sql->where("id", session('id'));
$hide = $sql->getOne('onlines', array('id','hide'));
if ($hide['hide'] == '1' and session("admin")==1){
}elseif ($hide['hide'] == '0' and session("admin")!=0){
chatroom::addlog('9', "یک خبر را حذف کرد");
}

?>