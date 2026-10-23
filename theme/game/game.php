<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo(site_title); ?> | بازی آنلاین </title>
<meta http-equiv="Pragma" content="No-Cache">
<meta http-equiv="Cache-Control" content="No-Cache,Must-Revalidate,No-Store">
<meta http-equiv="Expires" content="0">
<meta name="MSSmartTagsPreventParsing" content="True">
<meta http-equiv="ImageToolbar" content="No">
<meta content="<?php echo($options['chat_keywords']); ?>" name="keywords">
<meta content="<?php echo($options['chat_description']); ?>" name="description">
<meta name="distribution" content="global"> 
<script type="text/javascript" src="<?php echo(url('visit')); ?>"></script>
<meta name="robots" content="index, follow"> 
<meta name="rating" content="general">  
<META NAME="language" CONTENT="fa_IR"> 
<META NAME="Title" CONTENT="بازی آنلاین"> 
<link rel="stylesheet" href="<?php echo(theme::url("css/game.css", $options['chat_game_theme'])); ?>" type="text/css" media="all">
</head>
<body>
<?php if(chatroom::checkPermission('game')){ ?>
<center><h1>بازی آنلاین</h1></center>
<center style="margin-top: 120px;">
<object id="obj1" width="1000" height="618"  classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0">
    <param name="quality" value="High">
    <param name="src" value="<?php  echo(theme::url($gamefile)); ?>"> 
    <embed id="obj1" width="1000" height="618" type="application/x-shockwave-flash" src="<?php  echo(url($gamefile)); ?>" quality="High"></embed>
</object> 
</center>
<?php }else{ ?>
<h2 style="text-align: center;text-shadow: 0 0 10px #333;font-family: Tahoma;direction: rtl;color: #004040">این قابلیت برای گروه کاربری شما غیرفعال شده است!</h2>

<?php } ?>
</body>
</html>