<?php
include('header.php');
include('sidebar.php');
?>
<script type="text/javascript" src="<?php echo(theme::url('js/ajaxfileupload.js')); ?>"></script>
<script type="text/javascript">
function add(){
    var $form = $('#formaddsmily');
    var data = {
        'name':$('input[name=name]', $form).val(),
        'priority':$('input[name=priority]', $form).val(),
    };
    $.ajaxFileUpload({
        url:'<?php echo(url('ajax/admin.avatar.add.single')); ?>',
        secureuri:false,
        fileElementId:'filesmily',
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
$(document).ready(function(){
    $(".uploader").each(function(){
		var k=$(this);
		k.pluploadQueue($.extend({
			runtimes:"html5,flash,html4",
			url:"<?php echo(url('ajax/admin.avatar.add.tuple')); ?>",
			max_file_size:"10mb",
			chunk_size:"1mb",
			unique_names:true,
			filters:[
				{title:"فایل های تصویری",extensions:"jpg,gif,png"},
				{title:"فایل های فشرده",extensions:"zip"}
			],flash_swf_url:"js/mylibs/forms/uploader/plupload.flash.swf"
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
                    <h2>آپلود دست جمعی آواتار</h2>
                </div>
                <div class="content tabbed">

                
                
                    <div class="uploader"></div>
                </div>
               
            </form>
	   </div>
    </div>
</section>
<?php include('footer.php'); ?>