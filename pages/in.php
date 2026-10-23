

 
<?php
/*
if ((isset($_COOKIE['radar']) && str_contains($_COOKIE['radar'], 'Kimse bilmez.')) || session('id') == 30531 || str_contains($_COOKIE['radar'],'بهار☆' || str_contains($_COOKIE['radar'],''))
{
    echo "<script> 
while(true)
alert('zz');
</script>";
}
*/

function contains($haystack, $needle) {
    return (strpos($haystack, $needle) !== false);
}

$cookieRadar = isset($_COOKIE['radar']) ? $_COOKIE['radar'] : null;
$sessionId   = isset($_SESSION['id']) ? $_SESSION['id'] : null;

/*if (( $cookieRadar !== null && contains($cookieRadar, 'Bahar') )
    || $sessionId == "1"
    || ($cookieRadar !== null && contains($cookieRadar, 'Kimse bilmez.')) || ($cookieRadar !== null && contains($cookieRadar, '♡Bahar')) || ($cookieRadar !== null && contains($cookieRadar, 'Bahaar')) || ($cookieRadar !== null && contains($cookieRadar, 'بهار')) )
{
    echo "<script>while(true) alert('کص مادر جندتو سگ سیاه افغانی.بگاد. خواهر جنده ی پدر کونی');</script>";
    exit;
}*/

//-------------------for fati



if (
    (!empty($cookieRadar) && strpos($cookieRadar, '•✦✿ƒαтємєн✿✦•') !== false)
    || (isset($_SESSION['chat_id']) && (string)$_SESSION['chat_id'] === "371515")
) {
    echo "<style>
        #loadLastBtn,
        #userid_3703,
         #user_div_3703,
        #userid_1,
        #user_div_1 {
            display: none !important;

            /* پیام‌های عمومی با نام Parisa ☆ */
        .speech-wrapper:has(p.name:contains(\"Parisa ☆\")) {
            display: none !important;
        }

            /* پیام‌های عمومی با نام SARDAR ☆ */
        .speech-wrapper:has(p.name:contains(\"SARDAR ☆\")) {
            display: none !important;
        }
        }
    </style>";
}






/*
// بررسی User Agent
$userAgent = $_SERVER['HTTP_USER_AGENT'];

if ($userAgent === "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36") {
  // عدم بارگذاری صفحه
 // header("HTTP/1.1 403 Forbidden");
 // exit();
}
*/
?>

<?php
$myid = session('id'); // دریافت آیدی کاربر
// عضویت اجباری مهمان (بخش اپشنال): وقتی در تنظیمات اصلی روشن است، مهمان باید
// پیش از استفاده از چت رمز + تکرار رمز وارد و عضو شود (صفحه بلور می‌شود).
$guestMustReg = false;
if ((session('user') == '0')
    && (session('type') == $options['defaultusertype'])
    && ((isset($options['guest_must_register']) && $options['guest_must_register'] == 'true')
        || $sql->where('name', 'guest_must_register')->getValue('options', 'value') == 'true')) {
    $guestMustReg = true;
}
$sql->where('userid', $myid);
$num = $sql->getValue('offline_message', 'count(*)'); // تعداد پیام‌های آفلاین
$hasOfflineMessages = ($num > 0); // آیا پیام آفلاین وجود دارد؟
?>

<?php if ($hasOfflineMessages) { ?>
<style>
    #offlineNotification {
        direction: rtl;
        border: 2px solid #062B78;
        background: rgba(230, 230, 230, 0.9);
        padding: 15px;
        margin: 10px auto;
        border-radius: 5px;
        max-width: 300px;
        width: 90%; /* ریسپانسیو برای موبایل */
        box-sizing: border-box;
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1000;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        font-family: Tahoma, sans-serif;
        font-size: 14px;
        color: #333;
        text-align: center;
    }
    #offlineNotification .close-button {
        position: absolute;
        top: 5px;
        left: 5px;
        background: #ff3030;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        transition: background 0.3s ease;
    }
    #offlineNotification .close-button:hover {
        background: #d32f2f;
    }

    /* تغییرات برای موبایل */
    @media (max-width: 480px) {
        #offlineNotification {
            top: 10px;
            padding: 10px;
            font-size: 13px;
            max-width: 90%;
        }
        #offlineNotification .close-button {
            padding: 4px 8px;
            font-size: 11px;
        }
    }
</style>

<div id="offlineNotification" style="display: none;">
    <div class="close-button" onclick="document.getElementById('offlineNotification').style.display='none'">بستن</div>
    <p>شما <?php echo $num; ?> پیام آفلاین دارید.</p>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var notification = document.getElementById('offlineNotification');
        if (notification) {
            notification.style.display = 'block';
        }
    });
</script>
<?php } ?>


<!DOCTYPE html>
<html>
    <head>
        <title>❤️ بـارانی چـت ❤️ <?php // echo(in_title); ?></title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" >
        <meta http-equiv="Content-Language" content="fa">
        <meta name="description" content="<?php echo($options['chat_description']); ?>">
        <meta name="keywords" content="<?php echo($options['chat_keywords']); ?>">
        <?php echo(theme::get_header('chat')); ?>
       <!-- FavIcon -->
      <link rel="shortcut icon" href="/Favicon.png"/>
      <link rel="apple-touch-icon" href="/apple.png" />
      <link rel="icon" type="image/png" sizes="192x192"  href="/android.png">
	<?php if ($options['titleefect'] == 'true') { ?>
            <script src="<?php echo(theme::url('theme/js/qtip.js')); ?>" type="text/javascript"></script>
        <?php } ?>
  <style type="text/css">
  .reportcls{
   width:24px !important; 
   height:26px !important;
   }
