<?php
if(!session('id') or session('admin') == '0')exit();
adminpanel::check_Permission('exit', 'editusertypes');

if($options['typeload'] == '1'){echo('شما از نوع بارگذاری سنتی استفاده میکنید');return;}
$id = isset($_POST['id']) ? safe::number($_POST['id']) : '';
if($id == ''){echo('id');return false;}
$sql->where("id", $id);
if (!$type = $sql->getOne('usertypes')){echo('id');return false;}

$title = isset($_POST['title']) ? safe::string($_POST['title']) : $type['title'];


$admin = isset($_POST['admin']) ? safe::number($_POST['admin']) : $type['admin'];
//if($admin != '1' && $admin != '2' && $admin != '3')$admin = '0';


$score= isset($_POST['score']) ? safe::number($_POST['score']) : $type['score'];
if($score < 1)$score = 0;

$sort= isset($_POST['sort']) ? safe::number($_POST['sort']) : $type['sort'];

$color = isset($_POST['color']) ? safe::hexColor($_POST['color']) : $type['color'];



$msg = '';
$version = '?ver='.rand(1111,9999);
$isset_icon = true;
$isset_taj = true;
     $isicon=0;
if (isset($_FILES['icontype'])){
     $isicon=1;
    if($_FILES['icontype']['error'] == 0){
        $type = $_FILES['icontype']['type'];
        if($type != 'image/png' && $type != 'image/jpeg' && $type != 'image/gif'){
            $msg = 'type';
        }else{
            switch($type){
                case('image/png'):$format = 'png';break;
                case('image/jpeg'):$format = 'jpg';break;
                case('image/gif'):$format = 'gif';break;
            }
            $folder = 'theme/images/user-types';
            if(!is_dir($folder))mkdir($folder);
            
            $name = "$id.$format";
            $filename = "$folder/$name";
            if(move_uploaded_file($_FILES['icontype']['tmp_name'], $filename)){
                $img = file_get_contents($filename);
                if(strpos((string)$img, '<?php') !== false){
                    unlink($filename);
                    $msg = 'security';
                }else{
                    $icon = $filename.$version;
                }
            }else{
                $msg = 'noup';
            }
        }
    }else{
		$isset_icon = false;
	}
}
        $istaj=0;
if (isset($_FILES['imgtype'])){
        $istaj=1;
	if($_FILES['imgtype']['error'] == 0){
		$type = $_FILES['imgtype']['type'];
		if($type != 'image/png' && $type != 'image/jpeg' && $type != 'image/gif'){
			$msg = 'type';
		}else{
			switch($type){
				case('image/png'):$format = 'png';break;
				case('image/jpeg'):$format = 'jpg';break;
				case('image/gif'):$format = 'gif';break;
			}
			$folder = 'theme/images/user-types';
			if(!is_dir($folder))mkdir($folder);
			
			$name = "$id.taj.$format";
			$filename = "$folder/$name";
			if(move_uploaded_file($_FILES['imgtype']['tmp_name'], $filename)){
				$img = file_get_contents($filename);
				if(strpos((string)$img, '<?php') !== false){
					unlink($filename);
					$msg = 'security';
				}else{
					$taj = $filename.$version;
				}
			}else{
				$msg = 'noup';
			}
		}
	}else{
		$isset_taj = false;
	}
}

if(!isset($icon))$icon = $type['icon'];
if(!isset($taj))$taj = $type['taj'];


if($msg == ''){
   if($isicon==0 and $istaj==1) { 
    $sql->where("id", $id)->update('usertypes', array(
        'title' => $title,
        'admin' => $admin,
        'score' => $score,
        'taj' => $taj,
        'color' => $color,
        'sort' => $sort
    ));
   }
   if($isicon==1 and $istaj==0) {
    $sql->where("id", $id)->update('usertypes', array(
        'title' => $title,
        'admin' => $admin,
        'score' => $score,
        'icon' => $icon,
        'color' => $color,
        'sort' => $sort
    ));
   }
   if($isicon==1 and $istaj==1) {
    $sql->where("id", $id)->update('usertypes', array(
        'title' => $title,
        'admin' => $admin,
        'score' => $score,
        'icon' => $icon,
        'taj' => $taj,
        'color' => $color,
        'sort' => $sort
    ));
   }
   if($isicon==0 and $istaj==0) {
    $sql->where("id", $id)->update('usertypes', array(
        'title' => $title,
        'admin' => $admin,
        'score' => $score,
        'color' => $color,
        'sort' => $sort
    ));
   }

$sql->where("id", session('id'));
$hide = $sql->getOne('onlines', array('id','hide'));
if ($hide['hide'] == '1' and session("admin")==1){
}elseif ($hide['hide'] == '0' and session("admin")!=0){
chatroom::addlog('9', "سطح کاربری با عنوان($title)و قدرت مدیریتی($admin)را ویرایش کرد");
}
$tel = json_decode($options['teloptions'], true);
  $text = "سطوح کاربری با عنوان($title)و قدرت مدیریتی($admin)را ویرایش کرد \n";
    $text .='ای پی : '.ip()."\n";
    $text .= "آدرس : ".url()."\n";
    $text .= "نام نمایشی :".session('name')."\t";
    
       if($tel['sendusertypeslog'] == 'Sure'){
             tlg($text,'chat');
                 }
    echo('ok');
    chatroom::reloadconfig();
}else{
    echo($msg);
}
?>
