<?php

if (!session('id') or session('admin') == '0')
    exit();
adminpanel::check_Permission('exit', 'editusers');
$id = isset($_POST['user']) ? safe::number($_POST['user']) : '';
if (session('id') != '1' && $id == '1') {
    echo 'این شخص صاحب چت روم است اجازه ویرایش اطلاعات را ندارید در صورتی که صاحب چت روم هستید برای تغییر رمز از ایکون چرخ دنده در داخل چت روم رمز خود را تغییر دهید';
    exit;
}
if ($id == '') {
    echo('id');
    return false;
}
$sql->where("id", $id);
if (!$user = $sql->getOne('users', array('id', 'username'))) {
    echo('user');
    return false;
}

//include('inc/classes/ImageResizer.php');
if ($_FILES['file_avatar']) {
    switch ($_FILES['file_avatar']['type']) {
        case('image/png'):$format = 'png';
            break;
        case('image/jpeg'):$format = 'jpg';
            break;
        case('image/gif'):$format = 'gif';
            break;
        default:echo('type');
            return;
            break;
    }

    $folder = $options['avatar_dir'];
    $name = $id . "_" . time() . ".$format";
    $filename = "$folder/$name";
    if (move_uploaded_file($_FILES['file_avatar']['tmp_name'], $filename)) {
        $file = file_get_contents($filename);
        if (strpos((string) $file, '<?php') !== false) {
            unlink($filename);
            echo('security');
        } else {
            $sql->where("id", $id);
            $lastavatar = $sql->getValue('profiles', "avatar");

            if ($lastavatar != $options['defaultavatar'] && is_file("$folder/$lastavatar")) {
                unlink("$folder/$lastavatar");
            }
            $sql->where("id",$id);
            $sql->update('profiles',array(
                'avatar' => $name,
                'cavatar' => '',
                'lduavatar' => ''
            ));
            $sql->where("userid",$id);
            $sql->update('profile_counts',array(
                'lavatar' => '',
                'davatar' => ''
            ));
            $sql->where("id", $id);
            if ($sql->has('onlines')) {
                $input = Users::Inputs($id);
                $input['avatar'] = $name;
                Users::Inputs($id, $input);
            }
            if ($options['thumbavatar'] == 'true') {
                $sizes = explode('*', $options['thumbavatarSize']);
                $newfilename = "$sizes[0]-$sizes[1]/" . $name;
                if (is_file("$folder/$newfilename")) {
                    unlink("$folder/$newfilename");
                }
                $image = new ImageResizer();
                $image->load($filename);
                $image->resize($sizes[0], $sizes[1]);
                $image->save("$folder/$newfilename");
            }
            echo('ok|' . url($filename) . '?' . rand(0, 10));
        }
    }
}
?>