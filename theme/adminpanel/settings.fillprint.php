<?php
include('header.php');
include('sidebar.php');
?>
<script type="text/javascript">

    function edit() {
        $.ajax({
            type: "POST",
            url: "<?php echo(url('ajax/admin.settings.fillprint')); ?>",
            data: $('#formeditfillprint').serialize(),
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
            <form class="box  validate" id="formeditfillprint">
                <input type="hidden" name="token" value="<?php echo(session('changepw')); ?>" >
                <div class="header">
                    <h2>اثر انگشت دستگاه</h2>
                </div>
                <div class="content tabbed">
					<div class="row">
                        <label><strong>فعال بودن سیستم اثر انگشت:</strong><small>در صورت فعال بودن، از هر کاربر واردشده اثر انگشت دستگاه جمع‌آوری و با کاربران اخراجی مقایسه می‌شود</small></label>
                        <div>
                            <label><input type="checkbox" name="fillprint_enabled" value="true" <?php if ($options['fillprint_enabled'] == 'true') echo('checked'); ?> /> فعال باشد</label>
                        </div>
                    </div>
					<div class="row">
                        <label><strong>آستانه اخراج (درصد):</strong><small>اگر درصد شباهت اثر انگشت به این مقدار رسید یا بیشتر شد، کاربر اخراج و ورود مجدد وی مسدود می‌شود</small></label>
                        <div><input type="text" name="fillprint_block_threshold" value="<?php echo($options['fillprint_block_threshold']); ?>" class="required" /></div>
                    </div>
					<div class="row">
                        <label><strong>آستانه چت خصوصی (درصد):</strong><small>اگر شباهت بین این مقدار و آستانه اخراج باشد (این‌آستانه تا آستانه اخراج)، کاربر فقط مجاز به چت خصوصی است</small></label>
                        <div><input type="text" name="fillprint_private_threshold" value="<?php echo($options['fillprint_private_threshold']); ?>" class="required" /></div>
                    </div>
					<div class="row">
                        <label><strong>آستانه ارسال پیام به مدیر (درصد):</strong><small>اگر شباهت بین این مقدار و آستانه چت خصوصی باشد (این‌آستانه تا آستانه چت خصوصی)، کاربر فقط می‌تواند به مدیر پیام بدهد. زیر این مقدار فقط در لاگ ثبت می‌شود</small></label>
                        <div><input type="text" name="fillprint_admin_threshold" value="<?php echo($options['fillprint_admin_threshold']); ?>" class="required" /></div>
                    </div>
					<div class="row">
                        <label><strong>اطلاع به مدیر:</strong><small>در صورت مشکوک بودن، یک پیام سیستمی به مدیران آنلاین ارسال و در لاگ اثر انگشت ثبت شود</small></label>
                        <div>
                            <label><input type="checkbox" name="fillprint_notify_admin" value="true" <?php if ($options['fillprint_notify_admin'] == 'true') echo('checked'); ?> /> ارسال نوتیفیکیشن به مدیر</label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <center>
        <button class="button blue flat block" onclick="edit();">ویرایش</button>
    </center>
</section>
<?php include('footer.php'); ?>
