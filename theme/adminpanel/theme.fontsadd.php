<?php
include('header.php');
include('sidebar.php');
?>
<script type="text/javascript" src="<?php echo(theme::url('js/ajaxfileupload.js')); ?>"></script>
<script type="text/javascript">
    function add() {
        var $form = $('#formaddfonts');
        var formData = new FormData();
        formData.append('namefont', $('input[name=namefont]', $form).val());
        if ($('#filefontsttf')[0].files)
            formData.append('filefontsttf', $('#filefontsttf')[0].files[0]);
        if ($('#filefontswoff')[0].files)
            formData.append('filefontswoff', $('#filefontswoff')[0].files[0]);
        if ($('#filefontseot')[0].files)
            formData.append('filefontseot', $('#filefontseot')[0].files[0]);
        $.ajax({
            url: '<?php echo(url('ajax/admin.theme.fontsadd')); ?>',
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
    <h1 class="grid_12 margin-top no-margin-top-phone">افزودن فونت جدید</h1>
    <div class="grid_12">
        <div class="grid_12">
            <form class="box  validate" id="formaddfonts">
                <div class="header">
                    <h2>آپلود فونت</h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>نام :</strong></label>
                        <div><input type="text" name="namefont" id="namefont" value=""/></div>
                    </div>
                    <div class="row">
                        <label><strong>انتخاب فونت : فرمت eot</strong></label>
                        <div>
                            <input name="filefontseot" id="filefontseot" type="file" class="required" />
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>انتخاب فونت :فرمت woff</strong></label>
                        <div>
                            <input name="filefontswoff" id="filefontswoff" type="file" class="required" />
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>انتخاب فونت :  فرمت ttf</strong></label>
                        <div>
                            <input name="filefontsttf" id="filefontsttf" type="file" class="required" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <center> <button class="button blue flat block" onclick="add();">اضافه کردن</button></center>
    </div>
</section>
<?php include('footer.php'); ?>