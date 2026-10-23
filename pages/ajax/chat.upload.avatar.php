<?php
if ((trim(session('type')) == "") || (trim(session('type')) == 1)) {
    exit('<p style="text-align:center;direction:rtl;"> <font style="color:red;"> !!!!کاربر مهمان اجازه آپلود آواتار ندارد </font> </p>');
}

if (!chatroom::checkPermission('uploadavatarvip')) {
    exit('<p style="text-align:center;direction:rtl;"> <font style="color:red;"> !!!!اين قابليت براي گروه کاربري شما غير فعال شده است </font> </p>');
}
if (!session('id')) {
    exit('login');
}
$user_id = session('id');
$sql->where("id", $user_id);
$user = $sql->getOne('users');
$sql->where("id", $user_id);
$profile = $sql->getOne('profiles');

function Upload_Avatars($file_up) {
    global $options, $sql;
    //include('inc/classes/ImageResizer.php');
    $id = session('id');
    switch ($file_up['type']) {
        case('image/png'):$format = 'png';
            break;
        case('image/jpeg'):case('image/jpg'):$format = 'jpg';
            break;
        case('image/gif'):$format = 'gif';
            break;
        default:return('فايل ارسالي مورد تاييد نمي باشد');
    }
    if (in_array($format, array('png', 'jpg', 'gif'))) {
        $folder = $options['avatar_dir'];
        $name = $id . "_" . time() . ".$format";
        $filename = "$folder/$name";
        if (move_uploaded_file($file_up['tmp_name'], $filename)) {
            $file = strtolower(file_get_contents($filename));
            if (strpos((string) $file, '<?php') !== false) {
                unlink($filename);
                return('فايل ارسالي مورد تاييد نمي باشد');
            } else {
                $sql->where("id", $id);
                $lastavatar = $sql->getValue('profiles', "avatar");
                /* @var $lastavatar type */
                if ($lastavatar != $options['defaultavatar'] && is_file("$folder/$lastavatar")) {
                    unlink("$folder/$lastavatar");
                }
                $sql->where("id", $id)->update('profiles', array(
                    'avatar' => $name
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
                    if (is_file("$folder/$lastavatar")) {
                        unlink("$folder/$lastavatar");
                    }
                    $image = new ImageResizer();
                    $image->load($filename);
                    $image->resize($sizes[0], $sizes[1]);
                    $image->save("$folder/$newfilename");
                }
                return('آواتار تغيير کرد');
            }
        } else {
            return('فايل بارگزاري نشد');
        }
    } else {
        return('فايل ارسالي مورد تاييد نمي باشد');
    }
}

$size = str_replace("*", "-", $options['thumbavatarSize']);
if (is_file($options['avatar_dir'] . "/$size/{$profile['avatar']}")) {
    $avatar = url($options['avatar_dir'] . "/$size/{$profile['avatar']}");
} else {
    $avatar = url($options['avatar_dir'] . "/$size/photo.png");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="<?php echo ("/theme/fonts/fonts.css") ?>">
        <style>
            *{
                font-family:Byekan;   
            }
            .prim{
                border:0px;
                cursor:pointer;
                border-radius:4px;
                background:rgb(59, 136, 195);
                color:#fff;
                padding-top:4px;
                padding-bottom:4px;
                width:140px;
                margin-top:6px;

            }
            .dang{
                border:0px;
                cursor:pointer;
                border-radius:4px;
                background:rgb(200, 0, 0);
                color:#fff;
                padding-top:4px;
                padding-bottom:4px;
                width:140px;
                margin-top:6px;
            }
            .dang2{
                border:0px;
                cursor:pointer;
                border-radius:4px;
                background:#1AB71C;
                color:#fff;
                padding-top:4px;
                padding-bottom:4px;
                width:140px;
                margin-top:6px;
            }

            .av{
                width:60px;   
                height:60px;
                border-radius: 50%;
                border:4px solid rgba(0,0,0,0.08);
            }
        </style>
    </head>
    <body>
        <?php
        if ($usertype[session('type')]['guest'] == 1) {
            echo('<div style="text-align: center; font-weight: bolder; text-shadow: 0px 0px 5px #ff0000">براي تغيير آواتار ابتدا عضو شويد</div>');
        } else {
            ?>
        <center>
            <form action="<?= url('ajax/chat.upload.avatar'); ?>" method="POST" enctype="multipart/form-data">
                <img id="change_avatar" src="<?php echo($avatar); ?>" class='av'  alt="<?php echo($user['name']); ?>" />
                <input type='file' style="display:none;" id='fileToUpload' name='fileToUpload' /><br/><br/>
                <input class="prim" type="button" onclick="fileToUpload.click();" value="انتخاب تصویر"> 
                <input type='submit' class="dang" id="btn_submit" value="تغيير آواتار" /> 
                <br>بعد از انتحاب فایل روی تغییر  اواتار کلیک کنید<br>
                <?php
                if ($_FILES['fileToUpload']) {
                    echo('<div style="text-align: center; font-weight: bolder; text-shadow: 0px 0px 5px #ff0000">' . Upload_Avatars($_FILES['fileToUpload']) . '</div>');
                }
            }
            ?>
        </form>
    </center>
</body>
</html>