<!DOCTYPE html>
<link rel="stylesheet" href="<?php echo(theme::url('css/single.css', 'system')); ?>" type="text/css" media="all">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>تبادل اتوماتیک</title>
<script type="text/javascript" src="<?php echo(theme::url('js/jquery.js', 'system')); ?>"></script>
<script type="text/javascript" src="<?php echo(url('visit')); ?>"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('#content form').submit(function(){
        $.ajax({
            type: "POST",
            url: '<?php echo(url('ajax/links')); ?>',
            data:$(this).serialize(),
            success: function(data) {
                switch(data){
                    case('ok'):alert('با موفقیت ثبت شد');break;
                    case('noaddress'):alert('لطفا آدرس وبسایت خود را وارد کنید');break;
                    case('nolink'):alert('لطفا ابتدا لینک مارا اضافه کنید');break;
                    case('noaccsess'):alert('سرور نمیتواند به وبسایت شما متصل شود');break;
                    case('captcha'):alert('کد امنیتی اشتباه وارد شده');break;
                    default:alert(data);break;
                }
            }
        });
        return false;
    })
})


</script>
<div id="boxnew"></div>
<div id="container">
    <center><a id="logo-en" href="<?php echo(url()) ?>"></a></center>
    <div id="main">
        <div id="header"><p><a href="<?php echo(url('links')); ?>">تبادل اتوماتیک</a></p></div>
        <div id="content">
        <p>ابتدا لینک مارا در وبلاگ و یا وبسایت خود ثبت کنید و سپس از طریق فرم زیر لینک خود را ثبت کنید.عنوان لینک: <a href="<?php echo(url()); ?>"><?php echo(site_title); ?></a></p>
        <p style="color: #FF4A4A">توجه: درصورتی لینک مارا در وبسایت خودقرار ندهید این تبادل لینک انجام نخواهد گرفت و درصورت حذف، لینک شمانیز حذف میشود</p>
        <p>عنوان لینک: <?php echo(site_title); ?></p>
        <p>آدرس لینک: http://<?php echo($site_url[0]); ?></p>
        <form method="POST" autocomplete="off">
            <p>عنوان وبسایت شما: <input type="text" name="title"></p>
            <p>آدرس وبسایت شما: <input type="text" name="address" dir="ltr" value="http://www."></p>
            <p><label>کد امنیتی: <img alt="کد انسان یاب!" src="<?php echo(url('captcha/100/38')); ?>" style="width: 100px;height: 38px;"></label> <input type="text" style="width: 100px;" name="captcha" maxlength="5"></p>
            
            <p style="text-align: center;">
            <input type="submit" value="ارسال">
            </p>
        </form>
        <br><br>
        </div>
    </div><br><br>
</div>
<div id="space"></div>
<div id="w3">
    <a href="http://validator.w3.org/check?uri=referer"><img src="http://www.w3.org/Icons/valid-html401" alt="Valid HTML 4.01 Transitional" height="31" width="88"></a>
    <a href="http://jigsaw.w3.org/css-validator/check/referer"><img src="http://jigsaw.w3.org/css-validator/images/vcss" alt="!معتبر CSS" ></a>
</div>