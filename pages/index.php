<?php

$chat = $options['chat_prefix'];
if(session('id')){header::Go(url($chat));exit();}
setcookie('testcookie', '1');
if(session("token2")==""): token2(); endif;
$sql->where("expiration",0, '!=');
$sql->where("expiration", time(), '<');
$sql->delete('ads');
// نسخه موبایل تلگرامی (theme/phone) — حالت auto: انتقال خودکار اگر گزینه روی auto است
if (isset($options['mobilesite_mode']) && $options['mobilesite_mode'] == 'auto'
    && is_mobile_detect() && session('myOS') != 'pc' && is_file('pages/phone.php')) {
    header::Go(url('phone'));
    exit();
}
/* old mobile:
if (is_mobile_detect() && $options['mobiledetect'] == 'true' && is_file(theme::file('index.php', 'm')) && session('myOS') != 'pc') {
    $theme = 'm';
    include(theme::file('index.php', 'm'));
} else*/
if (is_file(theme::file('index.php'))) {
    include(theme::file('index.php'));
} else {
    include(theme::file('login.php'));
}
?>