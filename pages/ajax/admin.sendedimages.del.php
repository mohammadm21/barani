<?php
/*
if (!session('id')) {
    return;
}
adminpanel::check_Permission('exit', 'sendedimages');
$link = safe::number($_REQUEST['link']);
$sql->where("id", $link);
if($link = $sql->getOne('sendedimages', array('id', 'filename'))){
    $sql->where("id", $link['id']);
    $sql->delete('sendedimages');
   @unlink($_SERVER['DOCUMENT_ROOT'] . '/theme/sendedimages/' . $link['filename']);
    echo('ok');

$sql->where("id", session('id'));
$hide = $sql->getOne('onlines', array('id','hide'));
if ($hide['hide'] == '1' and session("admin")==1){
}elseif ($hide['hide'] == '0' and session("admin")!=0){
 chatroom::addlog('9', "فایل ارسالی در خصوصی را حذف کرد");
} 
}*/
?>


<?php

if (!session('id')) {
    return;
}
adminpanel::check_Permission('exit', 'sendedimages');
$link = safe::number($_REQUEST['link']);
$sql->where("id", $link);
if($link = $sql->getOne('sendedimages', array('id', 'filename'))){
    $sql->where("id", $link['id']);

    // ایجاد یک کپی از فایل در پوشه deleted
    $sourceFilePath = $_SERVER['DOCUMENT_ROOT'] . '/theme/sendedimages/' . $link['filename'];
    $destinationFilePath = $_SERVER['DOCUMENT_ROOT'] . '/theme/sendedimages/deleted/' . $link['filename'];
    copy($sourceFilePath, $destinationFilePath);

    // حذف فایل اصلی
    $sql->delete('sendedimages');
    @unlink($sourceFilePath);

    echo('ok');

    $sql->where("id", session('id'));
    $hide = $sql->getOne('onlines', array('id','hide'));
    if ($hide['hide'] == '1' and session("admin")==1){
    } elseif ($hide['hide'] == '0' and session("admin")!=0){
        chatroom::addlog('9', "فایل ارسالی در خصوصی را حذف کرد");
    } 
}
?>
