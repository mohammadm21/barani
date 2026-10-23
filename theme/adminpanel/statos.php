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
        window.location.href = '<?php echo(url('admin/statoss/\'+elem.value+\'')); ?>';
    }
    function delete_statos(id) {
        if (!id)
            return false;
        //alert(id);
        $.ajax({
            type: "POST",
            url: '<?php echo(url('ajax/admin.statos.delete')); ?>',
            data: {statos: id},
            success: function (data) {
                switch (data) {
                    case('ok'):
                        $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'حذف شد'});
                        $('#statos-' + id).slideUp(1000);
                        break;
                    default:
                        alert(data);
                        break;
                }
            }
        });
    }
</script>
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">وضعیت ها</h1>
    <div class="grid_12">
        <p style="text-align: center;"><a href="<?php echo(url('admin/statos/add')); ?>"><button class="button grey block">افزودن  وضعیت</button></a></p>
        <div class="box">
            <div class="header">
                <h2>
                    <img class="icon" src="<?php echo(theme::url('img/icons/packs/fugue/16x16/animal.png')); ?>">
                    وضعیت ها
                </h2>
            </div>
            <div class="content">
                <p>
                    صفحه:<select onchange="changepage(this);" class="search">
                        <?php
                        $num = db::num('statos');
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
                            <th>نام</th>
                            <th>تصویر کوچک</th>
                            <th>تصویر بزرگ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = db::query("SELECT * FROM {prefix}statos LIMIT $startpage,$msgshostory");
                        while ($row = mysqli_fetch_array($query)) {
                            ?>
                            <tr id="statos-<?php echo($row['id']); ?>">
                                <td><?php echo($row['id']); ?></td>
                                <td>
                                    <?php echo($row['text']); ?>
                                    <br>
                        <center>
                            <a href="javascript:delete_statos('<?php echo($row['id']); ?>');"><span style="font-size: 10px;">[حذف]</span></a>
                        </center>
                        </td>
                        <td align="center"><img src="<?php echo url('theme/images/status/' . status_ . $row['id'] . ".png"); ?>" /></td>
                        <td align="center"><img src="<?php echo url('theme/images/status/' . status_ . $row['id'] . _larg . ".png"); ?>" /></td>
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