<!DOCTYPE html>
<html>
    <head>
        <title><?php echo site_title; ?></title>       
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="<?php echo $options['chat_description']; ?>"/>
        <meta name="keywords" content="<?php echo $options['chat_keywords']; ?>"/>
        <?php theme::get_header('m'); ?>
        <style type="text/css">
            div {
                direction: rtl;
            }
        </style>
        <script>
            $(document).ready(function () {
                $("input[type=submit]").click(function (e) {
                    var username = $("#username").val();
                    var password = $("#password").val();
                    if (username == '')
                    {
                        e.preventDefault();
                        alert("لطفا نام خود را وارد کنید");
                    }
                });
            });


        </script>
    </head>
    <body>
        <div data-role="page">   
            <div data-role="header">
                <h1>نسخه موبایل <?php echo site_title; ?></h1>
            </div>  
            <div data-role="main" class="ui-content">  
                <form id="form" onsubmit="return login(this);">
                  <input type="hidden" name="token" value="<?=token2();?>" />
                              <input type="hidden" name="<?=rrobot();?>" value="<?=mrobot();?>"/>
                              <input type="hidden" name="<?=trobot();?>" value="<?=rtoken();?>"/>
                               <input type="hidden" name="<?=token2();?>" value="<?=gtoken();?>"/>
                    <label for="username">نام کاربری : <span>*</span></label>
                    <input type="text" name="username" id="username" placeholder="اسم شما">
                    <div id="lay_pw" style="display:none">
                        <label for="password">رمز ورود: <span>*</span></label>
                        <input type="password" id="password" name="password" placeholder="رمز شما"/>
                    </div>
<br><br>
  <div id="lay_hide" style="display:none">
                        <input type="checkbox" name="hide" value="1"> <span style="color:#fff" class="hide">ورود مخفی </span>
                    </div>
