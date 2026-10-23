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
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'نوع فایل توسط سرور تایید نشد'});
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
    $(document).ready(function () {
        $(".uploader").each(function () {
            var k = $(this);
            k.pluploadQueue($.extend({
                runtimes: "html5,flash,html4",
                url: "<?php echo(url('ajax/admin.smilies.add.tuple')); ?>",
                max_file_size: "10mb",
                chunk_size: "1mb",
                unique_names: true,
                filters: [
                    {title: "فایل های تصویری", extensions: "jpg,gif,png"},
                    {title: "فایل های فشرده", extensions: "zip"}
                ], flash_swf_url: "js/mylibs/forms/uploader/plupload.flash.swf"
            },
                    k.data()
                    ));
            k.find(".plupload_button").addClass("button grey btn");
            k.find(".plupload_add").addClass("icon-plus");
            k.find(".plupload_start").addClass("icon-ok")
        });
    });
</script>
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">شکلک جدید</h1>
    <div class="grid_12">
        <div class="grid_12">
            <form class="box  validate" id="formaddsmily">
                <div class="header">
                    <h2>آپلود دست جمعی شکلک ها</h2>
                </div>
                <div class="content">
                    <p style="color: red;text-decoration: blink;text-align: center;">شما در قسمت هم میتوانید فایل های فشرده (.zip) ارسال کنید هم شکلک ها را گروهی آپلود کنید</p>


                    <div class="uploader"></div>
                </div>

            </form>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>