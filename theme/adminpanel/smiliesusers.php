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
        window.location.href = '<?php echo(url('admin/usersmilies/\'+elem.value+\'')); ?>';
    }
    function smile(id,action) {
        $.ajax({
            type: "POST",
            url: '<?php echo(url('ajax/admin.smilies.users')); ?>',
            data: {id: id,action:action},
            success: function (data) {
                switch (data) {
					case('ok'):
                        $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'شکلک مورد نظر تایید شد'});
                        break;
                    case('okdel'):
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
    <h1 class="grid_12 margin-top no-margin-top-phone">شکلک های ارسال شده</h1>

    <div class="grid_12">

        <div class="box">
            <div class="header">
                <h2>
                    <img class="icon" src="<?php echo(theme::url('img/icons/packs/fugue/16x16/animal.png')); ?>">
                    شکلک های ارسال شده
                </h2>

            </div>
            <div class="content">
                <p >
                    صفحه:<select onchange="changepage(this);" class="search">
                        <?php
                        $num = db::num('user_smile');
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
                            <th>ارسال شده توسط</th>
                            <th style="width: 50%;">کد</th>
                            <th>تصویر</th>
                            <th>وضعیت</th>
                            <th>مدیریت</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = db::query("SELECT * FROM {prefix}user_smile LIMIT $startpage,$msgshostory");
                        while ($row = mysqli_fetch_array($query)) {
                            $code = $row['smilecode'];
							$username = db::get("users",'username',"`id`={$row['user']}");
                            ?>
                            <tr id="smily-<?php echo($row['id']); ?>">
                                <td><?php echo($row['id']); ?></td>
                                <td align="center"><?php echo($username); ?></td>
                                <td align="center"><?=$code;?></td>
                                <td align="center"><img src="<?php echo(url($options['smilies_dir'] . '/' . $code . '.gif')); ?>" /></td>
                                <td align="center"><?php if($row['active']=="0")echo('<span class="badge block red dark">در انتظار تایید </span>');else echo('<span class="badge block blue dark">تایید شده </span>');?></td>
                                <td align="center">
                                <?php if($row['active']=="0"){?><a href="javascript:smile('<?php echo($row['id']); ?>','active');" class="with-icon"><div class="icon icon-pencil"></div> تایید</a><?php }?>
                                <a class="removeavatar with-icon" href="javascript:smile('<?php echo($row['id']); ?>','delete');"><div class="icon icon-remove"></div> حذف</a></td>
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