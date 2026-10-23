<?php
$title = "بستن چت روم";
$tabactive = 'setting';
$params = (array) json_decode($options['chat_closeparam']);
include('header.php');
?>
<script type="text/javascript">
    function closechat(action, value) {
        $.ajax({
            url: '<?php echo(url("ajax/admin.close")); ?>',
            type: "POST",
            data: {action: action, value: value},
            success: function (data) {
                if (data == 'ok') {
                    showmsg('success', 'انجام شد', 'درخواست با موفقیت ثبت شد');
                } else {
                    showmsg('warning', 'پاسخ سرور: ', data);
                }
            }
        });
    }

</script>
<div id="masthead">	
    <div class="container">	
        <div class="masthead-pad">	
            <div class="masthead-text">
                <h2>تعطیلی موقتی</h2>
                <p>گاهی لازم میشود فعالیت چت روم خود را متوقف کنید!</p>
            </div>
        </div>
    </div>
</div>
<div id="content">
    <div class="container">		
        <div class="row">
            <p>بستن چت روم: <input type="button" class="btn btn-primary" onclick="closechat('close', 'false');" value="بازکردن"> <input type="button"  onclick="closechat('close', 'true');" class="btn btn-secondary" value="بستن"></p>
            <p>قالب: <input type="text" onchange="closechat('theme', this.value);" value="<?php echo($params['theme']); ?>" style="direction: ltr;"></p>
            <p>پیام تعطیلی: <input type="text" onchange="closechat('msg', this.value);" value="<?php echo($params['msg']); ?>"></p>
            <p>شمارش معکوس تا راه اندازی: <input type="text" onchange="closechat('timetolunch', this.value);" value="<?php echo($params['timetolunch']); ?>" style="direction: ltr;"><span style="color: red;font-size: 9px;">ثانیه:دقیقه:ساعت:روز</span></p>
            <p>درصد اقدامات انجام شده: <input type="text" onchange="closechat('procentageDone', this.value);" value="<?php echo($params['procentageDone']); ?>" class="input-mini" maxlength="2"></p>


        </div>
    </div>
</div>
<?php include('footer.php'); ?>