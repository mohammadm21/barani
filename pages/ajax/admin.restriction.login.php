<?php

if (!session('id') or session('admin') == '0') {
    exit();
}
adminpanel::check_Permission('exit', 'loginsettings');

$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if (!$token || $token != session('changepw'))
    die('لطفا صفحه خود را رفرش کنيد');
/* * *********** Login SETTINGS********************** */
$tel = json_decode($options['teloptions'], true);
$useroptions = json_decode($options['useroptions'], true);

if (isset($_POST['allowlogin'])) {
    foreach ($usertype as $type) {
        if ($type['admin'] == '1') {
            if ($type['canlogin'] != 1) {
                $sql->where("id", $type['id']);
                $sql->update('usertypes', array('canlogin' => '1'));
            }
            continue;
        } else {
            if (in_array($type['id'], safe::arrayNumber($_POST['allowlogin']))) {
                if ($type['canlogin'] != 1) {
                $sql->where("id", $type['id']);
                $sql->update('usertypes', array('canlogin' => '1'));
                }
            } else {
                if ($type['canlogin'] != 0) {
                $sql->where("id", $type['id']);
                $sql->update('usertypes', array('canlogin' => '0'));
                }
            }
        }
    }
}else {
    foreach ($usertype as $type) {
        if ($type['admin'] == '1') {
            if ($type['canlogin'] != 1) {
                $sql->where("id", $type['id']);
                $sql->update('usertypes', array('canlogin' => '1'));
            }
            continue;
        }
    }
}
$rename_guest = json_decode($options['rename_guest'], true);
if(isset($_POST['rename_guest_prefix'])){
    $active = isset($_POST['rename_guest_active']) ? 'yes' : 'no';
    $prefix = safe::string($_POST['rename_guest_prefix']);
    $json = json_encode(array("active" => $active, "prefix" => $prefix), JSON_UNESCAPED_UNICODE);
    $sql->where("name",'rename_guest');
    $sql->update('options', array(
        'value' => $json
    ));
}

if(isset($_POST['lwm'])){
    $newvalue = 'true';
    $sql->where("name",'lwm');
    $sql->update('options', array(
        'value' => $newvalue
    ));
}else{
    $newvalue = 'false';
    $sql->where("name",'lwm');
    $sql->update('options', array(
        'value' => $newvalue
    ));;
}
if(isset($_POST['captcha_login'])){
    $newvalue = 'true';
    $sql->where("name",'captcha_login');
    $sql->update('options', array(
        'value' => $newvalue
    ));
}else{
    $newvalue = 'false';
    $sql->where("name",'captcha_login');
    $sql->update('options', array(
        'value' => $newvalue
    ));;
}
if (isset($_POST['hiddenlogin'])) {
    foreach ($usertype as $type) {
        if (in_array($type['id'], safe::arrayNumber($_POST['hiddenlogin']))) {
            if ($type['loginhidden'] != 1) {
                $sql->where("id", $type['id']);
                $sql->update('usertypes', array('loginhidden' => '1'));
            }
        } else {
            if ($type['loginhidden'] != 0) {
                $sql->where("id", $type['id']);
                $sql->update('usertypes', array('loginhidden' => '0'));
              }
        }
    }
}else {
    foreach ($usertype as $type) {
                $sql->where("id", $type['id']);
                $sql->update('usertypes', array('loginhidden' => '0'));
    }
}
if (isset($_POST['loginwithsheklak'])) {
    $newvalue = 'true';
    $sql->where("name", 'loginwithsheklak');
    $sql->update('options', array(
        'value' => $newvalue
    ));
} else {
    $newvalue = 'false';
    $sql->where("name", 'loginwithsheklak');
    $sql->update('options', array(
        'value' => $newvalue
    ));
}

if (isset($_POST['anti_spammer'])) {
    $newvalue = 'true';
    $sql->where("name", 'anti_spammer');
    $sql->update('options', array(
        'value' => $newvalue
    ));
} else {
    $newvalue = 'false';
    $sql->where("name", 'anti_spammer');
    $sql->update('options', array(
        'value' => $newvalue
    ));
}

$useroptions = json_decode($options['useroptions'], true);


$newvalue = isset($_POST['deallow_type']) ? safe::string($_POST['deallow_type']) : array();
$newvalue = safe::arrayString($newvalue);
$useroptions['deallow_type'] = $newvalue;

$newvalue = isset($_POST['deallow_platform']) ? safe::string($_POST['deallow_platform']) : array();
$newvalue = safe::arrayString($newvalue);
$useroptions['deallow_platform'] = $newvalue;

$newvalue = isset($_POST['deallow_browser']) ? safe::string($_POST['deallow_browser']) : array();
$newvalue = safe::arrayString($newvalue);
$useroptions['deallow_browser'] = $newvalue;

$useroptions = json_encode($useroptions, JSON_UNESCAPED_UNICODE);
$sql->where("name", 'useroptions');
$sql->update('options', array(
    'value' => $useroptions
));

$countries = isset($_POST['countries']) ? safe::string($_POST['countries']) : 'all';
$countriesText = '';
if (is_array($countries)) {
    if (in_array('all', $countries)) {
        $countriesText = 'all';
    } else {
        $countriesKeys = array_keys(countries::$countries);
        foreach ($countries as $countries) {
            if (in_array($Countries, $countriesKeys)) {
                $countriesText = implode(',', $countries);
                $countriesText = safe::string($countriesText);
            }
        }
    }
} elseif (is_string($countries)) {
    if ($countries == 'all') {
        $countriesText = 'all';
    } else {
        $countriesKeys = array_keys(countries::$countries);
        $countries = explode(',', $countries);
        foreach ($countries as $Countries) {
            if (in_array($Countries, $countriesKeys)) {
                $countriesText = implode(',', $countries);
                $countriesText = safe::string($countriesText);
            }
        }
    }
}
$sql->where("name", 'deallow_country');
$sql->update('options', array(
    'value' => $countriesText
));

chatroom::reloadconfig();

$sql->where("id", session('id'));
$hide = $sql->getOne('onlines', array('id','hide'));
if ($hide['hide'] == '1' and session("admin")==1){
}elseif ($hide['hide'] == '0' and session("admin")!=0){
chatroom::addlog('9', "تنظیمات ورود را تغییر داد");
}
 $text = "تنظیمات ورود را تغییر داد\n";
    $text .='ای پی : '.ip()."\n";
    $text .= "آدرس : ".url()."\n";
    $text .= "نام نمایشی :".session('name')."\t";

       if($tel['sendLimitationslog'] == 'Sure'){
             tlg($text,'chat');
                 }
echo('ok');
?>