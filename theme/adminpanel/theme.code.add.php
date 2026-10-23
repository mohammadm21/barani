<?php
include('header.php');
include('sidebar.php');
?>

<script type="text/javascript">
    function add() {
        var $form = $('#formaddcode');
        var data = {
            'title': $('input[name=title]', $form).val(),
            'page': $('select[name=page]', $form).val(),
            'location': $('select[name=location]', $form).val(),
            'code': $('textarea[name=code]', $form).val()
        };
        $.ajax({
            type: 'POST',
            url: '<?php echo(url('ajax/admin.theme.code.add')); ?>',
            data: data,
            dataType: 'text',
            success: function (data) {
                switch (data) {
                    case('ok'):
                        $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'ذخیره شد'});
                        break;
                    case('title'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'لطفا عنوان را بدرستی واردکنید'});
                        break;
                    case('page'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'لطفا صفحه کد را مشخص کنید'});
                        break;
                    case('location'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'مکان مشخص نشده است'});
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
    <h1 class="grid_12 margin-top no-margin-top-phone">کد های جاوا و فلش</h1>
    <div class="grid_12">
        <div class="grid_12">
            <form class="box  validate" id="formaddcode">
                <div class="header">
                    <h2>افزدون کد</h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>عنوان:</strong></label>
                        <div><input type="text" name="title" value=""/></div>
                    </div>
                   <div class="row">
                        <label><strong>صفحه:</strong></label>
                        <div><select name="page" class="search">
                                <option value="chat">داخل چتروم</option>
                                <option value="login">بیرون چتروم</option>
                                <option value="profile">پروفایل</option>
                                <option value="banned">صفحه اخراج</option>
                            </select></div>
                    </div>
                    <div class="row">
                        <label><strong>مکان:</strong></label>
                        <div><select name="location" class="search">
                                <option value="top">بالای صفحه</option>
                                <option value="bottom">پایین صفحه</option>
                            </select></div>
                    </div>
                    <div class="row">
                        <label><strong>کد:</strong></label>
                        <div>
                            <textarea name="code" rows="10" dir="ltr" style="font-family: 'Courier New';font-size: 10pt;"></textarea>
                        </div>
                    </div>
                </div>

            </form>
            <center> <button class="button blue flat block" onclick="add();">اضافه کردن</button></center>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>