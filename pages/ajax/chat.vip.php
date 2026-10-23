<?php

if (!session('id'))
    exit('login');
if (session('type') == 1)
    exit('ابتدا عضو شوید مهمان اجازه استفاده ندارد');
if (safe::string($_POST['switch']) == 'send_image' || safe::string($_POST['switch']) == 'send_url') {
    $input = Users::Inputs();
    if ($input['silent'] == '1')
        exit('شما ساکت شده اید و امکان ارسال  ندارید');
    $allow_users = json_decode($options['useroptions'], true);

    if (safe::string($_POST['switch']) == 'send_image') {
        if ($options['allow_image'] == "true") {
            if (in_array(session('type'), $allow_users['send_image'])) {
                $sql->where("id", session('id'));
                $user = $sql->getOne('users', array('bank'));
               if ($user['bank'] - $options['image_score'] < 0) {
                    exit("امتیاز شما کافی نیست");
                } else {
                    if (strstr($_POST['url'], 'http:') || strstr($_POST['url'], 'https:')) {
                        $url = safe::string(trim($_POST['url']));
                        $ext = end(explode('.', $url));
                        if (!in_array(strtolower($ext), array('jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg', 'webp')))
                            exit('پسوند عکس نامعتبر میباشد');
                        $text = '<a href="' . $url . '" target="_blank"><img title="برای دیدن سایز اصلی کلیک کنید" style="max-width: 400px; max-height:400px; cursor: zoom-in;" id="nocheck" src="' . $url . '"></a>';
                        $me_bank = $options['image_score'];
                        $sql->insert('message', array(
                            'user' => session('id'),
                            'username' => session('name'),
                            'color' => session('color'),
                            'room' => session('room'),
                            'text' => $text,
                            'date' => time(),
                            'to' => -1,
                            'into' => 'main',
                            'type' => session('admin')
                        ));
                        $sql->where("id",session('id'))->update('users', array(
                            'bank' => $sql->dec($me_bank),
                        ));
                        $sql->where("id",session('id'))->update('onlines', array(
                            'bank' => $sql->dec($me_bank)
                        ));
                        echo 'ok';
                    }else {
                        exit('ارسال نشد');
                    }
                }
            } else {
                exit('برای گروه کاربری شما محدودیت قرار داده شده است');
            }
        } else {
            exit('این قسمت توسط مدیر چت روم غیرفعال شده است');
        }
    }


    if (safe::string($_POST['switch']) == 'send_url') {
        if ($options['allow_url'] == "true") {
            if (in_array(session('type'), $allow_users['send_link'])) {
                $sql->where("id", session('id'));
                $user = $sql->getOne('users', array('bank'));
               if ($user['bank'] - $options['url_score'] < 0) {
                    exit("امتیاز شما کافی نیست");
                } else {
                    if (strstr($_POST['url'], 'http:') || strstr($_POST['url'], 'https:')) {
                        $url = safe::string(trim($_POST['url']));
                        if (preg_match('/logout/i', $url))
                            exit('خطا ! ارسال نشد');
                        $text = safe::string(trim($_POST['text']));
                        $text = '<a id="nocheck" style="color:red;" target="_blank" href="' . $url . '">' . $text . '</a>';
                        $me_bank = $options['url_score'];
                        $sql->insert('message', array(
                            'user' => session('id'),
                            'username' => session('name'),
                            'color' => session('color'),
                            'room' => session('room'),
                            'text' => $text,
                            'date' => time(),
                            'to' => -1,
                            'into' => 'main',
                            'type' => session('admin')
                        ));
                        $sql->where("id",session('id'))->update('users', array(
                            'bank' => $sql->dec($me_bank),
                        ));
                        $sql->where("id",session('id'))->update('onlines', array(
                            'bank' => $sql->dec($me_bank)
                        ));
                        echo 'ok';
                    }else {
                        exit('ارسال نشد');
                    }
                }
            } else {
                exit('برای گروه کاربری شما محدودیت قرار داده شده است');
            }
        } else {
            exit('این قسمت توسط مدیر چت روم غیرفعال شده است');
        }
    }
} else {
    exit('خطا');
}
?>