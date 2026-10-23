<?php
include('header.php');
include('sidebar.php');
$thememanager = json_decode($options['thememanager'], true);
$avatarstyle= json_decode($options['avatarstyle'], true);
?>

<script type="text/javascript" src="<?php echo(theme::url('js/ajaxfileupload.js')); ?>"></script>
<style>
    .cpicker{
        display: inline-block !important;
        width: 25px !important;    

    }
</style>
<script type="text/javascript">
    var $typeedit = 'normal';
    function customedit() {
        if ($typeedit == 'normal') {
            $typeedit = 'custom';
            $('#normalchange').slideUp(1000, function () {
                $('#customchange').slideDown(1000);
            });
        } else if ($typeedit == 'custom') {
            $typeedit = 'normal';
            $('#customchange').slideUp(1000, function () {
                $('#normalchange').slideDown(1000);
            });
        }
    }
    var hexDigits = new Array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b", "c", "d", "e", "f");

    function rgb2hex(rgb) {
        rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
        return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
    }
    function hex(x) {
        return isNaN(x) ? "00" : hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];
    }
    function edit(form) {
        var datetosend = {};
        $('input', form).each(function () {
            var $type = $(this).attr('type');
            if ($type == 'file') {
                $inputfile = this.id;
            }
            if ($type == 'text') {
                var $name = $(this).attr('name'), $value = $(this).val()
                if (typeof $name != 'undefined') {
                    datetosend[$name] = $value;
                }
            }
            if ($type == 'color') {
                var $parent = $(this).parent(), $name = this.name;
                var $value = rgb2hex($('.color', $parent).css('background-color'));
                if ($name != '') {
                    datetosend[$name] = $value;
                }
            }
        });
        $('select', form).each(function () {
            var $name = this.name, $value = $(this).val();
            if ($name != '') {
                datetosend[$name] = $value;
            }
        });


        datetosend['part'] = 'chat';
        datetosend['subset'] = form.id;
        datetosend[$inputfile] = $('#' + $inputfile).attr('name');
        $.ajaxFileUpload({
            url: '<?php echo(url('ajax/admin.theme')); ?>',
            secureuri: false,
            fileElementId: $inputfile,
            data: datetosend,
            dataType: 'text',
            success: function (data, status) {
                switch (data) {
                    case('ok'):
                        $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'ذخیره شد'});
                        break;
                    case('type'):
                        $.msgGrowl({type: 'success', title: 'خطا', text: 'نوع فایل توسط سرور تایید نشد'});
                        break;
                    case('security'):
                        $.msgGrowl({type: 'success', title: 'خطاز', text: 'سرور در فایل شما مورد امنیتی یافت گرد فایل شما حذف گردید'});
                        break;
                    case('repeatname'):
                        $.msgGrowl({type: 'success', title: 'خطا', text: 'این نام ویژه قبلا ثبت شده است'});
                        break;
                    default:
                        alert(data);
                        break;
                }
            },
            error: function (data, status, e)
            {
                alert(data);
            }
        });
        return false;
    }

</script>

