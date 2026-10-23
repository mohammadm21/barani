<?php
include('header.php');
include('sidebar.php');
?>
<script type="text/javascript">
    function specialactions(request)
    {
        if (request == 'ld_delete_inprofile') {
            var trust = confirm('آیا از حذف تمامی لایک ها و دیسلایک های کاربران در پروفایل مطمئن هستید؟');
            if (!trust)
                return;
        }
        if (request == 'delreactions') {
            var trust = confirm('آیا از حذف تمامی تاریخچه ری اکشن ها مطمئن هستید؟');
            if (!trust)
                return;
        }


if(request == "reg_guests"){
		  var trust = confirm('کاربران مجازی نیز عضو شوند؟');
            if (trust)request = 'reg_guestsAll';
		}
        $.ajax({
            url: '<?php echo(url("ajax/admin.specialactions")); ?>',
            type: "POST",
            data: {action: request, token: '<?php echo(session('changepw')); ?>'},
            success: function (data) {
                switch (data) {
                    case('ok'):
                        $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'ذخیره شد'});
                        break;
                    default:
                        $.msgGrowl({type: 'warning', title: 'پیام سرور'});
                        break;
                }
            }
        });
    }
    function reg_guests() {
        $("#reg_guests_dialog").dialog('open');
    }
    function alertonlines() {
        $("#alert_dialog").dialog('open');
    }
    function alertadmins() {
        $("#alertadmins_dialog").dialog('open');
    }
    function delguestime() {
        $("#delguestime_dialog").dialog('open');
    }
     function delroommsg() {
        $("#delroommsg_dialog").dialog('open');
    }
    function delheader() {
        $("#delheader_dialog").dialog('open');
    }
    function scoreplus() {
        $("#scorepp").dialog('open');
    }
    $(document).ready(function () {
        $("#alert_dialog").dialog({
            autoOpen: false,
            modal: true,
            width: 500,
            open: function () {
                $(this).parent().css('overflow', 'visible');
                $$.utils.forms.resize()
            }
        }).find('button.submit').click(function () {
            var $el = $(this).parents('.ui-dialog-content');
            $.ajax({
                url: '<?php echo(url("ajax/admin.specialactions")); ?>',
                type: "POST",
                data: {action: 'alertonlines', textalert: $('input#textalert').val(), token: '<?php echo(session('changepw')); ?>'},
                success: function (data) {
                    switch (data) {
                        case('ok'):
                            $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'با موفقیت اخطار به هم داده شده'});
                            break;
                        default:
                            $.msgGrowl({type: 'success', title: 'پیام سرور'});
                            break;
                    }
                }
            });
            $el.find('form')[0].reset();
            $el.dialog('close');
        }).end().find('button.cancel').click(function () {
            var $el = $(this).parents('.ui-dialog-content');
            $el.find('form')[0].reset();
            $el.dialog('close');
            ;
        });
        $("#reg_guests_dialog").dialog({
            autoOpen: false,
            modal: true,
            width: 500,
            open: function () {
                $(this).parent().css('overflow', 'visible');
                $$.utils.forms.resize()
            }
        }).find('button.submit').click(function () {
            var $el = $(this).parents('.ui-dialog-content');
            $.ajax({
                url: '<?php echo(url("ajax/admin.specialactions")); ?>',
                type: "POST",
                data: {action: 'reg_guests', reg_guests_password: $('input#reg_guests_password').val(), token: '<?php echo(session('changepw')); ?>'},
                success: function (data) {
                    switch (data) {
                        case('password'):
                            $.msgGrowl({type: 'error', title: 'خطا!', text: 'کلمه عبور را وارد کنید'});
                            break;
                        case('ok'):
                            $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'با موفقیت کاربران مهمان عضو شدند'});
                            break;
                        default:
                            $.msgGrowl({type: 'warning', title: 'پیام سرور', text: data});
                            break;
                    }
                }
            });
            $el.find('form')[0].reset();
            $el.dialog('close');
        }).end().find('button.cancel').click(function () {
            var $el = $(this).parents('.ui-dialog-content');
            $el.find('form')[0].reset();
            $el.dialog('close');
            ;
        });       $("#delroommsg_dialog").dialog({
            autoOpen: false,
            modal: true,
            width: 500,
            open: function () {
                $(this).parent().css('overflow', 'visible');
                $$.utils.forms.resize()
            }
        }).find('button.submit').click(function () {
            var $el = $(this).parents('.ui-dialog-content');
            $.ajax({
                url: '<?php echo(url("ajax/admin.specialactions")); ?>',
                type: "POST",
                data: {action: 'delroommsg', where: $('select#delroommsg').val(), token: '<?php echo(session('changepw')); ?>'},
                success: function (data) {
                    switch (data) {
                        case('ok'):
                            $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'با موفقیت پیام های این اتاق حذف گردید'});
                            break;
                        default:
                            $.msgGrowl({type: 'success', title: 'پیام سرور'});
                            break;
                    }
                }
            });
            $el.find('form')[0].reset();
            $el.dialog('close');
        }).end().find('button.cancel').click(function () {
            var $el = $(this).parents('.ui-dialog-content');
            $el.find('form')[0].reset();
            $el.dialog('close');
            ;
        }); $("#delheader_dialog").dialog({
            autoOpen: false,
            modal: true,
            width: 500,
            open: function () {
                $(this).parent().css('overflow', 'visible');
                $$.utils.forms.resize()
            }
        }).find('button.submit').click(function () {
            var $el = $(this).parents('.ui-dialog-content');
            $.ajax({
                url: '<?php echo(url("ajax/admin.specialactions")); ?>',
                type: "POST",
                data: {action: 'delheader',"where": $('select#delheader').val(), token: '<?php echo(session('changepw')); ?>'},
                success: function (data) {
                  $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text:data});
                   
                }
            });
            $el.find('form')[0].reset();
            $el.dialog('close');
        }).end().find('button.cancel').click(function () {
            var $el = $(this).parents('.ui-dialog-content');
            $el.find('form')[0].reset();
            $el.dialog('close');
            ;
        });
        $("#alertadmins_dialog").dialog({
            autoOpen: false,
            modal: true,
            width: 500,
            open: function () {
                $(this).parent().css('overflow', 'visible');
                $$.utils.forms.resize()
            }
        }).find('button.submit').click(function () {
            var $el = $(this).parents('.ui-dialog-content');
            $.ajax({
                url: '<?php echo(url("ajax/admin.specialactions")); ?>',
                type: "POST",
                data: {action: 'alertadmins', textalert1: $('input#textalert1').val(), token: '<?php echo(session('changepw')); ?>'},
                success: function (data) {
                    switch (data) {
                        case('ok'):
                            $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'با موفقیت اخطار برای همه '});
                            break;
                        default:
                            $.msgGrowl({type: 'success', title: 'پیام سرور', });
                            break;
                    }
                }
            });
            $el.find('form')[0].reset();
            $el.dialog('close');
        }).end().find('button.cancel').click(function () {
            var $el = $(this).parents('.ui-dialog-content');
            $el.find('form')[0].reset();
            $el.dialog('close');
            ;
        });
        $("#delguestime_dialog").dialog({
            autoOpen: false,
            modal: true,
            width: 500,
            open: function () {
                $(this).parent().css('overflow', 'visible');
                $$.utils.forms.resize()
            }
        }).find('button.submit').click(function () {
            var $el = $(this).parents('.ui-dialog-content');
            $.ajax({
                url: '<?php echo(url("ajax/admin.specialactions")); ?>',
                type: "POST",
                data: {action: 'delguestime', time: $('select#timedelguest').val(), token: '<?php echo(session('changepw')); ?>'},
                success: function (data) {
                    switch (data) {
                        case('ok'):
                            $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'با موفقیت تمامی کاربران حذف شد'});
                            break;
                        default:
                            $.msgGrowl({type: 'success', title: 'پیام سرور'});
                            break;
                    }
                }
            });
            $el.find('form')[0].reset();
            $el.dialog('close');
        }).end().find('button.cancel').click(function () {
            var $el = $(this).parents('.ui-dialog-content');
            $el.find('form')[0].reset();
            $el.dialog('close');
            ;
        });

        $("#scorepp").dialog({
            autoOpen: false,
            modal: true,
            width: 500,
            open: function () {
                $(this).parent().css('overflow', 'visible');
                $$.utils.forms.resize()
            }
        }).find('button.submit').click(function () {
            var $el = $(this).parents('.ui-dialog-content');
            $.ajax({
                url: '<?php echo(url("ajax/admin.specialactions")); ?>',
                type: "POST",
                data: {action: 'scoreplus', scorepp: $('#scorepp2').val(), pOrn: $('#np').val(), sType: $('#sTypes').val(), token: '<?php echo(session('changepw')); ?>'},
                success: function (data) {
                    switch (data) {
                        case('okp'):
                            $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'با موفقیت امتیاز کاربران افزایش یافت'});
                            break;
                        case('okn'):
                            $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'با موفقیت امتیاز کاربران کاهش یافت'});
                            break;
                        default:
                            $.msgGrowl({type: 'success', title: 'پاسخ سرور'});
                            break;
                    }
                }
            });
            $el.find('form')[0].reset();
            $el.dialog('close');
        }).end().find('button.cancel').click(function () {
            var $el = $(this).parents('.ui-dialog-content');
            $el.find('form')[0].reset();
            $el.dialog('close');
            ;
        });
    })
