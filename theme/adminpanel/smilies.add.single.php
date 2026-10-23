
<?php
include('header.php');
include('sidebar.php');
?>
<script type="text/javascript" src="<?php echo(theme::url('js/ajaxfileupload.js')); ?>"></script>
<script type="text/javascript">
    function add() {
        var $form = $('#formaddsmily');
        var data = {
            'name': $('input[name=name]', $form).val(),
            'priority': $('input[name=priority]', $form).val(),
        };
        $.ajaxFileUpload({
            url: '<?php echo(url('ajax/admin.smilies.add.single')); ?>',
            secureuri: false,
            fileElementId: 'filesmily',
            data: data,
            dataType: 'text',
            success: function (data, status) {
                switch (data) {
                    case('ok'):
                        $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'ذخیره شد'});
                        break;
                    case('type'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'نوغ فایل توسط سرور تایید نشد'});
                        break;
                    case('security'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'سرور در فایل شما مورد امنیتی پیدا کرد و فایل شما از سرور حذف گردید'});
                        break;
                    case('repeatname'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'این نام ویژه قبلا ثبت شده'});
                        break;
                    default:
                        alert(data);
                        break;
                }

            },
            error: function (data, status, e)
            {
                alert(data);
            }
        });
    }
</script>
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">شکلک جدید</h1>
    <div class="grid_12">
        <div class="grid_12">
            <form class="box  validate" id="formaddsmily">
                <div class="header">
                    <h2>آپلود شکلک</h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>نام ویژه:</strong><small>تا حد امکان این گزینه خالی رها شود</small></label>
                        <div><input type="text" name="name" value=""/></div>
                    </div>
                    <div class="row">
                        <label><strong>اولویت نمایشی:</strong></label>
                        <div><input type="text" name="priority" class="required" value="1" pattern="^\d{1,10}$"/></div>
                    </div>
                    <div class="row">
                        <label><strong>انتخاب فایل:</strong></label>
                        <div>
                            <input name="filesmily" id="filesmily" type="file" class="required" />
                        </div>
                    </div>
                </div>

            </form>
            <center> <button class="button blue flat block" onclick="add();">اضافه کردن</button></center>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>

