<?php
adminpanel::check_Permission('notfound', 'editusers');
include('header.php');
include('sidebar.php');
$id = $useredit['id'];
Users::CheckProfile($id);
$profile = db::get('profiles', '*', "`id`='$id'");
switch ($useredit['online']) {
    case('1'):$online = '<span class="badge block green">آنلاین</span>';
        break;
    case('0'):$online = '<span class="badge block red">آفلاین</span>';
        break;
}
$user['birthDate'] = explode('|', $profile['birthdate']);
if ($user['birthDate'][0] == '0') {
    $birthDate = "وارد نشده";
} else {
    switch ($user['birthDate'][1]) {
        case('1'):$birthday['type']['day'] = 'checked="checked"';
            $birthday['type']['month'] = 'checked="checked"';
            $birthday['type']['year'] = 'checked="checked"';
            break;
        case('2'):$birthday['type']['day'] = 'checked="checked"';
            $birthday['type']['month'] = '';
            $birthday['type']['year'] = '';
            break;
        case('3'):$birthday['type']['day'] = '';
            $birthday['type']['month'] = 'checked="checked"';
            $birthday['type']['year'] = '';
            break;
        case('4'):$birthday['type']['day'] = '';
            $birthday['type']['month'] = '';
            $birthday['type']['year'] = 'checked="checked"';
            break;
        case('5'):$birthday['type']['day'] = 'checked="checked"';
            $birthday['type']['month'] = 'checked="checked"';
            $birthday['type']['year'] = '';
            break;
        case('6'):$birthday['type']['day'] = 'checked="checked"';
            $birthday['type']['month'] = '';
            $birthday['type']['year'] = 'checked="checked"';
            break;
        case('7'):$birthday['type']['day'] = '';
            $birthday['type']['month'] = 'checked="checked"';
            $birthday['type']['year'] = 'checked="checked"';
            break;
    }
}

$birthday['type']['org'] = explode('|', $profile['birthdate']);



include('inc/classes/wolde.php');

