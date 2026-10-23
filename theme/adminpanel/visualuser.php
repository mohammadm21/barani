<?php
$query = db::query("SELECT `id` FROM {prefix}onlines WHERE `time`='0'");
while ($row = mysqli_fetch_array($query)) {
    if (!$user = db::get('users', "`id`, `username`", "`id`='$row[0]'")) {
        db::delete('onlines', "`id`='$row[0]'");
    }
}
mysqli_free_result($query);
include('header.php');
include('sidebar.php');
?>
<script type="text/javascript">
    function deleteuser(id) {
        $.ajax({
            type: "POST",
            url: '<?php echo(url('ajax/admin.visualuser.delete')); ?>',
            data: {user: id},
            success: function (data) {
                switch (data) {
                    case('ok'):
                        $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'حذف گردید'});
                        $('#user-' + id).slideUp(1000);
                        break;
                    case('user'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'این کاربر وجود ندارد'});
                        break;
                    case('novisual'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'این کاربر مجازی نیست'});
                        break;
                    case('repeatuser'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'دسترسی به این کاربر قبلا داده شده است'});
                        break;
                }
            }
        });
    }

</script>

<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">کاربران مجازی</h1> 
    <div class="grid_12">
        <div class="box">
            <div class="header">
                <h2>
                    <img class="icon" src="<?php echo(theme::url('img/icons/packs/fugue/16x16/users.png')); ?>">
                    کاربران مجازی
                </h2>
            </div>
            <div class="content">
                <p style="text-align: center;">
                    <a href="../../../admin/visualuser/add"><button class="button grey block">کاربر مجازی جدید</button></a>
                    <a href="../../../admin/autoanswer"><button class="button blue flat block">پاسخگوی هوشمند</button></a>
                    <a href="../../../admin/visualuser/group"><button class="button red flat block">افزودن دسته جمعی کاربر مجازی</button></a>
					<a href="../../../admin/visualuser/random"><button class="button red flat block">افزودن کاربر مجازی بصورت تصادفی</button></a>
                </p>
                <div class="tabletools">
                </div>
                <table class="styled" id="daynmic_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>نام</th>
                            <th>جنیست</th>
                            <th>گروه کاربری</th>
							<th>بازه زمانی فعالیت</th>
                            <th>اتاق</th>
                            <th>وضعیت خصوصی کاربر</th>
                            <th>سیستم عامل کاربر</th>
                            <th>استاتوس</th>
                            <th>پاسخگوی خودکار</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = db::query("SELECT `id`,`name`,`gender`,`type`,`roomname`,`inputs` FROM {prefix}onlines WHERE `time`='0' and `lastcheck`='0'");
                        while ($row = mysqli_fetch_array($query)) {
                            switch ($row['gender']) {
                                case('1'):$row['gender'] = 'مذکر';
                                    break;
                                case('2'):$row['gender'] = 'مونث';
                                    break;
                            }
                            $inputs = json_decode($row['inputs'], true);
                            switch ($inputs['myOS']) {
                                case 'phone':$browser = 'موبایل';
                                    break;
                                case 'tablet':$browser = 'تبلت';
                                    break;
                                case 'pc':$browser = 'رایانه';
                                    break;
                                default:$browser = 'ناشناخته';
                            }
                            switch ($inputs['status_private']) {
                                case 'pmme':$privatlook = 'فعال';
                                    break;
                                case 'nopm':$privatlook = 'غیرفعال';
                                    break;
                            }
                            if ($inputs['stext'] != '') {
                                $stext = $inputs['stext'];
                            } else {
                                $stext = 'بدون استاتوس';
                            }
							$msg = "";
						if(0 == $inputs['firstTime'] && 0 == $inputs['lastTime']){
							$msg = "24 ساعته آنلاین";
						}else{
							$msg = "از ساعت {$inputs['firstTime']} تا ساعت {$inputs['lastTime']}";
						}
                            ?>
                            <tr id="user-<?php echo($row['id']); ?>">
                                <td><?php echo($row['id']); ?></td>
                                <td><?php echo($row['name']); ?><br/>
                                    <a href="javascript:deleteuser('<?php echo($row['id']); ?>');">[حذف]</a>
									<a href="../../../admin/visualuser/edit/<?php echo($row['id']);?>" target="_blank">[ویرایش]</a>
                                </td>
                                <td><?php echo($row['gender']); ?></td>
                                <td><a href="<?php echo(url('admin/users/' . $type)); ?>"><img src="<?php echo(theme::url($usertype[$row['type']]['icon'])); ?>"></a></td>
                                <td><?php echo($msg); ?></td>
								<td><?php echo($row['roomname']); ?></td>
                                <td><?php echo($privatlook); ?></td>
                                <td><?php echo($browser); ?></td>
                                <td><?php echo($stext); ?></td>
                                <td style="text-align: center;"><?php
                                    if (isset($inputs['autoanswer']) and $inputs['autoanswer'] == true) {
                                        echo("<span class=\"badge block green\">فعال</span>");
                                    } else {
                                        echo("<span class=\"badge block red\">غیرفعال</span>");
                                    }
                                    ?></td>
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