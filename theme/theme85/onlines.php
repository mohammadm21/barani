<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Language" content="fa">

</head>
<?php $query = db::query("SELECT `name` FROM {prefix}onlines"); ?>
<body dir="rtl" style="text-align: right;font-family: Tahoma;font-size: 12px;">
    <p>
      
    <font color="#ff0000">آنلاین <?php echo (mysqli_num_rows($query));  ?> نفر</font>
	<br>
    <br />
       
</table>
</body>
</html>