<?php
include('header.php');
include('sidebar.php');
?>
<style>
    .cpicker{
        display: inline-block !important;
        width: 25px !important;    

    }
</style>
<script type="text/javascript" src="<?php echo(theme::url('js/ajaxfileupload.js')); ?>"></script>
<script type="text/javascript">
    var hexDigits = new Array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b", "c", "d", "e", "f");

    function rgb2hex(rgb) {
        rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
        return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
    }
    function hex(x) {
        return isNaN(x) ? "00" : hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];
    }
    function add() {
        var $form = $('#formaddtype');
        var $parent = $('input[name=color]').eq(0).parent();
        var $color = rgb2hex($('.color', $parent).css('background-color'));

        var formData = new FormData();
        formData.append('title', $('input[name=title]', $form).val());
        formData.append('admin', $('input[name=admin]', $form).val());
        formData.append('score', $('input[name=score]', $form).val());
        formData.append('color', $color);
        if ($('#icontype')[0].files)
            formData.append('icontype', $('#icontype')[0].files[0]);
        if ($('#imgtype')[0].files)
            formData.append('imgtype', $('#imgtype')[0].files[0]);

        $.ajax({
            url: '<?php echo(url('ajax/admin.usertypes.add')); ?>',
            type: 'POST',
            xhr: function () {
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) {
                    //myXhr.upload.addEventListener('progress',progressHandlingFunction, false);
                }
                return myXhr;
            },
            success: function (data) {
                switch (data) {
                    case('ok'):
                        $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'ذخیره شد'});
                        break;
                    case('type'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'نوع فایل توسط سرور تایید نشد'});
                        break;
                    case('security'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'سرور در فایل شما مورد امنیتی یافت گرد فایل شما حذف گردید'});
                        break;
                    case('repeatname'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'لطفا عنوانی صحیح وارد کنید'});
                    case('score'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'لطفا حداقل امتیاز را مشخص کنید'});
                        break;
                    case('scorenoadmin'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'درجه ای بدون قدرت مدیریت ( سطح مدیریت 0 یا بدون محدودیت ) وجود دارد که امتیاز آن صفر است و فقط یک درجه امکان دارد که امتیاز و سطح مدیریت آن صفر یا بدون محدودیت باشد '});
                        break;
                    default:
                        alert(data);
                        break;
                }
            },
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        });
        return false;

    }
</script>
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">افزودن گروه کاربری</h1>
    <div class="grid_12">
        <div class="grid_12">
            <form class="box  validate" id="formaddtype">
                <input type="hidden" name="token" value="<?php echo(session('changepw')); ?>" />
                <div class="header">
                    <h2>افزودن یک مورد چدید</h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>نام گروه:</strong></label>
                        <div><input   class="required" autocomplete="off" type="text"  name="title" value=""/></div>
                    </div>
					<div class="row">
                        <label><strong>سطح مدیریتی:</strong><small>1 = مدیریت کل</small></label>
                        <div>
                            <input type="text" name="admin" value="0" />
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>حداقل امتیاز:</strong><small> برای بدون محدودیت 0 بگذارید</small></label>
                        <div>
                            <input type="text" name="score" pattern="" value="" /><br>
                           <medium style="color:red;">توجه :‌ فقط یکی از درجه های بدون قدرت مدیریت ( سطح مدیریت 0 یا بدون محدودیت ) امکان داشتن امتیاز 0 را دارد زیرا مهمان بعد از عضویت به درجه با امتیاز 0 و بدون سطح مدیریت تبدیل می شود</medium>
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>آیکون:</strong></label>
                        <div>
                            <input type="file" name="icontype" id="icontype" />
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>تاج:</strong></label>
                        <div>
                            <input type="file" name="imgtype" id="imgtype" />
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>رنگ نام:</strong></label>
                        <div>
                            <input type="color" name="color" value="#000000" />
                        </div>
                    </div>
                </div>
            </form>
            <center> <button class="button blue flat block" onclick="add();">ثبت</button></center>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>
