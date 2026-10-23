<?php
include('header.php');
include('sidebar.php');
$new = safe::number(giu('el4'));
$new = db::get('news', "*", "`id`='$new'");
if (!$new) {
    include('pages/notfound.php');
    return;
}
?>
<script type="text/javascript">
    function add() {
        var $form = $('#formeditnew');
        var data = {
            'id': '<?php echo($new['id']); ?>',
            'active': $('input[name=active]', $form).prop('checked'),
            'text': $('input[name=text]', $form).val()
        };
        $.ajax({
            type: "POST",
            url: "<?php echo(url('ajax/admin.news.edit')); ?>",
            data: data,
            success: function (data) {
                switch (data) {
                    case('ok'):
                        $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'ذخیره شد'});
                        break;
                    case('text'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'لطفا متن مناسبی وارد کنید'});
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
    <h1 class="grid_12 margin-top no-margin-top-phone">اخبار</h1>
    <div class="grid_12">
        <div class="grid_12">
            <form class="box  validate" id="formeditnew">
                <div class="header">
                    <h2>ویرایش خبر</h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>متن خبر :</strong></label>
                        <div><input class="required" autocomplete="off" type="text" name="text" value="<?php echo($new['text']); ?>" /></div>
                    </div>
                    <div class="row">
                        <label><strong>فعال:</strong></label>
                        <div>
                            <input type="checkbox" name="active" value="1" <?php if ($new['active'] == 1) echo('checked'); ?> />
                        </div>
                    </div>
                </div>
            </form>
            <center> <button class="button blue flat block" onclick="add();">ثبت</button></center>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>