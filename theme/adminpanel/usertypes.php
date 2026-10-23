<?php
include('header.php');
include('sidebar.php');

?>
<script type="text/javascript">
function updateSorts(){

    $.ajax({
        type: "POST",
        url: "<?php echo url('ajax/admin.usertypes.sortupdate');?>",
        data: $('#serializeSort').serialize(),
        success: function(data){
            switch(data){
                case('ok'):$.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'ذخیره شد'});break;
                default:alert(data);break;
            }
        }
    });
}
function admin_level_info(){
	$("#admin_level_info").dialog('open');
}
$(document).ready(function(){
	$("#admin_level_info").dialog({
		autoOpen:false,
		modal:true,
		width:500,
		buttons:[{text:"متوجه شدم",click:function(){$(this).dialog("close");}}]
	});
})

</script>
<?php
if(isset($_POST['openlock'])){
if(md5($_POST['code_password'])==$optionss['security_password']){
$_SESSION['loxtarh']=$optionss['security_password'];
}
}
?>
<?php
if($_SESSION['loxtarh']==$optionss['security_password']){
?>

<div style="display: none;" id="admin_level_info" title="توضیح در مورد اولویت مدیریتی">
    <p>در صورتی که اولویت ۱ رو برای یک گروه کاربری قرار بدین اون گروه کاربری جزو مدیریت کل چت روم شما حساب خواهند شد. برای مثال ما بخواهیم معاون بر ناظر ارشد کنترل داشته باشد اولویت معاون را ۲ قرار میدیم و اولویت ناظر ارشد را ۳ یعنی اولویت ۲ بر اولویت ۳ برتری داشته و میتواند شخص را مدیریت کند مثل اخراج - خفه کردن و ...</b>
	<p>
		<b>نکته: <font color="red">فرقی نمیکند شما اولویت گروه کاربری را چه عددی  میذارین برای مثال شما میتونید عدد ۲۰ را برای گروه کاربری معاون انتخاب کنید و عدد ۳۰ را برای گروه ناظر ارشد. یعنی هر چه عدد پایین تری انتخاب کنید قدرت مدیریتیش از بقیه گروه های کاربری که عدد بزرگتری دارن بیشتر خواهد بود دقت کنید این اولویت ها فقط برای درجه داران میباشد برای کاربران عادی بدون درجه  باید اولویت ۰ قرار بگیرد</font></b>
	</p>
</div>
<section id="content" class="container_12 clearfix" data-sort=true>
	<h1 class="grid_12 margin-top no-margin-top-phone">گروه های کاربری</h1>
    
    <div class="grid_12">
    <?php if($options['typeload'] == '2'){ ?>
    <p style="text-align: center;"><a href="<?php echo(url('admin/usertypes/add')); ?>"><button class="button grey block">افزودن  گروه</button></a>
	<a href="javascript:updateSorts();"><button class="button grey block">به روز رسانی اولویت ها</button></a>
	<a href="javascript:admin_level_info();"><button class="button grey block">توضیح در مورد اولویت مدیریتی</button></a></p>
	<?php } ?>
        <div class="box">
			<div class="header">
				<h2>
					<img class="icon" src="<?php echo(theme::url('img/icons/packs/fugue/16x16/bug.png')); ?>">
					گروه های کاربری
				</h2>
                
			</div>
			<div class="content">
				<div class="tabletools">
				</div>
				<table class="styled" id="daynmic_table">
					<thead>
						<tr>
							<th>#</th>
                            <th style="width: 50%;">نام گروه</th>
                            <th>اولویت مدیریتی</th>
                            <th>ایکون</th>
                            <th>تاج</th>
                            <th>امتیاز لازم</th>
                            <th>اولویت‌ در لیست</th>
						</tr>
					</thead>
					<tbody>
					<form id="serializeSort">
                    <?php
                    $query = db::query("SELECT * FROM {prefix}usertypes ORDER BY `sort` ASC");
					$x = 1;
                    while($row = mysqli_fetch_array($query)){
                        if($row['score'] == '0' and $row['admin'] != '0'){$row['score'] = 'تائید مدیریت';}if($row['score'] == '0'){$row['score'] = 'بدون محدودیت';}
                    ?>
					<tr id="type-<?php echo($row['id']); ?>">
                        <td><?php echo($row['id']); ?></td>
                        <td>
                        <a href="<?php echo($options['typeload'] == '2' ? ("../../../admin/usertypes/edit/$row[0]") : '#'); ?>"><?php echo($row['title']); ?></a>
                        <?php
                        if($options['typeload'] == '2'){ ?><br />
                        <center><a href="../../../admin/usertypes/delete/<?php echo $row[0];?>"><span style="font-size: 10px;">[حذف]</span></a><a href="../../../admin/usertypes/edit/<?php echo $row[0];?>"><span style="font-size: 10px;">[ویرایش]</span></a></center>
                        <?php } ?></td>
                        <td align="center"><input maxlength="4" style="text-align: center;" width="10%" name="admin_level[<?php echo($row['id']); ?>]" value="<?php echo($row['admin']); ?>"></td>
                        <td align="center"><img src="<?php echo(theme::url($row['icon'])); ?>" /></td>
				     <td align="center"><img src="<?php echo(theme::url($row['taj'])); ?>" /></td>
                        <td align="center"><?php echo($row['score']); ?></td>
                        <td align="center"><input maxlength="4" style="text-align: center;" width="10%" name="sort_values[<?php echo($row['id']); ?>]" value="<?php echo($row['sort']); ?>"></td>
                    </tr>  
                    <?php
                    }
                    mysqli_free_result($query);
                    ?>
					</form>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>
<?php }else{ ?> 
<center>
<br>
<form action="" method="POST">
<div style="background-image:url('/theme/adminpanel/img/files.png');height: 300px;width: 300px;margin-top:80px;margin-right:180px;color:#fff;border-radius: 5px;"><br><br>
<br><br><br><br><br><br><br>
پسورد : <input name="code_password" value="" type="text">
<button name="openlock" class="grey cancel">باز کن</button>
</div>
<p><b>رمز امنیتی را وارد کنید</b></p>
</form>
</center>
<?php } ?>
<?php include('footer.php'); ?>