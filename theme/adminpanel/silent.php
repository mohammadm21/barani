<?php
include('header.php');
include('sidebar.php');
?>
<script type="text/javascript">
    function deleteban(id) {
        $.ajax({
            type: "POST",
            url: '<?php echo(url('ajax/admin.silent.delete')); ?>',
            data: {id: id},
            success: function (data) {
                switch (data) {
                    case('ok'):
                        $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'با موافقیت از لیست ساکت شدگان در امد'});
                        $('#ip-' + id).slideUp(1000);
                        break;
                    default:
                        alert('پیام سرور:' + data);
                        break;
                }
            }
        });
    }
</script>
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">ساکت شدگان</h1>
    <div class="grid_12">
        <div class="box">
            <div class="header">
                <h2>
                    <img class="icon" src="<?php echo(theme::url('img/icons/packs/fugue/16x16/speaker-volume-control-mute.png')); ?>">
                    ساکت شدگان
                </h2>

            </div>
            <div class="content">
                <div class="tabletools">
                </div>
                <table class="styled" id="daynmic_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>آی پی</th>
                            <th>ساکت شده(ها)</th>
                            <th>ساکت کننده</th>
                            <th>زمان ساکت کردن</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = db::query("SELECT * FROM {prefix}silents");
                        while ($row = mysqli_fetch_array($query)) {
                            $users = explode(',', $row['users']);
                            ?>
                            <tr id="ip-<?php echo($row['id']); ?>">
                                <td><?php echo($row['id']); ?></td>
                                <td><p  style="text-align: center;"><?php echo($row['ip']); ?></p><a href="javascript:deleteban('<?php echo($row['id']); ?>');">[حذف]</a></td>
                                <td><?php
                                    foreach ($users as $user) {
                                        $user = db::get('users', "`id`,`username`", "`id`='$user'");
                                        ?>
                                        <a href="<?php echo(url('admin/users/edit/' . $user['id'])); ?>"><?php echo($user['username']); ?></a>
                                        <?php
                                    }
                                    ?></td>
                                <td><?php echo(db::get('users', "username", "`id`='{$row['muter']}'")); ?></td>
                                <td><?php echo(jdate::start('H:i:s Y/m/d ', $row['time'])); ?></td>
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