<!DOCTYPE html>
<html dir=rtl lang=fa>
<html>
<head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
        <title><?php echo(site_title); ?></title>
		<meta content="<?php echo($options['chat_keywords']); ?>" name="keywords"/>
		<meta content="<?php echo($options['chat_description']); ?>" name="description"/>
		<meta name="distribution" content="global"/>
		<meta name="copyright" content="copyright (c) by <?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?> >   
        <meta name="robots" content="index, follow"/>
        <meta name="revisit-after" content="1 days"/>
        <meta name="rating" content="general"/>
        <meta http-equiv="Cache-Control" content="no-cache"/>
        <meta http-equiv="Pragma" content="no-cache"/>
        <meta http-equiv="imagetoolbar" content="no"/>
	    <link rel="shortcut icon" href="/Favicon.png"/>
		<link rel="apple-touch-icon" href="/apple.png" />
		<link rel="icon" type="image/png" sizes="192x192"  href="/android.png">
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
		


	     <?php theme::get_header('login'); ?>
	

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-116692701-5"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-116692701-5');
</script>

<style>







@media screen and (max-width:1000px){
.pic {
    clear: both;
    position: reletive;
    background-image: url(/theme/theme85/images/picc.png);
    width: 100%;
    height: 118px;
    margin: auto;
    background-size: 100%;
    margin-bottom: 10px;
}
.main {font-size:9pt;position:relative;;text-align:center;width:90%;max-width:90%;margin-top:10px;margin-bottom:10px;max-height:350;}
.footer {display:none; }
.menu { display:none;} 
.pm { display:block; text-align:center;height:auto;}
.login{margin-right: 40px;}
span.on2{display:block;width:50%;padding:5px;}
#admincon{
  visibility: visible;
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

<span class="pic"></span>


<div class="box">

<div class="login"><span class="material-icons">assignment_late</span>از کـادر زيـر ميتوانيد وارد چـت شـويـد</div>


</div>
     
      
              <div class="inner">
			
                <form id="form" onsubmit="return login(this);">
				<input type="hidden" name="token" value="<?=token2();?>" /><input type="hidden" name="<?=rrobot();?>" value="<?=mrobot();?>" /><input type="hidden" name="<?=trobot();?>" value="<?=rtoken();?>" /><input type="hidden" name="<?=token2();?>" value="<?=gtoken();?>" />

	
					<input type="text" name="username" style="position:relative;"  class="tf-user" maxlength="<?php echo($options['chat_maxlengthname']); ?>" placeholder="اسـم خـوشـمـلـتـون">
                    
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
  <span style=";background:url(http://s6.uplod.ir/i/00707/n4bcfmzx11wh.gif);font-weight:bold" class="on"> افراد آنلاين :  <font color="#FFFB00"> <?php echo(db::onlines('onlines', "`hide`='0'")); ?> </font></span>
  </div>

<div class="clear" style="clear:both;"></div>
<!--Footer-->
<br>
<center>
    <span style="background:url(http://s6.uplod.ir/i/00707/n4bcfmzx11wh.gif);font-weight:bold;" class="on2"> افراد آنلاين :  <font color="#FFFB00"> <?php echo(db::onlines('onlines', "`hide`='0'")); ?> </font></span>
</center>
<br><br>
<div class="footer">

<div class="error"> اين قالب <font color="#EE8C8C"><?php echo site_title; ?></font>  ميباشد و هرگونه کپي برداري پيگرد قانوني در بر دارد </div><div class="error cop"> &nbsp;&nbsp طراحي توسط : <a href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>" rel="nofollow" style="color:#EE8C8C;">بارانی  طرح </a></div>
<br><br>
             <?php include ("pages/logofooter.php"); ?>  
<br><br><br><br>
  <?php include ("pages/seo.php"); ?> 
<div style="display:none">
<?php include ("theme/js1/js1.php"); ?><center> 
</div>  
    </body>
</html><?php $wlclogin = json_decode($options["wlclogin"], true); if ($wlclogin["wlclogin"] == "true") {include ("pages/wlclogin.php");} ?>