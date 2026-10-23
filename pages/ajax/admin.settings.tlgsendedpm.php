<?php

if(!session('id') or session('admin') == '0'){exit();}
$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if(!$token || $token != session('changepw'))die('لطفا صفحه خود را رفرش کنید');
$id = session('id');

if(isset($_POST['tlg_send_admins'])){
    $newvalue = safe::number($_POST['tlg_send_admins']);
    $sql->where("name", 'tlg_send_admins');
    $sql->update('options', array(
        'value' => $newvalue
    ));
}

$telegram = json_decode($options['teloptions'], true);

if (isset($_POST['sendloginlog'])) {
    $telegram['sendloginlog'] = 'Sure';
} else {
    $telegram['sendloginlog'] = 'noneed';
}


if (isset($_POST['sendthemechatlog'])) {
    $telegram['sendthemechatlog'] = 'Sure';
} else {
    $telegram['sendthemechatlog'] = 'noneed';
}
if (isset($_POST['sendmsgsenderlog'])) {
    $telegram['sendmsgsenderlog'] = 'Sure';
} else {
    $telegram['sendmsgsenderlog'] = 'noneed';
}

if (isset($_POST['sendipbanneraddlog'])) {
    $telegram['sendipbanneraddlog'] = 'Sure';
} else {
    $telegram['sendipbanneraddlog'] = 'noneed';
}

if (isset($_POST['sendLimitationslog'])) {
    $telegram['sendLimitationslog'] = 'Sure';
} else {
    $telegram['sendLimitationslog'] = 'noneed';
}

if (isset($_POST['sendsettinglog'])) {
    $telegram['sendsettinglog'] = 'Sure';
} else {
    $telegram['sendsettinglog'] = 'noneed';
}

if (isset($_POST['sendspecialactionslog'])) {
    $telegram['sendspecialactionslog'] = 'Sure';
} else {
    $telegram['sendspecialactionslog'] = 'noneed';
}

if (isset($_POST['sendroomlog'])) {
    $telegram['sendroomlog'] = 'Sure';
} else {
    $telegram['sendroomlog'] = 'noneed';
}

if (isset($_POST['sendadslog'])) {
    $telegram['sendadslog'] = 'Sure';
} else {
    $telegram['sendadslog'] = 'noneed';
}

if (isset($_POST['sendadsbuylog'])) {
    $telegram['sendadsbuylog'] = 'Sure';
} else {
    $telegram['sendadsbuylog'] = 'noneed';
}

if (isset($_POST['sendlinkdonilog'])) {
    $telegram['sendlinkdonilog'] = 'Sure';
} else {
    $telegram['sendlinkdonilog'] = 'noneed';
}

if (isset($_POST['senadeldminpanellog'])) {
    $telegram['senadeldminpanellog'] = 'Sure';
} else {
    $telegram['senadeldminpanellog'] = 'noneed';
}

if (isset($_POST['sendadminpaneladslog'])) {
    $telegram['sendadminpaneladslog'] = 'Sure';
} else {
    $telegram['sendadminpaneladslog'] = 'noneed';
}

if (isset($_POST['sendadminpaneleditlog'])) {
    $telegram['sendadminpaneleditlog'] = 'Sure';
} else {
    $telegram['sendadminpaneleditlog'] = 'noneed';
}

if (isset($_POST['sendusertypeslog'])) {
    $telegram['sendusertypeslog'] = 'Sure';
} else {
    $telegram['sendusertypeslog'] = 'noneed';
}






if (isset($_POST['senduserlog'])) {
    $telegram['senduserlog'] = 'Sure';
} else {
    $telegram['senduserlog'] = 'noneed';
}

if (isset($_POST['sendusertypeslog'])) {
    $telegram['sendusertypeslog'] = 'Sure';
} else {
    $telegram['sendusertypeslog'] = 'noneed';
}

if (isset($_POST['sendjavacodelog'])) {
    $telegram['sendjavacodelog'] = 'Sure';
} else {
    $telegram['sendjavacodelog'] = 'noneed';
}

if (isset($_POST['sendletterslog'])) {
    $telegram['sendletterslog'] = 'Sure';
} else {
    $telegram['sendletterslog'] = 'noneed';
}

if (isset($_POST['senddelletterslog'])) {
    $telegram['senddelletterslog'] = 'Sure';
} else {
    $telegram['senddelletterslog'] = 'noneed';
}

if (isset($_POST['senddelletterslog'])) {
    $telegram['senddelletterslog'] = 'Sure';
} else {
    $telegram['senddelletterslog'] = 'noneed';
}
$telegram = json_encode($telegram, JSON_UNESCAPED_UNICODE);
    $sql->where("name", 'teloptions');
    $sql->update('options', array(
        'value' => $telegram
    ));

	
chatroom::reloadconfig();

$sql->where("id", session('id'));
$hide = $sql->getOne('onlines', array('id','hide'));
if ($hide['hide'] == '1' and session("admin")==1){
}else if ($hide['hide'] == '0' and session("admin")!=0){
chatroom::addlog('9', "تنظیمات اطلاع رسانی تلگرام را تغییر داد");
}
  $tel = json_decode($options['teloptions'], true);
   $text = "تنظیمات اطلاع رسانی تلگرام را تغییر داد\n";
    $text .='ای پی : '.ip()."\n";
    $text .= "آدرس : ".url()."\n";
    $text .= "نام نمایشی :".session('name')."\t";
    
       if($tel['sendsettinglog'] == 'Sure'){
             tlg($text,'chat');
                 }
echo('ok');
?>