<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">ویرایش قالب</h1>
    <div class="grid_12">
        <div class="grid_12">
            <form class="box" id="body" onsubmit="return edit(this);">
                <div class="header">
                    <h2>ویرایش پس زمینه چت روم</h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>عکس پشت زمینه:</strong></label>
                        <div>
                            <span>عکس فعلی: <img style="width:80px;height:80px" src="<?php echo(url($thememanager['chat']['body']['background-image'])); ?>"></span>
                            <input type="file" name="background-image" id="bgchatroom_img" /></div>
                    </div>
                    <div class="row">
                        <label><strong>نحوه تکرار پشت زمینه:</strong></label>
                        <div><select name="background-repeat" class="search">
                                <option value="no-repeat" <?php if ($thememanager['chat']['body']['background-repeat'] == 'no-repeat') echo('selected'); ?>>بدون تکرار</option>
                                <option value="repeat" <?php if ($thememanager['chat']['body']['background-repeat'] == 'repeat') echo('selected'); ?>>تکرار عمودی و افقی</option>
                                <option value="repeat-x" <?php if ($thememanager['chat']['body']['background-repeat'] == 'repeat-x') echo('selected'); ?>>تکرار افقی</option>
                                <option value="repeat-y" <?php if ($thememanager['chat']['body']['background-repeat'] == 'repeat-y') echo('selected'); ?>>تکرار عمودی</option>
                            </select></div>
                    </div>
                    <div class="row">
                        <label><strong>رنگ پشت زمینه:</strong></label>
                        <div><input type="color" name="background-color"  value="<?php echo($thememanager['chat']['body']['background-color']); ?>"/></div>
                    </div>
                    <div class="row">
                        <label><strong>کادر :</strong></label>
                        <div><input type="text" dir="ltr" style="font-family: Yekp;" name="border"  value="<?php echo($thememanager['chat']['body']['border']); ?>"/></div>
                    </div>
                    <div class="row">
                        <label><strong> فونت متن های داخل چتروم مانند پنجره تنظیمات و.... : </strong></label>
                        <div><select name="font-family" class="search">
                                <?php
                                $font_family = array('BYekan', 'BMorvarid', 'BNarm', 'BZiba', 'BKoodak', 'BSina', 'BTitr', 'BSinaBold', 'BBadr', 'BBaran', 'BBardiya', 'BElham', 'BLotus', 'BNazanin', 'BZar', 'Yekp', 'myriadarabicregular', 'dinarm', 'Mj Baseet', 'Rezvan', 'AGoogle', 'tahoma');
                                foreach ($font_family as $font) {
                                    ?>
                                    <option<?php if ($thememanager['chat']['body']['font-family'] == $font) echo(' selected'); ?>><?php echo($font); ?></option>
                                    <?php
                                }
                                ?>
                            </select></div>
                    </div>
                    <div class="row">
                        <label><strong>سایز فونت متن های داخل چتروم مانند پنجره تنظیمات و .... :</strong></label>
                        <div><input name="font-size" type="text" value="<?php echo($thememanager['chat']['body']['font-size']); ?>" dir="ltr" style="font-family: Yekp;" /></div>
                    </div>
                    <div class="row">
                        <div>
                            <p style="text-align: center;">
                                <input type="submit" value="ذخیره" />
                            </p>
                        </div>    
                    </div>
                </div>
            </form>
        </div>
        <div class="grid_12">
            <form class="box" id="privat" onsubmit="return edit(this);">
                <div class="header">
                    <h2>ویرایش خصوصی چت روم</h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>عکس پشت زمینه:</strong></label>
                        <div>
                            <span>عکس فعلی: <img style="width:80px;height:80px" src="<?php echo(url($thememanager['chat']['privat']['background-image'])); ?>"></span>
                            <input type="file" name="background-image" id="privat_img" />
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>نحوه تکرار پشت زمینه:</strong></label>
                        <div><select name="background-repeat" class="search">
                                <option value="no-repeat" <?php if ($thememanager['chat']['privat']['background-repeat'] == 'no-repeat') echo('selected'); ?>>بدون تکرار</option>
                                <option value="repeat" <?php if ($thememanager['chat']['privat']['background-repeat'] == 'repeat') echo('selected'); ?>>تکرار عمودی و افقی</option>
                                <option value="repeat-x" <?php if ($thememanager['chat']['privat']['background-repeat'] == 'repeat-x') echo('selected'); ?>>تکرار افقی</option>
                                <option value="repeat-y" <?php if ($thememanager['chat']['privat']['background-repeat'] == 'repeat-y') echo('selected'); ?>>تکرار عمودی</option>
                            </select></div>
                    </div>
                    <div class="row">
                        <label><strong>رنگ پشت زمینه:</strong></label>
                        <div><input type="color" name="background-color" value="<?php echo($thememanager['chat']['privat']['background-color']); ?>"/></div>
                    </div>
                    <div class="row">
                        <div>
                            <p style="text-align: center;">
                                <input type="submit" value="ذخیره" />
                            </p>
                        </div>    
                    </div>
                </div>
            </form>
        </div>
        <div class="grid_12">
            <form class="box" id="chatinhalt" onsubmit="return edit(this);">
                <div class="header">
                    <h2>ویرایش عمومی</h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>عکس پشت زمینه:</strong></label>
                        <div>
                            <span>عکس فعلی: <img style="width:80px;height:80px" src="<?php echo(url($thememanager['chat']['chatinhalt']['background-image'])); ?>"></span>
                            <input type="file" name="background-image" id="chat_img" /></div>
                    </div>
                    <div class="row">
                        <label><strong>نحوه تکرار پشت زمینه:</strong></label>
                        <div><select name="background-repeat" class="search">
                                <option value="no-repeat" <?php if ($thememanager['chat']['chatinhalt']['background-repeat'] == 'no-repeat') echo('selected'); ?>>بدون تکرار</option>
                                <option value="repeat" <?php if ($thememanager['chat']['chatinhalt']['background-repeat'] == 'repeat') echo('selected'); ?>>تکرار عمودی و افقی</option>
                                <option value="repeat-x" <?php if ($thememanager['chat']['chatinhalt']['background-repeat'] == 'repeat-x') echo('selected'); ?>>تکرار افقی</option>
                                <option value="repeat-y" <?php if ($thememanager['chat']['chatinhalt']['background-repeat'] == 'repeat-y') echo('selected'); ?>>تکرار عمودی</option>
                            </select></div>
                    </div> 
                    <div class="row">
                        <label><strong>کادر :</strong></label>
                        <div><input type="text" dir="ltr" style="font-family: Yekp;" name="border"  value="<?php echo($thememanager['chat']['chatinhalt']['border']); ?>"/></div>
                    </div>                    
                    <div class="row">
                        <label><strong>رنگ پشت زمینه:</strong></label>
                        <div><input type="color" name="background-color"  value="<?php echo($thememanager['chat']['chatinhalt']['background-color']); ?>"/></div>
                    </div>
                    <div class="row">
                        <label><strong>فونت اسامی کاربران: </strong></label>
                        <div><select name="font-family" class="search">
                                <?php
                                $font_family = array('BYekan', 'BMorvarid', 'BNarm', 'BZiba', 'BKoodak', 'BSina', 'BTitr', 'BSinaBold', 'BBadr', 'BBaran', 'BBardiya', 'BElham', 'BLotus', 'BNazanin', 'BZar', 'Yekp', 'myriadarabicregular', 'dinarm', 'Mj Baseet', 'Rezvan', 'AGoogle', 'tahoma');
                                foreach ($font_family as $font) {
                                    ?>
                                    <option<?php if ($thememanager['chat']['chatinhalt']['font-family'] == $font) echo(' selected'); ?>><?php echo($font); ?></option>
                                    <?php
                                }
                                ?>
                            </select></div>
                    </div>
                    <div class="row">
                        <label><strong>سایز فونت اسامی کاربران:</strong></label>
                        <div><input name="font-size" type="text" value="<?php echo($thememanager['chat']['chatinhalt']['font-size']); ?>" dir="ltr" style="font-family: Yekp;" /></div>
                    </div>
                    <div class="row">
                        <label><strong>سایه:</strong></label>
                        <div><input name="box-shadow" type="text" value="<?php echo($thememanager['chat']['chatinhalt']['box-shadow']); ?>" dir="ltr" style="font-family: Yekp;" /></div>
                    </div>
                    <div class="row">
                        <label><strong>گرد کردن گوشه ها:</strong></label>
                        <div><input name="border-radius" type="text" value="<?php echo($thememanager['chat']['chatinhalt']['border-radius']); ?>" dir="ltr" style="font-family: Yekp;" /></div>
                    </div>
                    <div class="row">
                        <label><strong>سمت :</strong></label>
                        <div><select name="float" class="search">
                                <?php
                                $temp = array('right' => 'راست', 'left' => 'چپ');
                                foreach ($temp as $key => $val) {
                                    ?>
                                    <option value="<?php echo($key); ?>"<?php if ($thememanager['chat']['chatinhalt']['float'] == $key) echo(' selected'); ?>><?php echo($val); ?></option>
                                    <?php
                                }
                                ?>
                            </select></div>
                    </div>
               
                    
                    <div class="row">
                        <div>
                            <p style="text-align: center;">
                                <input type="submit" value="ذخیره" />
                            </p>
                        </div>    
                    </div>
                </div>
            </form>
        </div>
        <div class="grid_12">
            <form class="box" id="onlinelist" onsubmit="return edit(this);">
                <div class="header">
                    <h2>ویرایش لیست آنلاین ها</h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>عکس پشت زمینه:</strong></label>
                        <div>
                            <span>عکس فعلی: <img style="width:80px;height:80px" src="<?php echo(url($thememanager['chat']['onlinelist']['background-image'])); ?>"></span>
                            <input type="file" name="background-image" id="online_img" /></div>
                    </div>
                    <div class="row">
                        <label><strong>نحوه تکرار پشت زمینه:</strong></label>
                        <div><select name="background-repeat" class="search">
                                <option value="no-repeat" <?php if ($thememanager['chat']['onlinelist']['background-repeat'] == 'no-repeat') echo('selected'); ?>>بدون تکرار</option>
                                <option value="repeat" <?php if ($thememanager['chat']['onlinelist']['background-repeat'] == 'repeat') echo('selected'); ?>>تکرار عمودی و افقی</option>
                                <option value="repeat-x" <?php if ($thememanager['chat']['onlinelist']['background-repeat'] == 'repeat-x') echo('selected'); ?>>تکرار افقی</option>
                                <option value="repeat-y" <?php if ($thememanager['chat']['onlinelist']['background-repeat'] == 'repeat-y') echo('selected'); ?>>تکرار عمودی</option>
                            </select></div>
                    </div>
                    <div class="row">
                        <label><strong>کادر :</strong></label>
                        <div><input type="text" dir="ltr" style="font-family: Yekp;" name="border"  value="<?php echo($thememanager['chat']['onlinelist']['border']); ?>"/></div>
                    </div>
                    <div class="row">
                        <label><strong>رنگ پشت زمینه:</strong></label>
                        <div><input type="color" name="background-color"  value="<?php echo($thememanager['chat']['onlinelist']['background-color']); ?>"/></div>
                    </div>
                    <div class="row">
                        <label><strong>فونت:</strong></label>
                        <div><select name="font-family" class="search">
                                <?php
                                $font_family = array('dcfont-2','BYekan', 'BMorvarid', 'BNarm', 'BZiba', 'BKoodak', 'BSina', 'BTitr', 'BSinaBold', 'BBadr', 'BBaran', 'BBardiya', 'BElham', 'BLotus', 'BNazanin', 'BZar', 'Yekp', 'myriadarabicregular', 'dinarm', 'Mj Baseet', 'Rezvan', 'AGoogle', 'tahoma');
                                foreach ($font_family as $font) {
                                    ?>
                                    <option<?php if ($thememanager['chat']['onlinelist']['font-family'] == $font) echo(' selected'); ?>><?php echo($font); ?></option>
                                    <?php
                                }
                                ?>
                            </select></div>
                    </div>
                    <div class="row">
                        <label><strong>سایز فونت:</strong></label>
                        <div><input name="font-size" type="text" value="<?php echo($thememanager['chat']['onlinelist']['font-size']); ?>" dir="ltr" style="font-family: Yekp;" /></div>
                    </div>
                    <div class="row">
                        <label><strong>سایه:</strong></label>
                        <div><input name="box-shadow" type="text" value="<?php echo($thememanager['chat']['onlinelist']['box-shadow']); ?>" dir="ltr" style="font-family: Yekp;" /></div>
                    </div>
                    <div class="row">
                        <label><strong>گرد کردن گوشه ها:</strong></label>
                        <div><input name="border-radius" type="text" value="<?php echo($thememanager['chat']['onlinelist']['border-radius']); ?>" dir="ltr" style="font-family: Yekp;" /></div>
                    </div>
                    <div class="row">
                        <label><strong>پهنای لیست:</strong></label>
                        <div><input name="width" type="text" value="<?php echo(isset($thememanager['chat']['onlinelist']['width']) ? $thememanager['chat']['onlinelist']['width'] : '240px'); ?>" dir="ltr" style="font-family: Yekp;" /></div>
                    </div>
