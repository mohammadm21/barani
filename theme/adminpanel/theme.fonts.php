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
        window.location.href = '<?php echo(url('admin/theme/fonts/\'+elem.value+\'')); ?>';
    }
    function delete_fonts(id) {
        if (!id)
            return false;
        //alert(id);
        $.ajax({
            type: "POST",
            url: '<?php echo(url('ajax/admin.theme.fontsdelete')); ?>',
            data: {fonts: id},
            success: function (data) {
                switch (data) {
                    case('ok'):
                        $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'حذف شد'});
                        $('#fonts-' + id).slideUp(1000);
                        break;
                    default:
                        alert(data);
                        break;
                }
            }
        });
    }
    function updateSorts() {

        $.ajax({
            type: "POST",
            url: "<?php echo url('ajax/admin.theme.fontupdate'); ?>",
            data: $('#serializeSort').serialize(),
            success: function (data) {
                switch (data) {
                    case('ok'):
                        $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'ذخیره شد'});
                        break;
                    default:
                        alert(data);
                        break;
                }
            }
        });
    }


    var $font_onlinelist = 'tahoma';
    $(document).ready(function () {
        var allRadios = document.getElementsByName('radioFnt');
        var booRadio;
        var x = 0;
        for (x = 0; x < allRadios.length; x++) {
            allRadios[x].onclick = function () {
                if (booRadio == this) {
                    this.checked = false;
                    booRadio = null;
                } else {
                    $font_onlinelist = this.value;
                    booRadio = this;
                    $('input[name=checkboxFnt]#' + this.id).attr('checked', true);
                }
            }
        }
        $font_onlinelist = $('input[name=radioFnt]:checked').val();
        if (typeof $font_onlinelist == 'undefined') {
            $font_onlinelist = 'tahoma';
        }
        $('.btnadd').click(function () {
            var checkValues = $('input[name=checkboxFnt]:checked').map(function () {
                return $(this).val();
            }).get();
            $.ajax({
                url: '<?php echo url('ajax/admin.theme.fontsupdate'); ?>',
                type: 'POST',
                data: {checkboxFnt: checkValues, action: 'update', onlinelist: $font_onlinelist},
                success: function (data) {
                    if (data == 'ok') {
                        $.jGrowl('ویرایش شد', {header: 'موفقیت آمیز'});
                    } else {
                        alert(data);
                    }
                }
            });
        });
    });
</script>
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">فونت ها</h1>
    <div class="grid_12">
        <p style="text-align: center;"><a href="<?php echo(url('admin/theme/fontsadd')); ?>"><button class="button grey block">افزودن  فونت</button></a>
            <button class="button green block btnadd">ویرایش</button></p>
        <div class="box">
            <div class="header">
                <h2>
                    <img class="icon" src="<?php echo(theme::url('img/icons/packs/fugue/16x16/animal.png')); ?>">
                    فونت ها
                </h2>
            </div>
            <div class="content">
                <p>
                    صفحه:<select onchange="changepage(this);" class="search">
                        <?php
                        $num = db::num('chatfonts');
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
                            <th>نمونه</th>
                            <th>استفاده در عمومی/خ</th>
                            <th>فونت لیست آنلاین</th>
                            <th>حذف</th>
                        </tr>
                    </thead>
                    <tbody>
                    <form id="serializeSort">
                        <?php
                        $query = db::query("SELECT * FROM {prefix}chatfonts LIMIT $startpage,$msgshostory");
                        while ($row = mysqli_fetch_array($query)) {
                            ?>
                            <tr id="fonts-<?php echo($row['id']); ?>">
                                <td><?php echo($row['id']); ?></td>
                                <td>
                                    <?php echo($row['name']); ?>
                                </td>
                                <td>
                                    <b style="font-family:<?php echo("dcfont-" . $row['id']); ?>;color:red;">سلام خوبی؟</b>
                                </td>
                                <td><input name="checkboxFnt" type="checkbox" id="<?php echo($row['id']); ?>" value="<?php echo($row['id']); ?>" <?php if ($row['enable'] == '1') echo('checked'); ?> ></td>
                                <td><input name="radioFnt" type="radio" id="<?php echo($row['id']); ?>" value="<?php echo($row['id']); ?>"  ></td>
                                <td>
                                    <a href="javascript:delete_fonts('<?php echo($row['id']); ?>');"><span style="font-size: 10px;">[حذف]</span></a>
                                </td>
                            </tr>
                            <?php
                        }
                        mysqli_free_result($query);
                        ?>
                    </form>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>