<?php
include('header.php');
include('sidebar.php');
$wlclogin = json_decode($options['wlclogin'], true);
?>
<style>
    .cpicker{
        display: inline-block;
        width: 25px !important;
    }
</style>
<script type="text/javascript">
 
    function edit() {
        $.ajax({
            type: "POST",
            url: "<?php echo(url('ajax/admin.settings.wlclogin')); ?>",
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
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">تنظیمات</h1>
    <div class="grid_12">
        <div class="grid_12">
            <form class="box  validate" id="formeditsettings">
                <div class="header">
                    <h2>تنظیمات پنجره خوش امدگویی بیرون</h2>
                </div>
                <div class="content tabbed">


<div class="row">
                        <label><strong>پنجره خوش امدگویی بیرون  :</strong> </label>
                        <div> فعال باشد<input type="checkbox" name="wlclogin" value="1" <?php if ($wlclogin['wlclogin'] == 'true') echo('checked'); ?>  /></div>
                    </div>
                    <div class="row">
                        <label><strong>سرتیتر:</strong></label>
                        <div><input type="text" name="head_Title" value="<?php echo($wlclogin['head_Title']) ?>"/></div>
                    </div>
    <div class="row">
                        <label><strong>رنگ سرتیتر:</strong></label>
                        <div><input type="color" name="headtitle_color"  value="<?php echo($wlclogin['headtitle_color']); ?>"/></div>
                    </div>
                    <div class="row">
                        <label><strong>تیتر:</strong></label>
                        <div><input type="text" name="Title" value="<?php echo($wlclogin['Title']) ?>"/></div>
                    </div>
    <div class="row">
                        <label><strong>رنگ تیتر:</strong></label>
                        <div><input type="color" name="title_color" value="<?php echo($wlclogin['title_color']); ?>"/></div>
                    </div>
                    <div class="row">
                        <label><strong>متن اول:</strong></label>
                        <div><input type="text" name="pm_one" value="<?php echo($wlclogin['pm_one']) ?>"/></div>
                    </div>
    <div class="row">
                        <label><strong>رنگ متن اول:</strong></label>
                        <div><input type="color" name="pmone_color" value="<?php echo($wlclogin['pmone_color']); ?>"/></div>
                    </div>
                    <div class="row">
                        <label><strong>ادرس اول چت روم :</strong></label>
                        <div><input type="text" name="Fst_url" value="<?php echo($wlclogin['Fst_url']) ?>"/></div>
                    </div>
    <div class="row">
                        <label><strong>رنگ ادرس اول چت روم:</strong></label>
                        <div><input type="color" name="Fsturl_color"  value="<?php echo($wlclogin['Fsturl_color']); ?>"/></div>
                    </div>
<div class="row">
                        <label><strong>ادرس دوم چت روم :</strong></label>
                        <div><input type="text" name="Sec_url" value="<?php echo($wlclogin['Sec_url']) ?>"/></div>
                    </div>
<div class="row">
                        <label><strong>رنگ ادرس دوم چت روم:</strong></label>
                        <div><input type="color" name="Securl_color"  value="<?php echo($wlclogin['Securl_color']); ?>"/></div>
                    </div>
<div class="row">
                        <label><strong>ادرس سوم چت روم :</strong></label>
                        <div><input type="text" name="thd_url" value="<?php echo($wlclogin['thd_url']) ?>"/></div>
                    </div>
<div class="row">
                        <label><strong>رنگ ادرس سوم چت روم:</strong></label>
                        <div><input type="color" name="thdurl_color"  value="<?php echo($wlclogin['thdurl_color']); ?>"/></div>
                    </div>


<div class="row">
                        <label><strong>متن موردنظر برای لینک  :</strong></label>
                        <div><input type="text" name="pm_link" value="<?php echo($wlclogin['pm_link']) ?>"/></div>
                    </div>
<div class="row">
                        <label><strong>رنگ متن موردنظر برای لینک :</strong></label>
                        <div><input type="color" name="pm_color"  value="<?php echo($wlclogin['pm_color']); ?>"/></div>
                    </div>
<div class="row">
                        <label><strong>ایدی یا شماره  :</strong></label>
                        <div><input type="text" name="id_tel" value="<?php echo($wlclogin['id_tel']) ?>"/></div>
                    </div>
<div class="row">
                        <label><strong>رنگ ایدی یا شماره :</strong></label>
                        <div><input type="color" name="id_color"  value="<?php echo($wlclogin['id_color']); ?>"/></div>
                    </div>
<div class="row">
                        <label><strong>متن اخر :</strong></label>
                        <div><input type="text" name="pm_else" value="<?php echo($wlclogin['pm_else']) ?>"/></div>
                    </div>
<div class="row">
                        <label><strong>رنگ متن اخر :</strong></label>
                        <div><input type="color" name="pmelse_color"  value="<?php echo($wlclogin['pmelse_color']); ?>"/></div>
                    </div>
<div class="row">
                        <label><strong>لینک موردنظر :</strong></label>
                        <div><input type="text" name="link" value="<?php echo($wlclogin['link']) ?>"/></div>
                    </div>
                </div>
            </form>
            <center> <medium style="color:red">پس از ویرایش ممکن است به طور طبیعی حدودا یک دقیقه اعمال تغییرات طول بکشد</medium><br><button class="button blue flat block" onclick="edit();">ویرایش</button></center>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>
