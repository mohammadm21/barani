<?php
include('header.php');
include('sidebar.php');
?>
<script type="text/javascript">

    function edit() {
        $.ajax({
            type: "POST",
            url: "<?php echo(url('ajax/admin.settings.seo')); ?>",
            data: $('#formeditseo').serialize(),
            success: function (data) {
                switch (data) {
                    case('ok'):
                        $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'ذخیره شد'});
                        break;
                    case('title'):
                        $.msgGrowl({type: 'success', title: 'لطفا نامی برای اتاق انتخاب کنید', text: 'خطا'});
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
            <form class="box  validate" id="formeditseo">
                <input type="hidden" name="token" value="<?php echo(session('changepw')); ?>" >
                <div class="header">
                    <h2>تنظیمات سئو</h2>
                </div>
                <div class="content tabbed">
					<div class="row">
                        <label><strong>کد گوگل:</strong></label>
                        <div><input type="text" name="google_code" value="<?php echo($options['google_code']) ?>" class="required" /></div>
                    </div>
					<div class="row">
                        <label><strong>کد گوگل پلاس:</strong></label>
                        <div><input type="text" name="googleplus_code" value="<?php echo($options['googleplus_code']) ?>" class="required" /></div>
                    </div>
					<div class="row">
                        <label><strong>کد ساماندهی:</strong></label>
                        <div><input type="text" name="samandehi_code" value="<?php echo($options['samandehi_code']) ?>" class="required" /></div>
                    </div>
                    <div class="row">
                        <label><strong>محل قرارگیری تگ H1:</strong><small>یک عنوان اجازه نوشتن دارید</small></label>
                        <div><input type="text" name="h1_code" value="<?php echo($options['h1_code']) ?>" class="required" /></div>
                    </div>
                    <div class="row">
                        <label><strong>محل قرار گیری تگ H2:</strong><small>کلمات ریشه ای و مهم</small></label>
                        <div><input type="text" name="h2_code" value="<?php echo($options['h2_code']) ?>" class="required" /></div>
                    </div>
                    <div class="row">
                        <label><strong>محل قرار گیری تگ های H3:</strong><small>ریشه مطالب نوشته شود</small></label>
                        <div><input type="text" name="h3_code" value="<?php echo($options['h3_code']) ?>" class="required" /></div>
                    </div>
                    <div class="row">
                        <label><strong>محل قرار گیری تگ های H4:</strong><small>اسم رومهای کوچیک نوشته شود</small></label>
                        <div><input type="text" name="h4_code" value="<?php echo($options['h4_code']) ?>" class="required" /></div>
                    </div>
                    <div class="row">
                        <label><strong>محل قرار گیری تگ های H5:</strong><small>اسم رومهای کوچیک نوشته شود</small></label>
                        <div><input type="text" name="h5_code" value="<?php echo($options['h5_code']) ?>" class="required" /></div>
                    </div>
                    <div class="row">
                        <label><strong>لینک برای چت روم های بزرگ:</strong><small>ازاینجا به پایین اسم چت روم های بزرگ نوشته شود</small></label>
                        <div><input type="text" name="link1_code" value="<?php echo($options['link1_code']) ?>" class="required" /></div>
                    </div>
                    <div class="row">
                        <label><strong>لینک برای چت روم های بزرگ:</strong></label>
                        <div><input type="text" name="link2_code" value="<?php echo($options['link2_code']) ?>" class="required" /></div>
                    </div>
                    <div class="row">
                        <label><strong>لینک برای چت روم های بزرگ:</strong></label>
                        <div><input type="text" name="link3_code" value="<?php echo($options['link3_code']) ?>" class="required" /></div>
                    </div>
                    <div class="row">
                        <label><strong>لینک برای چت روم های بزرگ:</strong></label>
                        <div><input type="text" name="link4_code" value="<?php echo($options['link4_code']) ?>" class="required" /></div>
                    </div>
                    <div class="row">
                        <label><strong>لینک برای چت روم های بزرگ:</strong></label>
                        <div><input type="text" name="link5_code" value="<?php echo($options['link5_code']) ?>" class="required" /></div>
                    </div>
                    <div class="row">
                        <label><strong>لینک برای چت روم های بزرگ:</strong></label>
                        <div><input type="text" name="link6_code" value="<?php echo($options['link6_code']) ?>" class="required" /></div>
                    </div>
                    <div class="row">
                        <label><strong>لینک برای چت روم های بزرگ:</strong></label>
                        <div><input type="text" name="link7_code" value="<?php echo($options['link7_code']) ?>" class="required" /></div>
                    </div>
                    <div class="row">
                        <label><strong>لینک برای چت روم های بزرگ:</strong></label>
                        <div><input type="text" name="link8_code" value="<?php echo($options['link8_code']) ?>" class="required" /></div>
                    </div>
                    <div class="row">
                        <label><strong>لینک برای چت روم های بزرگ:</strong></label>
                        <div><input type="text" name="link9_code" value="<?php echo($options['link9_code']) ?>" class="required" /></div>
                    </div>
                    <div class="row">
                        <label><strong>لینک برای چت روم های بزرگ:</strong></label>
                        <div><input type="text" name="link10_code" value="<?php echo($options['link10_code']) ?>" class="required" /></div>
                    </div>
                    <div class="row">
                        <label><strong>لینک برای چت روم های بزرگ:</strong></label>
                        <div><input type="text" name="link11_code" value="<?php echo($options['link11_code']) ?>" class="required" /></div>
                    </div>
                    <div class="row">
                        <label><strong>لینک برای چت روم های بزرگ:</strong></label>
                        <div><input type="text" name="link12_code" value="<?php echo($options['link12_code']) ?>" class="required" /></div>
                    </div>
                    <div class="row">
                        <label><strong>لینک برای چت روم های بزرگ:</strong></label>
                        <div><input type="text" name="link13_code" value="<?php echo($options['link13_code']) ?>" class="required" /></div>
                    </div>
                    <div class="row">
                        <label><strong>لینک برای چت روم های بزرگ:</strong></label>
                        <div><input type="text" name="link14_code" value="<?php echo($options['link14_code']) ?>" class="required" /></div>
                    </div>
                    <div class="row">
                        <label><strong>لینک برای چت روم های بزرگ:</strong></label>
                        <div><input type="text" name="link15_code" value="<?php echo($options['link15_code']) ?>" class="required" /></div>
                    </div>
                    <div class="row">
                        <label><strong>لینک برای چت روم های بزرگ:</strong></label>
                        <div><input type="text" name="link16_code" value="<?php echo($options['link16_code']) ?>" class="required" /></div>
                    </div>
                    <div class="row">
                        <label><strong>لینک برای چت روم های بزرگ:</strong></label>
                        <div><input type="text" name="link17_code" value="<?php echo($options['link17_code']) ?>" class="required" /></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <center><medium style="color:red">پس از ویرایش ممکن است به طور طبیعی حدودا یک دقیقه اعمال تغییرات طول بکشد</medium><br> <button class="button blue flat block" onclick="edit();">ویرایش</button></center>
</section>
<?php include('footer.php'); ?>