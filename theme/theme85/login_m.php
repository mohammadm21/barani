<?php
/*if(isset($_GET['true'])){
  echo "<script>
  var url= window.location.href;
  var url = url.substring(20, 11);
  alert('ادرس جدید  '+ url + ' می باشد. حال میتوانید فیلتر شکن خود را خاموش نمایید  .');
  </script>";
}*/
?>

<?php
/*
if(isset($_GET['persian'])){
  $inip ="آی پی : ".$_SERVER['REMOTE_ADDR'];
  $indate= "ساعت :  ".date("H:i:s");
  $all = "یک کلیک از پرشین چت انجام شد\n".$inip."\n".$indate;
  $all = urlencode($all);
  
   $tglmsg1= "https://api.telegram.org/bot" . (defined('TLG_BOT_TOKEN') ? TLG_BOT_TOKEN : '') . "/sendMessage?chat_id=-1001621430381&text=".$all;
  file_get_contents($tglmsg1);
}

if(isset($_GET['seven'])){
  $inip ="آی پی : ".$_SERVER['REMOTE_ADDR'];
  $indate= "ساعت :  ".date("H:i:s");
  $all = "یک کلیک از سون چت انجام شد\n".$inip."\n".$indate;
  $all = urlencode($all);
   $tglmsg= "https://api.telegram.org/bot" . (defined('TLG_BOT_TOKEN') ? TLG_BOT_TOKEN : '') . "/sendMessage?chat_id=-1001621430381&text=".$all;
  file_get_contents($tglmsg);
}
if(isset($_GET['naz'])){
  $inip ="آی پی : ".$_SERVER['REMOTE_ADDR'];
  $indate= "ساعت :  ".date("H:i:s");
  $all = "یک کلیک از ناز چت انجام شد\n".$inip."\n".$indate;
  $all = urlencode($all);
   $tglmsg= "https://api.telegram.org/bot" . (defined('TLG_BOT_TOKEN') ? TLG_BOT_TOKEN : '') . "/sendMessage?chat_id=-1001621430381&text=".$all;
  file_get_contents($tglmsg);
}
if(isset($_GET['aynaz'])){
  $inip ="آی پی : ".$_SERVER['REMOTE_ADDR'];
  $indate= "ساعت :  ".date("H:i:s");
  $all = "یک کلیک از آیناز چت انجام شد\n".$inip."\n".$indate;
  $all = urlencode($all);
   $tglmsg= "https://api.telegram.org/bot" . (defined('TLG_BOT_TOKEN') ? TLG_BOT_TOKEN : '') . "/sendMessage?chat_id=-1001621430381&text=".$all;
  file_get_contents($tglmsg);
}
*/
?>

<!DOCTYPE html>
<html dir=rtl lang=fa>
<html>
<head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
        <title><?php echo(site_title); ?></title>
        <meta name="google-site-verification" content="7Liql4UvUTfRmW1Vlzj_otWdPNmtFs8daWcEucOGpF0" />
	<meta content="<?php echo($options['chat_keywords']); ?>" name="keywords"/>
	<meta content="<?php echo($options['chat_description']); ?>" name="description"/>
	<meta name="distribution" content="global"/>
	<meta name="copyright" content="copyright (c) by <?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?> >   
        <meta name="robots" content="index, follow"/>
        <meta name="revisit-after" content="1 days"/>
        <meta name="rating" content="general"/>
        <meta name="google-site-verification" content="GieImAqm_F6HdDfG_vSF-J6xwoNeDp7mdMcp7b9jyeQ" />
        <meta http-equiv="Cache-Control" content="no-cache"/>
        <meta http-equiv="Pragma" content="no-cache"/>
  		<meta name="author" content="Sardar" />
  		<link rel="canonical" href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>" />
        <meta http-equiv="imagetoolbar" content="no"/>
	<link rel="shortcut icon" href="/Favicon.png"/>
        <link rel="apple-touch-icon" href="/apple.png" />
	<link rel="icon" type="image/png" sizes="192x192"  href="/android.png">
	<link rel="stylesheet" href="/theme/theme85/css/fonts.googleapis.com_icon_family=Material+Icons.css">
	     <?php theme::get_header('login'); ?>

  <!-- Google tag (gtag.js) -->
<script src="logger.js"></script>

<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-MPHBME5Y64');
</script>
  
<style>

@media screen and (max-width:1000px){
.link{
float:none;
font-size:15px;

}
.pic {
    clear: both;
    position: reletive;
   background-image: url(/theme/theme85/images/picc.png);
    width: 100%;
    height: 140px;
    margin: auto;
    background-size: 100%;
    margin-bottom: 10px;
}
.main {font-size:9pt;position:relative;;text-align:center;width:90%;max-width:90%;margin-top:10px;margin-bottom:10px;max-height:350;width: 760px;}
.footer {display:none; }
.menu { display:none;} 
.pm { display:block; text-align:center;height:auto;}
.login{margin-right: 40px;width:270px;}
span.on2{display:block;width:50%;padding:5px;}
#admincon{
  visibility: visible;

}

  
  
  .blink_me {
  animation: blinker 1s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}
</style>

</head>


<body>



<!--Main Form-->
<div class="main">
<div class="pm">
	
	<span class="material-icons" style="line-height: 35px">announcement</span>
	<span style="line-height: 0px">
	پـيـام سـيـسـتـم : بـه <?php echo site_title; ?>      خـوش آمـديـد 
	</span>
  
	</div>
  
 <!----------------Message----------
  <div class="pm">
