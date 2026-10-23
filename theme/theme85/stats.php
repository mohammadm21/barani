<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html dir="rtl">
	<head>
		<title><?php echo(site_title); ?> | آمار ها</title>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
	</head>
<center>
    <body style="font-family:Tahoma;color:white;font-size:11px;line-height:130%">
     افراد آنلاین: <font style="font-weight:bold;color:#FF6600"><?php echo(db::onlines('onlines', "`hide`='0'")); ?></font> نفر
    <br>
    حاضرین آقا: <font style="font-weight:bold;color:#FF6600"><?php echo(db::onlines('onlines', "`gender`='1'")); ?></font> نفر
    <br>
    حاضرین خانم: <font style="font-weight:bold;color:#FF6600"><?php echo(db::onlines('onlines', "`gender`='2'")); ?></font> نفر
    <br>
    اعضاء حاضر: <font style="font-weight:bold;color:#FF6600"><?php echo(db::onlines('onlines', "`type`!='1'")); ?></font> نفر
     <br>
    تعداد مدیران: <font style="font-weight:bold;color:#FF6600"><?php $query1 = db::query("SELECT * FROM `{prefix}usertypes` WHERE admin='1'");$row1 = mysqli_fetch_assoc($query1);echo(db::num('users', "`type`='".$row1['id']."'")); ?></font> نفر
    <br>
    تعداد ناظر ارشدها: <font style="font-weight:bold;color:#FF6600"><?php $query2 = db::query("SELECT * FROM `{prefix}usertypes` WHERE admin='3'");$row2 = mysqli_fetch_assoc($query2);echo(db::num('users', "`type`='".$row2['id']."'")); ?></font> نفر
    <br>
    تعداد ناظرها: <font style="font-weight:bold;color:#FF6600"><?php $query3 = db::query("SELECT * FROM `{prefix}usertypes` WHERE admin='2'");$row3 = mysqli_fetch_assoc($query3);echo(db::num('users', "`type`='".$row3['id']."'")); ?></font> نفر
    <br>
    تعداد شکلک ها: <font style="font-weight:bold;color:#FF6600"><?php echo(db::num('smilies')); ?></font> عدد
    <br>
</center>
</body>
</html>