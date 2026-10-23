<?php if(chatroom::checkPermission('chat_news')){ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?php echo(site_title); ?> | اخبار</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Content-Language" content="fa">

    <link href="<?php echo(theme::url('css/news.font.css')); ?>" rel="stylesheet" />
</head>
<center>
<div class="mod_type" style="color:white;font-family:B Yekan;font-size:19px;width:98%;height:30px;background-color:rgba(0,0,0,0.5);">
<span id="theTicker"> </span>
<script type="text/javascript">
var theSummaries = new Array();
var theSiteLinks = new Array();
<?php
$query = db::query("SELECT * FROM ahyir_news WHERE `active`='1'");
  $num_results = $query->num_rows;
    for ($i=0; $i <$num_results; $i++)
    	{
     $row = $query->fetch_assoc();
?>
theSummaries[<?php echo $i; ?>]='<?php echo $row['text']; ?>';
<?php
}
?>
</script>
<script src="/theme/js/news.js"></script>
</div>
</center>
<?php }else{ ?>
<h3 style="text-align: center;text-shadow: 0 0 10px red;padding-top:20px;">این قابلیت برای گروه کاربری شما غیرفعال شده است!!</h3>
<?php } ?>
