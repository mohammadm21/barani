<?php
$el4 = giu('el4');
if($el4){
    $el4 = safe::string($el4);
    if($el4 == 'add'){
        include('theme.code.add.php');
        exit();
    }
}
include('header.php');
include('sidebar.php');
?>
<script type="text/javascript">
    function delcode(id) {
        $.ajax({
            type: "POST",
            url: '<?php echo(url('ajax/admin.theme.code.delete')); ?>',
            data: {id: id},
            success: function (data) {
                switch (data) {
                    case('ok'):
                        $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'ذخیره شد'});
                        $('#code-' + id).slideUp(1000);
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
    <h1 class="grid_12 margin-top no-margin-top-phone">مدیریت قالب</h1>
    <div class="grid_12">
        <p style="text-align: center;"><a href="<?php echo(url('admin/theme/code/add')); ?>"><button class="button grey block">کد جدید</button></a></p>

        <div class="box">
            <div class="header">
                <h2>
                    <img class="icon" src="<?php echo(theme::url('img/icons/packs/fugue/16x16/blue-document-code.png')); ?>">
                    کدهای جاوا و فلش
                </h2>

            </div>
            <div class="content">
                <div class="tabletools">
                </div>
                <table class="styled" id="daynmic_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>عنوان</th>
                            <th>صفحه</th>
                            <th>مکان</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = db::query("SELECT * FROM {prefix}codes");
                        while ($row = mysqli_fetch_array($query)) {
                            switch ($row['page']) {
                                case('chat'):$row['page'] = 'داخل چت روم';
                                    break;
                                case('login'):$row['page'] = 'بیرون چت روم';
                                    break;
                                case('profile'):$row['page'] = 'پروفایل';
                                    break;
                                case('banned'):$row['page'] = 'صفحه اخراج';
                                    break;
                            }
                            switch ($row['location']) {
                                case('top'):$row['location'] = 'بالای صفحه';
                                    break;
                                case('bottom'):$row['location'] = 'پایین صفحه';
                                    break;
                            }
                            ?>
                            <tr id="code-<?php echo($row['id']); ?>">
                                <td><?php echo($row['id']); ?></td>
                                <td><p><?php echo($row['title']); ?></p><a href="javascript:delcode('<?php echo($row['id']); ?>');">[حذف]</a>
                                     <a href="../../../admin/theme/codeedit/<?php echo $row['id']; ?>">[ویرایش]</a>
                                  </td>					
                                <td><?php echo($row['page']); ?></td>
                                <td><?php echo($row['location']); ?></td>
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