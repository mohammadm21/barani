<?php
include('header.php');
include('sidebar.php');
$page = safe::number($el3) ? ((int) safe::number($el3)) : 1;
if ($page == 1) {
    $startpage = 0;
} else {
    $startpage = ($page - 1) * 20;
}
?>
<script type="text/javascript">
    function changepage(elem) {
        window.location.href = '<?php echo(url('admin/sendedimages/\'+elem.value+\'')); ?>';
    }
    function delete_link(link_id) {
        $('#link-' + link_id + ' td')[0].innerHTML = "لطفا صبر کنید...";
        $.ajax({
            url: '<?php echo(url('ajax/admin.sendedimages.del')); ?>',
            type: "POST",
            data: {link: link_id},
            success: function (data) {
                switch (data) {
                    case('ok'):
                        $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'با موفقیت پاک شد'});
                        $('#link-' + link_id).slideUp(1000);
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
    <h1 class="grid_12 margin-top no-margin-top-phone">فایل های ارسال شده در خصوصی</h1>
    <div class="grid_12">
        <div class="box">
            <div class="header">
                <h2>
                    <img class="icon" src="<?php echo(theme::url('img/icons/packs/fugue/16x16/sitemap.png')); ?>">
                    اطلاعات
                </h2>
            </div>
            <div class="content">
                <p>
                    صفحه:<select onchange="changepage(this);" class="search">
                        <?php
                        $num = db::num('sendedimages');
                        $msgshostory = 20;
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
                            <th>فرستنده</th>
                            <th>گیرنده</th>
                            <th>آدرس فایل</th>
                            <th>پیشنمایش</th>
                            <th>تاریخ</th>
                            <th>حذف</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = db::query("SELECT * FROM {prefix}sendedimages LIMIT $startpage,$msgshostory");
                        while ($link = mysqli_fetch_array($query)) {
                            $sender_type = Users::get('type', $link['sender_id']);
                            if ($link['userid']) {
                                $reciver_type = Users::get('type', $link['userid']);
                            }
                            if (substr($link['url'], 0, 6) == "theme/") {
                                $link['url'] = "/".$link['url'];
                            }
                            $cf = $link['into'] == 'cf' ? 'اتاق کنفرانس ' : '';
                            $preview = '<a target="_blank" href="' . $link['url'] . '">کلیک کنید</a>';
                            $extension = substr($link['url'], strrpos($link['url'], '.') + 1);
                            if (in_array($extension, array('jpg', 'png', 'gif'))) {
                                $preview = '<img style="width:100px;height:100px;" src="' . $link['url'] . '">';
                            }
                            ?> 
                            <tr id="link-<?php echo($link['id']); ?>">
                                <td><?php echo($link['id']); ?></td>
                                <td><center><b><font color="<?php echo $usertype[$sender_type]['color'] ?>"><?php echo(Users::get('name', $link['sender_id'])); ?></font></b> <img src="<?php echo url('theme/images/user-types/' . $sender_type . '.png'); ?>"></center></td>
                                <td><center><?php  if ($link['userid']) { ?><b><?php echo $cf; ?><font color="<?php echo $usertype[$reciver_type]['color'] ?>"><?php echo(Users::get('name', $link['userid'])); ?></font></b> <img src="<?php echo url('theme/images/user-types/' . $reciver_type . '.png'); ?>"><?php }else { echo ("عمومی"); }?></center></td>
                                <td><center><?php echo('<a target="_blank" href="' . $link['url'] . '">کلیک کنید</a>'); ?></center></td>
                                <td><center><?php echo($preview); ?></center></td>
                                <td><center><?php echo(jdate::start('l d F Y ساعت H:i:s', $link['time'])); ?></center></td>
                                <td><center><p class="line_p"><a style="font-size: smaller;" onclick="return delete_link('<?php echo($link['id']); ?>');" href="javascript:void(0);"><b>حذف</b></a></p></center></td>
                        </tr>
                        <?php
                    }
                    mysqli_free_result($query);
                    ?>
                    </tbody>
                </table>
                <div class="tabletools">
                </div>
                <br>
                <center style="direction:rtl">
                    <?php
                    $num = db::num('sendedimages');
                    $msgshostory = 20;
                    $totalpages = ceil($num / $msgshostory);
                    $x = 0;
                    while ($x < $totalpages) {
                        $nextpage = $x + 1;
                        $x++;
                        $num -= $msgshostory;
                        if ($nextpage == $page) {
                            $selected = "class=\"paginate_active\"";
                        } else {
                            $selected = "";
                        }
                        echo("<div id=\"DataTables_Table_0_paginate\" class=\"dataTables_paginate paging_full_numbers\"><span><a  $selected href=\"../../admin/sendedimages/$nextpage\" class=\"paginate_button\">$nextpage</a></span></div>");
                    }
                    if ($x == 0) {
                        echo("<span>1</span>");
                    }
                    ?>
                </center>
                <br>
                <div class="tabletools">
                </div>
            </div>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>