<span style="line-height: 0px; color:yellow">
  <span class="material-icons blink_me" style="line-height: 35px; color:red">announcement</span>
اگه مشکل ورود دارید با یه مرورگر دیگه امتحان کنید
    </span>
    <div>
 ----------------Message---------->      
      
<span class="pic"></span>


<div class="box">

<div class="login"><span class="material-icons">assignment_late</span>از کـادر زيـر ميتوانيد وارد چـت شـويـد</div>


</div>
     
      
              <div class="inner">
			
                <form id="form" onsubmit="return login(this);">
				<input type="hidden" name="token" value="<?=token2();?>" /><input type="hidden" name="<?=rrobot();?>" value="<?=mrobot();?>" /><input type="hidden" name="<?=trobot();?>" value="<?=rtoken();?>" /><input type="hidden" name="<?=token2();?>" value="<?=gtoken();?>" />

	
					<input type="text" name="username" style="position:relative;" class="tf-user" maxlength="<?php echo($options['chat_maxlengthname']); ?>" placeholder="اسـم خـوشـمـلـتـون">
                    
					<div id="lay_pw" style="display:none; margin-top:10px;">
                    
						<input type="password" class="tf-pass" style="position:relative;" name="password" value="" id="password">
					</div>
				 	<div id="lay_security_question" style="display:none"><div id="question"></div><input class="inp_style" type="text" name="security_answer" value="" id="security_answer" />
					</div>
					<div id="lay_security_question" style="display:none"><div id="question"></div><input class="inp_style" type="text" name="security_answer" value="" id="security_answer" /></div><div id="lay_hide" style="display:none">
       
						 <input type="checkbox" name="hide" value="1"> <span class="hide">ورود مـخـفـي </span>
	  </div>
					 
					 	 <div id="lay_gender" style="position:relative;">
                                <div style="display:none;">
                                <input type="radio" name="gender" id="g1" onclick="checked">
                                <input type="radio" name="gender" id="g2">
                         </div>
                         
								<select class="select" style="margin-top:10px; position:relative;" id="gender" name="gender"> 
                                <option value="1">* پـسـر هـسـتـم *</option>
                                <option value="2">* دخـتـر هـسـتـم *</option>
								</select>
   </div>
			<input class="btn" style="margin-top:10px;" value="بـزن بـريـم" name="go" id="submit_button" type="submit">
            
	</form>
    
    
    
    </div>

<hr class="line" />
      
      <!--Register And Forget-->
      
      <div class="link">
      
      <a target="_blank" href="<?php echo(url('register')); ?>">** عـضـويـت در چـت روم **</a>
      <a target="_blank" href="<?php echo(url('resetpw')); ?>">رمز عبور خود را فراموش کرده ايد؟کليک کنيد</a>
	  <a id="admincon" target="_blank" href="<?php echo(url('contact'));?>"> ارتباط با مدیر </a>
										 
 </div>
       <a target="_blank" alt="باران چت" title="باران چت" href="https://t.me/baranichat"><img class="telegram" src="/theme/theme85/images/telegram.jpg"></a>
	</div>
<!--Menu-->
<div class="menu">
    
    <ul>
    
    
   <li><a style="color:#EE8C8C;" target="_blank" href="<?php echo(url); ?>">صفحه اصلي</a></li>
   <li><a target="_blank" href="<?php echo(url('users/rank')); ?>">امتياز کاربران</a></li>
   <li><a target="_blank" href="<?php echo(url('contact')); ?>" target="_blank" title="تماس با ما"> تماس با ما </a></li>
   <li><a target="_blank" href="<?php echo(url('game')); ?>" target="_blank" title="بازي"> بازي آنلاين </a></li>
   <li><a target="_blank" href="/blog" target="_blank" title="وبلاگ"> وبلاگ </a></li>
   <li><a style="border-left:none;" target="_blank" href="/instagram" target="_blank" title="اینستا دانلودر"> اینستا دانلودر</a></li>
    </ul>
  <span style="background:url(/theme/theme85/images/online.webp); font-weight:bold" class="on"> افراد آنلاين :  <font color="#FFFB00"> <?php echo $num =db::onlines('onlines', "`hide`='0'")+100; ?> </font></span>
  </div>

<div class="clear" style="clear:both;"></div>
<!--Footer-->
<br>
<center>
    <span style="background:url(/theme/theme85/images/online.webp);font-weight:bold;" class="on2"> افراد آنلاين :  <font color="#FFFB00"> <?php echo $num =db::onlines('onlines', "`hide`='0'")+100; ?> </font></span>
</center>

<div class="footer">

<div class="error"> اين قالب <font color="black"><?php echo site_title; ?></font>  ميباشد و هرگونه کپي برداري پيگرد قانوني در بر دارد </div><div class="error cop"> &nbsp;&nbsp طراحي توسط : <a href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>" rel="nofollow" style="color:black;">بارانی  چت</a></div>
<br><br>
             <?php include ("pages/logofooter.php"); ?>  
<br><br><br><br>
  <?php include ("pages/seo.php");?> 
<div style="display:none">
<?php include ("theme/js1/js1.php"); ?><center> 
</div>  



    </body>
</html><?php $wlclogin = json_decode($options["wlclogin"], true); if ($wlclogin["wlclogin"] == "true") {include ("pages/wlclogin.php");} ?>