switch ($birthday['type']['org'][1]) {
    case('1'):$birthday['type']['day'] = 'checked="checked"';
        $birthday['type']['month'] = 'checked="checked"';
        $birthday['type']['year'] = 'checked="checked"';
        break;
    case('2'):$birthday['type']['day'] = 'checked="checked"';
        $birthday['type']['month'] = '';
        $birthday['type']['year'] = '';
        break;
    case('3'):$birthday['type']['day'] = '';
        $birthday['type']['month'] = 'checked="checked"';
        $birthday['type']['year'] = '';
        break;
    case('4'):$birthday['type']['day'] = '';
        $birthday['type']['month'] = '';
        $birthday['type']['year'] = 'checked="checked"';
        break;
    case('5'):$birthday['type']['day'] = 'checked="checked"';
        $birthday['type']['month'] = 'checked="checked"';
        $birthday['type']['year'] = '';
        break;
    case('6'):$birthday['type']['day'] = 'checked="checked"';
        $birthday['type']['month'] = '';
        $birthday['type']['year'] = 'checked="checked"';
        break;
    case('7'):$birthday['type']['day'] = '';
        $birthday['type']['month'] = 'checked="checked"';
        $birthday['type']['year'] = 'checked="checked"';
        break;
}
?>
<script type="text/javascript">
function typesetting(){
	var $form = $('#formedituser');
    if($('input[name=typesetting_time]', $form).val() > '0'){
        $('#typesetting_types').slideDown(1000);
    }else{
        $('#typesetting_types').slideUp(1000);
    }
}
$(document).ready(function(){
    var $type_time = $('#typesetting_time').val();
    if($type_time > '0'){
        $('#typesetting_types').slideDown(1000);
    }else{
        $('#typesetting_types').slideUp(1000);
    }
});
    function inArray(needle, haystack) {
    var length = haystack.length;
    for (var i = 0; i < length; i++) {
    if (haystack[i] == needle) return true;
    }
    return false;
    }
    function changescore(elem){

    var score = elem.value;
    if (score == ''){score = 0; elem.value = '0'; }
    $('input[name=onlinetime]').val(score * 60);
    var type = $('#type_user').val(), newtype = '00';
<?php
$admins = Users::AdminsType();
$admins = implode(',', $admins);
?>
    var admins = '<?php echo($admins); ?>';
    admins = admins.split(',');
    if (inArray(type, admins))return false;
<?php
$types = array();
foreach ($usertype as $val) {
    if ($val['guest'] == 1) {
        continue;
    }
    if ($val['admin'] == '1') {
        continue;
    }
    if ($options['automod'] == 'false' and $val['admin'] != '0') {
        continue;
    }
    $types[$val['id']] = (int) $val['score'];
}
arsort($types);
$x = 0;
foreach ($types as $keytype => $scoree) {
    if ($keytype == '0') {
        continue;
    }
    ?>if(score > <?php echo($scoree); ?>){type = '<?php echo($keytype); ?>'; }<?php
    if ($x != count($types) - 1) {
        echo('else ');
    }
    $x++;
}
?>


    $('#type_user').val(type);
    $("#type_user").trigger("liszt:updated");
    }
    function edit(){
    var $form = $('#formedituser');
    var data = {
    'token':'<?php echo(session('changepw')); ?>',
            'id':'<?php echo($useredit['id']); ?>',
            'username':$('input[name=username]', $form).val(),
            'name':$('input[name=name]', $form).val(),
            'gender':$('select[name=gender]', $form).val(),
            'type':$('select[name=type]', $form).val(),
            'score':$('input[name=score]', $form).val(),
            'bank':$('input[name=bank]', $form).val(),
            'activeuser':$('input[name=activeuser]', $form).prop('checked'),
            'activeprofile':$('input[name=activeprofile]', $form).prop('checked'),
            'country':$('select[name=country]', $form).val(),
            'city':$('select[name=city]', $form).val(),
            'infos':$('textarea[name=infos]', $form).val(),
            'site':$('input[name=site]', $form).val(),
            'email':$('input[name=email]', $form).val(),
            'typesetting_time':$('input[name=typesetting_time]', $form).val(),
            'typesetting_type':$('select[name=typesetting_type]', $form).val()
    };
    if ($('input[name=password]', $form).length > 0)data.password = $('input[name=password]', $form).val();
    $.ajax({
    type: "POST",
            url: "<?php echo(url('ajax/admin.users.edit')); ?>",
            data: data,
            success: function(data){
            switch (data){
            case('ok'):$.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'ذخیره شد'}); break;
            case('emailpattern'):$.msgGrowl({type: 'warning', title: 'خطا', text: 'لطفا یک ایمیل معتبر وارد کنید'}); break;
            case('emailrepeat'):$.msgGrowl({type: 'warning', title: 'خطا', text: 'این ایمیل متعلق به کاربر دیگری است'}); break;
            case('nullusername'):$.msgGrowl({type: 'warning', title: 'خطا', text: 'پر کردن نام کاربری اجباری است'}); break;
            case('lenusername'):$.msgGrowl({type: 'warning', title: 'خطا', text: 'تعداد حروف نام کاربری پیش از حد تعیین شده است'}); break;
            case('lenname'):$.msgGrowl({type: 'warning', title: 'خطا', text: 'تعداد حروف نمایشی بیش از حد تعیین شده است'}); break;
            default:alert(data); break;
            }
            }
    });
    }
