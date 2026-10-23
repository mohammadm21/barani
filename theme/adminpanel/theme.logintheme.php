<?php
include('header.php');
include('sidebar.php');
?>
<style type="text/css">
    a.fancybox img {
        border: none;
        box-shadow: 0 1px 7px rgba(0,0,0,0.6);
        -o-transform: scale(1,1); -ms-transform: scale(1,1); -moz-transform: scale(1,1); -webkit-transform: scale(1,1); transform: scale(1,1); -o-transition: all 0.2s ease-in-out; -ms-transition: all 0.2s ease-in-out; -moz-transition: all 0.2s ease-in-out; -webkit-transition: all 0.2s ease-in-out; transition: all 0.2s ease-in-out;
    } 
    a.fancybox:hover img {
        position: relative; z-index: 999; -o-transform: scale(1.03,1.03); -ms-transform: scale(1.03,1.03); -moz-transform: scale(1.03,1.03); -webkit-transform: scale(1.03,1.03); transform: scale(1.03,1.03);
    }
    .cpicker{
        display: inline-block;
    }
</style>
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo(theme::url('css/jquery.fancybox-1.3.4.css')); ?>">
<script type="text/javascript">

function filemanager_password_checker() {
        $.ajax({
            type: "POST",
            url: "<?php echo(url('ajax/admin.filemanager.checkpw')); ?>",
            data: {password: $("#filemanager_password").val()},
            success: function (data) {
                switch (data) {
                    case('ok'):
                        window.location.reload();
                        break;
                    case('invalid'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'پسورد اشتباه است'});
                        break;
                    default:
                        alert(data);
                        break;
                }
            }
        });
    }
  /*  $(function ($) {
        var addToAll = false;
        var gallery = true;
        var titlePosition = 'inside';
        $(addToAll ? 'img' : 'img.fancybox').each(function () {
            var $this = $(this);
            var title = $this.attr('title');
            var src = $this.attr('data-big') || $this.attr('src');
            var a = $('<a href="#" class="fancybox"></a>').attr('href', src).attr('title', title);
            $this.wrap(a);
        });
        if (gallery)
            $('a.fancybox').attr('rel', 'fancyboxgallery');
        $('a.fancybox').fancybox({
            titlePosition: titlePosition
        });
    });
    $.noConflict(); */
    function selecttheme(theme) {

        jQuery.ajax({
            url: '<?php echo(url('ajax/admin.logintheme')); ?>',
            type: 'POST',
            data: {theme: theme},
            success: function (data) {
                switch (data) {
                    case('ok'):
                        jQuery.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'ذخیره شد'});
                        break;
                    default:
                        alert(data);
                        break;
                }
            }
        });
        return false;
    }
    function removetheme(remove) {

        jQuery.ajax({
            url: '<?php echo(url('ajax/admin.removetheme')); ?>',
            type: 'POST',
            data: {remove: remove},
            success: function (data) {
                switch (data) {
                    case('ok'):
                        jQuery.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'حذف گردید'});
                        break;
                    case('notremov'):
                        jQuery.msgGrowl({type: 'warning', title: 'خطا', text: 'شما قادر به حذف قالب پیش فرض چت روم نمیباشید'});
                        break;
                    case('nofile'):
                        jQuery.msgGrowl({type: 'warning', title: 'خطا', text: 'این قالب بر روی چت روم شما موجود نمیباشد .'});
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
<?php if (session('filemanager_password') == $options['filemanager_password']) { ?>
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">تنظیمات قالب</h1>
    <div class="grid_12">
        <div class="grid_12">

            <div class="header">
                <h2>انتخاب قالب </h2>
            </div>
            <div class="header">
                <h2>
                    <img class="icon" src="<?php echo(theme::url('img/icons/packs/fugue/16x16/rooztarh.png')); ?>">
<font color="red">
                    توجه : بعد از انتخاب قالب مورد نظر قالب های اضافه رو جهت سبک تر شدن چت روم حذف کنید .
                    </font>
            </div>
            <div class="content ">
                <?php

                function dirToArray($dir) {
                    $result = array();
                    $cdir = scandir($dir);
                    foreach ($cdir as $key => $value) {
                        if (!in_array($value, array(".", ".."))) {
                            if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                                $result[] = $value;
                                sort($result, SORT_NATURAL | SORT_FLAG_CASE);
                            } else {
                                
                            }
                        }
                    }
                    return $result;
                }

                $my_theme = $options['chat_theme'];
                $themes_dir = dirToArray('theme');
                foreach ($themes_dir as $row) {
                    $select = null;
                    $dir = substr($row, 0, 5);
                    if ($dir != 'theme') {
                        continue;
                    }
                    if ($my_theme == $row) {
                        $select = 'checked';
                    }
                    ?>
                    <div class="enthkamtheme" id="theme-<?php echo($row); ?>">
                        <center>
                            <div class="nametheme"><p><?php echo($row); ?> </p></div>
                            <img class="fancybox" data-big="<?php echo(url('theme/' . $row . '/images/screenshot.jpg')); ?>" title="<?php echo($row); ?>" src="<?php echo(url('theme/' . $row . '/images/screenshot.jpg')); ?>">
                            <button class="chosethemen" onclick="selecttheme('<?php echo($row); ?>');">انتخاب قالب</button>
                            <button class="chosethemen" onclick="removetheme('<?php echo($row); ?>');">حذف قالب</button>
                        </center>
                    </div>
                    <?php
                }
                ?>    
            </div>
        </div>
    </div>
</section>
<?php } else { ?> 
    <center>
        <div style="background-image:url('<?php echo(theme::url('img/files.png')); ?>');height: 300px;width: 300px;margin-top:80px;margin-right:180px;color:#F00;border-radius: 5px;">
            <label>پسورد انتخاب قالب:</label> 
            <br>
            <input type="password" style="text-align: center; margin-top: 120px; width: 50%;" placeholder="پسوردبه روز رسانی قالب" id="filemanager_password" name="filemanager_password">
            <br>
            <button onclick="filemanager_password_checker();" class="grey cancel">باز کن</button>
        </div>
        <p><b>رمز -&gt; از ناحیه کاربری دریافت کنید</b></p>
    </center>
<?php } ?>

<?php include('footer.php'); ?>
