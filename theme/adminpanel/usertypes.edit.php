<?php
$type = safe::number(giu('el4'));
if(!$type = db::get('usertypes', '*', "`id`='$type'")){
    include('pages/notfound.php');
    exit();
}

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
var hexDigits = new Array("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f"); 

function rgb2hex(rgb) {
    rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
    return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}
function hex(x) {
    return isNaN(x) ? "00" : hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];
}
function edit(){
    var $form = $('#formeditype');
    var $parent  = $('input[name=color]').eq(0).parent();
    var $color = rgb2hex($('.color', $parent).css('background-color'));
    var formData = new FormData();
    formData.append('id', '<?php echo($type['id']); ?>');
    formData.append('title', $('input[name=title]', $form).val());
    formData.append('admin', $('input[name=admin]', $form).val());
    formData.append('score', $('input[name=score]', $form).val());
    formData.append('sort', $('input[name=sort]', $form).val());
    formData.append('color', $color);
    if($('#icontype')[0].files)formData.append('icontype', $('#icontype')[0].files[0]);
    if($('#imgtype')[0].files)formData.append('imgtype', $('#imgtype')[0].files[0]);
    $.ajax({
        url: '<?php echo(url('ajax/admin.usertypes.edit')); ?>',
        type: 'POST',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        success: function (data) {
                switch (data) {
                    case('ok'):
                        $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'ویرایش شد'});
                        break;
                    case('type'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'نوع فایل توسط سرور تایید نشد'});
                        break;
                    case('security'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'سرور در فایل شما مورد امنیتی یافت گرد فایل شما حذف گردید'});
                        break;
                    case('title'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'لطفا عنوانی صحیح وارد کنید'});
                        break;
                    case('score'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'لطفا امتیازی را مشخص کنید'});
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
}
</script>
<section id="content" class="container_12 clearfix" data-sort=true>
	<h1 class="grid_12 margin-top no-margin-top-phone">ویرایش سطح کاربری</h1>
    <div class="grid_12">
        <div class="grid_12">
            <form class="box  validate" id="formeditype">
				<input type="hidden" name="token" value="<?php echo(session('changepw')); ?>" />
                <div class="header">
                    <h2>ویرایش <?php echo($type['title']); ?></h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>نام گروه:</strong></label>
                        <div><input   class="required" autocomplete="off" type="text"  name="title" value="<?php echo($type['title']); ?>"/></div>
                    </div>
                    <div class="row">
                        <label><strong>سطح مدیریتی:</strong><small>1 = مدیریت کل</small></label>
                        <div>
                            <input type="text" name="admin" value="<?php echo($type['admin']); ?>" />
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>حداقل امتیاز:</strong><small> برای بدون محدودیت 0 بگذارید</small></label>
                        <div>
                            <input type="text" name="score" pattern="" value="<?php echo($type['score']); ?>" /><br>
                           <medium style="color:red;">توجه :‌ فقط یکی از درجه های بدون قدرت مدیریت ( سطح مدیریت 0 یا بدون محدودیت ) امکان داشتن امتیاز 0 را دارد زیرا مهمان بعد از عضویت به درجه با امتیاز 0 و بدون سطح مدیریت تبدیل می شود</medium>
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>اولویت:</strong></label>
                        <div>
                            <input type="text" name="sort" pattern="" value="<?php echo($type['sort']); ?>" />
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>آیکون:</strong></label>
                        <div>
                            <span>آیکون فعلی: <img src="<?php echo(theme::url($type['icon'])); ?>" /></span>
                            <input type="file" name="icontype" id="icontype" />
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>تاج:</strong></label>
                        <div>
                        	<span>تاج فعلی: <img src="<?php echo(theme::url($type['taj'])); ?>" /></span>
                            <input type="file" name="imgtype" id="imgtype" />
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>رنگ نام:</strong></label>
                        <div>
                        	
                            <input type="color" name="color" value="<?php echo($type['color']); ?>" />
                        </div>
                    </div>
                </div>
            </form>
            <center> <button class="button blue flat block" onclick="edit();">ثبت</button></center>
	   </div>
    </div>
</section>
<?php include('footer.php'); ?>
