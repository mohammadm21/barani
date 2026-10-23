<?php
include('header.php');
include('sidebar.php');
?>
<script type="text/javascript">

    function edit() {
        $.ajax({
            type: "POST",
            url: "<?php echo(url('ajax/admin.settings.texts')); ?>",
            data: $('#formeditsettings').serialize(),
            success: function (data) {
                switch (data) {
                    case('ok'):
                        $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'ذخیره شد'});
                        break;
                    default:
                        alert(data);
                        break;
                }
            }
        });
    }

</script>
<?php
if(isset($_POST['openlock'])){
if(md5($_POST['code_password'])==$options['security_password']){
$_SESSION['yaser']=$options['security_password'];
}
}
?>
<?php
if($_SESSION['yaser']==$options['security_password']){
?>
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">تنظیمات</h1>
    <div class="grid_12">
        <div class="grid_12">
            <form class="box  validate" id="formeditsettings">
                <div class="header">
                    <h2>تنظیمات متون</h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>متن باکس بالای چت روم:</strong></label>
                        <div><input type="text" name="up_box" value="<?php echo($options['up_box']) ?>"/></div>
                    </div>
                    <div class="row">
                        <label><strong>متن بالای فیلد عمومی:</strong></label>
                        <div><input type="text" name="down_box" value="<?php echo($options['down_box']) ?>"/></div>
                    </div>
                    <div class="row">
                        <label><strong>متن داخل فیلد عمومی:</strong></label>
                        <div><input type="text" name="omomi_fild" value="<?php echo($options['omomi_fild']) ?>"/></div>
                    </div>
                    <div class="row">
                        <label><strong>پیام ورودی هنگام ورود کاربر به چت روم در خصوص :</strong></label>
                        <div><input type="text" name="texttouserpv" value="<?php echo($options['texttouserpv']) ?>"/></div>
                    </div>
                </div>
            </form>
            <center> <medium style="color:red">پس از ویرایش ممکن است به طور طبیعی حدودا یک دقیقه اعمال تغییرات طول بکشد</medium><br><button class="button blue flat block" onclick="edit();">ویرایش</button></center>
        </div>
    </div>
</section>
<?php
}else{
?>
<center>
<br>
<form action="" method="POST">
<div style="background-image:url('/theme/adminpanel/img/files.png');height: 300px;width: 300px;margin-top:80px;margin-right:180px;color:#fff;border-radius: 5px;"><br><br>
<br><br><br><br><br><br><br><br>
<input name="code_password" value="" type="text">
<button name="openlock" class="grey cancel">باز کن</button>
</div>
<p><b>رمز امنیتی را وارد کنید</b></p>
<p><b>این رمز از پنل اضطراری قابل تغییر است</b></p>
</form>
</center>
<?php } ?>
<?php include 'footer.php'; ?>