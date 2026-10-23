<?php
include('header.php');
include('sidebar.php');
?>
<script type="text/javascript">

    function edit() {
        $.ajax({
            type: "POST",
            url: "<?php echo(url('ajax/admin.settings.general')); ?>",
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
    function info_top_smilies() {
        $("#info_top_smilies").dialog('open');
    }
    $(document).ready(function () {
        $("#info_top_smilies").dialog({
            autoOpen: false,
            modal: true,
            width: 500,
            buttons: [{text: "متوجه شدم", click: function () {
                        $(this).dialog("close");
                    }}]
        });
    });


</script>
<div style="display: none;" id="info_top_smilies" title="راهنمای تنظیمات این سیستم">
    <p>تو جه داشته باشید این امکان به شما میدهد که شکلک های که در چت روم پر کاربر هست شماره شکلک رو در چت روم پنل مدیریت در قمت شکلک ها دیده و در اینجا وارد نمایید تا در شکلک پر کاربرد نشان داده شود مثلا شکلک 1 چت روم خیلی استفاده می شود در اینجا وارد کرده ویرایش بزنید و در قسمت چت روم شکلک ها در کادر پر کاربر نشان داده شود  </p>
</div>
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">تنظیمات</h1>
    <div class="grid_12">
        <div class="grid_12">
            <form class="box  validate" id="formeditsettings">
                <input type="hidden" name="token" value="<?php echo(session('changepw')); ?>" />
                <div class="header">
                    <h2>تنظیمات اصلی</h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>عنوان چت روم:</strong></label>
                        <div><input type="text" name="site_title" value="<?php echo(site_title); ?>"  class="required" /></div>
                    </div>
                    <div class="row">
                        <label><strong>عنوان داخلی چت روم:</strong></label>
                        <div><input type="text" name="in_title" value="<?php echo(in_title); ?>"  class="required" /></div>
                    </div>
                    <div class="row">
                        <label><strong>آدرس(های) چتروم:</strong><small>با , از هم جدا شوند</small></label>
                        <div><input type="text" name="site_url"  style="padding-left: 25px;direction: ltr;font-family: sans-serif;" class="required" value="<?php echo implode(',', $site_url) ?>"/></div>
                    </div>
 <div class="row">
                        <label><strong>آدرس پایگاه اینترنتی در پروفایل :</strong><small>پیش فرض</small></label>
                        <div><input type="text" name="profile_url"  style="padding-left: 25px;direction: ltr;font-family: sans-serif;" class="required" value="<?php echo($options['profile_url']) ?>"/></div>
                    </div>
                    <div class="row">
                        <label><strong>کلمات کلیدی چت روم:</strong></label>
                        <div><input type="text" name="keywords" value="<?php echo($options['chat_keywords']) ?>"/></div>
                    </div>
                    <div class="row">
                        <label><strong>توضیحات چت روم:</strong></label>
                        <div><input type="text" name="description" value="<?php echo($options['chat_description']) ?>"/></div>
                    </div>
                   
                   
                    <div class="row">
                        <label><strong>اتاق پیشفرض:</strong></label>
                        <div>
                            <select name="defaultroom" class="search required">
                                <?php
                                $query = db::query("SELECT `id`,`title` FROM {prefix}rooms");
                                while ($row = mysqli_fetch_array($query)) {
                                    ?>
                                    <option value="<?php echo($row['id']); ?>" <?php if ($row['id'] == $options['chat_defaultroom']) echo('selected'); ?> ><?php echo($row['title']); ?></option>
                                    <?php
                                }
                                mysqli_free_result($query);
                                ?>
                            </select>
                        </div>
                    </div> 
                    <div class="row">
                        <label><strong>ترتیب نمایش لیست آنلاین:</strong></label>
                        <div>
                            <select name="method_online_sort" class="search required">
                                <?php
                                $methodsSort = array(
                                    'sort_score' => 'بر اساس اولویت و امتیاز کاربران ( پیشنهاد میشود در این حالات قرار داده شود)',
                                    'sort_name' => 'بر اساس اولویت و نام کاربران',
                                    'type_score' => 'بر اساس آخرین درجات ساخته شده و امتیاز کاربران',
                                    'type_name' => 'بر اساس آخرین درجات ساخته شده و نام کاربری',
                                    'default' => 'پیشفرض'
                                );
                                foreach ($methodsSort as $key => $value) {
                                    ?>
                                    <option value="<?php echo($key); ?>" <?php if ($key == $options['method_online_sort']) echo('selected'); ?> ><?php echo($value); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>قالب چت روم:</strong></label>
                        <div>
                            <?php

                            function dirToArray($dir) {
                                $result = [];

                                $cdir = scandir($dir);
                                foreach ($cdir as $key => $value) {
                                    if (!in_array($value, array(".", ".."))) {
                                        if (is_dir($dir . \DIRECTORY_SEPARATOR . $value)) {
                                            $result[] = $value;
                                            sort($result, SORT_NATURAL | SORT_FLAG_CASE);
                                            // $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value); 
                                        } else {
                                            // $result[] = $value; 
                                        }
                                    }
                                }
                                return $result;
                            }
                            ?>

                            <select name="theme" class="search required">
                                <?php
                                $my_theme = theme;

                                $themes_dir = dirToArray('theme');
                                foreach ($themes_dir as $row) {
                                    $select = null;
                                    $dir = substr($row, 0, 5);
                                    if ($dir != 'theme') {
                                        continue;
                                    }
                                    if ($my_theme == $row) {
                                        $select = 'selected';
                                    }
                                    ?>
                                    <option value="<?php echo($row); ?>" <?php echo $select; ?> ><?php echo($row); ?></option>
                                    <?php
                                }
                                ?>
                            </select>

                        </div>
                    </div>
                    <div class="row">
                        <label><strong>انتقال خودکار به www:</strong> <small><a href="http://moz.com/learn/seo/redirection">تاثیر گذار در seo</a></small></label>
                        <div>بلی<input type="checkbox" name="rejecttowww" value="1" <?php if ($options['rejecttowww'] == 'true') echo('checked'); ?>  /></div>
                    </div>
                   <div class="row">
                        <label><strong>حالت حمله:</strong><small>فقط در حالت حمله شدید فعال شود</small> </label>
                        <div>بلی<input type="checkbox" name="attackmod" value="1" <?php if ($options['attackmod'] == 'true') echo('checked'); ?>  /></div>
                    </div>
                    <div class="row">
                        <label><strong>عضویت اجباری مهمان:</strong><small>با فعال شدن، مهمان پیش از استفاده باید رمز و تکرار رمز وارد و عضو شود (کل صفحه بلور می‌شود).</small></label>
                        <div>بلی<input type="checkbox" name="guest_must_register" value="1" <?php if ($options['guest_must_register'] == 'true') echo('checked'); ?>  /></div>
                    </div>
                    <div class="row">
                        <label><strong>اجازه به کپی شکلک ها:</strong>  </label>
                        <div>بلی<input type="checkbox" name="permitCPsmlies" value="1" <?php if ($options['permitCPsmlies'] == 'true') echo('checked'); ?>  /></div>
                    </div>


                    <div class="row">
                        <label><strong>ظرفیت چت روم:</strong></label>
                        <div>
                            <?php
                            $is_unlimited = chatroom::theCapacity() == '0' ? true : false;
                            ?>
                            <input type="text" readonly="yes" disabled value="<?php
                            if ($is_unlimited) {
                                echo('نا محدود');
                            } else {
                                echo(chatroom::theCapacity());
                            }
                            ?>" />
                                   <?php
                                   if (!$is_unlimited) {
                                       ?>
                                <div class="ui-progressbar" data-value="<?php echo(db::num('onlines', "`time`!='0'")); ?>"  data-max="<?php echo(chatroom::theCapacity()); ?>" data-animate="false" data-color="auto" data-showtitle="title" data-title="ظرفیت" data-showvalue="true" data-format="0 نفر"></div>

                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <center><medium style="color:red">پس از ویرایش ممکن است به طور طبیعی حدودا یک دقیقه اعمال تغییرات طول بکشد</medium><br><button class="button blue flat block" onclick="edit();">ویرایش</button></center>
    </div>
</section>
<?php include('footer.php'); ?>
