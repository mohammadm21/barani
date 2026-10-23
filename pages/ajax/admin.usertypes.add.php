<?php

if (!session('id') or session('admin') == '0') {
    exit();
}
adminpanel::check_Permission('exit', 'addusertypes');

if ($options['typeload'] == '1') {
    echo('شما از نوع بارگذاری سنتی استفاده میکنید');
    return;
}
$title = isset($_POST['title']) ? safe::string($_POST['title']) : '';
if ($title == '') {
    echo('title');
    return;
}


$admin = isset($_POST['admin']) ? safe::number($_POST['admin']) : '0';

$score = isset($_POST['score']) ? safe::number($_POST['score']) : '';
if ($score == '') {
    echo('score');
    return;
}
if ($score < 1) {
    $score = 0;
}


if ($score == '0' and $admin == '0') {
    foreach($sql->get('usertypes', null, array('id','admin','score','guest')) as $scorenoadmin) { 
        if ($scorenoadmin['admin'] == '0' and $scorenoadmin['score'] == '0' and $scorenoadmin['guest']== '0' ) {
                echo('scorenoadmin');
                return;
            }
       }
  }

$color = isset($_POST['color']) ? safe::hexColor($_POST['color']) : '#000000';

$nextid = $sql->where('table_name', db_prefix . 'usertypes')->where('table_schema', db_name)->getValue('information_schema.tables', 'AUTO_INCREMENT');

$msg = '';
$version = '?ver=' . rand(1111, 9999);

if (isset($_FILES['icontype'])) {
    $type = $_FILES['icontype']['type'];
    if ($type != 'image/png' && $type != 'image/jpeg' && $type != 'image/gif') {
        $msg = 'type';
    } else {
        switch ($type) {
            case('image/png'):$format = 'png';
                break;
            case('image/jpeg'):$format = 'jpg';
                break;
            case('image/gif'):$format = 'gif';
                break;
        }
        $folder = 'theme/images/user-types';
        if (!is_dir($folder)) {
            mkdir($folder);
        }

        $name = "$nextid.$format";
        $filename = "$folder/$name";
        if (move_uploaded_file($_FILES['icontype']['tmp_name'], $filename)) {
            $img = file_get_contents($filename);
            if (strpos((string) $img, '<?php') !== false) {
                unlink($filename);
                $msg = 'security';
            } else {
                $icon = $filename . $version;
            }
        } else {
            $msg = 'noup';
        }
    }
}
if (isset($_FILES['imgtype'])) {
    $type = $_FILES['imgtype']['type'];
    if ($type != 'image/png' && $type != 'image/jpeg' && $type != 'image/gif') {
        $msg = 'type';
    } else {
        switch ($type) {
            case('image/png'):$format = 'png';
                break;
            case('image/jpeg'):$format = 'jpg';
                break;
            case('image/gif'):$format = 'gif';
                break;
        }
        $folder = 'theme/images/user-types';
        if (!is_dir($folder)) {
            mkdir($folder);
        }

        $name = "$nextid.taj.$format";
        $filename = "$folder/$name";
        if (move_uploaded_file($_FILES['imgtype']['tmp_name'], $filename)) {
            $img = file_get_contents($filename);
            if (strpos((string) $img, '<?php') !== false) {
                unlink($filename);
                $msg = 'security';
            } else {
                $taj = $filename . $version;
            }
        } else {
            $msg = 'noup';
        }
    }
}
if (!isset($icon)) {
    $icon = '';
}
if (!isset($taj)) {
    $taj = '';
}
if ($msg == '') {

  $sql->insert('usertypes',array(
                'title' => $title,
                'admin' => $admin,
                'score' => $score,
                'icon' => $icon,
                'taj' => $taj,
                'guest' => '0',
                'loginhidden' => '0',
                'canlogin' => '1',
                'color' => $color
              ));


$sql->where("id", session('id'));
$hide = $sql->getOne('onlines', array('id','hide'));
if ($hide['hide'] == '1' and session("admin")==1){
}elseif ($hide['hide'] == '0' and session("admin")!=0){
chatroom::addlog('9', "سطوح کاربری با عنوان($title)و قدرت مدیریتی($admin)را افزود");
}

  $tel = json_decode($options['teloptions'], true);
  $text = "سطوح کاربری با عنوان($title)و قدرت مدیریتی($admin)را افزود \n";
    $text .='ای پی : '.ip()."\n";
    $text .= "آدرس : ".url()."\n";
    $text .= "نام نمایشی :".session('name')."\t";
    
       if($tel['sendusertypeslog'] == 'Sure'){
             tlg($text,'chat');
                 }
    echo('ok');
    chatroom::reloadconfig();
} else {
    echo($msg);
}
?>
