<?php
include('header.php');
include('sidebar.php');
$page = safe::number($el3) ? ((int) safe::number($el3)) : 1;
if ($page == 1) {
    $startpage = 0;
} else {
    $startpage = ($page - 1) * 100;
}
?>
<script type="text/javascript">
    function changepage(elem) {
        window.location.href = '<?php echo(url('admin/stats/\'+elem.value+\'')); ?>';
    }

</script>
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">آمارگیر</h1>

    <div class="grid_12">
        <div class="box">
            <div class="header">
                <h2>
                    <img class="icon" src="<?php echo(theme::url('img/icons/packs/fugue/16x16/chart.png')); ?>">
                    آمارگیر
                </h2>

            </div>
            <div class="content">
                <p >
                    صفحه:<select onchange="changepage(this);" class="search">
                        <?php
                        $num = db::num('stats_num', "`day`!='0'");
                        $msgshostory = 100;
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
                            <th>تاریخ</th>
                            <th>بازدید</th>
                            <th>عضویت</th>
                            <th>اخراج</th>
                            <th>پیام ها</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = db::query("SELECT * FROM {prefix}stats_num WHERE `day`!='0' ORDER BY `day` DESC LIMIT $startpage,$msgshostory");
                        while ($row = mysqli_fetch_array($query)) {
                            ?>
                            <tr id="user-<?php echo($row['id']); ?>">
                                <td><?php echo(jdate::start('l d F Y', $row['day'])); ?></td>
                                <th><?php echo($row['visit']); ?></th>
                                <th><?php echo($row['register']); ?></th>
                                <th><?php echo($row['bann']); ?></th>
                                <th><?php echo($row['msgs']); ?></th>
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