</script>
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">ویرایش کاربر</h1>
    <div class="grid_12">
        <div class="grid_12">
            <div class="box tabbedBox">
                <div class="header">
                    <h2><?php echo($useredit['username']); ?></h2>
                    <ul>
                        <li><a href="#edit-base">ویرایش اطلاعات پایه</a></li>
                        <li><a href="#edit-profile">ویرایش پروفایل</a></li>
                    </ul>
                </div>
                <form class="validate" id="formedituser">
                    <div class="content tabbed">
                        <div id="edit-base">
                            <p>شماره کاربری: <input type="text" name="id" value="<?php echo($useredit['id']); ?>"  disabled="disabled" readonly="yes"  style="width: 100px;text-align: center;"/></p>
                            <p>نام کاربری: <input type="text" name="username" maxlength="<?php echo($options['chat_maxlengthname']); ?>"  class="required" value="<?php echo($useredit['username']); ?>"/></p>
                            <p>نام: <input type="text" name="name"  class="required "  maxlength="<?php echo($options['chat_maxlengthname']); ?>" value="<?php echo($useredit['name']); ?>"/></p>
                            <p>جنیست: <select name="gender" class="chzn-select chzn-rtl search">
                                    <option value="1"<?php if ($useredit['gender'] == '1') echo(' selected="selected"'); ?>>مذکر</option>
                                    <option value="2"<?php if ($useredit['gender'] == '2') echo(' selected="selected"'); ?>>مونث</option>
                                </select></p>
                            <?php
                            if (adminpanel::check_Permission('bool', 'changepw') or adminpanel::check_Permission('bool', 'changepwadminteam') or adminpanel::check_Permission('bool', 'seepwadminteam') or adminpanel::check_Permission('bool', 'seepw')) {
                                $readonly = true;
                                $showpw = false;
                                if (($usertype[$useredit['type']]['admin'] != 0 and adminpanel::check_Permission('bool', 'changepwadminteam')) or ( $usertype[$useredit['type']]['admin'] == 0 and adminpanel::check_Permission('bool', 'changepw'))) {
                                    $readonly = false;
                                }
                                if (($usertype[$useredit['type']]['admin'] != 0 and adminpanel::check_Permission('bool', 'seepwadminteam')) or ( $usertype[$useredit['type']]['admin'] == 0 and adminpanel::check_Permission('bool', 'seepw'))) {
                                    $showpw = true;
                                }
                                if ($readonly and ! $showpw) {
                                    
                                } else {
                                    ?>
                                    <p>کلمه عبور ( <a href="javascript:;" style="color: rgb(255, 0, 0);" onclick="getpassword()">نمایش کلمه عبور</a> ) : <input <?php if ($readonly) echo('readonly');  if($id ==1) echo ('disabled'); ?> type="text" name="password" value="" />  به دلیل بالا بردن امنیت رمز نگاری پیچیده شده و امکان مشاهده پسورد نیست . اگر کاربری رمز خود را گم کرده است لطفا رمز جدید برایش بگذارید و به کاربر تحویل دهید و بگویید از داخل روم رمز خود را شخصا عوض کند   </p>
                                    <?php
                                }
                            }
                            ?>
                            <p>درجه کاربری: <select id="type_user" name="type" class="chzn-select chzn-rtl search required">
                                    <?php
                                    foreach ($usertype as $type) {
                                             //---------------------edited---------
                                            if($type['id'] ==1)
                                             $type['id'] =2;
                                             //---------------------edite end---------

                                        ?>
                                        <option value="<?php echo($type['id']); ?>"<?php if ($useredit['type'] == $type['id']) echo(' selected="selected"'); ?>><?php echo($type['title']); ?></option>

                                        <?php
                                    }
                                    ?>
                                </select></p>
                                <?php 
								$useredit['typesetting'] = json_decode($useredit['typesetting'],true);
								if($useredit['typesetting']['time'] != "0"){	
								$type_time = jdate::start('Y/m/d H:i:s', $useredit['typesetting']['time']);
									
								}else{
								$type_time = '0';	
								 	
								}
								?>
                            <p>مدت زمان درجه: <input type="text" name="typesetting_time" id="typesetting_time" onchange="typesetting()" value="<?php echo($useredit['typesetting']['times']); ?>"> روز <span style="color: red;font-size: 9px;">0 =  غیر فعال</span>
                            </p>
                            <p id="typesetting_types" style="display:none">
                            زمان غیر فعال شدن درجه: <input type="text" value="<?php echo($type_time); ?>" readonly="yes" disabled="disabled">
                            <br /><br />
                            
                            تغییر درجه بعد از اتمام زمان درجه به : <select id="typesetting_type" name="typesetting_type" class="chzn-select chzn-rtl search required">
                                    <?php
                                    foreach ($usertype as $type) {
                                        ?>
                                        <option value="<?php echo($type['id']); ?>"<?php if ($useredit['typesetting']['type'] == $type['id']) echo(' selected="selected"'); ?>><?php echo($type['title']); ?></option>

                                        <?php
                                    }
                                    ?>
                                </select></p>
                            <p>آخرین ورود: <input type="text" name="lastlogin" value="<?php echo(jdate::start('Y/m/d H:i:s', $useredit['lastlogin'])); ?>" readonly="yes"></p>
                            <p>مدت زمان آنلاین بودن: <input type="text" disabled="disabled" name="onlinetime" value="<?php echo($useredit['onlinetime']); ?>">ثانیه</p>
                            <p>امتیاز: <input type="text" name="score" value="<?php echo($useredit['score']); ?>"   class="required" onkeydown="changescore(this);" onchange="changescore(this);" onkeypress="changescore(this);" onkeyup="changescore(this);" <?php if ($useredit['type'] == '1') echo(' disabled="disabled"'); ?>><span style="color: red;font-size: 9px;">لطفا با توجه به سطح کاربری امتیاز دهی فرمائید</span></p>
                            <p>امتیاز بانک: <input type="text" name="bank" value="<?php echo($useredit['bank']); ?>"   class="required" <?php if ($useredit['type'] == '1') echo(' disabled="disabled"'); ?>></p>
                            <p>آی پی: <input type="text" name="ip" value="<?php echo($useredit['ip']); ?>" readonly="yes"/></p>
                            <p>آنلاین: <?php echo($online); ?></p>
                            <p>فعال: <input type="checkbox" <?php if ($useredit['active'] == '1') echo('checked'); ?> name="activeuser"  /></p>
                        </div>
                        <div id="edit-profile" style="position: relative;">
                            <a id="change_avatar_btn" href="javascript:void(0);"><img height="220" width="160" id="avatarimg" src="<?php echo(theme::url('avatars/' . $profile['avatar'], 'images')); ?>" alt="" style="position: absolute;left:0;top:20px;" /></a>
                            <script type="text/javascript">
                                $(document).ready(function(){
                                $("#change_avatar").dialog({
                                autoOpen: false,
                                        modal: true,
                                        width: 400,
                                        open: function(){ $(this).parent().css('overflow', 'visible'); $$.utils.forms.resize() }
                                }).find('button.submit').click(function(){
                                var $el = $(this).parents('.ui-dialog-content');
                                $.ajaxFileUpload({
                                url:'<?php echo(url('ajax/admin.users.changeavatar')); ?>',
                                        secureuri:false,
                                        fileElementId:'file_avatar',
                                        data:{user:'<?php echo($useredit['id']); ?>'},
                                        dataType: 'text',
                                        success: function (data, status){
                                        switch (data){
                                        case('type'):$.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'نوع فایل توسط سرور تایید نشد'}); break;
                                        case('security'):$.msgGrowl({type: 'success', title: 'خطا', text: 'سرور در فایل شما مورد امنیتی یافت گرد فایل شما حذف گردید'}); break;
                                        default:
                                                if (data.substr(0, 2) == 'ok'){
                                        $('#avatarimg').attr('src', data.substr(3));
                                        } else{alert(data); }

                                        break;
                                        }
                                        },
                                        error: function (data, status, e)
                                        {
                                        alert(data);
                                        },
                                        done:function(){
                                        $el.dialog('close');
                                        }
                                });
                                }).end().find('button.cancel').click(function(){
                                var $el = $(this).parents('.ui-dialog-content');
                                $el.find('form')[0].reset();
                                $el.dialog('close'); ;
                                });
                                $("#change_avatar_btn").click(function() {
                                $("#change_avatar").dialog("open");
                                return false;
                                });
                                });
                            </script>
                            <script type="text/javascript" src="<?php echo(theme::url('js/ajaxfileupload.js')); ?>"></script>
                            <div style="display: none;" id="change_avatar" title="تغییر آواتار <?php echo($useredit['username']); ?>">
                                <form action="" class="full" enctype="multipart/form-data">
                                    <div class="row">
                                        <label for="file_avatar"><strong>انتخاب فایل</strong></label>
                                        <div>
                                            <input type="file" id="file_avatar" name="file_avatar" />
                                        </div>
                                    </div>
                                </form>
                                <div class="actions">
                                    <div class="left"><button class="grey cancel">انصراف</button></div>
                                    <div class="right"><button class="submit">تغییر</button></div>
                                </div>
                            </div>

                            <p>پروفال فعال؟ <input <?php if ($profile['active'] == '1') echo('checked'); ?> type="checkbox" name="activeprofile" /></p>
                            <p><label>تاریخ تولد:</label> 
                                <label>روز:<input type="text" value="<?php echo(jdate::start('d', $birthday['type']['org'][0])); ?>" style="width: 20px;" id="tt.day" name="tt.day" maxlength="2"/></label>
                                <label> ماه: <input type="text"  value="<?php echo(jdate::start('m', $birthday['type']['org'][0])); ?>" class="tt" style="width: 20px;" id="tt.month" name="tt.month"  maxlength="2"/></label>
                                <label> سال: <input type="text"  value="<?php echo(substr(jdate::start('Y', $birthday['type']['org'][0]), 2)); ?>" class="tt" style="width: 20px;"  name="tt.year" id="tt.year" maxlength="2"/>13</label>
                            </p>

                            <p><label>کشور: </label><select id="country" name="country" class="chzn-select chzn-rtl search">
                                    <?php wolde::countries('check', $profile['country']); ?>
                                </select></p>
                            <p><label>شهر :</label><select name="city"  class="chzn-select chzn-rtl search">
                                    <?php wolde::cyties('check', $profile['city']); ?>
                                </select></p>
                            <p><label>توضیحات:</label><textarea name="infos" cols="50" rows="5"><?php echo($profile['info']); ?></textarea></p>
                            <p><label>پایگاه اینترنتی:</label> <span dir="ltr">http://www.<input style="text-align: left;" name="site" type="text" value="<?php echo($profile['site']); ?>"></span></p>
                            <p><label>ایمیل:</label> <input style="text-align: left;" name="email" type="text" value="<?php echo($useredit['email']); ?>"></p>

                        </div>
                        <div id="t1-c3">

                        </div>
                    </div>
                </form>
                <center>
                    <?php if ($useredit['email'] != '') { ?><button class="button blue flat block" onclick="window.location.href = '<?php echo(url("admin/users/sendmail/{$useredit['id']}")); ?>'">ارسال ایمیل</button><?php } ?>
                    <?php if (adminpanel::check_Permission('bool', 'bann')) { ?>
                        <button class="button red flat block" id="dialog_bann_btn">اخراج</button>
                        <script type="text/javascript">
                            $(document).ready(function(){
                            $("#dialog_bann").dialog({
                            autoOpen: false,
                                    modal: true,
                                    width: 400,
                                    open: function(){ $(this).parent().css('overflow', 'visible'); $$.utils.forms.resize() }
                            }).find('button.submit').click(function(){
                            var $el = $(this).parents('.ui-dialog-content');
                            if ($el.validate().form()) {
                            var $form = $el.find('form')[0];
                            $.ajax({
                            type: "POST",
                                    url: "<?php echo(url('ajax/admin.users.bann')); ?>",
                                    data:{user:'<?php echo($useredit['id']); ?>', date:$('#to_btnn').val(), reason:$('#reasonBann').val(), token:'<?php echo(session('changepw')); ?>'},
                                    success: function(data){
                                    switch (data){
                                    case('ok'):$.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'اخراج شد'}); break;
                                    case('admin'):$.msgGrowl({type: 'success', title: 'خطا', text: 'این کاربر،یک مدیر است'}); break;
                                    case('user'):$.msgGrowl({type: 'success', title: 'خطا', text: 'این کاربر وجود ندارد'}); break;
                                    default:alert(data); break;
                                    }
                                    }
                            });
                            $form.reset();
                            $el.dialog('close');
                            }
                            }).end().find('button.cancel').click(function(){
                            var $el = $(this).parents('.ui-dialog-content');
                            $el.find('form')[0].reset();
                            $el.dialog('close'); ;
                            });
                            $("#dialog_bann_btn").click(function() {
                            $("#dialog_bann").dialog("open");
                            return false;
                            });
                            });
                        </script>

                        <div style="display: none;" id="dialog_bann" title="اخراج <?php echo($useredit['username']); ?>">
                            <form action="" class="full">
                                <div class="row">
                                    <label for="to_btnn"><strong>دلیل اخراج</strong></label>
                                    <div>
                                        <input name="reason" id="reasonBann" type="text" value="">
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="to_btnn"><strong>مدت زمان</strong></label>
                                    <div>
                                        <select style="padding-bottom: 10px" name="to_btnn" id="to_btnn" class="search required" data-placeholder="یک بازه زمانی را انتخاب کنید">
                                            <option value=""></option>
                                            <option value="exit">لحظه ای</option>
                                            <option value="1min">یک دقیقه</option>
                                            <option value="10min">ده دقیقه</option>
                                            <option value="30min">سی دقیقه</option>
                                            <option value="1hour">یک ساعت</option>
                                            <option value="12hour">دوازده ساعت</option>
                                            <option value="1day">یک روز</option>
                                            <option value="3day">سه روز</option>
                                            <option value="1week">یک هفته</option>
                                            <option value="2week">دوهفته</option>
                                            <option value="1month">یک ماه</option>
                                            <option value="6month">شش ماه</option>
                                            <option value="1year">یک سال</option>
                                            <option value="always">همیشگی</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                            <div class="actions">
                                <div class="left"><button class="grey cancel">انصراف</button></div>
                                <div class="right"><button class="submit">اخراج</button></div>
                            </div>
                        </div>
                        <?php
                    }
                    if (adminpanel::check_Permission('bool', 'delusers')) {
                        ?>
                        <button class="button red flat block" id="dialog_delete_btn">حذف</button>
                        <script type="text/javascript">
                            $(document).ready(function(){
                            $("#dialog_delete").dialog({
                            autoOpen: false,
                                    modal: true,
                                    width: 400,
                                    open: function(){ $(this).parent().css('overflow', 'visible'); $$.utils.forms.resize() }
                            }).find('button.submit').click(function(){
                            var $el = $(this).parents('.ui-dialog-content');
                            $.ajax({
                            type: "POST",
                                    url: "<?php echo(url('ajax/admin.users.delete')); ?>",
                                    data: {'user':'<?php echo($useredit['id']); ?>', token:'<?php echo(session('changepw')); ?>'},
                                    success: function(data){
                                    switch (data){
                                    case('ok'):$.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'ذخیره شد'}); setTimeout("window.location.href = '<?php echo(url('ref')); ?>';", 1500); break;
                                    case('user'):$.msgGrowl({type: 'success', title: 'خطا', text: 'این کاربر وجود ندارد'})); break;
                                    default:alert(data); break;
                                    }
                                    }
                            });
                            $el.dialog('close');
                            }).end().find('button.cancel').click(function(){
                            var $el = $(this).parents('.ui-dialog-content');
                            $el.dialog('close'); ;
                            });
                            $("#dialog_delete_btn").click(function() {
                            $("#dialog_delete").dialog("open");
                            return false;
                            });
                            });
                        </script>

                        <div style="display: none;" id="dialog_delete" title="حذف <?php echo($useredit['username']); ?>">
                            <p>آیا از حذف "<?php echo($useredit['username']); ?>" با شماره کاربری "<?php echo($useredit['id']); ?>" کمال اطمینان را دارید؟</p>
                            <p style="font-weight: 800;text-align: center;text-decoration: blink;color:red">این فرایند غیرقابل برگشت میباشد و تمامی اطلاعات کاربر از جمله پروفایل ، پیام ها حذف میشوند</p>

                            <div class="actions">
                                <div class="left"><button class="grey cancel">انصراف</button></div>
                                <div class="right"><button class="submit">حذف</button></div>
                            </div>
                        </div>
                        <?php
                    }
                    if (Users::is_online($useredit['id'])) {
                        if (adminpanel::check_Permission('bool', 'refreshusers')) {
                            ?>
                            <button class="button blue flat block" onclick="refreshuser();">رفرش کردن</button>
                            <script type="text/javascript">
                                function refreshuser(){
                                $.ajax({
                                type: "POST",
                                        url: "<?php echo(url('ajax/admin.users.refresh')); ?>",
                                        data: {'user':'<?php echo($useredit['id']); ?>', token:'<?php echo(session('changepw')); ?>'},
                                        success: function(data){
                                        switch (data){
                                        case('ok'):$.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'کاربر با موفقت رفرش شد'}); break;
                                        case('user'):$.jGrow$.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'این کاربر وجود ندارد'}); break;
                                        default:alert(data); break;
                                        }
                                        }
                                });
                                }

                            </script>
                        <?php }if (adminpanel::check_Permission('bool', 'alertuser')) { ?>
                            <button class="button blue flat block" id="dialog_alert_btn">اخطار دادن</button>
                            <script type="text/javascript">
                                $(document).ready(function(){
                                $("#dialog_alert").dialog({
                                autoOpen: false,
                                        modal: true,
                                        width: 400,
                                        open: function(){ $(this).parent().css('overflow', 'visible'); $$.utils.forms.resize() }
                                }).find('button.submit').click(function(){
                                var $el = $(this).parents('.ui-dialog-content');
                                if ($el.validate().form()) {
                                var $form = $el.find('form')[0];
                                var data = {
                                'token':'<?php echo(session('changepw')); ?>',
                                        'user':'<?php echo($useredit['id']); ?>',
                                        'text':$('input[name=text_alert]', $form).val(),
                                };
                                $.ajax({
                                type: "POST",
                                        url: "<?php echo(url('ajax/admin.users.alert')); ?>",
                                        data: data,
                                        success: function(data){
                                        switch (data){
                                        case('ok'):$.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'نشان داده شد'}); break;
                                        case('user'):$.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'این کاربری وجود ندارد'}); break;
                                        default:alert(data); break;
                                        }
                                        }
                                });
                                $form.reset();
                                $el.dialog('close');
                                }
                                }).end().find('button.cancel').click(function(){
                                var $el = $(this).parents('.ui-dialog-content');
                                $el.find('form')[0].reset();
                                $el.dialog('close'); ;
                                });
                                $("#dialog_alert_btn").click(function() {
                                $("#dialog_alert").dialog("open");
                                return false;
                                });
                                });
                            </script>

                            <div style="display: none;" id="dialog_alert" title="اخطار به <?php echo($useredit['username']); ?>">
                                <form action="" class="full validate">
                                    <div class="row">
                                        <label for="text_alert"><strong>متن اخطار</strong></label>
                                        <div>
                                            <input type="text" name="text_alert" id="text_alert" class="required" />

                                        </div>
                                    </div>
                                </form>
                                <div class="actions">
                                    <div class="left"><button class="grey cancel">انصراف</button></div>
                                    <div class="right"><button class="submit">اخطار</button></div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                    <button class="button blue flat block" onclick="edit();">ویرایش</button>
                </center>

            </div>
        </div> 
    </div>
</section>
<?php include('footer.php'); ?>
