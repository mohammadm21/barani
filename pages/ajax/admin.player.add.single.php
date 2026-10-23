<?php
if (!session('id') or session('admin') == '0') {
    exit();
}
adminpanel::check_Permission('exit', 'addplayer');
$error = "";
if (isset($_FILES['filemusic'])) {
    $type = $_FILES['filemusic']['type'];
    $file = basename($_FILES['filemusic']['name']);
    $ext = substr($file, strrpos($file, '.') + 1);
    $allowedExts = array("mp3", "wmv");
    if (!in_array($ext, $allowedExts)) {
        exit('type');
    } else {
        //include('inc/classes/file.php');
        $format = file::get_ext($_FILES['filemusic']['name']);
        $folder = 'theme/music';
	$nextid = $sql->where('table_schema', db_name)->where('table_name', db_prefix . 'player')->getValue('information_schema.tables', 'AUTO_INCREMENT');
        $name = "music$nextid.$format";
        $filename = "$folder/$name";
        if (move_uploaded_file($_FILES['filemusic']['tmp_name'], $filename)) {
            $img = file_get_contents($filename);
            if (strpos((string) $img, '<?php') !== false) {
                unlink($filename);
                echo('security');
                return;
            } else {
                $priority = isset($_POST['priority']) ? safe::number($_REQUEST['priority']) : 1;
                $title = isset($_POST['name']) ? safe::string($_POST['name']) : $_FILES['filemusic']['name'];
                chatroom::addmusic($title, $filename, $nextid, $priority);

$sql->where("id", session('id'));
$hide = $sql->getOne('onlines', array('id','hide'));
if ($hide['hide'] == '1' and session("admin")==1){
}elseif ($hide['hide'] == '0' and session("admin")!=0){
chatroom::addlog('9', "آهنگی اضافه کرد:$title");
}
               
                echo('ok');
                return;
            }
        } else {
            $error = 'noup';
        }
    }
} else {
    $error = 'error...';
}
echo"{\n\terror: '$error'\n}";
?>