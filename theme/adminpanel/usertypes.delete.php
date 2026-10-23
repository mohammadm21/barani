<?php
$type = safe::number(giu('el4'));
if (!$type = db::get('usertypes', '*', "`id`='$type'")) {
    include('pages/notfound.php');
    exit();
}

include('header.php');
include('sidebar.php');
?>
<script type="text/javascript">
    function del() {
        $.ajax({
            type: 'post',
            data: {type: '<?php echo($type['id']); ?>', totype: $('select[name=totype]').val(), token: '<?php echo(session('changepw')); ?>'},
            url: '<?php echo(url('ajax/admin.usertypes.delete')); ?>',
            success: function (data) {
                switch (data) {
                    case('ok'):
                        $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'حذف شد'});
                        break;
                    case('guest'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'امکان حذف سطح کاربری مهمان وجود ندارد'});
                        break;
                    case('admin'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'وجود حداقل یک سطح مدریت کامل لازم است'});
                        break;
                    case('totype'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'گروه جایگزینی صحیح نیست'});
                        break;
                    default:
                        alert(data);
                        break;
                }
            }
        });
        return false;
    }
</script>
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">سطوح کاربری</h1>
    <div class="grid_12">
        <div class="grid_12">
            <form class="box  validate" id="formdelype">
                <div class="header">
                    <h2>حذف <?php echo($type['title']); ?></h2>
                </div>
                <div class="content tabbed">
                    <?php
                    if ($type['guest'] == 1) {
                        ?>
                        <p style="color: red;text-align: center;font-family: BNazanin;font-size:19px">امکان حذف درجه مهمانان وجود ندارد</p>
                        <?php
                    } elseif (db::num('usertypes', "`id`!='{$type['id']}' and `admin` = '1'") == 0) {
                        ?>
                        <p style="color: red;text-align: center;font-family: BNazanin;font-size:19px">وجود حداقل یک سطح مدیریت کامل لازم است</p>

                        <?php
                    } else {
                        ?>
                        <div class="row">
                            <label><strong>انتقال کاربران به:</strong></label>
                            <div>
                                <select name="totype" class="search">
                                    <?php
                                    foreach ($usertype as $t) {
                                        if ($t['guest'] == 1) {
                                            continue;
                                        }
                                        if ($t['id'] == $type['id']) {
                                            continue;
                                        }
                                        ?>
                                        <option value="<?php echo($t['id']); ?>"><?php echo($t['title']); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <center> <button class="button blue flat block" onclick="return del();">ثبت</button></center>
                        <?php } ?>
                </div>
            </form>

        </div>
    </div>
</section>
<?php include('footer.php'); ?>