</script>

<div style="display: none;" id="delroommsg_dialog" title="حذف پیام در اتاق ها">
    <form action="" class="full validate">
        <div class="row">
            <label for="delroommsg"><strong>حذف پیام های اتاق : </strong></label>
            <div>
                <select id="delroommsg" class="search">
                    <?php
                    $query = db::query("SELECT `id` , `title` FROM {prefix}rooms");
                    while ($row = mysqli_fetch_array($query)) {
                        ?>
                        <option value="<?php echo($row['id']); ?>"><?php echo($row['title']); ?></option>    
                    <?php }mysqli_free_result($query);
                    ?>
                </select>
            </div>
        </div>
    </form>
    <div class="actions">
        <div class="left">
            <button class="grey cancel">انصراف</button>
        </div>
        <div class="right">
            <button class="submit">حذف کن</button>
        </div>
    </div>
</div>

<div style="display: none;" id="delheader_dialog" title="حذف فایل های مربوط به سربرگ ها">
    <form action="" class="full validate">
        <div class="row">
            <label for="delheader"><strong>حذف فایل های مربوط به  : </strong></label>
            <div>
                <select id="delheader" class="search">
                   
               <option value="header">سربرگ داخل چت روم</option>
                <option value="users">سربرگ امتیازات</option>
                <option value="history">سربرگ تاریخچه</option>

                </select>
            </div>
        </div>
    </form>
    <div class="actions">
        <div class="left">
            <button class="grey cancel">انصراف</button>
        </div>
        <div class="right">
            <button class="submit">حذف کن</button>
        </div>
    </div>
