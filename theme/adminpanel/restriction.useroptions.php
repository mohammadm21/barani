<?php
include('header.php');
include('sidebar.php');
?>
<script type="text/javascript">

    function edit() {
        $.ajax({
            type: "POST",
            url: "<?php echo(url('ajax/admin.restriction.useroptions')); ?>",
            data: $('#formeditsettings').serialize(),
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
    var formblock;
    var forminputs;
    function smart() {
        $("#smart").dialog('open');
    }
    $(document).ready(function () {
    $("#smart").dialog({
            autoOpen: false,
            modal: true,
            width: 500,
            buttons: [{text: "متوجه شدم", click: function () {
                        $(this).dialog("close");
                    }}]
        });
    });
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
    function info_allowonlinelist() {
        $("#info_allowonlinelist").dialog('open');
    }
    $(document).ready(function () {
        $("#info_allowonlinelist").dialog({
            autoOpen: false,
            modal: true,
            width: 500,
            buttons: [{text: "متوجه شدم", click: function () {
                        $(this).dialog("close");
                    }}]
        });

    });

</script>
<div style="display: none;" id="info_allowonlinelist" title="راهنمای تنظیمات این سیستم">
    <p>برای استفاده از این قسمت حتما باید از قسمت تنظیمات اصلی قسمت شکل اولیه لیست انلاین را روی نمایش پروفایل های بروز شده قرار دهید تا این سیستم کار بکنه.</p>
</div>
  <div style="display: none;" id="smart" title="راهنمای تنظیمات این سیستم">
    <p>اگر تیک اخراج هوشمند یک کلمه را در قسمت کلمات زشت بزنید و  از اینجا هر درجه ای که فعال باشد با نوشتن آن کلمه زشت در چت روم به طور هوشمند اخراج می شود . که فعلا توصیه می شود این امکان را فعال نکنید</p>
</div>
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">تنظیمات</h1>
    <div class="grid_12">
        <div class="grid_12">
            <form class="box  validate" id="formeditsettings">
                <input type="hidden" name="token" value="<?php echo(session('changepw')); ?>" />
                <div class="header">
                    <h2>محدودیت های امکانات</h2>
                </div>
                <?php $useroptions = json_decode($options['useroptions'], true); ?>
                <?php $allow_formats = json_decode($options['allow_formats'], true); ?>
<div class="content tabbed">
 <div class="row">
                        <label><strong>آپلود شکلک شخصی:</strong></label>
                        <div>
                            <?php
                            foreach ($usertype as $type) {
                                ?>
                                <input type="checkbox" name="usersmile[]" value="<?php echo($type['id']); ?>" <?php if (in_array($type['id'], $useroptions['usersmile'])) echo('checked'); ?> /> <?php echo($type['title']); ?>
                                <?php
                            }
                            ?>
                            <br>
                            <a style="color:red;cursor:pointer; " onClick="select_all('usersmile', '1');">انتخاب همه</a> | <a style="color:red;cursor:pointer; "  onClick="select_all('usersmile',
                                            '0');">
                                لغو انتخاب همه</a>
                            <br>
                        </div>
                    </div>

                    <div class="row">
                        <label><strong>ویرایش دلنوشته (استاتوس):</strong></label>
                        <div>
                            <?php
                            foreach ($usertype as $type) {
                                ?>
                                <input type="checkbox" name="statos[]" value="<?php echo($type['id']); ?>" <?php if (in_array($type['id'], $useroptions['statos'])) echo('checked'); ?> /> <?php echo($type['title']); ?>
                                <?php
                            }
                            ?>
                            <br>
                            <a style="color:red;cursor:pointer; " onClick="select_all('statos', '1');">انتخاب همه</a> | <a style="color:red;cursor:pointer; "  onClick="select_all('statos',
                                            '0');">
                                لغو انتخاب همه</a>
                            <br>
                        </div>
                    </div>

                    <div class="row">
                        <label><strong>بازی:</strong></label>
                        <div>
                            <?php
                            foreach ($usertype as $type) {
                                ?>
                                <input type="checkbox" name="game[]" value="<?php echo($type['id']); ?>" <?php if (in_array($type['id'], $useroptions['game'])) echo('checked'); ?> /> <?php echo($type['title']); ?>
                                <?php
                            }
                            ?>
                            <br>
                            <a style="color:red;cursor:pointer; " onClick="select_all('game', '1');">انتخاب همه</a> | <a style="color:red;cursor:pointer; "  onClick="select_all('game',
                                            '0');">
                                لغو انتخاب همه</a>
                            <br>
                        </div>
                    </div>
                      <div class="row">
                        <label><strong>تاریخچه:</strong></label>
                        <div>
                            <?php
                            foreach ($usertype as $type) {
                                ?>
                                <input type="checkbox" name="history[]" value="<?php echo($type['id']); ?>" <?php if (in_array($type['id'], $useroptions['history'])) echo('checked'); ?> /> <?php echo($type['title']); ?>
                                <?php
                            }
                            ?>
                            <br>
                            <a style="color:red;cursor:pointer; " onClick="select_all('history', '1');">انتخاب همه</a> | <a style="color:red;cursor:pointer; "  onClick="select_all('history',
                                            '0');">
                                لغو انتخاب همه</a>
                            <br>
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>لیست کاربران:</strong></label>
                        <div>
                            <?php
                            foreach ($usertype as $type) {
                                ?>
                                <input type="checkbox" name="users[]" value="<?php echo($type['id']); ?>" <?php if (in_array($type['id'], $useroptions['users'])) echo('checked'); ?> /> <?php echo($type['title']); ?>
                                <?php
                            }
                            ?>
                            <br>
                            <a style="color:red;cursor:pointer; " onClick="select_all('users', '1');">انتخاب همه</a> | <a style="color:red;cursor:pointer; "  onClick="select_all('users',
                                            '0');">
                                لغو انتخاب همه</a>
                            <br>
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>فرمت های مجاز برای ارسال فایل در خصوصی:</strong></label>
                        <div>
                            mp3<input type="checkbox" name="allow_formats[]" value="mp3" <?php if ($allow_formats['mp3'] == 1) echo('checked'); ?> /> 
                            3gp<input type="checkbox" name="allow_formats[]" value="3gp" <?php if ($allow_formats['3gp'] == 1) echo('checked'); ?> /> 
                            jpg<input type="checkbox" name="allow_formats[]" value="jpg" <?php if ($allow_formats['jpg'] == 1) echo('checked'); ?> /> 
                            gif<input type="checkbox" name="allow_formats[]" value="gif" <?php if ($allow_formats['gif'] == 1) echo('checked'); ?> /> 
                            jpeg<input type="checkbox" name="allow_formats[]" value="jpeg" <?php if ($allow_formats['jpeg'] == 1) echo('checked'); ?> />
                            mov<input type="checkbox" name="allow_formats[]" value="mov" <?php if ($allow_formats['mov'] == 1) echo('checked'); ?> /> 
                                 png<input type="checkbox" name="allow_formats[]" value="png" <?php if ($allow_formats['png'] == 1) echo('checked'); ?> /> 
                            txt<input type="checkbox" name="allow_formats[]" value="txt" <?php if ($allow_formats['txt'] == 1) echo('checked'); ?> /> 
                            pdf<input type="checkbox" name="allow_formats[]" value="pdf" <?php if ($allow_formats['pdf'] == 1) echo('checked'); ?> /> 
                            wma<input type="checkbox" name="allow_formats[]" value="wma" <?php if ($allow_formats['wma'] == 1) echo('checked'); ?> /> 
                            wmv<input type="checkbox" name="allow_formats[]" value="wmv" <?php if ($allow_formats['wmv'] == 1) echo('checked'); ?> /> 
                            mp4<input type="checkbox" name="allow_formats[]" value="mp4" <?php if ($allow_formats['mp4'] == 1) echo('checked'); ?> /> 
                            <br>
                            <a style="color:red;cursor:pointer; " onClick="select_all('allow_formats', '1');">انتخاب همه</a> | <a style="color:red;cursor:pointer; "  onClick="select_all('allow_formats',
                                            '0');">
                                لغو انتخاب همه</a>
                            <br>
                        </div>
                    </div>


       
	
        <div class="row">
                <?php $allow_formatso = json_decode($options['allow_formatso'], true); ?>
                    <label><strong>فرمت های مجاز برای ارسال فایل در عمومی :</strong></label>
                    <div>
                        mp3<input type="checkbox" name="allow_formatso[]" value="mp3" <?php if($allow_formats['mp3'] == 1)echo('checked'); ?> /> 
                                  3gp<input type="checkbox" name="allow_formatso[]" value="3gp" <?php if($allow_formatso['3gp'] == 1)echo('checked'); ?> /> 
                                  jpg<input type="checkbox" name="allow_formatso[]" value="jpg" <?php if($allow_formatso['jpg'] == 1)echo('checked'); ?> /> 
                                  gif<input type="checkbox" name="allow_formatso[]" value="gif" <?php if($allow_formatso['gif'] == 1)echo('checked'); ?> /> 
                                  jpeg<input type="checkbox" name="allow_formatso[]" value="jpeg" <?php if($allow_formatso['jpeg'] == 1)echo('checked'); ?> />
                                  mov<input type="checkbox" name="allow_formatso[]" value="mov" <?php if($allow_formatso['mov'] == 1)echo('checked'); ?> /> 
                                  rar<input type="checkbox" name="allow_formatso[]" value="rar" <?php if($allow_formatso['rar'] == 1)echo('checked'); ?> /> 
                                  zip<input type="checkbox" name="allow_formatso[]" value="zip" <?php if($allow_formatso['zip'] == 1)echo('checked'); ?> /> 
                                  png<input type="checkbox" name="allow_formatso[]" value="png" <?php if($allow_formatso['png'] == 1)echo('checked'); ?> /> 
                                  txt<input type="checkbox" name="allow_formatso[]" value="txt" <?php if($allow_formatso['txt'] == 1)echo('checked'); ?> /> 
                                  pdf<input type="checkbox" name="allow_formatso[]" value="pdf" <?php if($allow_formatso['pdf'] == 1)echo('checked'); ?> /> 
                                  wma<input type="checkbox" name="allow_formatso[]" value="wma" <?php if($allow_formatso['wma'] == 1)echo('checked'); ?> /> 
                                  wmv<input type="checkbox" name="allow_formatso[]" value="wmv" <?php if($allow_formatso['wmv'] == 1)echo('checked'); ?> /> 
                                  mp4<input type="checkbox" name="allow_formatso[]" value="mp4" <?php if($allow_formatso['mp4'] == 1)echo('checked'); ?> /> 
                    <br>
                            <a style="color:red;cursor:pointer; " onClick="select_all('allow_formatso', '1');">انتخاب همه</a> | <a style="color:red;cursor:pointer; "  onClick="select_all('allow_formatso',
                                            '0');">
                                لغو انتخاب همه</a>
                            <br>
                    </div>
                </div>

                    <div class="row">
                        <label><strong>ارسال پیام در عمومی:</strong><small></small></label>
                        <div>
                            <?php
                            foreach ($usertype as $type) {
                                ?>
                                <input type="checkbox" name="allowtypessender_main[]" value="<?php echo($type['id']); ?>" <?php if (in_array($type['id'], $useroptions['allowtypessender_main'])) echo('checked'); ?> /> <?php echo($type['title']); ?>
                                <?php
                            }
                            ?>
                            <br>
                            <a style="color:red;cursor:pointer; " onClick="select_all('allowtypessender_main', '1');">انتخاب همه</a> | <a style="color:red;cursor:pointer; "  onClick="select_all('allowtypessender_main',
                                            '0');">
                                لغو انتخاب همه</a>
                            <br>
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>ساخت اتاق کنفرانس:</strong><small></small></label>
                        <div>
                            <?php
                            foreach ($usertype as $type) {
                                if ($type['id'] == '1')
                                    continue;
                                ?>
                                <input type="checkbox" name="conference_premission[]" value="<?php echo($type['id']); ?>" <?php if (in_array($type['id'], $useroptions['conference_premission'])) echo('checked'); ?> /> <?php echo($type['title']); ?>
                                <?php
                            }
                            ?>
                            <br>
                            <a style="color:red;cursor:pointer; " onClick="select_all('conference_premission', '1');">انتخاب همه</a> | <a style="color:red;cursor:pointer; "  onClick="select_all('conference_premission',
                                            '0');">
                                لغو انتخاب همه</a>
                            <br>
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>محدودیت سرعت در ارسال پیام:</strong><small>برای توضحات کامل تنظیمات قسمت پیام ها را بخوانید</small></label>
                        <div>
                            <?php
                            foreach ($usertype as $type) {
                                ?>
                                <input type="checkbox" name="chat_speedmsg[]" value="<?php echo($type['id']); ?>" <?php if (in_array($type['id'], $useroptions['chat_speedmsg'])) echo('checked'); ?> /> <?php echo($type['title']); ?>
                                <?php
                            }
                            ?>
                            <br>
                            <a style="color:red;cursor:pointer; " onClick="select_all('chat_speedmsg', '1');">انتخاب همه</a> | <a style="color:red;cursor:pointer; "  onClick="select_all('chat_speedmsg',
                                            '0');">
                                لغو انتخاب همه</a>
                            <br>
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>نمایش سیستم عامل کاربران در لیست آنلاین:</strong><small></small></label>
                        <div>
                            <?php
                            foreach ($usertype as $type) {
                                ?>
                                <input type="checkbox" name="show_userOs[]" value="<?php echo($type['id']); ?>" <?php if (in_array($type['id'], $useroptions['show_userOs'])) echo('checked'); ?> /> <?php echo($type['title']); ?>
                                <?php
                            }
                            ?>
                            <br>
                            <a style="color:red;cursor:pointer; " onClick="select_all('show_userOs', '1');">انتخاب همه</a> | <a style="color:red;cursor:pointer; "  onClick="select_all('show_userOs',
                                            '0');">
                                لغو انتخاب همه</a>
                            <br>
                        </div>
                    </div>	
                    <div class="row">
                        <label><strong>نمایش زمان بیکار بودن کاربران در لیست آنلاین:</strong><small></small></label>
                        <div>
                            <?php
                            foreach ($usertype as $type) {
                                ?>
                                <input type="checkbox" name="show_idleTime_onlinelist[]" value="<?php echo($type['id']); ?>" <?php if (in_array($type['id'], $useroptions['show_idleTime_onlinelist'])) echo('checked'); ?> /> <?php echo($type['title']); ?>
                                <?php
                            }
                            ?>
                            <br>
                            <a style="color:red;cursor:pointer; " onClick="select_all('show_idleTime_onlinelist', '1');">انتخاب همه</a> | <a style="color:red;cursor:pointer; "  onClick="select_all('show_idleTime_onlinelist',
                                            '0');">
                                لغو انتخاب همه</a>
                            <br>
                        </div>
                    </div>

                    <div class="row">
                        <label><strong>قسمت اخبار:</strong></label>
                        <div>
                            <?php
                            foreach ($usertype as $type) {
                                ?>
                                <input type="checkbox" name="chat_news[]" value="<?php echo($type['id']); ?>" <?php if (in_array($type['id'], $useroptions['chat_news'])) echo('checked'); ?> /> <?php echo($type['title']); ?>
                                <?php
                            }
                            ?>
                            <br>
                            <a style="color:red;cursor:pointer; " onClick="select_all('chat_news', '1');">انتخاب همه</a> | <a style="color:red;cursor:pointer; "  onClick="select_all('chat_news',
                                            '0');">
                                لغو انتخاب همه</a>
                            <br>
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>نمایش مرورگر کاربران در لیست انلاین:</strong></label>
                        <div>
                            <?php
                            foreach ($usertype as $type) {
                                ?>
                                <input type="checkbox" name="show_browser[]" value="<?php echo($type['id']); ?>" <?php if (in_array($type['id'], $useroptions['show_browser'])) echo('checked'); ?> /> <?php echo($type['title']); ?>
                                <?php
                            }
                            ?>
                            <br>
                            <a style="color:red;cursor:pointer; " onClick="select_all('show_browser', '1');">انتخاب همه</a> | <a style="color:red;cursor:pointer; "  onClick="select_all('show_browser',
                                            '0');">
                                لغو انتخاب همه</a>
                            <br>
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>نمایش پیام های ارسالی در خصوصی :</strong></label>
                        <div>
                            <?php
                            foreach ($usertype as $type) {
                                ?>
                                <input type="checkbox" name="showpv[]" value="<?php echo($type['id']); ?>" <?php if (in_array($type['id'], $useroptions['showpv'])) echo('checked'); ?> /> <?php echo($type['title']); ?>
                                <?php
                            }
                            ?>
                            <br>
                            <a style="color:red;cursor:pointer; " onClick="select_all('showpv', '1');">انتخاب همه</a> | <a style="color:red;cursor:pointer; "  onClick="select_all('showpv',
                                            '0');">
                                لغو انتخاب همه</a>
                            <br>
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>نمایش پیام های دریافتی در خصوصی</strong></label>
                        <div>
                            <?php
                            foreach ($usertype as $type) {
                                ?>
                                <input type="checkbox" name="showms[]" value="<?php echo($type['id']); ?>" <?php if (in_array($type['id'], $useroptions['showms'])) echo('checked'); ?> /> <?php echo($type['title']); ?>
                                <?php
                            }
                            ?>
                            <br>
                            <a style="color:red;cursor:pointer; " onClick="select_all('showms', '1');">انتخاب همه</a> | <a style="color:red;cursor:pointer; "  onClick="select_all('showms',
                                            '0');">
                                لغو انتخاب همه</a>
                            <br>
                        </div>
                    </div>
        
                    <div class="row">
                        <label><strong>ایموجی‌های مجاز برای Reaction:</strong></label>
                        <div>
                            <input type="text" name="reaction_emojis" value="<?php 
                                $r_emojis = isset($options['reaction_emojis']) ? json_decode($options['reaction_emojis'], true) : array('👍','❤️','😂','😮','😢','😡','👎');
                                echo implode(',', $r_emojis);
                            ?>" style="width:100%;direction:ltr;text-align:left;" />
                            <br><small style="color:#888;">ایموجی‌ها را با کاما (,) جدا کنید. مثال: 👍,❤️,😂,😮,😢,😡,👎</small>
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>گروه‌های کاربری مجاز به Reaction:</strong></label>
                        <div>
                            <?php
                            foreach ($usertype as $type) {
                                ?>
                                <input type="checkbox" name="allow_reactions[]" value="<?php echo($type['id']); ?>" <?php if (isset($useroptions['allow_reactions']) && in_array($type['id'], $useroptions['allow_reactions'])) echo('checked'); ?> /> <?php echo($type['title']); ?>
                                <?php
                            }
                            ?>
                            <br>
                            <a style="color:red;cursor:pointer;" onClick="select_all('allow_reactions', '1');">انتخاب همه</a> | 
                            <a style="color:red;cursor:pointer;" onClick="select_all('allow_reactions', '0');">لغو انتخاب همه</a>
                        </div>
                    </div>
                </div>
            </form>
            <center> <medium style="color:red">پس از ویرایش ممکن است به طور طبیعی حدودا یک دقیقه اعمال تغییرات طول بکشد</medium><br><button class="button blue flat block" onclick="edit();">ویرایش</button></center>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>
