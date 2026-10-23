<?php
include('header.php');
include('sidebar.php');
?>
<script type="text/javascript">
    function edit() {

        var $form = $('#formeditquest');
        var data = {
            'quest': '<?php echo($question['id']); ?>',
            'question': $('input[name=question]', $form).val(),
            'answer': $('textarea[name=answer]', $form).val()
        };
        $.ajax({
            type: "POST",
            url: "<?php echo(url('ajax/admin.autoanswer.edit')); ?>",
            data: data,
            success: function (data) {
                switch (data) {
                    case('ok'):
                        $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'ذخیره شد'});
                        break;
                    case('question'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'سوالی وارد کنید!'});
                        break;
                    case('repeatquest'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'این متن دریافتی قبلا وارد شده'});
                        break;
                    case('answer'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'لطفا پاسخی وارد کنید'});
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
    <h1 class="grid_12 margin-top no-margin-top-phone">پاسخگوی خودکار</h1>
    <div class="grid_12">
        <div class="grid_12">
            <form class="box  validate" id="formeditquest">
                <div class="header">
                    <h2>افزودن پاسخ خودکار</h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>متن دریافتی:</strong></label>
                        <div> <input type="text" name="question" class="required" value="<?php echo($question['question']); ?>"/> </div>
                    </div>
                    <div class="row">
                        <label><strong>پاسخ:</strong><small>پاسخ هارا با  , از هم جدا کنید</small></label>
                        <div>
                            <textarea name="answer"><?php echo($question['answer']); ?></textarea>

                        </div>
                    </div>
                </div>

            </form>
            <center> <button class="button blue flat block" onclick="edit();">ویرایش</button></center>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>