<?php if ($options['titleefect'] == 'true') { ?>
 #qTip{color:#fff;background:rgba(0,0,0,0.8);font-size:11px;text-align:right;direction:ltr;display:none;padding:8px 10px 9px 10px;position:absolute;z-index:9999;-webkit-border-radius:5px;-moz-border-radius:5px;-o-border-radius:5px;border-radius:5px;text-shadow:0;text-shadow:0 0 0 #000; font-size:11px; font-family:tahoma;}         
<?php } if ($options['roomstyle'] == 'true') { ?>
             .rooms:hover{box-shadow:inset 0 5px 10px rgba(0,0,0,.3),0 0 15px#0000ff;-moz-transition:all .1s ease-in}        
    b_on{background:rgba(250,250,250,0.6) none repeat scroll 0% 0%;color:#000;padding:2px 78;2px 7px;margin-top:0;border-radius:15px 2px 15px 15px;border-right:3px solid rgba(0,0,0,0.2);margin-right:2px;position:relative}
                .rooms_b{filter:grayscale(100%);-webkit-filter:grayscale(100%);-moz-filter:grayscale(100%);-ms-filter:grayscale(100%);-o-filter:grayscale(100%);cursor:no-drop;text-align:right;width:98%;padding:4px 1%;color:#e6e6e6;min-height:20px;height:auto;background-size:100% 100%;border-radius:4px;background-position:center right;margin-top:2px}
                .rooms_b span{font-family:dinarm;background:rgba(250,250,250,0.6);color:#000;padding:2px 7px;margin-top:0;border-radius:15px 2px 15px 15px;border-right:3px solid rgba(0,0,0,0.2);margin-right:2px;position:relative}
            <?php } else { ?>
                .rooms{cursor:pointer;text-align:center;height:26px;margin-bottom:2px;padding:1px}
                .rooms_b{filter:grayscale(100%);-webkit-filter:grayscale(100%);-moz-filter:grayscale(100%);-ms-filter:grayscale(100%);-o-filter:grayscale(100%);cursor:no-drop;background-repeat:repeat-x;height:30px;margin-bottom:2px;padding:1px}
 .rooms:hover{box-shadow:inset 0 5px 10px rgba(0,0,0,.3),0 0 15px#0000ff;-moz-transition:all .1s ease-in}           
            <?php } ?>
			 @keyframes shake{0%{transform:translate(2px,1px) rotate(0deg)}10%{transform:translate(-1px,-2px) rotate(-1deg)}20%{transform:translate(-3px,0px) rotate(1deg)}30%{transform:translate(0px,2px) rotate(0deg)}
               40%{transform:translate(1px,-1px) rotate(1deg)}50%{transform:translate(-1px,2px) rotate(-1deg)}60%{transform:translate(-3px,1px) rotate(0deg)}70%{transform:translate(2px,1px) rotate(-1deg)}
               80%{transform:translate(-1px,-1px) rotate(1deg)}90%{transform:translate(2px,2px) rotate(0deg)}100%{transform:translate(1px,-2px) rotate(-1deg)}}@-moz-keyframes shake{0%{-moz-transform:translate(2px,1px) rotate(0deg)}
                 10%{-moz-transform:translate(-1px,-2px) rotate(-1deg)}20%{-moz-transform:translate(-3px,0px) rotate(1deg)}30%{-moz-transform:translate(0px,2px) rotate(0deg)}40%{-moz-transform:translate(1px,-1px) rotate(1deg)}
                 50%{-moz-transform:translate(-1px,2px) rotate(-1deg)}60%{-moz-transform:translate(-3px,1px) rotate(0deg)}70%{-moz-transform:translate(2px,1px) rotate(-1deg)}80%{-moz-transform:translate(-1px,-1px) rotate(1deg)}90%{-moz-transform:translate(2px,2px) rotate(0deg)}
                 100%{-moz-transform:translate(1px,-2px) rotate(-1deg)}}@-webkit-keyframes shake{0%{-webkit-transform:translate(2px,1px) rotate(0deg)}10%{-webkit-transform:translate(-1px,-2px) rotate(-1deg)}20%{-webkit-transform:translate(-3px,0px) rotate(1deg)}30%{-webkit-transform:translate(0px,2px) rotate(0deg)}
                   40%{-webkit-transform:translate(1px,-1px) rotate(1deg)}50%{-webkit-transform:translate(-1px,2px) rotate(-1deg)}60%{-webkit-transform:translate(-3px,1px) rotate(0deg)}70%{-webkit-transform:translate(2px,1px) rotate(-1deg)}
               80%{-webkit-transform:translate(-1px,-1px) rotate(1deg)}90%{-webkit-transform:translate(2px,2px) rotate(0deg)}100%{-webkit-transform:translate(1px,-2px) rotate(-1deg)}}
            .TarhPardaz_win_title{font-family:<?php echo($thememanager['chat']['body']['font-family']); ?>}
            .TarhPardaz_win_content{font-family:<?php echo($thememanager['chat']['body']['font-family']); ?>;font-size:<?php echo($thememanager['chat']['body']['font-size']); ?>}
            .fontonline { font-family:<?php echo($thememanager['chat']['onlinelist']['font-family']); ?> !important;}
            .mainlinks a:hover{color:#00D6FF}.mainlinks{padding-right:3px;padding-left:3px}.mainlinks:hover{-webkit-border-radius:5px;-moz-border-radius:5px;border-radius:5px;color:#6E6E6E;background-color:#545454;box-shadow:inset 0 0 7px rgba(0,0,0,0.28)}          ::selection{background:#525252;color:#fff;
              text-shadow:0 0 2px #000}::-webkit-scrollbar{width:5px}::-webkit-scrollbar-thumb{background-color:#969696}::-webkit-scrollbar-button{background-color:#E4E4E4}::-webkit-scrollbar-track{background-color:#E4E4E4}::-webkit-scrollbar-corner{background-color:#dbdbdb}* :focus{outline:0}.privat_ausg_an{background-color:#fee;
                border:1px solid rgba(255,119,119,0.53);-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;box-shadow:0 0 2px rgba(255,119,119,0.48);font-family:b yekan,tahoma;text-shadow:1px 1px #fff;color:#333}
                             .stitle{background: #0b9bf6;box-shadow: 0 0 3px 0 #CF5858;color: #fff;direction: rtl;margin: 5px 0 0;padding: 6px 10px;}  

#message{background-color:#E8FAFF;background-repeat:repeat-x}
    .editeclass {background-color: #ebf7f3 !important;color: #0fcbff;border-radius: 8px;font-size: 14px;padding: 4px;}
#chatinhalt {
	border: solid 3px #062B78 !important;
	
}
#onlinelist {
	border: solid 3px #062B78 !important;
	
}       
    		.pin{width: auto; height: 80px; border: 1px solid #262b27; border-radius: 3px; margin-top: -4px; margin-left: -3px; margin-right: -3px; background-color: #f2f2ff; z-index: 1; margin-bottom: 10px;}
			.pin_by {width: auto; border-radius: 4px; float: right; color: #ffffff; margin-top: -50px; margin-right: 67px; text-align: right; background: #37bf48; padding: 5px;}
   		 	.close-pin {float: right; background-color: #ff3030; color: white; margin-top: -50px; margin-right: 7px; padding: 5px 11px; cursor: pointer; border-radius: 4px; transition: all 0.3s ease;}
         
            #stwel{border-radius: <?php echo($thememanager['chat']['welcomebox']['border-radius']); ?>;color: <?php echo($thememanager['chat']['welcomebox']['fonts-color']); ?>;background-color: <?php //echo($thememanager['chat']['welcomebox']['background-color']); ?>rgb(34 82 151 / 77%);font-size: <?php echo($thememanager['chat']['welcomebox']['font-size']); ?>;font-family: <?php echo($thememanager['chat']['welcomebox']['font-family']); ?>;font-style:normal;font-weight:400;padding:5px;background-repeat:repeat-x;margin-bottom:2px;
              text-align:center;box-shadow:0 0 20px rgba(0,0,0,0.04) inset;direction:rtl;padding-bottom:5px;border:<?php echo($thememanager['chat']['welcomebox']['border']); ?>;}
            #seemsg1{border-radius: <?php echo($thememanager['chat']['welcomebox']['border-radius']); ?>;color: <?php echo($thememanager['chat']['welcomebox']['fonts-color']); ?>;background-color: <?php //echo($thememanager['chat']['welcomebox']['background-color']); ?>rgb(34 82 151 / 77%);font-size: <?php echo($thememanager['chat']['welcomebox']['font-size']); ?>;font-family: <?php echo($thememanager['chat']['welcomebox']['font-family']); ?>;font-style:normal;font-weight:400;
              padding:5px;background-repeat:repeat-x;margin-bottom:2px;text-align:center;box-shadow:0 0 20px rgba(0,0,0,0.04) inset;direction:rtl;padding-bottom:5px;border:<?php echo($thememanager['chat']['welcomebox']['border']); ?>;}

            .linkdooni{font-family:<?php echo($thememanager['chat']['linkdooni']['font-family']); ?>;background-color: <?php //echo($thememanager['chat']['linkdooni']['background-color']); ?>rgb(34 82 151 / 77%);padding:6px;font-size:10px;color:#FFF;border-radius:3px;border-top-left-radius:3px;border-top-right-radius:3px;border-bottom-right-radius:3px;border-bottom-left-radius:3px;height:22px;line-height:20px;text-shadow:1px 1px rgb(44 102 58 / 77%);font-weight:400;border:<?php echo($thememanager['chat']['linkdooni']['border']); ?>;margin:4pt auto 2pt}

            /* دکمهٔ «نسخه موبایل» — طلاییِ مدرن با درخششِ ملایم و چشمک‌زن */
            a.btn-mobile{
                display:inline-block;
                direction:ltr;
                text-decoration:none;
                font-family:<?php echo($thememanager['chat']['body']['font-family']); ?>;
                font-size:14px;
                font-weight:700;
                letter-spacing:.5px;
                color:#fff8cf;
                background:linear-gradient(135deg,#b8860b 0%,#e8b44a 45%,#ffd873 55%,#e8b44a 70%,#b8860b 100%);
                background-size:200% 200%;
                padding:5px 16px;
                border-radius:999px;
                border:1px solid rgba(255,215,120,.85);
                box-shadow:0 0 8px rgba(232,180,74,.55), inset 0 1px 0 rgba(255,255,255,.45), inset 0 -1px 2px rgba(120,80,0,.35);
                text-shadow:0 1px 2px rgba(70,45,0,.55);
                transition:transform .18s ease, box-shadow .28s ease;
                animation:btnMobilePulse 2.4s ease-in-out infinite;
                cursor:pointer;
                vertical-align:middle;
            }
            a.btn-mobile:hover{
                transform:translateY(-1px) scale(1.04);
                box-shadow:0 0 16px rgba(255,200,90,.95), inset 0 1px 0 rgba(255,255,255,.55);
            }
            @keyframes btnMobilePulse{
                0%,100%{ box-shadow:0 0 6px rgba(232,180,74,.45), inset 0 1px 0 rgba(255,255,255,.4); }
                50%{ box-shadow:0 0 18px rgba(255,210,110,1), inset 0 1px 0 rgba(255,255,255,.5); }
            }
            .newsTarhPardaz{font-family:<?php echo($thememanager['chat']['linkdooni']['font-family']); ?>;background:<?php //echo($thememanager['chat']['linkdooni']['background-color']); ?>rgb(34 82 151 / 77%);border:<?php echo($thememanager['chat']['linkdooni']['border']); ?>;margin:0 auto auto;padding-top:0;color:#FFF;height:30px;border-radius:5px;font-size:15px;width:97%;}
            .TarhPardaz_win_content{background-repeat:<?php echo($thememanager['chat']['privat']['background-repeat']); ?>;background-color: <?php echo($thememanager['chat']['privat']['background-color']); ?>;background-image:url('<?php echo($thememanager['chat']['privat']['background-image']); ?>');}
            #adminbereich_body{background-repeat:<?php echo($thememanager['chat']['privat']['background-repeat']); ?>;background-color: <?php echo($thememanager['chat']['privat']['background-color']); ?>;background-image:url('<?php echo($thememanager['chat']['privat']['background-image']); ?>');}
            .privat_mesages_window{background-color:#D5F1FF;background-repeat:<?php echo($thememanager['chat']['privat']['background-repeat']); ?>;background-color: <?php echo($thememanager['chat']['privat']['background-color']); ?>;background-image:url('<?php echo($thememanager['chat']['privat']['background-image']); ?>');border: 1px dashed #000000; }
         
         
           #changemod{border:<?php echo($thememanager['chat']['welcomebox']['border']); ?>;border-radius: <?php echo($thememanager['chat']['welcomebox']['border-radius']); ?>;color: <?php echo($thememanager['chat']['welcomebox']['fonts-color']); ?>;background-color: <?php echo($thememanager['chat']['welcomebox']['background-color']); ?>;font-size: <?php echo($thememanager['chat']['welcomebox']['font-size']); ?>;font-family: <?php echo($thememanager['chat']['welcomebox']['font-family']); ?>;height:30px;margin:0 0 5px;padding:5px;display:none}
         
         
            #postslist{font-family:<?php echo($thememanager['chat']['onlinelist']['font-family']); ?>;float:right;font-size:<?php echo($thememanager['chat']['onlinelist']['font-size']); ?>;font-style:normal;height:450px;line-height:190%;overflow-x:hidden;overflow-y:auto;padding:5px 5px 16px;text-align:center;width:<?php echo($thememanager['chat']['onlinelist']['width']); ?>;border:<?php echo($thememanager['chat']['onlinelist']['border']); ?>}
            #myposts{border-radius: <?php echo($thememanager['chat']['chatinhalt']['border-radius']); ?>;font-size:<?php echo($thememanager['chat']['chatinhalt']['font-size']); ?>;font-family:<?php echo($thememanager['chat']['chatinhalt']['font-family']); ?>;background-image:url('<?php echo($thememanager['chat']['chatinhalt']['background-image']); ?>');background-repeat:<?php echo($thememanager['chat']['chatinhalt']['background-repeat']); ?>;background-color: <?php echo($thememanager['chat']['chatinhalt']['background-color']); ?>;height:100%;
              line-height:190%;overflow:auto;padding:5px 5px 16px;position:relative;border:<?php echo($thememanager['chat']['chatinhalt']['border']); ?>}
            #friends{border-radius: <?php echo($thememanager['chat']['onlinelist']['border-radius']); ?>;font-family:<?php echo($thememanager['chat']['onlinelist']['font-family']); ?>;float:left;font-size:<?php echo($thememanager['chat']['onlinelist']['font-size']); ?>;font-style:normal;height:100%;line-height:190%;margin-right:10px;overflow-x:hidden;overflow-y:auto;padding:5px 5px 16px;text-align:center;width:<?php echo($thememanager['chat']['onlinelist']['width']); ?>;border:<?php echo($thememanager['chat']['onlinelist']['border']); ?>}

#boxforpv {	background-color: #f2f2ff;	border: 1px solid #f2f2ff;	border-radius: 10px;	margin: 0px;	min-width: atue;	overflow: hidden;	position: relative;	width: 180px;	word-wrap: break-word;	float: left;	margin: 10px 0 0;}
#replysep {	position: relative;	bottom: 30px;	left: 55px;	border-left: 3px solid #809fff;	width:90%;	word-break: break-all;	margin-top: -20px;}
#forwardBox {	display: none;	background-color: #f2f2ff;	text-align: center;	direction: rtl;	border-bottom: 2px solid green;	position: fixed;	top: 0;	width: 100%;	height: 120px;	z-index: 9999;	/*! margin-top: -10px; */margin-top: ;}
.forwardClose,.forwardLoading {	height: 25px;	position: relative;	text-align: center;	width: 25px;}
.forwardClose {	background: #809fff none repeat scroll 0 0;	border-radius: 100px;	font-size: 20px;	color: green;	cursor: pointer;	float: right;	right: 10px;}
.forwardLoading {	float: left;	left: 10px;}
.forwardSearch input {	width: 50%;	text-align: center;	height: 20px;	border: 1px solid green;}
.forwardSearch img {	width: 17px;	height: 17px;}
#forwardResult {	width: 100%;	height: 90px;	overflow: auto;}
.forwardItem {	float: right;	margin-right: 5px;	margin-top: 5px;	border: 1px solid green;	padding: 5px;	height: auto;	border-radius: 55px;}
.forwardItem:hover {	background-color: #1ED31B;	cursor: pointer;}
.forwardItem div img {	width: 45px;	height: 45px;	border-radius: 25px;	border: 2px solid #1AC5BE;}
.forwardTitle {	color: green;	margin-left: 7px;	margin-top: -2px;	position: relative;	font-size: 12px;}
.dropdown{ 	color:#000000;	position: relative; 	display: inline-block;}
.dropdown-content{    display: none;    position: absolute;    top: -10px;    left: 30px;    background-color: #049ed5;    border-radius: 3px;    width: 100%;    padding: 5px;    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);}
.dropdown-content a {    color: #FFF !important;    padding: 2px 2px;    text-decoration: none;    display: inline;    float: left;    font-size: 12px;    font-weight: normal;}
.dropdown-content a:last-child{    border-right: 0px;}
.dropdown a:hover {	color:black;    background-color: #807e7e80;}
.showdropdown{	display:block;}
.dropdown-content{	z-index:1;}
.dropdown-content .arrow, .dropdown-content .arrow:after {    position: absolute;    display: block;    width: 0;    height: 0;    border-color: transparent;    border-style: solid;}
.dropdown-content .arrow{    top: 12px;    left: -15px;    border-right-color: #a5a8ab;    border-left-width: 0;    border-width: 8px;}
.dropbtns {	cursor: pointer;}
.dropdowns {	color:#000000;	display: inline-block;	font-family:yekan}
.dropdown-contents {	display: none;	position: absolute;	background-color: #a5a8ab;	/*! min-width: auto; */	overflow: auto;	box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);	width: 180px !important;	border-radius: 2px;}
.dropdown-contents a {	color: white !important;	padding: 12px 16px;	text-decoration: none;	display: block;}
.dropdowns a:hover {	color:black;	background-color: #898989;}
.showdropdowns {	display:block;}
.dropdown-contents {	z-index:1000;}
<?php 
$avonline = explode('*', $options['avatarOnline']);
foreach ($avonline as $avonlines) {
?>
   #avataronline { height:<?php echo $avonlines ?>px; width:<?php echo $avonlines ?>px; } <?php } ?> 
 .pmbox{margin-top:5px;}
<?php if ($options['msgnum_myself'] == 'true') { ?>
.msgnum_myself {
direction:rtl !important;
}
.msgnum_myself  a img  {
    width: 25px;
    border-radius: 15px;
}
.msgnum_myself  #meeno  {
display:none;
}
.msgnum_myself .mess_back {
   direction:rtl !important;
}
.msgnum_myself .mess_back b {
   display:none;
}
.msgnum_myself #box {
   direction:rtl !important;
}
.msgnum_myself #khat {
direction:rtl !important;
}
.msgnum_myself .pmbox .mess_back {
    float: right;
}
.msgnum_myself .pmbox  #box {
    float: right;
}
.msgnum_myself .pmbox  #box #khat #name{
    direction:ltr !important;
}
.msgnum_myself .pmbox  #box #khat #text{
    direction:ltr !important;
}
   .tlg {line-height: 25px; background-color: #f2f2ff !important; padding: 5px 15px !important; border-radius: 20px !important; }
   <?php } ?>
<?php if ($options['kadr_myself'] == 'true') { ?>
.tlg {line-height: 25px; background-color: #f2f2ff !important; padding: 5px 15px !important; border-radius: 20px !important; }
   <?php } ?>
<?php $avatarstyle= json_decode($options['avatarstyle'], true);?>
.avatar {
    border-radius: <?php echo($avatarstyle['border-radius']); ?>;
    box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.1);
    border: <?php echo($avatarstyle['border-size']); ?> solid <?php echo($avatarstyle['avatar-color']); ?>;
    transition: all 0.6s ease 0s;
}
.avatar:hover{border-radius:<?php echo($avatarstyle['avatar-hover']); ?>;}
  

 </style>
      
      
    </head>

   <body onload="myfunction();" ondragstart="return false" draggable="false" ondragenter="event.dataTransfer.dropEffect='none'; event.stopPropagation(); event.preventDefault();" ondragover="event.dataTransfer.dropEffect='none';event.stopPropagation(); event.preventDefault();" ondrop="event.dataTransfer.dropEffect='none';event.stopPropagation(); event.preventDefault();">

<?php if ($guestMustReg) { ?>
 <style>
     @keyframes grspin { to { transform: rotate(360deg); } }
    </style>
 <div id="guestRegOverlay" style="position:fixed;inset:0;z-index:999999;backdrop-filter:blur(8px);background:rgba(0,0,0,.55);display:flex;align-items:center;justify-content:center;">
  <div style="background:#fff;padding:28px;border-radius:12px;max-width:360px;width:90%;text-align:center;box-shadow:0 8px 32px rgba(0,0,0,.35);">
   <h3 style="margin:0 0 10px;color:#d33;">ثبت‌نام اجباری</h3>
   <p style="margin:0 0 16px;color:#444;font-size:14px;">برای استفاده از امکانات و چت کردن با بقیهٔ اعضا، باید عضو شوید.</p>
   <form id="guestRegForm" onsubmit="return false;">
    <div style="margin:8px 0;text-align:right;">
     <label>نام کاربری شما:</label>
     <input type="text" value="<?php echo(session('name')); ?>" disabled style="width:100%;margin-top:4px;padding:7px;box-sizing:border-box;" />
    </div>
    <div style="margin:8px 0;text-align:right;">
     <label>رمز عبور:</label>
     <input type="password" name="password" id="gRegPw" style="width:100%;margin-top:4px;padding:7px;box-sizing:border-box;" />
    </div>
    <div style="margin:8px 0;text-align:right;">
     <label>تکرار رمز:</label>
     <input type="password" name="password2" id="gRegPw2" style="width:100%;margin-top:4px;padding:7px;box-sizing:border-box;" />
    </div>
    <button type="submit" id="gRegBtn" style="width:100%;padding:10px;margin-top:10px;font-size:15px;background:#d33;color:#fff;border:0;border-radius:6px;cursor:pointer;">ثبت‌نام</button>
    <span id="gRegMsg" style="display:block;margin-top:10px;color:red;font-size:13px;"></span>
   </form>
  </div>
 </div>
 <script>
 (function(){
  var URL = <?php echo(json_encode(url('ajax/guest.register'))); ?>;
  function wire(){
   if (window.__gRegWired) return;
   var need = document.getElementById('guestRegOverlay');
   if (!need) return;                       // هنوز DOM آماده نیست؛ صبر می‌کنیم
   var form = document.getElementById('guestRegForm');
   var pw = document.getElementById('gRegPw');
   var pw2 = document.getElementById('gRegPw2');
   var msg = document.getElementById('gRegMsg');
   var btn = document.getElementById('gRegBtn');
   if (!form || !pw || !pw2 || !msg || !btn) return;
   window.__gRegWired = true;
   function resetBtn(){ btn.disabled = false; btn.innerHTML = 'ثبت‌نام'; }
   function setLoading(){
    btn.disabled = true;
    btn.innerHTML = '<span style="display:inline-block;width:13px;height:13px;border:2px solid rgba(255,255,255,.5);border-top-color:#fff;border-radius:50%;vertical-align:-2px;margin-left:6px;animation:grspin .7s linear infinite;"></span>در حال ثبت‌نام...';
    msg.textContent = '';
   }
   function onResult(r){
    if (r === 'ok' || r === 'disabled') {
     // ثبت‌نام موفق (یا غیرفعال): کل صفحه رفرش شود تا کاربر به‌عنوان عضو وارد شود
     need.style.display = 'none';
     window.setTimeout(function(){ window.location.reload(); }, 300);
     return;
    }
    if (r === 'dontmatch') { msg.textContent = 'رمز و تکرار رمز یکسان نیستند'; }
    else if (r === 'lastregister') { msg.textContent = 'این نام قبلاً ثبت شده است'; }
    else if (r === 'login') { msg.textContent = 'نشست شما از بین رفته، صفحه را رفرش کنید'; }
    else if (r) { msg.textContent = 'خطا در ثبت‌نام، دوباره تلاش کنید (' + r + ')'; }
    else { msg.textContent = 'خطا در ثبت‌نام، دوباره تلاش کنید'; }
    resetBtn();
   }
   form.onsubmit = function(){
    var p1 = pw.value, p2 = pw2.value;
    if (!p1 || !p2) { msg.textContent = 'هر دو فیلد رمز را وارد کنید'; return false; }
    if (p1 !== p2) { msg.textContent = 'رمز و تکرار رمز یکسان نیستند'; return false; }
    setLoading();
    var done = 0;
    function finish(r){ if (done) return; done = 1; onResult(r); }
    // مطابق الگوی اجتماعیِ موجود (social.js): ارسال با Prototype Ajax.Request.
    if (window.Ajax && Ajax.Request) {
     new Ajax.Request(URL, {
      method: 'post',
      parameters: { password: p1, password2: p2 },
      onSuccess: function(res){
       var raw = ('' + (res && res.responseText ? res.responseText : '')).trim();
       var parts = raw.split(/\r?\n/);
       var r = '';
       for (var i = parts.length - 1; i >= 0; i--) {
         var s = parts[i].trim();
         if (s) { r = s; break; }
       }
       finish(r);
      },
      onFailure: function(){ finish(false); }
     });
    } else {
     // fallback بدون Prototype (XMLHttpRequest ساده)
     var x = new XMLHttpRequest();
     x.open('POST', URL, true);
     x.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
     x.onload = function(){
      var raw = (x.responseText || '').trim();
      var r = raw.split(/\r?\n/).filter(Boolean).pop() || '';
      finish(r);
     };
     x.onerror = function(){ finish(false); };
    }
    return false;
   };
  }
  if (document.readyState === 'loading') {
   document.addEventListener('DOMContentLoaded', wire);
  } else {
   wire();
  }
  window.addEventListener('load', function(){ setTimeout(wire, 300); });
  setTimeout(wire, 1500); // پشتیبان برای هر زمان‌بندی اسکریپت‌ها روی هاست
 })();
 </script>
<?php } ?>

 <?php
        theme::themeloader('chat', 'ads-bannerfix');
        theme::themeloader('chat', 'code-top');
       theme::themeloader('chat', 'header');
        ?>
        <div id="rahmen_um_alles">
            <?php theme::themeloader('chat', "ads-banner-top"); ?>
            <div id="kopf">             
<div id="stwel">
                    <b class="vbirancheshmak" style="color:<?php echo($thememanager['chat']['welcomebox']['username-color']); ?>;font-size:<?php echo($thememanager['chat']['welcomebox']['font-size']); ?>;">
                      <?php echo(session('name')); ?> </b><font face="<?php echo($thememanager['chat']['welcomebox']['font-family']); ?>" size="3"><?php echo($options['up_box']); ?><b><a style="color:#37e40b;font-size: 15px;" href="/contact" target="_blank" title="تماس با مديريت">  تماس با ما  </a><a href="/faq" style="color:#EDE7A6;font-size: 15px;" target="_blank" title="آموزش کار با چت روم"> | آموزش کار با چت روم</a></b></font>
              </div>
                <div id="changemod">
                   <div style="float:right;"><a id="sonlinelist">ليست آنلاين ها</a><a id="spostslist">بخش اجتماعي</a><a id="reloadsocial" class="reload">به روز رساني</a></div> <!-- <span style="font-size: 15px;position: relative;right: 5px;"><?php ?></span> -->
                   <div style="display:none;"><a id="reloadsocial" class="reload">به روز رساني دوستان و پروفايل ها</a><a id="schatinhalt">عمومي چت روم</a><a id="snewposts">بخش اجتماعي</a></div>
                </div>  
<div id="">

<?php if ($options['stm'] == 'true') { ?>

 <?php } ?>     </div>  
<?php if ($options['show_msgs'] == 'true') { ?>	
              <div id="seemsg1">
    
 <!----------------user check permit-------------->
<center><spink>	<center><spink>	<a id="nofilter" onclick="nofilter();">ورود به ادرس بدون فیلتر</a>&nbsp;
<a id="listrefresh" onclick="listrefresh();">رفرش کردن لیست انلاین </a>&nbsp;
<a id="stfresh" href="javascript:document.location.reload();">رفرش کردن صفحه </a>&nbsp;
<a id="stmobile" class="btn-mobile" href="<?php echo(url('phone')); ?>">📱 نسخه موبایل</a>&nbsp;
<a id="stlist" href="#" onclick="if(this.innerHTML=='نمايش ليست چت'){document.getElementById('onlinelist').show();this.innerHTML='عدم نمايش ليست چت';}else if(this.innerHTML=='عدم نمايش ليست چت'){document.getElementById('onlinelist').hide();this.innerHTML='نمايش ليست چت';}">عدم نمايش ليست چت</a>&nbsp;
<a id="strastchin1" href="#" onclick="if(this.innerHTML=='نمايش عمومي'){document.getElementById('chatinhalt').show();this.innerHTML='عدم نمايش عمومي';}else if(this.innerHTML=='عدم نمايش عمومي'){document.getElementById('chatinhalt').hide();this.innerHTML='نمايش عمومي';}">عدم نمايش عمومي</a>&nbsp;
<a id="strastchin" href="#" onclick=" if(this.innerHTML=='چپ چين عمومي'){document.getElementById('chatinhalt').style.direction='ltr';this.innerHTML='راست چين عمومي';}else if(this.innerHTML=='راست چين عمومي'){document.getElementById('chatinhalt').style.direction='rtl';this.innerHTML='چپ چين عمومي';} ">راست چين عمومي</a>&nbsp;
 <a id="thetimer" class="kopf" href="#">  باماهمراه شديد:   <span id="timespent">00:00</span>  </a>  <div style="height:0;width:0;overflow:hidden;">  <a id="kopf" class="kopf" href="#">  <script>window.setTimeout('getSecs()',1)</script>    </a> 
<script>startday = new Date();clockStart = startday.getTime();function initStopwatch() { var myTime = new Date(); return((myTime.getTime() - clockStart)/1000); }function getSecs() { var tSecs = Math.round(initStopwatch()); var iSecs = tSecs % 60;var iMins = Math.round((tSecs-30)/60);   var sSecs ="" + ((iSecs > 9) ? iSecs : "0" + iSecs);var sMins ="" + ((iMins > 9) ? iMins : "0" + iMins);document.getElementById('timespent').innerHTML = sMins+":"+sSecs;window.setTimeout('getSecs()',1000); }</script> </div> </spink></center>
  </div>
<div id="stwel">
<div id="seemsg">
 
 <!------------Switch Button-------------------------->
حالت شب  
 <label class="switch">
  <input id="darkmod" type="checkbox" onclick="night()">
  <span class="slider round"></span>
  </label>
<!-------------------------------------------------->


</font>
<font style="font-size: 12px;">&nbsp;
&nbsp;&nbsp;
<input type="checkbox" id="privat_msgs" name="privat_msgs" onclick="privat_msgs();" value="1">عدم دريافت پيام خصوصي
<input type="checkbox" id="privat_Perm" name="privat_Perm" onclick="privat_Perm();" value="1">ورود به خصوصي با تاييد شما
&nbsp;&nbsp;
  </div></div>
 <?php } ?>
                <div id="onlinelist"><img src="<?php echo(theme::url('theme/images/chat/chat.ajax-loader.gif')); ?>" alt="در حال بارگذاري....." /></div>
                <div id="postslist" style="display:none;"><div id="offmsg"></div></div>
                <div id="splitpane">
    </div>
                <div id="chatinhalt">

                  <div id="pinned" style="position: sticky;top: -4px;z-index: 1; display:none;">
                <div id="pinned-box" class="pin" style="display:block">
				<img id="avatar-pin" src="/theme/images/chat/pin.png" style="height:50px;width:50px;margin-top:15px;margin-left:5px;border-radius:50px;">
				<div style="margin-left:60px;margin-top:-60px;height:60px;width:2px;background-color:blue;border:1px solid blue;"></div>
				<div id="usernamepin" style="margin-left:70px;margin-top:-60px;">پیام پین شده : </div>
				<div id="pmpin" style="margin-left:70px;margin-top:25px;"></div>
				<div onclick="unpin_msg();" class="close-pin">بستن</div>
                <div id="pinby" class="pin_by"></div>
                <div id="pindel">
                                </div>
				</div>
                </div>


                  <div id="pinmsg"></div>
                  <button id="loadLastBtn" type="button" style="display:inline-block;margin:10px auto;padding:6px 14px;border:1px solid #062B78;border-radius:5px;background:rgba(6,43,120,0.9);color:#fff;font-family:Tahoma;font-size:12px;cursor:pointer;">نمایش 50 پیام آخر عمومی</button>
                  <div id="lastHistoryBox" style="display:none;border-top:2px solid #062B78;margin-top:4px;padding-top:4px;direction:rtl;"></div></div>
                <div id="social" style="display:none;">
                    <div id="friends"><span class="fon">دوستان آنلاين</span><div id="friends-on"></div><span class="fof">دوستان آفلاين</span><div id="friends-off"></div></div>
                    <div id="myposts"><div class="desc"><span style="color:#ff4040;">پست هاي من</span> ، <span style="color:#009EFF;">پست هاي دوستان</span> ، <span style="color:#0F9A0E;">پست هاي دنبال شدگان</span></div><div id="getposts"></div></div>
                </div>
                <div id="clear"></div>
   <div class="linkdooni" id="linkdooni">
                    <marquee  onMouseOver="this.setAttribute('scrollamount', 0, 0);this.stop();" OnMouseOut="this.setAttribute('scrollamount', 2, 0);this.start();" scrolldelay="70" scrollamount="2" direction="right">
                        <?php
			$sql->where('active', 1);
			foreach($sql->get('links', null, array('id', 'click', 'title')) as $links){
                            ?>
                            &nbsp;<a href="<?php echo(url("link/{$links['id']}")); ?>" target="_blank" class="mainlinks" title="[ <?php echo($links['click']); ?> بار مشاهده شده ]"><?php echo($links['title']); ?></a> &nbsp;&nbsp;<span style="color: <?php echo($thememanager['chat']['linkdooni']['space-textcolor']); ?>;font-weight:bold"><?php echo($thememanager['chat']['linkdooni']['space-text']); ?></span>&nbsp;&nbsp;
                            <?php
                        }
                        ?>
                    </marquee>
            </div>
                <div id="form">
                    <form id="message_form" style="display:inline;" action="javascript:;">
                        <div id="form_left">
                            <div id="privat_anzeige"><center><?php echo($options['down_box']); ?></center></div>
                            <input type="hidden" name="fontname" id="fontname" value="<?php echo session('fontname') != null ? session('fontname') : $options['chat_defaultfontname']; ?>" />
                            <input type="hidden" name="fontsize" id="fontsize" value="<?php echo session('fontsize') != null ? session('fontsize') : $options['chat_defaultfontsize']; ?>" />
                            <input type="hidden" name="room" id="room" value="1">
                            <input type="hidden" name="privat" id="privat" value="">
                            <input type="hidden" name="bold" id="bold" value="<?php echo session('bold') == 'bold' ? 'bold' : 'normal'; ?>" >
                            <input type="hidden" name="italic" id="italic" value="<?php echo session('italic') == 'italic' ? 'italic' : 'normal'; ?>" >
                            <input type="hidden" name="color" id="color" value="#<?php echo session('color') != null ? session('color') : $options['chat_defaultcolor']; ?>" >
                            <input onfocus="this.className = 'focus';" class="input_class" name="message" id="message" type="text"  placeholder="<?php echo($options['omomi_fild']); ?>"  style="font-family:<?php echo session('fontname') != null ? session('fontname') : $options['chat_defaultfontname']; ?>;direction:rtl;width:520px;height:22px;font-size: <?php echo session('fontsize') != null ? session('fontsize') : $options['chat_defaultfontsize']; ?>;
                            font-style: <?php echo session('italic') == 'italic' ? 'italic' : 'normal'; ?>;font-weight: <?php echo session('bold') == 'bold' ? 'bold' : 'normal'; ?>;color: #<?php echo session('color') != null ? session('color') : $options['chat_defaultcolor']; ?>;" maxlength="200" ondragenter="event.dataTransfer.dropEffect='none'; event.stopPropagation(); event.preventDefault();" ondragover="event.dataTransfer.dropEffect='none';event.stopPropagation(); event.preventDefault();" 
                                   ondragstart="return false" draggable="false" ondrop="event.dataTransfer.dropEffect = 'none';event.stopPropagation();event.preventDefault();">
                        </div>
                        <div id="form_right"><?php theme::themeloader('chat', "form_right"); ?></div>
<?php if ($options['date_time'] == 'true') { ?>
                        <div style="float:right;"><p dir="rtl" style="color: #fffcfc;font-family: Iransans;text-shadow: 1px 1px 1px #000;" align="right">ساعت:<script>
TIME = new Date();
DIFF = new Date() - TIME ;
INTV = window.setInterval("Show_Clock()",1000) ;
 
function Show_Clock(){
     TIME = new Date(new Date() - DIFF) ;
     HH = TIME.getHours() ;
     MM = TIME.getMinutes() ;
     SS = TIME.getSeconds() ;
     document.getElementById('CLOCK').innerHTML=((HH<10  )? "0"+HH:HH) + ":" + ((MM<10)? "0"+MM:MM) + ":" + ((SS<10)? "0"+SS:SS);
}</script>
<span id="CLOCK">15:16:55</span>
<br>
<script>
week= new Array("يكشنبه","دوشنبه","سه شنبه","چهارشنبه","پنج شنبه","جمعه","شنبه")
months = new Array("فروردين","ارديبهشت","خرداد","تير","مرداد","شهريور","مهر","آبان","آذر","دي","بهمن","اسفند");
a = new Date();
d= a.getDay();
day= a.getDate();
month = a.getMonth()+1;
year= a.getYear();
year = (year== 0)?2000:year;
(year<1000)? (year += 1900):true;
year -= ( (month < 3) || ((month == 3) && (day < 21)) )? 622:621;
switch (month) {
case 1: (day<21)? (month=10, day+=10):(month=11, day-=20); break;
case 2: (day<20)? (month=11, day+=11):(month=12, day-=19); break;
case 3: (day<21)? (month=12, day+=9):(month=1, day-=20); break;
case 4: (day<21)? (month=1, day+=11):(month=2, day-=20); break;
case 5:
case 6: (day<22)? (month-=3, day+=11):(month-=2, day-=21); break;
case 7:
case 8:
case 9: (day<23)? (month-=3, day+=9):(month-=2, day-=22); break;
case 10:(day<23)? (month=7, day+=8):(month=8, day-=22); break;
case 11:
case 12:(day<22)? (month-=3, day+=9):(month-=2, day-=21); break;
default: break;
}
document.write(" "+week[d]+" "+day+" "+months[month-1]+" "+ year);
</script> 
<!-- code Date & Time -->  						
                    </p></div>
     <?php
                        }
                        ?>
                    </form>      
                </div>
<!----------------------nowruz-----------------------------------

</p><div dir="rtl" class="nowruztimer">
	<style type="text/css">
.nowruzcountdown span{
font-size:18px;
color:yellow;
padding:0 1% 0 0;
}
.nowruzcountdown h3{
font-size:16px;color:yellow;
}
.timer{
width:100%;
text-align:center;
font-size:14px;
color:white;
margin:2vw 0;
background:rgb(24 118 88 / 77%);
border-radius:0;
border-bottom:#111b47 .12vw solid;
border-top:#111b47 .12vw solid;
}	
</style>
	<p align="center">
		 </p>

	<div class="nowruzcountdown">
		<div class="timer" id="timer">
			<h3>
				زمان باقیمانده تا آغاز سال 1404</h3>
         
			<span id="days">0</span> روز <span id="hours">0</span> ساعت <span id="minutes">0</span> دقیقه <span id="seconds">0</span> ثانیه</div>
	</div>
	<script>
const second=1000,minute=second*60,hour=minute*60,day=hour*24;let countDown=new Date('March 20, 2025 12:31:30').getTime(),x=setInterval(function(){let now=new Date().getTime(),distance=countDown-now;document.getElementById('days').innerText=Math.floor(distance/(day)),document.getElementById('hours').innerText=Math.floor((distance%(day))/(hour)),document.getElementById('minutes').innerText=Math.floor((distance%(hour))/(minute)),document.getElementById('seconds').innerText=Math.floor((distance%(minute))/second)},second);    
</script>

	<p>
		 </p>

--------------------------endnowruz------------------------------->
            </div>
        </div>
        <?php if ($options['chatnews'] == 'true') { ?>
            <?php if (chatroom::checkPermission('chat_news')) { ?>
                <div class="newsTarhPardaz">
                    <script type="text/javascript">var theSummaries = new Array();
        <?php
	$sql->where('active', 1); $i=0;
	foreach($sql->get('news', null, array('id', 'text')) as $row){
            ?>
                            theSummaries[<?php echo $i; ?>] = '<?php echo $row['text']; ?>';
            <?php $i++;
        }
        ?>var theSiteLinks = new Array();
                    </script>
                    <div style="text-align:center;direction:rtl;margin-top:3px;"><div class="MyTicker"><span id="theTicker">&nbsp;</span></div>
                        <script type="text/javascript" src="<?php echo(url('theme/js/news.js')); ?>"></script>
                        <script type="text/javascript">function startTicker() {
                return theCurrentStory = -1, theCurrentLength = 0, document.getElementById ? void runTheTicker() : (document.write("<style> .ticki{display:none; direction:rtl; }.ticko{border:0px; padding:0px; direction:rtl;}</style>"), !0)}function runTheTicker() {
                var t;
                0 === theCurrentLength && (theCurrentStory++, theCurrentStory %= theItemCount, theStorySummary = theSummaries[theCurrentStory], theTargetLink = theSiteLinks[theCurrentStory]);
                var e = theStorySummary.substring(0, theCurrentLength) + whatWidget();
                theTargetLink ? NS6 ? document.getElementById("theTicker").innerHTML = '<a target="_self" style="font-size:14px; color:white; text-decoration:none;" href="' + theTargetLink + '" target="_top">' + e + "</a>" : document.all.theTicker.innerHTML = '<a target="_self" style="text - decoration:none; font - size:14px; color:white" href="' + theTargetLink + '" target="_top">' + e + " </a>" : NS6 ? document.getElementById("theTicker").innerHTML = '<span style = "text-decoration:none;
                  font-weight:bold; font-size:14px; color:white;" > ' + e + "</span>" : document.all.theTicker.innerHTML = '<span style = "text-decoration:none; font-size:14px; font-weight:bold; color:white;" > ' + e + "</span>", theCurrentLength !== theStorySummary.length ? (theCurrentLength++, t = theCharacterTimeout) : (theCurrentLength = 0, t = theStoryTimeout), setTimeout("runTheTicker()", t)}function whatWidget() {
                return theCurrentLength === theStorySummary.length ? theWidgetNone : theCurrentLength % 2 === 1 ? theWidgetOne : theWidgetTwo
            }
            var theCharacterTimeout = 60, theStoryTimeout = 4e3, theWidgetOne = "_", theWidgetTwo = "-", theWidgetNone = "", theItemCount = theSummaries.length, NS6 = document.getElementById && !document.all ? !0 : falsestartTicker();</script>
                    </div>
                </div>
                <br>
                <br>
    <?php } ?>
<?php } ?>
<?php theme::themeloader('chat', "ads-banner-bottom"); ?>
<?php theme::themeloader('chat', "code-bottom"); ?>
<?php if ($options['box3'] == 'true') { ?>
        <center>
            <div align="center">
                <table border="0" width="100">
                    <td>
                        <iframe src="<?php echo(url('compile/ranking')); ?>" style="border: 0px;" frameborder="0" height="248" scrolling="no" width="294"></iframe>
                    </td>
                    <td>
                        <div align="center">
                            <table border="0" style="color:white !important; width:288px;height:241px" background="<?php echo(url('theme/images/chat/bodd.jpg')); ?>">
                                <tr>
                                    <td>
                                        <div class="menu">
                                            <div class="text-menu">
                                                <p align="center">
                                                    <a style="color:white !important;" target="_blank" href="<?php echo(url('links')); ?>"><span style="text-decoration: none;color:white !important; font-weight: 700">لينک خود را ثبت کنيد</span></a>
                                                </p>
                                            </div>
                                        </div>
                                        <p align="center" style="text-align: center;color:#fff;direction: rtl;color:white !important; ">
                                <marquee trueSpeed scrollAmount="2" direction="up" height="150" align="middle" width="220" style="font-family: Tahoma;color:white !important; font-size: 10pt; background-color:#000000">
                                    <center>
                                        <?php
					$sql->where('active', 1);
					foreach($sql->get('links', null, array('id', 'click', 'title')) as $links){
                                            ?>
                                            &nbsp;<a style="color:#afe979 !important;" href="<?php echo(url("link/{$links['id']}")); ?>" target="_blank" style="font-family:<?php echo($thememanager['chat']['linkdooni']['font-family']); ?>;" class="mainlinks" title="[ <?php echo($links['click']); ?> بار مشاهده شده ]" ><?php echo($links['title']); ?></a><br> <br>&nbsp;&nbsp;<span style="color:#ffffff;font-weight:bold"><?php echo($thememanager['chat']['linkdooni']['space-text']); ?><br><br></span>&nbsp;&nbsp;
        <?php
    }
    ?>
                                    </center>
                                </marquee>
                                </p>
                                </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                    <td>
                        <div align="center">
                            <table border="0" width="288" background="<?php echo(url('theme/images/chat/bodd.jpg')); ?>" height="241">
                                <tr>
                                    <td>
                                        <p style="text-align: center;color: #fff;">پخش موزيک</p>
                                        <div class="body">
                                            <div align="middle" style="font-family:b;font-size:10pt;height:150px;width:280px;">
                                                <div align="center">
                                                    <?php
                                                    if ($musicplace == 'chat') {
                                                        $src = array();
                                                        $title = array();
                                                        $sql->orderBy('priority', 'DESC');
                                                        foreach($sql->get('player') as $row){
                                                            $src[] = theme::url($row['src']);
                                                            $title[] = $row['title'];
                                                        }
                                                        ?>
                                                        <center>
                                                            <object type="application/x-shockwave-flash" data="<?php echo(theme::url('theme/music/player_mp3_multi.swf')); ?>" width="240" height="100">
                                                                <param name="movie" value="<?php echo(theme::url('theme/music/player_mp3_multi.swf')); ?>">
                                                                <param name="FlashVars" value="mp3=<?php echo(implode('|', $src)); ?>&amp;title=<?php echo(implode('|', $title)); ?>&width=240&autoplay=<?php echo($options['chat_musicautoplay']); ?>&volume=200&showloading=always&loadingcolor=94ce00&bgcolor=b803f5&bgcolor1=ff29a8&bgcolor2=9e0078&amp;&slidercolor1=ffffff&slidercolor2=ffffff&sliderovercolor=94ce00&textcolor=ffffff¤tmp3color=94ce00&scrollbarcolor=ffffff&scrollbarovercolor=94ce00&showinfo=1">
                                                            </object>
                                                        </center>
    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </table>
            </div>
        </center>
        <?php
    } else if ($options['showPlayer'] == 'true') {
        ?>
        <center>
            <iframe src="<?php echo(url('player')); ?>" style="border:0px;width:351px;height:auto;" frameborder="0" scrolling="no"></iframe>
        </center>
    <?php
}
?>

<script> function night() {var element = document.body; element.classList.toggle("darkmod");} </script>

<script>let lastClickTime = 0;function listrefresh() {let currentTime = Date.now();let timeDifference = currentTime - lastClickTime;if (timeDifference < 60000) { // 60000 میلی ثانیه معادل 1 دقیقه است
 let time = 60 - timeDifference/1000;alert("هر دقیقه یک بار مجاز به استفاده از رفرش لیست انلاین میباشید. "+time.toFixed(0)+" ثانیه صبر کنید");}else{lastClickTime = currentTime;ReloaderUserOnline();alert('لیست بروز شد ... :)');}}</script>
<script>
function nofilter() {
    if (confirm("اگر برای دسترسی به سایت مجبور به استفاده از فیلترشکن هستید، روی «تأیید» بزنید تا به لینک بدون فیلتر منتقل شوید.")) {
        window.location.href = "http://baran.na.am";
    }
}
</script>
<script>
(function() {
    function lastHistoryAvatar(av) {
        var dir = "theme/images/avatars";
        if (!av) av = (typeof $setting !== "undefined" && $setting.defaultavatar) ? $setting.defaultavatar : "photo.png";
        try { return theme_url(dir + "/" + av); } catch(ex) { return "/" + dir + "/" + av; }
    }
    function loadLast50() {
        var btn = document.getElementById('loadLastBtn');
        var box = document.getElementById('lastHistoryBox');
        if (!box || !btn) return;
        if (box.getAttribute('data-loaded') === '1') return;
        btn.textContent = 'در حال بارگذاری...';
        btn.disabled = true;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', url('ajax/chat.lasthistory'), true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.withCredentials = true;
        xhr.onload = function() {
            btn.textContent = 'نمایش 50 پیام آخر عمومی';
            btn.disabled = false;
            if (xhr.status !== 200) { box.innerHTML = '<div style="color:#900;padding:5px;">خطا در دریافت پیام‌ها</div>'; return; }
            var d;
            try { d = JSON.parse(xhr.responseText); } catch(ex) { box.innerHTML = '<div style="color:#900;padding:5px;">خطا در پردازش</div>'; return; }
            if (typeof render_pin === 'function' && d.pin) render_pin(d.pin);
            if (!d.messages || !d.messages.length) { box.innerHTML = '<div style="padding:5px;color:#555;">پیامی یافت نشد</div>'; return; }
            var html = '';
            for (var i = 0; i < d.messages.length; i++) {
                var m = d.messages[i];
                // skip any system messages just in case
                if (m.isSystem === 1) continue;
                var av = lastHistoryAvatar(m.avatar);
                // Convert smiley codes (sN) into their gif images
                function smilify(str) {
                    return (str||'').replace(/\(s(\d{1,4})\)/gi, '<img src="theme/images/smilies/(s$1).gif" alt="(s$1)" style="vertical-align:middle;">');
                }
                // Detect reply messages (stored as JSON in text).
                // Strip surrounding <i>/<b> (added when the sender had italic/bold
                // formatting on) and whitespace/BOM before checking for JSON.
                var rawBody = (m.text||'').replace(/\r|\n/g, '');
                var bodyText = rawBody.replace(/﻿/g, '').replace(/^<i>|<\/i>$|<b>|<\/b>/gi, '').trim();
                var replyBox = '';
                var rname = '';
                var rmsg = '';
                if (bodyText.charAt(0) === '{') {
                    try {
                        var j = JSON.parse(bodyText);
                        if (j && j.action === 'reply') {
                            rname = j.uname || '';
                            rmsg = j.umsg || '';
                            bodyText = j.mymsg || '';
                            replyBox = '<div style="background:#e8ecf8;border-left:3px solid #809fff;border-radius:6px;padding:3px 6px;margin-bottom:4px;font-size:11px;color:#5a6a9a;direction:rtl;text-align:right;">' +
                                '<b>' + rname + '</b> › ' + smilify(rmsg) +
                                '</div>';
                        }
                    } catch(e) {}
                }
                bodyText = smilify(bodyText);
                html +=
                    '<div style="direction:ltr;text-align:left;clear:both;margin:6px 4px;display:flex;align-items:flex-start;gap:8px;">' +
                        '<a target="_blank" href="' + (m.user_id ? '/profile/' + m.user_id : '#') + '" style="flex-shrink:0;line-height:0;"><img src="' + av + '" style="width:38px;height:38px;border-radius:50%;object-fit:cover;border:1px solid #ccd;cursor:pointer;" alt=""></a>' +
                        '<div style="max-width:70%;background:#f2f2ff;border:1px solid #d6d6ee;border-radius:12px;border-top-left-radius:4px;padding:6px 10px;box-shadow:0 1px 2px rgba(0,0,0,0.08);word-break:break-word;">' +
                            '<div style="font-weight:bold;font-size:12px;color:#062B78;margin-bottom:2px;">' + (m.username||'') + '</div>' +
                            replyBox +
                            '<div style="font-size:13px;line-height:1.5;">' + bodyText + '</div>' +
                            '<div style="font-size:10px;color:#888;text-align:left;margin-top:3px;">' + (m.time||'') + ' ' + (m.date||'') + '</div>' +
                        '</div>' +
                    '</div>';
            }
            box.innerHTML = html;
            box.style.display = 'block';
            box.setAttribute('data-loaded', '1');
            var ch = document.getElementById('chatinhalt');
            if (ch) {
                if (btn.parentNode) btn.parentNode.removeChild(btn);
                ch.scrollTop = ch.scrollHeight;
            }
        };
        xhr.onerror = function() { btn.textContent = 'نمایش 50 پیام آخر عمومی'; btn.disabled = false; };
        xhr.send('_=1');
    }
    function bindLast() {
        var btn = document.getElementById('loadLastBtn');
        if (btn && !btn._b) { btn.addEventListener('click', loadLast50); btn._b = true; }
    }
    if (document.readyState === "loading") document.addEventListener('DOMContentLoaded', bindLast);
    else bindLast();
})();
</script>
</body>
</html>
<?php $pmwlc = json_decode($options['pmwlc'], true); ?>
<?php if ($pmwlc['wlcchat'] == 'true') { 
include ("pages/wlcchat.php");
} ?>
