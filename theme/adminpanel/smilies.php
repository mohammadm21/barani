<?php
include('header.php');
include('sidebar.php');

$page = safe::number($el3) ? ((int) safe::number($el3)) : 1;
if ($page == 1) {
    $startpage = 0;
} else {
    $startpage = ($page - 1) * 50;
}
?>
<script type="text/javascript">
    function changepage(elem) {
        window.location.href = '<?php echo(url('admin/smilies/\'+elem.value+\'')); ?>';
    }
    function deletesmily(id) {
        $.ajax({
            type: "POST",
            url: '<?php echo(url('ajax/admin.smilies.delete')); ?>',
            data: {smily: id},
            success: function (data) {
                switch (data) {
                    case('ok'):
                        $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'ذخیره شد'});
                        $('#smily-' + id).slideUp(1000);
                        break;
                    default:
                        $.msgGrowl({type: 'warning', title: 'پیام سرور'});
                        break;
                }
            }
        });
    }
</script>
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">شکلک ها</h1>

    <div class="grid_12">
        <p style="text-align: center;"><a href="<?php echo(url('admin/smilies/add')); ?>"><button class="button grey block">افزودن  شکلک</button></a></p>
        <div class="box">
            <div class="header">
                <h2>
                    <img class="icon" src="<?php echo(theme::url('img/icons/packs/fugue/16x16/animal.png')); ?>">
                    شکلک ها
                </h2>

            </div>
            <div class="content">
                <p >
                    صفحه:<select onchange="changepage(this);" class="search">
                        <?php
                        $num = db::num('smilies');
                        $msgshostory = 50;
                        $totalpages = ceil($num / $msgshostory);
                        $x = 0;
                        while ($x < $totalpages) {
                            $nextpage = $x + 1;
                            $x++;
                            $num -= $msgshostory;
                            if ($nextpage == $page) {
                                $selected = " selected=\"selected\"";
                            } else {
                                $selected = "";
                            }
                            echo("<option$selected>$nextpage</option>");
                        }
                        if ($x == 0) {
                            echo("<option>1</option>");
                        }
                        ?>
                    </select>
                </p>
                <div class="tabletools">
                </div>
                <table class="styled" id="daynmic_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th style="width: 50%;">کد</th>
                            <th>اولویت نمایشی</th>
                            <th>تصویر</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = db::query("SELECT * FROM {prefix}smilies LIMIT $startpage,$msgshostory");
                        while ($row = mysqli_fetch_array($query)) {
                            $code = $row['name'] == null ? $row['code'] : $row['name'];
                            ?>
                            <tr id="smily-<?php echo($row['id']); ?>">
                                <td><?php echo($row['id']); ?></td>
                                <td>
                                    <a href="../../../admin/smilies/edit/<?php echo $row[0]; ?>"><?php echo($code); ?></a>
                                    <br />
                        <center><a href="../../../admin/smilies/edit/<?php echo $row[0]; ?>">[ویرایش]</a>  <a href="javascript:deletesmily('<?php echo($row['id']); ?>');"><span style="font-size: 10px;">[حذف]</span></a></center>
                        </td>
                        <td><?php echo($row['priority']); ?></td>
                        <td align="center"><img src="<?php echo(url($options['smilies_dir'] . '/' . $row['img'])); ?>" /></td>
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