<?php
include('header.php');
include('sidebar.php');
$useroptions = json_decode($options['msgoptions'],true);
$useroptionss = json_decode($options['useroptions'],true);
?>
<script type="text/javascript">
    function resemblancedesc() {
        $("#resemblancedes").dialog('open');

    }
    function edit() {
        $.ajax({
            type: "POST",
            url: "<?php echo(url('ajax/admin.settings.msg')); ?>",
            data: $('#formeditsettings').serialize(),
            success: function (data) {
                switch (data) {
                    case('ok'):
                        $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'ذخیره شد'});
                        break;
                    case('title'):
                        $.msgGrowl({type: 'success', title: 'لطفا نامی برای اتاق انتخاب کنید', text: 'خطا'});
                        break;
                    default:
                        alert(data);
                        break;
                }
            }
        });
    }
$(document).ready(function(){
    var $speedmsgenable = $('#speedmsgenable').prop('checked');
    if($speedmsgenable == true){
        $('#msgdiv').slideDown(1000);
    }else{
        $('#msgdiv').slideUp(1000);
    }
	var $score_msg_status = $('#score_msg_status').prop('checked');
        if($score_msg_status == true){
            $('#msgdivs').slideDown(1000);
        }else{
            $('#msgdivs').slideUp(1000);
        }
    $('.checkbox').click(function(){
        $speedmsgenable = $('#speedmsgenable').prop('checked');
        if($speedmsgenable == true){
            $('#msgdiv').slideDown(1000);
        }else{
            $('#msgdiv').slideUp(1000);
        }
        $score_msg_status = $('#score_msg_status').prop('checked');
        if($score_msg_status == true){
            $('#msgdivs').slideDown(1000);
        }else{
            $('#msgdivs').slideUp(1000);
        }
    });
	
    $("#resemblancedes").dialog({
        autoOpen:false,
        modal:true,
        width:500,
        buttons:[{text:"متوجه شدم",click:function(){$(this).dialog("close");}}]
    });
});
	    var formblock;
    var forminputs;

    function prepare() {
        formblock = document.getElementById('formeditsettings');
        forminputs = formblock.getElementsByTagName('input');
    }
	    function select_all(name, value) {
        for (i = 0; i < forminputs.length; i++) {
            // regex here to check name attribute
            var regex = new RegExp(name, "i");
            if (regex.test(forminputs[i].getAttribute('name'))) {
                if (value == '1') {
                    forminputs[i].checked = true;
                } else {
                    forminputs[i].checked = false;
                }
            }
        }
    }
    if (window.addEventListener) {
        window.addEventListener("load", prepare, false);
    } else if (window.attachEvent) {
        window.attachEvent("onload", prepare)
    } else if (document.getElementById) {
        window.onload = prepare;
    }

</script>
<div style="display: none;" id="resemblancedes" title="درصد شباهت">
    <p>ما برای جلوگیری از تکرار متون در چت روم شما انواع تدابیر را اندیشه کرده ایم.</p>
    <p>در این روش ما به شما اجازه میدهیم که پیام های تکراری را بر اساس شباهتشان با هم شناسایی کنید</p>
    <p>درصورتی که درصد شباهت را 0 قرار دهید این امکان غیرفعال میشود</p>
    <p style="text-align: center;">با ما در لبه تکنولوژی باشید</p>
</div>
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">تنظیمات</h1>
    <div class="grid_12">
        <div class="grid_12">
            <form class="box  validate" id="formeditsettings">
                <input type="hidden" name="token" value="<?php echo(session('changepw')); ?>" />
                <div class="header">
                    <h2>تنظیمات پیام ها</h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>محدودیت سرعت:</strong></label>
                        <?php
                        $msgspeedEnable = $options['chat_speedmsg'] != '0' ? true : false;
                        ?>
                        <div>
                            فعال: <input type="checkbox" value="1" name="speedmsgenable" id="speedmsgenable" <?php if ($msgspeedEnable) echo('checked'); ?> />
                            <div id="msgdiv" style="display: none;">
                                فاصله دو پیام: <input name="speedmsg" id="speedmsg" pattern="^\d{1,10}$" value="<?php echo($options['chat_speedmsg']); ?>" type="text" style="text-align: center;width: 100px;" />ثانیه 
                            </div><medium style="color:#4078da;">این محدودیت روش خوبی برا جلوگیری از پیام های خودکار نرم افزار های اسپمر می باشد