</div>

<div style="display: none;" id="alert_dialog" title="اخطار به همه کاربران">
    <form action="" class="full validate">
 <div class="row">
            <label for="textalert"><strong>متن پیام</strong></label>
            <div>
                <input class="required" type="text" name="textalert" id="textalert" /> 
            </div>
        </div>
    </form>
    <div class="actions">
        <div class="left">
            <button class="grey cancel">انصراف</button>
        </div>
        <div class="right">
            <button class="submit">نمایش</button>
        </div>
    </div>
</div>
<div style="display: none;" id="alertadmins_dialog" title="اخطار به همه مدیران و ناظران آنلاین">
    <form action="" class="full validate">
        <div class="row">
            <label for="textalert"><strong>متن پیام</strong></label>
            <div>
                <input class="required" type="text" name="textalert" id="textalert1" /> 
            </div>
        </div>
    </form>
    <div class="actions">
        <div class="left">
            <button class="grey cancel">انصراف</button>
        </div>
        <div class="right">
            <button class="submit">نمایش</button>
        </div>
    </div>
</div>
<div style="display: none;" id="delguestime_dialog" title="حذف کاربران مهمان">
    <form action="" class="full validate">
        <div class="row">
            <label for="timedelguest"><strong>از زمان به قبل</strong></label>
            <div>
                <select id="timedelguest" class="search">
                    <option value="30">30 دقیقه پیش</option>
                    <option value="60">60 دقیقه پیش</option>
                    <option value="120">2 ساعت پیش</option>
                    <option value="240">4 ساعت پیش</option>
                    <option value="480">8 ساعت پیش</option>
                    <option value="560">16 ساعت پیش</option>
                    <option value="1440">1 روز پیش</option>
                    <option value="2880">2 روز پیش</option>
                    <option value="5760">4 روز پیش</option>
                    <option value="11520">8 روز پیش</option>
                    <option value="23040">16 روز پیش</option>
                    <option value="43200" selected>1 ماه پیش</option>
                    <option value="86400">2 ماه پیش</option>
                    <option value="172800">4 ماه پیش</option>
                    <option value="345600">8 ماه پیش</option>
                    <option value="691200">16 ماه پیش</option>
                </select>
            </div>
        </div>
    </form>
    <div class="actions">
        <div class="left">
            <button class="grey cancel">انصراف</button>
        </div>
        <div class="right">
            <button class="submit">حذف کن</button>
        </div>
    </div>
