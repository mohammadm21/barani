<?php
include('header.php');
include('sidebar.php');
$codes = safe::number(giu('el4'));
$codes = db::get('codes', "*", "`id`='$codes'");
?>

<script type="text/javascript">
    function edit() {
        var $form = $('#formeditcode');
        var data = {
            'id':'<?php echo($codes['id']); ?>',
            'title': $('input[name=title]', $form).val(),
            'page': $('select[name=page]', $form).val(),
            'location': $('select[name=location]', $form).val(),
            'code': $('textarea[name=code]', $form).val()
        };
        $.ajax({
            type: 'POST',
            url: '<?php echo(url('ajax/admin.theme.code.edit')); ?>',
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
function filemanager_password_checker() {
        $.ajax({
            type: "POST",
            url: "<?php echo(url('ajax/admin.filemanager.checkpw')); ?>",
            data: {password: $("#filemanager_password").val()},
            success: function (data) {
                switch (data) {
                    case('ok'):
                        window.location.reload();
                        break;
                    case('invalid'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'پسورد اشتباه است'});
                        break;
                    default:
                        alert(data);
                        break;
                }
            }
        });
    }
</script>
<?php if (session('filemanager_password') == $options['filemanager_password']) { ?>
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">کد های جاوا و فلش</h1>
    <div class="grid_12">
        <div class="grid_12">
            <form class="box  validate" id="formeditcode">
                <div class="header">
                    <h2>ویرایش کد <?php echo $codes['title']; ?></h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>عنوان:</strong></label>
                        <div><input type="text" name="title" value="<?php echo $codes['title']; ?>"/></div>
                    </div>
<?php 
     if ($codes['page'] == 'chat') 
       {
 	    $n1= 'selected="selected"';
            
     }if ($codes['page'] == 'login') 
       {
 	    $n2= 'selected="selected"';
            
     }if ($codes['page'] == 'profile') 
       {
 	    $n3= 'selected="selected"';
            
     }if ($codes['page'] == 'banned') 
       {
 	    $n4= 'selected="selected"';
            
     }  if ($codes['location'] == 'top') 
       {
 	    $m1= 'selected="selected"';
            
     }if ($codes['location'] == 'bottom') 
       {
 	    $m2= 'selected="selected"';
            
     }
?>
                    <div class="row">
                        <label><strong>صفحه:</strong></label>
                        <div><select name="page" class="search">
                                <option value="chat" <?php print $n1; ?> >داخل چتروم</option>
                                <option value="login" <?php print $n2; ?> >بیرون چتروم</option>
                                <option value="profile" <?php print $n3; ?> >پروفایل</option>
                                <option value="banned" <?php print $n4; ?> >صفحه اخراج</option>
                            </select></div>
                    </div>
                    <div class="row">
                        <label><strong>مکان:</strong></label>
                        <div><select name="location" class="search">
                                <option value="top" <?php print $m1; ?>>بالای صفحه</option>
                                <option value="bottom" <?php print $m2; ?>>پایین صفحه</option>
                            </select></div>
                    </div>
                    <div class="row">
                        <label><strong>کد:</strong></label>
                        <div>
                            <textarea name="code" rows="10" dir="ltr" style="font-family: 'Courier New';font-size: 10pt;" value="" ><?php echo $codes['code']; ?></textarea>
                        </div>
                    </div>
                </div>

            </form>
            <center> <button class="button blue flat block" onclick="edit();">ویرایش</button></center>
        </div>
    </div>
</section>
<?php } else { ?> 
    <center>
        <div style="background-image:url('<?php echo(theme::url('img/files.png')); ?>');height: 300px;width: 300px;margin-top:80px;margin-right:180px;color:#F00;border-radius: 5px;">
            <label>پسورد انتخاب قالب:</label> 
            <br>
            <input type="password" style="text-align: center; margin-top: 120px; width: 50%;" placeholder="پسوردبه روز رسانی قالب" id="filemanager_password" name="filemanager_password">
            <br>
            <button onclick="filemanager_password_checker();" class="grey cancel">باز کن</button>
        </div>
        <p><b>رمز -&gt; از ناحیه کاربری دریافت کنید</b></p>
    </center>
<?php } ?>
<?php include('footer.php'); ?>