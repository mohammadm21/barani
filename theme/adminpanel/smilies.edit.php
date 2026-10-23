<?php
include('header.php');
include('sidebar.php');
$smiliy = safe::number(giu('el4'));
$smiliy = db::get('smilies', "*", "`id`='$smiliy'");
if(!$smiliy){
    include('pages/notfound.php');
    return;
}
$code = $smiliy['name'] == null ? $smiliy['code'] : $row['name'];

?>
<script type="text/javascript">
function edit(){
    var $form = $('#formeditsmily');
    var data = {
        'smily':'<?php echo($smiliy['id']); ?>',
        'name':$('input[name=name]', $form).val(),
        'priority':$('input[name=priority]', $form).val()
    };
    $.ajax({
        type: "POST",
        url: "<?php echo(url('ajax/admin.smilies.edit')); ?>",
        data: data,
        success: function(data){
            switch(data){
                case('ok'):$.jGrowl('ذخیره شد', { header: 'موفقیت آمیز' });break;
                case('repeatname'):$.jGrowl('این نام ویژه قبلا ثبت شده', { header: 'خطا' });break;
                default:alert(data);break;
            }
        }
    });
}
</script>
<section id="content" class="container_12 clearfix" data-sort=true>
	<h1 class="grid_12 margin-top no-margin-top-phone">ویرایش شکلک</h1>
    <div class="grid_12">
        <div class="grid_12">
            <form class="box  validate" id="formeditsmily">
                <div class="header">
                    <h2>ویرایش "<?php echo($code); ?>"</h2>
                </div>
                <div class="content tabbed">
                    <p style="text-align: center;"><img src="<?php echo(url('')); ?><?php echo($options['smilies_dir'].'/'.$smiliy['img']); ?>"/></p>
                    <div class="row">
                        <label><strong>شماره شکلک:</strong></label>
                        <div><input type="text" name="id" value="<?php echo($smiliy['id']); ?>"  disabled="disabled" readonly="yes"  style="width: 100px;text-align: center;"/></div>
                    </div>
                    <div class="row">
                        <label><strong>نام ویژه:</strong></label>
                        <div><input type="text" name="name"  value="<?php echo($smiliy['name']); ?>"/></div>
                    </div>
                    <div class="row">
                        <label><strong>اولویت نمایشی:</strong></label>
                        <div><input type="text" name="priority" class="required" value="<?php echo($smiliy['priority']); ?>" pattern="^\d{1,10}$"/></div>
                    </div>
                </div>
               
            </form>
            <center> <button class="button blue flat block" onclick="edit();">ویرایش</button></center>
	   </div>
    </div>
</section>
<?php include('footer.php'); ?>