بهترین حالت دو ثانیه می باشد که در این صورت فاصله اولین پیام با دومین پیام باید حداقل دو ثانیه باشد تا پیام ارسال شود و یک انسان معمولی حداقل دو ثانیه طول میکشه پیام بعدی را ارسال کند . اگر ربات های اسپمر زیادی مشکل ایجاد کرد می توانید در حالت های سه ثانیه و بیشتر به طور موقت قرار دهید به طور موقت چون برای پیام  های معمولی کاربران اخطار عدم ارسال پیام می دهد.سپس از قسمت محدودیت امکانات درجه هایی را که میخواهید این محدودیت سرعت براشون فعال بشه گزینه هایشان را تیک بزنید و توصیه این است درجه مهمان و عضو فقط فعال شود</medium>


                        </div>
                    </div>
                    <div class="row">
                        <label><strong>تعداد پیام ها در عمومی:</strong></label>
                        <div>
                            <input type="text"  class="required" pattern="^\d{1,10}$" name="numsgmain" id="numsgmain" value="<?php echo($options['chat_numsgmain']); ?>" />
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>ارسال پیام مهمانان در عمومی:</strong></label>
                        <?php
                        $sendmsguestEnable = $options['chat_sendmsguest'] == 'true' ? true : false;
                        ?>
                        <div>
                            فعال: <input type="checkbox" value="1" name="sendmsguest"  id="sendmsguest" <?php if ($sendmsguestEnable) echo('checked'); ?> />
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>امتیاز لازم برای پیام در عمومی:</strong> <small> 0 = غیرفعال</small></label>
                        <div>
                            <input name="scoretosendmsg"  class="required" pattern="^\d{1,10}$" value="<?php echo($options['scoretosendmsg']); ?>" type="text" style="text-align: center;width: 100px;" />
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>حداکثر شکلک ها در یک پیام:</strong></label>
                        <div>
                            <input name="SmiliespMsg"  class="required" pattern="^\d{1,10}$" value="<?php echo($options['SmiliespMsg']); ?>" type="text" style="text-align: center;width: 100px;" />
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>تکرار متن:</strong></label>
                        <div><input type="text" name="repeatmsg"  id="repeatmsg" pattern="^(\d{1,10})$" style="padding-left: 0px;direction: ltr;font-family: sans-serif;text-align: center;" value="<?php echo($options['repeatmsg']); ?>" /></div>
                    </div>
                    <div class="row">
                        <label><strong>درصد شباهت:</strong> <small><a href="javascript:resemblancedesc();">[توضیحات]</a></small></label>
                        <div><input type="text" name="resemblance" id="resemblance"  pattern="^(\d{1,10})$" style="padding-left: 0px;direction: ltr;font-family: sans-serif;text-align: center;" value="<?php echo($options['resemblance']); ?>" /></div>
                    </div>
                     <div class="row">
                        <label><strong>امتیاز به کاربر برای ارسال پیام در عمومی:</strong></label>
                        <?php
                        $options['score_msg'] = json_decode($options['score_msg'],true);
                        ?>
                        <div>
                            فعال: <input type="checkbox" value="1" name="score_msg_status" id="score_msg_status" <?php if ($options['score_msg']['status'] == "true") echo('checked'); ?> />
                            <div id="msgdivs" style="display: none;">
                                به ازای هر: <input name="score_msg_num" id="score_msg_num" pattern="^\d{1,10}$" value="<?php echo($options['score_msg']['num']); ?>" type="text" style="text-align: center;width: 100px;" />پیام به کاربر مقدار <input name="score_msg_score" id="score_msg_score" pattern="^\d{1,10}$" value="<?php echo($options['score_msg']['score']); ?>" type="text" style="text-align: center;width: 100px;" /> امتیاز تعلق بگیرد 
                           <br><br>
                           <label><strong>گروه های مجاز به دریافت امتیاز:</strong><small></small></label>
                        <div>
                            <?php
                            foreach ($usertype as $type) {
                                ?>
                                <input type="checkbox" name="score_msg[]" value="<?php echo($type['id']); ?>" <?php if (in_array($type['id'], $useroptionss['score_msg'])) echo('checked'); ?> /> <?php echo($type['title']); ?>
                                <?php
                            }
                            ?>
                            <br>
                            <a style="color:red;cursor:pointer; " onClick="select_all('score_msg', '1');">انتخاب همه</a> | <a style="color:red;cursor:pointer; "  onClick="select_all('score_msg',
                                            '0');">
                                لغو انتخاب همه</a>
                            <br>
                        </div>
                            </div>


                        </div>
                    </div>
  <div class="row">
                        <label><strong> پاسخ پیام:</strong></label>
                        <div>
                            فعال: <input type="checkbox" value="1" name="reply"  id="reply" <?php if ($options['reply'] == 'true') echo('checked'); ?> />
                        </div>
                    </div>
                    <div class="row">
                        <label><strong> فوروارد پیام:</strong></label>
                        <div>
                            فعال: <input type="checkbox" value="1" name="forwardtext"  id="forwardtext" <?php if ($options['forwardtext'] == 'true') echo('checked'); ?> />
                        </div>
                    </div>
                  <div class="row">
                        <label><strong>حذف پیام:</strong></label>
                        <div>
                            فعال: <input type="checkbox" value="1" name="deletetext"  id="deletetext" <?php if ($options['deletetext'] == 'true') echo('checked'); ?> />
                        </div>
                    </div>
                  <div class="row">
                        <label><strong>ویرایش  پیام:</strong></label>
                        <div>
                            فعال: <input type="checkbox" value="1" name="edittext"  id="edittext" <?php if ($options['edittext'] == 'true') echo('checked'); ?> />
                        </div>
                    </div>
     <div class="row">
                        <label><strong>پاسخ پیام ها:</strong><small></small></label>
                        <div>
                            <?php
                            foreach ($usertype as $type) {
                                ?>
                                <input type="checkbox" name="reply[]" value="<?php echo($type['id']); ?>" <?php if (in_array($type['id'], $useroptionss['reply'])) echo('checked'); ?> /> <?php echo($type['title']); ?>
                                <?php
                            }
                            ?>
                            <br>
                            <a style="color:red;cursor:pointer; " onClick="select_all('reply', '1');">انتخاب همه</a> | <a style="color:red;cursor:pointer; "  onClick="select_all('reply',
                                            '0');">
                                لغو انتخاب همه</a>
                            <br>
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>فوروراد پیام ها:</strong><small></small></label>
                        <div>
                            <?php
                            foreach ($usertype as $type) {
                                ?>
                                <input type="checkbox" name="allowforward[]" value="<?php echo($type['id']); ?>" <?php if (in_array($type['id'], $useroptions['allowforward'])) echo('checked'); ?> /> <?php echo($type['title']); ?>
                                <?php
                            }
                            ?>
                            <br>
                            <a style="color:red;cursor:pointer; " onClick="select_all('forward', '1');">انتخاب همه</a> | <a style="color:red;cursor:pointer; "  onClick="select_all('allowforward',
                                            '0');">
                                لغو انتخاب همه</a>
                            <br>
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>حذف پیام ها:</strong><small></small></label>
                        <div>
                            <?php
                            foreach ($usertype as $type) {
                                ?>
                                <input type="checkbox" name="allowdelmsg[]" value="<?php echo($type['id']); ?>" <?php if (in_array($type['id'], $useroptions['allowdelmsg'])) echo('checked'); ?> /> <?php echo($type['title']); ?>
                                <?php
                            }
                            ?>
                            <br>
                            <a style="color:red;cursor:pointer; " onClick="select_all('allowdelmsg', '1');">انتخاب همه</a> | <a style="color:red;cursor:pointer; "  onClick="select_all('allowdelmsg',
                                            '0');">
                                لغو انتخاب همه</a>
                            <br>
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>ویرایش پیام ها:</strong><small></small></label>
                        <div>
                            <?php
                            foreach ($usertype as $type) {
                                ?>
                                <input type="checkbox" name="alloweditmsg[]" value="<?php echo($type['id']); ?>" <?php if (in_array($type['id'], $useroptions['alloweditmsg'])) echo('checked'); ?> /> <?php echo($type['title']); ?>
                                <?php
                            }
                            ?>
                            <br>
                            <a style="color:red;cursor:pointer; " onClick="select_all('alloweditmsg', '1');">انتخاب همه</a> | <a style="color:red;cursor:pointer; "  onClick="select_all('alloweditmsg',
                                            '0');">
                                لغو انتخاب همه</a>
                            <br>
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>پین پیام ها:</strong><small></small></label>
                        <div>
                            <?php
                            foreach ($usertype as $type) {
                                ?>
                                <input type="checkbox" name="allowpinmsg[]" value="<?php echo($type['id']); ?>" <?php if (in_array($type['id'], isset($useroptions['allowpinmsg']) ? $useroptions['allowpinmsg'] : array())) echo('checked'); ?> /> <?php echo($type['title']); ?>
                                <?php
                            }
                            ?>
                            <br>
                            <a style="color:red;cursor:pointer; " onClick="select_all('allowpinmsg', '1');">انتخاب همه</a> | <a style="color:red;cursor:pointer; "  onClick="select_all('allowpinmsg',
                                            '0');">
                                لغو انتخاب همه</a>
                            <br>
                        </div>
                    </div>
                </div>

            </form>
            <center> <medium style="color:red">پس از ویرایش ممکن است به طور طبیعی حدودا یک دقیقه اعمال تغییرات طول بکشد</medium><br><button class="button blue flat block" onclick="edit();">ویرایش</button></center>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>
