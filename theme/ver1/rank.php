<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>امتیازات</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?php echo(url('theme/ver1/css/rank.css')); ?>" type="text/css">
<script type="text/javascript" src="<?php echo(url('theme/ver1/js/jquery.js')); ?>"></script>
</head>
<body>
<a href="<?php echo url(); ?>" id="logo"></a>
<div id="countiner">
    <div id="box" style="overflow: auto;">
        <table class="tabellist" cellpadding="0" cellspacing="1" width="500" style="width: 550px;" align="center">
            <tr class="tr_title">
                <th>رتبه</th>
                <th>امتیاز</th>
                <th>نام کاربری</th>
                <th>نام نمایشی</th>
                <th>مدت حضور</th>
            </tr>
            <?php
            $x = 0;
            $query = db::query("SELECT `id`, `username`, `name`,`onlinetime`,`score` FROM {prefix}users WHERE `type` != '1' and `score` != '0'  ORDER BY `score` DESC");
            while($row = mysqli_fetch_array($query)){
                $x++;
                $url = url("profile/$row[0]/$row[2]");
            ?>
            <tr class="tr_line">
                <td style="text-align: center;"><?php echo($x); ?></td>
                <td style="text-align: center;"><?php echo($row['score']); ?></td>
                <td style="text-align: center;"><a href="<?php echo($url); ?>"><?php echo($row['username']); ?></a></td>
                <td style="text-align: center;"><a href="<?php echo($url); ?>"><?php echo($row['name']); ?></a></td>
                <td style="text-align: center;"><?php echo($row['onlinetime']); ?>ثانیه</td>
            </tr>
            <?php
            }
            mysqli_free_result($query);
            ?>
        </table><br><br>
        <center><a href="<?php echo(url('chat')); ?>"><input type="button" value="برگشت"></a></center>
    </div>
</div>
</body>
</html>