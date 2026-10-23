<?php
include('header.php');
include('sidebar.php');
?>
<style>
    .cpicker{
        display: inline-block;
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {
        $("#typeloadd1,#typeloadd2").dialog({
            autoOpen: false,
            modal: true,
            width: 500,
            buttons: [{text: "متوجه شدم", click: function () {
                        $(this).dialog("close");
                    }}]
        });
        $('#typeloadi1').click(function () {
            $("#typeloadd1").dialog("open");
        });
        $('#typeloadi2').click(function () {
            $("#typeloadd2").dialog("open");
        });
    })

    function edit(form) {
        $.ajax({
            url: '<?php echo(url('ajax/admin.theme.advanded')); ?>',
            type: 'POST',
            success: function (data) {
                switch (data) {
                    case('ok'):
                        $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'ذخیره شد'});
                        break;
                    case('type'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'نوع فایل توسط سرور تایید نشد'});
                        break;
                    case('security'):
                        $.msgGrowl({type: 'warning', title: 'خطاز', text: 'سرور در فایل شما مورد امنیتی یافت گرد فایل شما حذف گردید'});
                        break;
                    case('repeatname'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'این نام ویژه قبلا ثبت شده است'});
                        break;
                    default:
                        alert(data);
                        break;
                }
            },
            data: $(form).serialize()
        });
        return false;
    }
</script>
<div style="display: none;" id="typeloadd1" title="بارگذاری سنتی">
    <p>در این نوع بارگذاری که مخصوص چتروم هایی هست که علاقه ای به داشتن مدیریت های ساده ندارند!</p>
    <p>مادر این نوع بارگذاری بگونه ای برنامه ریزی کرده ایم تا همه چیز در همان لحظه اول برای کاربر لود شود پس کمی زمان بارگذاری بالا میرود</p>
    <p>مهم ترین قابلیتی که در این نوع بارگذاری غیرفعال میشود ایجاد و حذف سطوح کاربری است و اگراین نوع بارگذاری فعال باشد تمامی کاربران شما به هشت گروه اصلی با توجه به امتیازشان باز میگردند.</p>
    <p>ما این نوع بارگذاری را پیشنهاد نمیکنیم!</p>
</div>
<div style="display: none;" id="typeloadd2" title="بارگذاری مدرن">
    <p>در نوع مدرن بارگذاری همه چیز در دستان شماست و مدیریت برای شما روانتر میشود.</p>
    <p>صفحه و عناصر اصلی صفحه در این نوع خیلی سریع لود میشوند و تبیلغات و کد های اضافی در گام بعدی اضافه خواهد شد</p>
    <p>ما اکثر امکانات جدید خود را بر پایه این نوع بارگذاری اضافه خواهیم کرد پس به شما هم استفاده از این نوع را پیشنهاد خواهیم کرد</p>
</div>
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">تنظیمات قالب</h1>
    <div class="grid_12">
        <div class="grid_12">
            <form class="box " id="form-1" method="post" onsubmit="return edit(this);;">
                <input type="hidden" name="part" value="chat" />
                <input type="hidden" name="subset" value="header" />
                <div class="header">
                    <h2>نوع بارگذاری</h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>نوع بارگذاری</strong></label>
                        <div>
                            <div><input type="radio" name="typeload" id="typeload1" value="1"<?php if ($options['typeload'] == '1') echo(' checked'); ?>><label for="typeload1">سنتی</label> <a href="javascript:void(0)" id="typeloadi1">[توضیحات]</a></div>
                            <div><input type="radio" name="typeload" id="typeload2" value="2"<?php if ($options['typeload'] == '2') echo(' checked'); ?>><label for="typeload2">مدرن </label> <a href="javascript:void(0)" id="typeloadi2">[توضیحات]</a></div>
                        </div>
                    </div>
                    <div class="row"> 
                        <p style="text-align: center;"><input type="submit" value="ویرایش" class="button blue flat block" /></p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>