<div id="lay_security_question" style="display:none">
                        <label for="security_answer">سوال امنیتی: <span>*</span></label>
                        <input type="text" id="security_answer" name="security_answer" placeholder="جواب امنیتی شما"/>
                    </div>
                   <select class="select" style="margin-top:5px; position:relative;" id="gender" name="gender"> 
                            <option value="1">پسر هستم</option>
                            <option value="2">دختر هستم</option>
                        </select>
                   
                   <fieldset data-role="controlgroup">
                        <legend>اگه عضو نیستید و رمز ندارید، ناراحت نباشید شما باید اسم خود را تغییر دهید تا بدون رمز وارد شوید .مثلا به آخر اسم عدد اضافه کنید مثال : شما طرح 1394</legend>
                    </fieldset>
                    <center><input type="submit" name="go" title="<?php echo site_title; ?>" value="ورود" data-inline="true" data-theme="b">	</center>  
                </form>
            </div>
            <p>تعداد کاربران انلاین : <b><font color="#66CC00" face="tahoma"><?php echo $sql->getValue("onlines", 'count(*)'); ?></font></b> نفر</p>
			<div align="center">
        
    </div>
	<div align="center">
        <span style="font-size:13px;"><h1><a href="<?php echo(url()); ?>" alt="<?php echo($options['h1_code']) ?>" title="<?php echo($options['h1_code']) ?>"><?php echo($options['h1_code']) ?></a></h1></span>
        <span style="font-size:12px;"><h2><a href="<?php echo(url()); ?>" alt="<?php echo($options['h2_code']) ?>" title="<?php echo($options['h2_code']) ?>"><?php echo($options['h2_code']) ?></a></h2></span>
        <span style="font-size:8px;"><h3><a href="<?php echo(url()); ?>" alt="<?php echo($options['h3_code']) ?>" title="<?php echo($options['h3_code']) ?>"><?php echo($options['h3_code']) ?></a></h3></span>
        <span style="font-size:8px;"><h4><a href="<?php echo(url()); ?>" alt="<?php echo($options['h4_code']) ?>" title="<?php echo($options['h4_code']) ?>"><?php echo($options['h4_code']) ?></a></h4></span>
        <span style="font-size:8px;"><h5><a href="<?php echo(url()); ?>" alt="<?php echo($options['h5_code']) ?>" title="<?php echo($options['h5_code']) ?>"><?php echo($options['h5_code']) ?></a></h5></span>
    </div>
    <div align="center">
        <p style="text-align:center;margin-top:15px;">
            <span style="font-size:8px;">
                <span style="font-family:Byekan;color:#ddd">کلمات چتی : </span>
                <strong>
                    <span style="font-family:Byekan;"> <a href="<?php echo(url()); ?>" alt="<?php echo($options['link1_code']) ?>" title="<?php echo($options['link1_code']) ?>"><?php echo($options['link1_code']) ?></a> , <a href="<?php echo(url()); ?>" alt="<?php echo($options['link2_code']) ?>" title="<?php echo($options['link2_code']) ?>"><?php echo($options['link2_code']) ?></a> , <a href="<?php echo(url()); ?>" alt="<?php echo($options['link3_code']) ?>" title="<?php echo($options['link3_code']) ?>"><?php echo($options['link3_code']) ?></a> , <a href="<?php echo(url()); ?>" alt="<?php echo($options['link4_code']) ?>" title="<?php echo($options['link4_code']) ?>"><?php echo($options['link4_code']) ?></a> , <a href="<?php echo(url()); ?>" alt="<?php echo($options['link5_code']) ?>" title="<?php echo($options['link5_code']) ?>"><?php echo($options['link5_code']) ?></a> , <a href="<?php echo(url()); ?>" alt="<?php echo($options['link6_code']) ?>" title="<?php echo($options['link6_code']) ?>"><?php echo($options['link6_code']) ?></a> , <a href="<?php echo(url()); ?>" alt="<?php echo($options['link7_code']) ?>" title="<?php echo($options['link7_code']) ?>"><?php echo($options['link7_code']) ?></a> , <a href="<?php echo(url()); ?>" alt="<?php echo($options['link8_code']) ?>" title="<?php echo($options['link8_code']) ?>"><?php echo($options['link8_code']) ?></a> , <a href="<?php echo(url()); ?>" alt="<?php echo($options['link9_code']) ?>" title="<?php echo($options['link9_code']) ?>"><?php echo($options['link9_code']) ?></a> , <a href="<?php echo(url()); ?>" alt="<?php echo($options['link10_code']) ?>" title="<?php echo($options['link10_code']) ?>"><?php echo($options['link10_code']) ?></a> , <a href="<?php echo(url()); ?>" alt="<?php echo($options['link11_code']) ?>" title="<?php echo($options['link11_code']) ?>"><?php echo($options['link11_code']) ?></a> , <a href="<?php echo(url()); ?>" alt="<?php echo($options['link12_code']) ?>" title="<?php echo($options['link12_code']) ?>"><?php echo($options['link12_code']) ?></a> , <a href="<?php echo(url()); ?>" alt="<?php echo($options['link13_code']) ?>" title="<?php echo($options['link13_code']) ?>"><?php echo($options['link13_code']) ?></a> , <a href="<?php echo(url()); ?>" alt="<?php echo($options['link14_code']) ?>" title="<?php echo($options['link14_code']) ?>"><?php echo($options['link14_code']) ?></a> , <a href="<?php echo(url()); ?>" alt="<?php echo($options['link15_code']) ?>" title="<?php echo($options['link15_code']) ?>"><?php echo($options['link15_code']) ?></a> , <a href="<?php echo(url()); ?>" alt="<?php echo($options['link16_code']) ?>" title="<?php echo($options['link16_code']) ?>"><?php echo($options['link16_code']) ?></a> , <a href="<?php echo(url()); ?>" alt="<?php echo($options['link17_code']) ?>" title="<?php echo($options['link17_code']) ?>"><?php echo($options['link17_code']) ?></a>
                    </span>
                </strong>
            </span>
        </p>
    </div>
		
            <div data-role="footer">
                <h2>Designed BY <span id="yellow">Tarhpardaz</span>.oRg</h2>
            </div>
        </div>

    </body>
</html>