<div class="row">
                        <label><strong>گرد کردن گوشه آواتار:</strong></label>
                        <div><input type="text" dir="ltr" style="font-family: Yekp;" name="avatar-radius"  value="<?php echo($avatarstyle['border-radius']); ?>"/></div>
                    </div>
                    <div class="row">
                        <label><strong>تغییر رنگ دور آواتار در لیست و عمومی:</strong></label>
                        <div><input type="color" name="avatar-color" value="<?php echo($avatarstyle['avatar-color']); ?>"/></div>
                    </div>
       <div class="row">
                        <label><strong>سایز دور آواتار :</strong></label>
                        <div><input type="text" dir="ltr" style="font-family: Yekp;" name="border-size"  value="<?php echo($avatarstyle['border-size']); ?>"/></div>
                    </div>
         
  <div class="row">
                        <label><strong>افکت هاور:</strong><small></small></label>
                        <div>
<input type="text" dir="ltr" style="font-family: Yekp;" name="avatar-hover"  value="<?php echo($avatarstyle['avatar-hover']); ?>"/>
<b> <font color="red" font-weight:bold;>توجه:برای غیرفعال سازی این کادررا خالی بگذارید</font></b></div>
                    </div>
                    <div class="row">
                        <div>
                            <p style="text-align: center;">
                                <input type="submit" value="ذخیره" />
                            </p>
                        </div>    
                    </div>
                </div>
            </form>
        </div>
        <div class="grid_12">
            <form class="box" id="rooms" onsubmit="return edit(this);">
                <div class="header">
                    <h2>ویرایش اتاق ها</h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>عکس پشت زمینه:</strong></label>
                        <div>
                            <span>عکس فعلی: <img style="width:80px;height:80px" src="<?php echo(url($thememanager['chat']['rooms']['background-image'])); ?>"></span>
                            <input type="file" name="background-image" id="room_img" />
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>نحوه تکرار پشت زمینه:</strong></label>
                        <div><select name="background-repeat" class="search">
                                <option value="no-repeat" <?php if ($thememanager['chat']['rooms']['background-repeat'] == 'no-repeat') echo('selected'); ?>>بدون تکرار</option>
                                <option value="repeat" <?php if ($thememanager['chat']['rooms']['background-repeat'] == 'repeat') echo('selected'); ?>>تکرار عمودی و افقی</option>
                                <option value="repeat-x" <?php if ($thememanager['chat']['rooms']['background-repeat'] == 'repeat-x') echo('selected'); ?>>تکرار افقی</option>
                                <option value="repeat-y" <?php if ($thememanager['chat']['rooms']['background-repeat'] == 'repeat-y') echo('selected'); ?>>تکرار عمودی</option>
                            </select></div>
                    </div>
                    <div class="row">
                        <label><strong>کادر :</strong></label>
                        <div><input type="text" dir="ltr" style="font-family: Yekp;" name="border"  value="<?php echo($thememanager['chat']['rooms']['border']); ?>"/></div>
                    </div>
                    <div class="row">
                        <label><strong>رنگ پشت زمینه:</strong></label>
                        <div><input type="color" name="background-color"  value="<?php echo($thememanager['chat']['rooms']['background-color']); ?>"/></div>
                    </div>
                    <div class="row">
                        <label><strong>فونت:</strong></label>
                        <div><select name="font-family" class="search">
                                <?php
                                $font_family = array('BYekan', 'BMorvarid', 'BNarm', 'BZiba', 'BKoodak', 'BSina', 'BTitr', 'BSinaBold', 'BBadr', 'BBaran', 'BBardiya', 'BElham', 'BLotus', 'BNazanin', 'BZar', 'Yekp', 'myriadarabicregular', 'dinarm', 'Mj Baseet', 'Rezvan', 'AGoogle', 'tahoma');
                                foreach ($font_family as $font) {
                                    ?>
                                    <option<?php if ($thememanager['chat']['rooms']['font-family'] == $font) echo(' selected'); ?>><?php echo($font); ?></option>
                                    <?php
                                }
                                ?>
                            </select></div>
                    </div>
                    <div class="row">
                        <label><strong>سایز فونت:</strong></label>
                        <div><input name="font-size" type="text" value="<?php echo($thememanager['chat']['rooms']['font-size']); ?>" dir="ltr" style="font-family: Yekp;" /></div>
                    </div>
                    <div class="row">
                        <label><strong>کلفتی فونت:</strong></label>
                        <div><input type="text" name="font-weight" value="<?php echo($thememanager['chat']['rooms']['font-weight']); ?>" dir="ltr" style="font-family: Yekp;" /></div>
                    </div>
                    <div class="row">
                        <label><strong>رنگ نوشته:</strong></label>
                        <div><input type="color" name="color"  value="<?php echo($thememanager['chat']['rooms']['color']); ?>"/></div>
                    </div>
                    <div class="row">
                        <label><strong>سایه:</strong></label>
                        <div><input name="box-shadow" type="text" value="<?php echo($thememanager['chat']['rooms']['box-shadow']); ?>" dir="ltr" style="font-family: Yekp;" /></div>
                    </div>
                    <div class="row">
                        <label><strong>گرد کردن گوشه ها:</strong></label>
                        <div><input name="border-radius" type="text" value="<?php echo($thememanager['chat']['rooms']['border-radius']); ?>" dir="ltr" style="font-family: Yekp;" /></div>
                    </div>
                    <div class="row">
                        <div>
                            <p style="text-align: center;">
                                <input type="submit" value="ذخیره" />
                            </p>
                        </div>    
                    </div>
                </div>
            </form>
        </div>
        <div class="grid_12">
            <form class="box" id="form" onsubmit="return edit(this);">
                <div class="header">
                    <h2>ویرایش کادر ارسال</h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>عکس پشت زمینه:</strong></label>
                        <div>                            
                            <span>عکس فعلی: <img class="fancybox" style="width:80px;height:80px" src="<?php echo(url($thememanager['chat']['form']['background-image'])); ?>"></span>
                            <input type="file" name="background-image" id="taskbar_img" />
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>نحوه تکرار پشت زمینه:</strong></label>
                        <div><select name="background-repeat" class="search">
                                <option value="no-repeat" <?php if ($thememanager['chat']['form']['background-repeat'] == 'no-repeat') echo('selected'); ?>>بدون تکرار</option>
                                <option value="repeat" <?php if ($thememanager['chat']['form']['background-repeat'] == 'repeat') echo('selected'); ?>>تکرار عمودی و افقی</option>
                                <option value="repeat-x" <?php if ($thememanager['chat']['form']['background-repeat'] == 'repeat-x') echo('selected'); ?>>تکرار افقی</option>
                                <option value="repeat-y" <?php if ($thememanager['chat']['form']['background-repeat'] == 'repeat-y') echo('selected'); ?>>تکرار عمودی</option>
                            </select></div>
                    </div>
                    <div class="row">
                        <label><strong>کادر :</strong></label>
                        <div><input type="text" dir="ltr" style="font-family: Yekp;" name="border"  value="<?php echo($thememanager['chat']['form']['border']); ?>"/></div>
                    </div>
                    <div class="row">
                        <label><strong>رنگ پشت زمینه:</strong></label>
                        <div><input type="color" name="background-color"  value="<?php echo($thememanager['chat']['form']['background-color']); ?>"/></div>
                    </div>
                    <div class="row">
                        <label><strong>فونت:</strong></label>
                        <div><select name="font-family" class="search">
                                <?php
                                $font_family = array('BYekan', 'BMorvarid', 'BNarm', 'BZiba', 'BKoodak', 'BSina', 'BTitr', 'BSinaBold', 'BBadr', 'BBaran', 'BBardiya', 'BElham', 'BLotus', 'BNazanin', 'BZar', 'Yekp', 'myriadarabicregular', 'dinarm', 'Mj Baseet', 'Rezvan', 'AGoogle', 'tahoma');
                                foreach ($font_family as $font) {
                                    ?>
                                    <option<?php if ($thememanager['chat']['form']['font-family'] == $font) echo(' selected'); ?>><?php echo($font); ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>سایز فونت:</strong></label>
                        <div><input name="font-size" type="text" value="<?php echo($thememanager['chat']['form']['font-size']); ?>" dir="ltr" style="font-family: Yekp;" /></div>
                    </div>
                    <div class="row">
                        <label><strong>سایه:</strong></label>
                        <div><input name="box-shadow" type="text" value="<?php echo($thememanager['chat']['rooms']['box-shadow']); ?>" dir="ltr" style="font-family: Yekp;" /></div>
                    </div>
                    <div class="row">
                        <label><strong>گرد کردن گوشه ها:</strong></label>
                        <div><input name="border-radius" type="text" value="<?php echo($thememanager['chat']['rooms']['border-radius']); ?>" dir="ltr" style="font-family: Yekp;" /></div>
                    </div>
                    <div class="row">
                        <div>
                            <p style="text-align: center;">
                                <input type="submit" value="ذخیره" />
                            </p>
                        </div>    
                    </div>
                </div>
            </form>
        </div>
        <div class="grid_12">
            <form class="box" id="linkdooni" onsubmit="return edit(this);">
                <div class="header">
                    <h2>ویرایش لینکدونی چت روم</h2>
                </div>
                <div class="content tabbed">
                    <div class="row" style="display: none">
                        <label><strong></strong></label>
                        <div><input type="file" name="background-image" id="privat_img" /></div>
                    </div>
                    <div class="row">
                        <label><strong>کادر :</strong></label>
                        <div><input type="text" dir="ltr" style="font-family: Yekp;" name="border"  value="<?php echo($thememanager['chat']['linkdooni']['border']); ?>"/></div>
                    </div>
                    <div class="row">
                        <label><strong>رنگ پشت زمینه:</strong></label>
                        <div><input type="color" name="background-color" value="<?php echo($thememanager['chat']['linkdooni']['background-color']); ?>"/></div>
                    </div>
                    <div class="row">
                        <label><strong>نحوه حرکت لینک ها:</strong></label>
                        <div><select name="direction" class="search">
                                <option value="right" <?php if ($thememanager['chat']['linkdooni']['direction'] == 'right') echo('selected'); ?>>چپ به راست (( از سمت چپ به راست ))</option>
                                <option value="left" <?php if ($thememanager['chat']['linkdooni']['direction'] == 'left') echo('selected'); ?>>راست به چپ (( از سمت راست به چپ ))</option>
                            </select></div>
                    </div>
                    <div class="row">
                        <label><strong>متن فاصله ی بین لینک ها:</strong></label>
                        <div><input name="space-text" type="text" value="<?php echo($thememanager['chat']['linkdooni']['space-text']); ?>" dir="ltr" style="font-family:Yekp;" /></div>
                    </div>
                    <div class="row">
                        <label><strong>رنگ متن فاصله ی بین لینک ها:</strong></label>
                        <div><input type="color" name="space-textcolor" value="<?php echo($thememanager['chat']['linkdooni']['space-textcolor']); ?>"/></div>
                    </div>
                    <div class="row">
                        <label><strong>فونت لینک ها:</strong></label>
                        <div><select name="font-family" class="search">
                                <?php
                                $font_family = array('BYekan', 'BMorvarid', 'BNarm', 'BZiba', 'BKoodak', 'BSina', 'BTitr', 'BSinaBold', 'BBadr', 'BBaran', 'BBardiya', 'BElham', 'BLotus', 'BNazanin', 'BZar', 'Yekp', 'myriadarabicregular', 'dinarm', 'Mj Baseet', 'Rezvan', 'AGoogle', 'tahoma');
                                foreach ($font_family as $font) {
                                    ?>
                                    <option<?php if ($thememanager['chat']['linkdooni']['font-family'] == $font) echo(' selected'); ?>><?php echo($font); ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div>
                            <p style="text-align: center;">
                                <input type="submit" value="ذخیره" />
                            </p>
                        </div>    
                    </div>
                </div>
            </form>
        </div>


        <div class="grid_12">
            <form class="box" id="welcomebox" onsubmit="return edit(this);">
                <div class="header">
                    <h2>ویرایش باکس خوش امد گویی چتروم</h2>
                </div>
                <div class="content tabbed">
                    <div class="row" style="display: none">
                        <label><strong></strong></label>
                        <div><input type="file" name="background-image" id="welcomebox_img" /></div>
                    </div>
                    <div class="row">
                        <label><strong>رنگ پشت زمینه:</strong></label>
                        <div><input type="color" name="background-color" value="<?php echo($thememanager['chat']['welcomebox']['background-color']); ?>"/></div>
                    </div>
                    <div class="row">
                        <label><strong>کادر :</strong></label>
                        <div><input type="text" dir="ltr" style="font-family: Yekp;" name="border"  value="<?php echo($thememanager['chat']['welcomebox']['border']); ?>"/></div>
                    </div>
                    <div class="row">
                        <label><strong>رنگ نوشته ها:</strong></label>
                        <div><input type="color" name="fonts-color" value="<?php echo($thememanager['chat']['welcomebox']['fonts-color']); ?>"/></div>
                    </div>
                    <div class="row">
                        <label><strong>سایز فونت ها:</strong></label>
                        <div><input name="font-size" type="text" value="<?php echo($thememanager['chat']['welcomebox']['font-size']); ?>" dir="ltr" style="font-family:Yekp;" /></div>
                    </div>
                    <div class="row">
                        <label><strong>رنگ نام کاربر:</strong></label>
                        <div><input type="color" name="username-color" value="<?php echo($thememanager['chat']['welcomebox']['username-color']); ?>"/></div>
                    </div>
                    <div class="row">
                        <label><strong>سایه:</strong></label>
                        <div><input name="box-shadow" type="text" value="<?php echo($thememanager['chat']['welcomebox']['box-shadow']); ?>" dir="ltr" style="font-family:Yekp;" /></div>
                    </div>
                    <div class="row">
                        <label><strong>گرد کردن گوشه ها:</strong></label>
                        <div><input name="border-radius" type="text" value="<?php echo($thememanager['chat']['welcomebox']['border-radius']); ?>" dir="ltr" style="font-family:Yekp;" /></div>
                    </div>
                    <div class="row">
                        <label><strong>فونت نوشته ها:</strong></label>
                        <div><select name="font-family" class="search">
                                <?php
                                $font_family = array('BYekan', 'BMorvarid', 'BNarm', 'BZiba', 'BKoodak', 'BSina', 'BTitr', 'BSinaBold', 'BBadr', 'BBaran', 'BBardiya', 'BElham', 'BLotus', 'BNazanin', 'BZar', 'Yekp', 'myriadarabicregular', 'dinarm', 'Mj Baseet', 'Rezvan', 'AGoogle', 'tahoma');
                                foreach ($font_family as $font) {
                                    ?>
                                    <option<?php if ($thememanager['chat']['linkdooni']['font-family'] == $font) echo(' selected'); ?>><?php echo($font); ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div>
                            <p style="text-align: center;">
                                <input type="submit" value="ذخیره" />
                            </p>
                        </div>    
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>