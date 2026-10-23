<?php
check_session();
if(Users::guestType(session('type')))return;

include('inc/classes/profile.php');
Profile::CheckActiveMe();

$id = session('id');
if ($_FILES['CoverName']){
	$file = basename($_FILES['CoverName']['name']);
    $ext = substr($file, strrpos($file, '.') + 1);
	$allowedExts = array("jpg", "gif",'jpeg','png');
	if(in_array(strtolower($ext), $allowedExts)){
		switch($_FILES['CoverName']['type']){
			case('image/png'):$format = 'png';break;
			case('image/jpeg'):$format = 'jpg';break;
			case('image/gif'):$format = 'gif';break;
			default:echo('type');return;break;
		}
	}else{exit('type');}
    $folder = "theme/images/covers/";
	if(!is_dir($folder))mkdir($folder);
    $name = $id."_".time().".$format";
    $filename = "$folder/$name";
    if(move_uploaded_file($_FILES['CoverName']['tmp_name'], $filename)){
        $file = file_get_contents($filename);
        if(strpos((string)$file, '<?php') !== false){
            unlink($filename);
            echo('security');
        }else{
            $sql->where("id", $id);
            $lastcover = $sql->getOne('profiles', array('cover'));

            if($lastcover != 'cover.jpg' && is_file("$folder/$lastcover"))unlink("$folder/$lastcover");
	$sql->where("id", $id)->update('profiles', array(
		'cover' => $name,
		'ccover' => '',
		'lducover' => ''
	));
         

			
			//session('myavatar', $name);
			Profile::Send2Friends('کاور پروفايل خود را عوض کرد');
                         
            echo('ok|'.url($filename).'?'.rand(0, 10));
        }
    }else{
		echo('error');
	}
}

?>