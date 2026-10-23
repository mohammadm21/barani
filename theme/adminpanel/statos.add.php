<?php
include('header.php');
include('sidebar.php');
?>
<script type="text/javascript" src="<?php echo(theme::url('js/ajaxfileupload.js')); ?>"></script>
<script type="text/javascript">
    function add() {
        var $form = $('#formaddstatos');
        var formData = new FormData();
        formData.append('text', $('input[name=text]', $form).val());
        if ($('#filestatos')[0].files)
            formData.append('filestatos', $('#filestatos')[0].files[0]);
        if ($('#filestatoslarg')[0].files)
            formData.append('filestatoslarg', $('#filestatoslarg')[0].files[0]);

        $.ajax({
            url: '<?php echo(url('ajax/admin.statos.add.single')); ?>',
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
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'نوع فایل توسط سرور تائید نشد'});
                        break;
                    case('security'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'سرور در فایل شما مورد امنیتی پیدا کرد و فایل شما از سرور حذف گردید.'});
                        break;
                    case('repeatname'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'این نام ویژه قبلا ثبت شده'});
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
    <h1 class="grid_12 margin-top no-margin-top-phone">وضعیت جدید</h1>
    <div class="grid_12">
        <div class="grid_12">
            <form class="box  validate" id="formaddstatos">
                <div class="header">
                    <h2>آپلود وضعیت</h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>نام :</strong></label>
                        <div><input type="text" name="text" value=""/></div>
                    </div>

                    <div class="row">
                        <label><strong>انتخاب فایل سایز کوچک : </strong></label>
                        <div>
                            <input name="filestatos" id="filestatos" type="file" class="required" />
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>انتخاب فایل سایز بزرگ : </strong></label>
                        <div>
                            <input name="filestatoslarg" id="filestatoslarg" type="file" class="required" />
                        </div>
                    </div>
                </div>

            </form>
            <center> <button class="button blue flat block" onclick="add();">اضافه کردن</button></center>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>