</div>
<div style="display: none;" id="scorepp" title="دادن امتیاز گروهی">
    <form action="" class="full validate">
        <div class="row">
            <label for="scorepp"><strong>مقدار امتیاز؟</strong></label>
            <div>
                <input class="required" type="text" maxlength="4" name="scorepp" id="scorepp2" /> 
                <label for="np"><strong>میخواهید امتیاز افزایش دهید یا کاهش ؟</strong></label>
                <div>
                    <select id="np" class="search">
                        <option value="p" selected>افزایش</option>
                        <option value="n">کاهش</option>
                    </select>
                </div>
                <label for="np"><strong>گروه کاربری را مشخص کنید</strong></label>			
                <div>
                    <select id="sTypes" class="search">
                        <option value="all" selected>همه</option>
                        <?php
                        $query = db::query('select * from {prefix}usertypes order by id DESC');
                        while ($row = mysqli_fetch_array($query)) {
                            ?>
                            <option value="<?= $row['id'] ?>"><?= $row['title'] ?></option>  
                        <?php } mysqli_free_result($query); ?>
                    </select>
                </div>
            </div>
        </div>
    </form>
    <div class="actions">
        <div class="left">
            <button class="grey cancel">انصراف</button>
        </div>
        <div class="right">
            <button class="submit">ثبت</button>
        </div>
    </div>
