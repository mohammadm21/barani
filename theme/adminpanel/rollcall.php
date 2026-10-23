<?php
include('header.php');
include('sidebar.php');
?>
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">حضور و غیاب</h1>
    <p style="text-align: center;">
        <button class="button grey block" onclick="window.location.href = '/admin/rollcall/top'">مشاهده مدیران برتر</button>
    </p>
    <div class="grid_12">
        <div class="box">
            <div class="header">
                <h2>
                    <img class="icon" src="<?php echo(theme::url('img/icons/packs/fugue/16x16/users.png')); ?>">
                    حضور و غیاب
                </h2>

            </div>
            <div class="content">
                <div class="tabletools">
                </div>
                <table class="styled" id="daynmic_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>نام کاربری</th>
                            <th>نام</th>
                            <th>جنسیت</th>
                            <th>سطح کاربری</th>
                            <th>آی پی</th>
                            <th>آخرین ورود</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $admins = implode(',', Users::AdminsType());
                        $query = db::query("SELECT * FROM {prefix}users WHERE `type` IN($admins) ORDER BY `lastlogin` DESC");
                        while ($row = mysqli_fetch_array($query)) {
                            switch ($row['gender']) {
                                case('0'):$gender = 'نامشخص';
                                    break;
                                case('1'):$gender = 'مرد';
                                    break;
                                case('2'):$gender = 'زن';
                                    break;
                                default:$gender = 'نا مشخص';
                                    break;
                            }
                            ?>
                            <tr id="user-<?php echo($row['id']); ?>">
                                <td><?php echo($row['id']); ?></td>
                                <td><a href="../../../admin/users/edit/<?php echo $row[0] ?>"><?php echo($row['username']); ?></a></td>
                                <td><?php echo($row['name']); ?></td>
                                <td><?php echo($gender); ?></td>
                                <td><a href="<?php echo(url('admin/users/list/' . $row['type'])); ?>"><img src="<?php echo(theme::url($usertype[$row['type']]['icon'])); ?>"></a></td>
                                <td><?php echo($row['ip']); ?></td>
                                <td><?php echo(jdate::start('l d F Y ساعت H:i', $row['lastlogin'])); ?></td>
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