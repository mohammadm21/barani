<?php
include('header.php');
include('sidebar.php');
$page = safe::number(giu('el4')) ? ((int) safe::number(giu('el4'))) : 1;
if ($page == 1) {
    $startpage = 0;
} else {
    $startpage = ($page - 1) * 100;
}
?>
<script type="text/javascript">
    function changepage(elem) {
        window.location.href = '<?php echo(url('admin/stats/list/\'+elem.value+\'')); ?>';
    }

</script>
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">آمارگیر</h1>

    <div class="grid_12">
        <div class="box">
            <div class="header">
                <h2>
                    <img class="icon" src="<?php echo(theme::url('img/icons/packs/fugue/16x16/chart.png')); ?>">
                    ریز آمار
                </h2>

            </div>
            <div class="content">
                <p >
                    صفحه:<select onchange="changepage(this);" class="search">
                        <?php
                        $num = db::num('stats');
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
                            <th>#</th>
                            <th>آی پی</th>
                            <th>زمان</th>
                            <th>کاربر</th>
                            <th>مرورگر</th>
                            <th>آدرس</th>
                            <th>سابقه</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = db::query("SELECT * FROM {prefix}stats WHERE 1 ORDER BY `time` DESC LIMIT $startpage,$msgshostory");
                        while ($row = mysqli_fetch_array($query)) {
                            ?>
                            <tr id="stats-<?php echo($row['id']); ?>">
                                <td align="center"><?php echo($row['id']); ?></td>
                                <td align="center"><?php echo($row['ip']); ?></td>
                                <td align="center"><?php echo(jdate::start('l d F Y <br /> H:i:s', $row['time'])); ?></td>
                                <td align="center"><?php
                                    if ($row['user'] != '0' and $user = db::get('users', 'name', "`id`='{$row['user']}'")) {
                                        echo($user);
                                    } else {
                                        echo(' مهمان '.$row['user']);
                                    }
                                    ?></td>
                                <td align="center"><?php echo($row['browser']); ?></td>
                                <td  align="left" dir="ltr"><?php echo($row['adress']); ?></td>
                               <td  align="left" dir="ltr"><?php echo($row['radar']); ?></td>
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