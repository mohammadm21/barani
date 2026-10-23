<?php
if (!session('id') or session('admin') == '0') {
    exit();
}
adminpanel::check_Permission('exit', 'msgreader');

if (!adminpanel::check_Permission('bool', 'delofflinemessage'))
    die('شما دسترسی لازم برای حذف ندارید');

$id = isset($_POST['id']) ? safe::number($_POST['id']) : die('id');
$text = isset($_POST['text']) ? safe::string($_POST['text']) : die('text');

$sql->where("id", $id);
$get = $sql->getOne('offline_message', array('text'));
$explode = explode(',', $get['text']);
$txt = array();
if (count($explode) == 1) {
    $sql->where("id", $id);
    $sql->delete('offline_message');
    echo 'ok';
    exit;
}
foreach ($explode as $texts) {
    if ($texts == $text)
        continue;
    $txt[] = $texts;
}
$implode = implode(',', $txt);
$sql->where("id", $id);
$sql->update('offline_message', array(
    'text' => $implode
));
echo 'ok';
?>