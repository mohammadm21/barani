<?php

check_session();
if (!session('id')) {
    exit('login');
}
if (session('type') == 1) {
    exit('ابتدا عضو شوید مهمان اجازه استفاده ندارد');
}
$allow_users = json_decode($options['useroptions'], true);
if (in_array(session('type'), $allow_users['allow_removeimages'])) {
    $sql->where("sender_id", session('id'));
    foreach($sql->get('sendedimages', null, array('filename')) as $link){
        //@unlink('theme/sendedimages/' . $link['filename']);
    }
    $sql->where("sender_id", session('id'))->delete('sendedimages');
    echo('تمام فایل های ارسالی شما درخصوصی از روی چت روم حذف شدند');
} else {
    exit('این قسمت توسط مدیر چت روم غیرفعال شده است');
}
?>