</div>
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">اقدامات ویژه</h1>
    <div class="grid_12">
        <div class="grid_12">
       <span style="font-size:18px;">امتیاز گروهی  </span><br><br>
            <a href="javascript:scoreplus();"><button class="button blue block">امتیاز گروهی</button></a>
    <br>
     <br><span style="font-size:18px;">پاکسازی تاریخچه </span><br><br>
            <a href="javascript:specialactions('delmsgs');"><button class="button red block">حذف تاریخچه</button></a>
            <a href="javascript:specialactions('deloldmsgs');"><button class="button red block">حذف تاریخچه قدیمی</button></a>
             <a href="javascript:delroommsg();"><button class="button red block">حذف پیام های هر اتاق به صورت جدا گانه</button></a><br>
          <br><span style="font-size:18px;">کاربران  </span><br><br>
          <a href="javascript:specialactions('delguests');"><button class="button red block">حذف کاربران بی فایده</button></a>
          <a href="javascript:delguestime();"><button class="button red block">حذف کاربران مهمان</button></a>
          <a href="javascript:specialactions('deldactived');"><button class="button red block">حذف غیرفعال ها</button></a>
                       <a href="javascript:specialactions('delvisualusers');"><button class="button red block">حذف کاربران مجازی</button></a>
    <br>
         <a href="javascript:specialactions('reg_guests');"><button class="button red block">عضویت مهمان ها</button></a>
             <a href="javascript:specialactions('refreshusers');"><button class="button grey block">رفرش همه آنلاین ها</button></a>
            <a href="javascript:specialactions('refreshadmins');"><button class="button grey block">رفرش همه مدیران و ناظران آنلاین</button></a>
            <a href="javascript:alertonlines();"><button class="button grey block">اخطار به همه آنلاین ها</button></a>
            <a href="/fakemanager/index.php"><button class="button grey block">تغییر نام کاربران افلاین</button></a>
            <a href="javascript:alertadmins();"><button class="button grey block">اخطار به همه مدیران و ناظران آنلاین</button></a><br>
        <br> <span style="font-size:18px;">فعال سازی و آزاد سازی  </span><br><br>
          <a href="javascript:specialactions('activeprofs');"><button class="button blue flat block">فعال سازی پروفایل ها</button></a>
         <a href="javascript:specialactions('activedactived');"><button class="button blue flat block">فعال سازی غیرفعال ها</button></a>
     <br>   <a href="javascript:specialactions('delusersreport');"><button class="button blue flat block">آزاد سازی تمامی ریپورت شده ها</button></a>    
         <a href="javascript:specialactions('delipbanner');"><button class="button blue flat block">آزاد سازی تمامی تحریم ها</button></a>
            <a href="javascript:specialactions('delbanns');"><button class="button blue flat block">آزاد سازی تمامی اخراج ها</button></a>
            <a href="javascript:specialactions('delsilents');"><button class="button blue flat block">آزاد سازی ساکت شدگان</button></a>
            <br>
             <br><span style="font-size:18px;">بازنویسی و بررسی دیتابیس  </span><br><br>
            <a href="javascript:specialactions('checkdb');"><button class="button  blue flat block">بررسی تنظیمات دیتابیس</button></a>
            <a href="javascript:specialactions('checktlbs');"><button class="button  blue flat block">بررسی جدوال</button></a>
            <a href="javascript:specialactions('reloadconfig');"><button class="button grey block">بازنویسی تنظیمات</button></a><br>
       
            <br><span style="font-size:18px;">حذفیات  </span><br><br>
            <a href="javascript:delheader();"><button class="button red block">حذف فایل مربوط به سربرگ به صورت جداگانه</button></a>
            <a href="javascript:specialactions('delsendedimages');"><button class="button red block">حذف تمامی فایل های ارسال شده در خصوصی</button></a><br>
            <a href="javascript:specialactions('delofflinemessage');"><button class="button red block">حذف پیام های آفلاین</button></a>
            <a href="javascript:specialactions('delreactions');"><button class="button red block">حذف تاریخچه ری اکشن ها</button></a>
            <a href="javascript:specialactions('ld_delete_inprofile');"><button class="button red block">حذف لایک و دیسلایک همه کاربران در پروفایل</button></a> <br>
            <a href="javascript:specialactions('deloldvisit');"><button class="button red block">حذف ریز آمار های قدیمی</button></a>
            <a href="javascript:specialactions('delvisit');"><button class="button red block">حذف تمامی ریز آمار</button></a>
            <br>
            <a href="javascript:specialactions('deloldlogs');"><button class="button red block">حذف تراکنشات قدیمی</button></a>
            <a href="javascript:specialactions('delogs');"><button class="button red block">حذف همه تراکنشات</button></a>  
            <br>
            <a href="javascript:specialactions('delsmilies');"><button class="button red block">حذف همه شکلک ها</button></a>
            <a href="javascript:specialactions('reloadsmilies');"><button class="button grey block">بارگزاری دوباره شکلک ها</button></a>
            <br>
            <a href="javascript:specialactions('delbadwords');"><button class="button red block">حذف همه فیلتر کلمات زشت</button></a>
            <a href="javascript:specialactions('delbadnames');"><button class="button red block">حذف همه فیلتر نام های کاربری زشت</button></a>
            <br>
            <a href="javascript:specialactions('deloldletters');"><button class="button red block">حذف نامه های قدیمی</button></a>
            <a href="javascript:specialactions('delreadletters');"><button class="button red block">حذف نامه های خوانده شده</button></a>
            <a href="javascript:specialactions('deletters');"><button class="button red block">حذف همه نامه ها</button></a>
            <a href="javascript:specialactions('allreadletters');"><button class="button blue flat block">نشان خوانده شدن برای همه نامه ها</button></a>
            <br>     
            <a href="javascript:specialactions('delmusices');"><button class="button red block">حذف همه موزیک ها</button></a>
            <a href="javascript:specialactions('reloadmusices');"><button class="button grey block">بارگزاری دوباره موزیک ها</button></a>
            <br>
            <a href="javascript:specialactions('delinks');"><button class="button red block">حذف همه لینک ها</button></a>
            <a href="javascript:specialactions('deldeactivelinks');"><button class="button red block">حذف همه لینک های غیرفعال</button></a>
            <br>
            <a href="javascript:specialactions('pays');"><button class="button red block">حذف همه پرداخت ها</button></a>
            <a href="javascript:specialactions('failedpays');"><button class="button red block">حذف همه پرداخت های ناموفق</button></a>
            <a href="javascript:specialactions('oldpays');"><button class="button red block">حذف همه پرداخت های قدیمی</button></a>
            <br>

        </div>
    </div>
</section>
<?php include('footer.php'); ?>