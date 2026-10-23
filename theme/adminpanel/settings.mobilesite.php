<?php
include('header.php');
include('sidebar.php');
?>
<script type="text/javascript">

    function edit() {
        $.ajax({
            type: "POST",
            url: "<?php echo(url('ajax/admin.settings.mobilesite')); ?>",
            data: $('#formeditsitemobile').serialize(),
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
            <form class="box  validate" id="formeditsitemobile">
                <input type="hidden" name="token" value="<?php echo(session('changepw')); ?>" >
                <div class="header">
                    <h2>نسخه موبایل (تلگرامی)</h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>وضعیت:</strong><small>off = غیرفعال | auto = تشخیص خودکار موبایل و انتقال | link = فقط لینک (اختیاری)</small></label>
                        <div>
                            <select name="mobilesite_mode">
                                <option value="off" <?php if ((isset($options['mobilesite_mode']) ? $options['mobilesite_mode'] : 'off') == 'off') echo('selected'); ?>>غیرفعال (off)</option>
                                <option value="auto" <?php if ((isset($options['mobilesite_mode']) ? $options['mobilesite_mode'] : 'off') == 'auto') echo('selected'); ?>>تشخیص خودکار + انتقال (auto)</option>
                                <option value="link" <?php if ((isset($options['mobilesite_mode']) ? $options['mobilesite_mode'] : 'off') == 'link') echo('selected'); ?>>فقط لینک (link)</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>آدرس نسخه موبایل:</strong></label>
                        <div><span dir="ltr"><?php echo(url('phone')); ?></span></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <center><medium style="color:red">این فقط روی سرور اصلی فعال شود؛ روی نسخه پایلوت/تست باید off بماند.</medium><br> <button class="button blue flat block" onclick="edit();">ویرایش</button></center>
</section>
<?php include('footer.php'); ?>