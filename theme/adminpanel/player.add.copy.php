<?php
include('header.php');
include('sidebar.php');
?>
<script type="text/javascript">
    function add(elem) {
        elem.innerHTML = 'این عملیات ممکن است طول بکشد';
        elem.disabled = true;
        var $form = $('#formcopysmily');
        var data = {
            'site': $('input[name=site]', $form).val()
        };
        $.ajax({
            type: "POST",
            url: "<?php echo(url('ajax/admin.smilies.add.copy')); ?>",
            data: data,
            success: function (data) {
                switch (data) {
                    case('ok'):
                        $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'ذخیره شد'});
                        break;
                    case('invalidurl'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'این ادرس تایید نمیشود'});
                        break;
                    case('cannotopen'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'سرور توان دسترسی را به این چت روم تدارد'});
                        break;
                    case('noimage'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'سرور توان پیدا کردن شکلک ها را ندراد'});
                        break;
                    case('permit'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'مدیر چت روم به سایرین اجازه کپی کردن شکلک را نداده است'});
                        break;
                    default:
                        alert(data);
                        break;
                }
                elem.innerHTML = 'کپی کن';
                elem.disabled = false;
            }, error: function () {
                elem.innerHTML = 'کپی کن';
                elem.disabled = false;

            },
            timeout: 600000
        });
    }
</script>
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">شکلک جدید</h1>
    <div class="grid_12">
        <div class="grid_12">
            <form class="box  validate" id="formcopysmily">
                <div class="header">
                    <h2>کپی دست جمعی شکلک ها</h2>
                </div>
                <div class="content tabbed">
                    <p style="color: red;text-decoration: blink;text-align: center;">لطفا از این ابزار با رضایت چت روم مقصد استفاده نمایید</p>
                    <div class="row">
                        <label><strong>آدرس چت روم:</strong></label>
                        <div><input type="text" name="site" value="" class="required"/></div>
                    </div>
                </div>
            </form>                                             
            <center> <button class="button blue flat block" onclick="add(this);">کپی کن</button></center>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>