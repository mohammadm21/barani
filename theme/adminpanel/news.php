<?php
include('header.php');
include('sidebar.php');
?>
<script type="text/javascript">
    function deletenew(id) {
        $.ajax({
            type: "POST",
            url: '<?php echo(url('ajax/admin.news.delete')); ?>',
            data: {new : id},
            success: function (data) {
                switch (data) {
                    case('ok'):
                        $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'ذخیره شد'});
                        $('#new-' + id).slideUp(1000);
                        break;
                    case('nonew'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'این خبر وجود ندارد'});
                        break;
                    default:
                        $.msgGrowl({type: 'warning', title: 'پیتم سرور'});
                        break;
                }
            }
        });
    }
</script>
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">اخبار</h1>

    <div class="grid_12">
        <p style="text-align: center;"><a href="<?php echo(url('admin/news/add')); ?>"><button class="button grey block">افزودن خبر</button></a></p>
        <div class="box">
            <div class="header">
                <h2>
                    <img class="icon" src="<?php echo(theme::url('img/icons/packs/fugue/16x16/bookmark.png')); ?>">
                    مشاهده اخبار
                </h2>

            </div>
            <div class="content">
                <div class="tabletools">
                </div>
                <table class="styled" id="daynmic_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th style="width: 50%;">متن</th>
                            <th>نویسنده</th>
                            <th>وضعیت</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = db::query("SELECT * FROM {prefix}news");
                        while ($row = mysqli_fetch_array($query)) {
                            ?>
                            <tr id="new-<?php echo($row['id']); ?>">
                                <td><?php echo($row['id']); ?></td>
                                <td><?php echo($row['text']); ?><br />
                        <center><a href="javascript:deletenew('<?php echo($row['id']); ?>');"><span style="font-size: 10px;">[حذف]</span></a><a href="<?php echo(url("admin/news/edit/$row[0]")); ?>"><span style="font-size: 10px;">[ویرایش]</span></a></center>
                        </td>
                        <td><span class="badge block red"><?php echo(Users::get('username', $row['writer'])); ?></span><span class="badge block green"><?php
                                $user = db::get('users', "`id`,`username`,`name`,`type`, `score`", "`id`='{$row['writer']}'");
                                echo($usertype[$user['type']]['title']);
                                ?></span></td>
                        <td><?php if ($row['active'] == '1') { ?><span class="badge block blue dark">فعال</span><?php } else { ?><span class="badge block orange">غیر فعال</span><?php } ?></td>
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