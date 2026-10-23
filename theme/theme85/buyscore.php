<?php if(!session('id')){header::Go(url('login'));exit();}
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo(site_title); ?> | خرید امتیاز</title>
		<script type="text/javascript" src="<?php echo(url('theme/js/jquery.js')); ?>"></script>
<?php
if(session('type') == 1)exit(RedError('مهمان اجازه خرید امتیاز ندارد'));
if($options['buyscore_status'] == 'true'){ ?>
<style>
body {
    background: none repeat scroll 0 0 #f1f1f1;
    direction: rtl;
    font-family: Tahoma;
    font-size: 12px;
    text-align: center;
}
#warp {
    border: 3px solid #f5f5f5;
    margin: 100px auto;
    width: 500px;
}
#header {
    background: none repeat scroll 0 0 #e0e0e0;
    padding: 10px 0;
    text-align: center;
}
#content {
    background: none repeat scroll 0 0 #ebebeb;
}
#header h2 {
    color: #555555;
    font-family: b;
    margin: 0;
    padding: 0 0 20px;
}
#header h5 {
    color: #888888;
    margin: 0;
}
#text {
    display: inline;
    float: right;
    font-family: Tahoma;
    margin: 60px 60px 40px 0;
    text-align: right;
    width: 500px;
}
#loading {
    background: none repeat scroll 0 0 rgba(0, 0, 0, 0.60);
    display: block;
    height: 100%;
    position: fixed;
    top: 0;
    width: 100%;
}
#loading img{
    margin: auto;
    position: relative;
    top: 50%;
}
.resualt {
    font-size: 14px;
    margin: 0 40px;
    width: 300px;
}

.resualt li {
    background: none repeat scroll 0 0 #f7f7f7;
    border: 1px solid #fff;
    list-style-type: none;
    padding: 5px;
}
.resualt a {
    background: none repeat scroll 0 0 #4bbce8;
    border: 1px solid #2f96be;
    color: #ffffff;
    cursor: pointer;
    display: block;
    margin: 0 auto;
    padding: 3px;
    text-align: center;
    width: 60px;
}
.resualt span {
    color: #ff0000;
}
#money-input{
    padding: 2px 2px 2px 30px;
    width: 120px;
}
#buyform span{
    margin: -2px -30px -2px -2px;
}

</style>

	</head>
<body>
	<div id="loading" style="display: none;"><img src="<?php echo(theme::url('images/ajax-search.gif?223')); ?>"></div>
	<div id="warp">
		<div id="header">
			<h2>خرید امتیاز</h2>
			<h5 id="des"><?php echo "رقم مورد نظر خود را وارد کنید. هر {$options['pay_bank']} تومان معادل 1 امتیاز میباشد"; ?></h5>
		</div>
		<div id="content">
			<div id="text">
<form method="post" id="buyform">
<input name="money" type="text" id="money-input"><span>تومان</span>
<input type="submit" value="ارسال">
</form>
<script type="text/javascript">
var $money = 0;
$( "#buyform" ).submit(function( event ) {
$("#loading").show();
setTimeout(function() {
$("#loading").hide();
}, 1000);
 var $form = $( this )
$money = parseInt($form.find( "input[name='money']" ).val());
$( "#des" ).text( "آیا از پرداخت مبلغ مورد نظر مطمئن هستید ؟" );
$( "#text" ).html( "<div class=\"resualt\"><li><span>مبلغ: </span>"+$money+" تومان</li><li><a id=\"pay\" onclick=\"buy();\">پرداخت</a></li></div>" );
    return false;
});
function buy() {
$("#loading").show();
    $.ajax({
        type: "POST",
        url: '<?php echo(url('ajax/buyscore')); ?>',
        data:{amount:$money},
        success:function(data){
                                    $("#loading").hide();
                                    if(data.search("http://")==0 || data.search("https://")==0){
                                        $("#text").html("<div class=\"resualt\"><li>در حال انتقال به بانک ...</li></div>");
                                        redirect(data);
                                    }else{
                                        alert(data);
                                        setTimeout(function(){
                                            var $form=$("<form />");
                                            $form.attr("action","");
                                            $form.attr("method","post");
                                            $form.append('<input type="hidden" name="payid" value="'+data+'" />');
                                            $('body').append($form);
                                            $form.submit();
                                        },1000);
                                    }
                                }
                            });
                        }
                        function redirect(url){
                            window.location=url;
                        }
</script>
		</div>
		<div style="clear:both;"></div>
	</div>
</body>
<?php }else{
	echo '<center><b><font color=red>با عرض پوزش ! مدیر چت روم این سیستم را غیر فعال کرده است</font></b></center>';
}?>
</html>