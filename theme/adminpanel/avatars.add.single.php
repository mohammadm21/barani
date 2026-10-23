<?php
include('header.php');
include('sidebar.php');
?>
<script type="text/javascript" src="<?php echo(theme::url('js/ajaxfileupload.js')); ?>"></script>
<script type="text/javascript">
function add(){
    var $form = $('#formaddavatar');
    var data = {
        'name':$('input[name=name]', $form).val(),
        'priority':$('input[name=priority]', $form).val(),
    };
    $.ajaxFileUpload({
        url:'<?php echo(url('ajax/admin.avatars.add.single')); ?>',
        secureuri:false,
        fileElementId:'fileavatar',
        data:data,
        dataType: 'text',
        success: function (data, status){
            switch(data){
                case('ok'):$.jGrowl('ذخیره شد', { header: 'موفقیت آمیز' });break;
                case('type'):$.jGrowl('نوع فایل توسط سرور تائید نشد', { header: 'خطا' });break;
                case('security'):$.jGrowl('سرور در فایل شما مورد امنیتی پیدا کرد و فایل شما از سرور حذف گردید.', { header: 'خطا' });break;
                case('repeatname'):$.jGrowl('این نام ویژه قبلا ثبت شده', { header: 'خطا' });break;
                default:alert(data);break;
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
	<h1 class="grid_12 margin-top no-margin-top-phone">آواتار جدید</h1>
    <div class="grid_12">
        <div class="grid_12">
            <form class="box  validate" id="formaddavatar">
                <div class="header">
                    <h2>آپلود آواتار</h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>نام :</strong></label>
                        <div><input type="text" name="name" value=""/></div>
                    </div>
                  
                    <div class="row">
                        <label><strong>انتخاب فایل:</strong></label>
                        <div>
                            <input name="fileavatar" id="fileavatar" type="file" class="required" />
                        </div>
                    </div>
                </div>
               
            </form>
            <center> <button class="button blue flat block" onclick="add();">اضافه کردن</button></center>
	   </div>
    </div>
</section>
<?php include('footer.php'); ?>