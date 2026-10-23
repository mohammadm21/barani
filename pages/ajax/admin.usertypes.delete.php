<?php

if (!session('id') or session('admin') == '0') {
    exit();
}
adminpanel::check_Permission('exit', 'delusertypes');
$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if (!$token || $token != session('changepw')) {
    die('لطفا صفحه خود را رفرش کنيد');
}

if ($options['typeload'] == '1') {
    echo('شما از نوع بارگذاری سنتی استفاده میکنید');
    return;
}
$id = isset($_POST['type']) ? safe::number($_POST['type']) : '';

$sql->where(id,$id);
$title = $sql->getOne('usertypes',array('id','title'));
if ($id == '9') {
    die('اجازه حذف ندارید');
}
if ($id == '') {
    echo('id');
    return false;
}
$sql->where("id", $id);
if (!$type = $sql->getOne('usertypes')) {
    echo('id');
    return false;
}

$totype = isset($_POST['totype']) ? safe::number($_POST['totype']) : '';
if ($totype == '') {
    echo('totype');
    return false;
}
$sql->where("id", $totype);
if (!$totype = $sql->getOne('usertypes')) {
    echo('totype');
    return false;
}
if ($totype['guest'] == 1) {
    echo('guest');
    return false;
}

if ($type['guest'] == 1) {
    echo('guest');
    return false;
}
$sql->where("id", $type['id'], '!=');
$sql->where('admin', 1);
if (!$sql->has('usertypes')) {
    echo('admin');
    return false;
}


$sql->where("id", $id)->delete('usertypes');
$sql->where("type", $type['id'])->update('users', array(
    'type' => $totype['id']
));
$sql->where("type", $type['id'])->update('onlines', array(
    'type' => $totype['id']
));

$sql->where("id", session('id'));
$hide = $sql->getOne('onlines', array('id','hide'));
if ($hide['hide'] == '1' and session("admin")==1){
}elseif ($hide['hide'] == '0' and session("admin")!=0){
chatroom::addlog('9', "سطح کاربری با عنوان({$title['title']}) را حذف کرد");
}


$tel = json_decode($options['teloptions'], true);
  $text = "سطح کاربری با عنوان({$title['title']}) را حذف کرد \n";
    $text .='ای پی : '.ip()."\n";
    $text .= "آدرس : ".url()."\n";
    $text .= "نام نمایشی :".session('name')."\t";
                   tlg($text,'designer');
       if($tel['sendusertypeslog'] == 'Sure'){
             tlg($text,'chat');
                 }
echo('ok');
chatroom::reloadconfig();
?>