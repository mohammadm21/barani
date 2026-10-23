<?php
if (!chatroom::checkPermission('statos')) {
    exit('این قابلیت برای سطوح کاربری شما غیر فعال شده است');
}
?>
<title>تغییر استاتوس</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<link type="text/css" rel="stylesheet" href="<?php echo url("theme/fonts/fonts.css"); ?>">
<style>
    *{font-family: Byekan;text-align:center;direction:rtl}
    button,button[type="button"],button[type="submit"]{background: #007236;border:#007236 solid 1px;color:#fff;border-radius:5px;font-weight:700;text-shadow:#007236 0 1px;cursor:pointer;padding:5px 10px;}
    input[type="text"],form select,form input[type="password"]{vertical-align:middle;color:#007236;background: #fcfdfe;border-radius:3px;border:#b8c1cb solid 1px;color:#000;width:175px;direction:rtl;padding:10px 10px;}
    input[type="button"],input[type="submit"]{padding:10px;font-family: Byekan}
</style>
<form action="" method="POST">
    <input type="text" placeholder="استاتوس خود را وارد کنید" name="statos" value="">
    <input type="submit" value="تغییر استاتوس" name="send">
    <br>
    <font style="font-size:13px" ><b>کاربران عزیز لطفا از استاتوس های زشت و ناپسند و بر خلاف قوانین چت روم استفاده نکنید </b></font>
</form>
<?php
check_session();
$text = safe::string($_POST['statos']);
$text = str_replace('\d', 'd', $text);
if (isset($_POST['send'])) {
    if (session("silent") == "1") {
        exit("شما در چت روم خفه شده اید و اجازه استفاده از استاتوس را ندارید");
    }
    if (empty($text)) {
        exit('لطفا استاتوس خود را وارد کنید');
    }
    if (!empty($text)) {
        $inputs = Users::Inputs();
        $inputs['simg'] = 'status_notification';
        $inputs['stext'] = Message::badwords($text);
        Users::Inputs(null, $inputs);
        echo('استاتوس با موفقیت تغییر کرد');
    }
}
?>
