<?php include('header.php'); include('sidebar.php'); ?>
<script type="text/javascript">
function DelReport(id){
    $.ajax({
        type:"POST",
        url:'<?php echo(url('ajax/admin.users.report')); ?>',
        data:{id:id},
        success: function(data){
            switch(data){
				 
                case('ok'):$.msgGrowl({type: 'success', title: 'باموفقیت کاربر از ریپورتی خارج شد', text: 'موفقیت آمیز'});$('#report-'+id).slideUp(1000);break;
                case('nouser'):$.msgGrowl({type: 'warning', title: 'کاربر مورد نظر در لیست کاربران ریپورت شده وجود ندارد', text: 'خطا'});break;
            }
        }
    });
}
</script>
<section id="content" class="container_12 clearfix" data-sort=true>
	<h1 class="grid_12 margin-top no-margin-top-phone">کاربران ریپورت شده</h1>
    
    <div class="grid_12">
		<div class="box">
			<div class="header">
				<h2>
					<img class="icon" src="<?php echo(theme::url('img/icons/packs/fugue/16x16/balloons-box.png')); ?>">
					ریپورت شده ها
				</h2>
			</div>
			<div class="content">
            
				<div class="tabletools">
				</div>
				<table class="styled" id="daynmic_table">
					<thead>
						<tr>
							<th>ردیف</th>
                            <th>کاربر</th>
                            <th>تعداد دفعات بلاک</th>
                            <th>زمان خارج شدن از ریپورت</th>
                            <th>مدیریت</th>
						</tr>
					</thead>
					<tbody>
                    <?php
                    $x = 0;
                    $query = db::query("SELECT * FROM {prefix}report_users");
                    while($row = mysqli_fetch_array($query)){
					$user = db::get('users','*',"`id`='".$row['user']."'");
                    $x++; 
                    ?>
						<tr id="report-<?php echo($row['user']); ?>">
                            <td align="center"><?php echo($x); ?></td>
                            <td align="center"><?php echo($user['username']); ?></td>
                            <td align="center"><?php echo($user['report']); ?></td>
                            <td align="center"><?=jdate::start('l d F Y ساعت H:i:s', $row['time'])?></td>
                            <td align="center"><a onclick="DelReport('<?=$row['user'];?>')" style="cursor:pointer; color:#F00">خارج کردن از ریپورت</a></td>
                        </tr>
                        <?php
                        }
                        mysqli_free_result($query);
                        ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>
<?php include('footer.php'); ?>