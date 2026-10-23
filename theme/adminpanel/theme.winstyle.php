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
<script type="text/javascript" src="<?php echo(theme::url('js/jquery.fancybox-1.3.4.pack.min.js')); ?>"></script>
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo(theme::url('css/jquery.fancybox-1.3.4.css')); ?>">
<script type="text/javascript">
    $(function ($) {
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
    $.noConflict();
</script>
<script type="text/javascript">
    function edit() {
        var jQueryform = jQuery('#formeditsettings');
        jQuery.ajax({
            type: "POST",
            url: "<?php echo(url('ajax/admin.theme.winstyle')); ?>",
            data: jQueryform.serialize(),
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
    }
</script>
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">ویرایش قالب</h1>
    <div class="grid_12">
        <div class="grid_12">
            <form class="box  validate" id="formeditsettings">
                <input type="hidden" name="token" value="<?php echo(session('changepw')); ?>" />
                <div class="header">
                    <h2>تنظیمات استایل پنجره ها</h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>سبک نمایش پنجره ها:</strong></label>
                        <div>
                            <select name="chat_winstyle" class="search required">
                                <option value="theme/winstyle/style1.css" <?php if ($options['chat_winstyle'] == 'theme/winstyle/style1.css') echo('selected'); ?>>استایل 1</option>
                                <option value="theme/winstyle/style2.css" <?php if ($options['chat_winstyle'] == 'theme/winstyle/style2.css') echo('selected'); ?>>استایل 2</option>
                                <option value="theme/winstyle/style3.css" <?php if ($options['chat_winstyle'] == 'theme/winstyle/style3.css') echo('selected'); ?>>استایل 3</option>
                                <option value="theme/winstyle/style4.css" <?php if ($options['chat_winstyle'] == 'theme/winstyle/style4.css') echo('selected'); ?>>استایل 4</option>
                                <option value="theme/winstyle/style5.css" <?php if ($options['chat_winstyle'] == 'theme/winstyle/style5.css') echo('selected'); ?>>استایل 5</option>
                                <option value="theme/winstyle/style6.css" <?php if ($options['chat_winstyle'] == 'theme/winstyle/style6.css') echo('selected'); ?>>استایل 6</option>
                                <option value="theme/winstyle/style7.css" <?php if ($options['chat_winstyle'] == 'theme/winstyle/style7.css') echo('selected'); ?>>استایل 7</option>
                                <option value="theme/winstyle/style8.css" <?php if ($options['chat_winstyle'] == 'theme/winstyle/style8.css') echo('selected'); ?>>استایل 8</option>
                                <option value="theme/winstyle/style9.css" <?php if ($options['chat_winstyle'] == 'theme/winstyle/style9.css') echo('selected'); ?>>استایل 9</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <center> <button class="button blue flat block" onclick="edit();">ویرایش</button></center>
    </div>
    <div class="content">
        <div class="enthkamtheme" id="theme-theme/winstyle/style1.css">
            <center>
                <div class="nametheme"><p>استایل 1</p></div>
                <img class="fancybox" data-big="<?php echo(url('theme/winstyle/style1.png')); ?>" title="استایل 1" src="<?php echo(url('theme/winstyle/style1.png')); ?>">
            </center>
        </div>
        <div class="enthkamtheme" id="theme-theme/winstyle/style2.css">
            <center>
                <div class="nametheme"><p>استایل 2</p></div>
                <img class="fancybox" data-big="<?php echo(url('theme/winstyle/style2.png')); ?>" title="استایل 2" src="<?php echo(url('theme/winstyle/style2.png')); ?>">
            </center>
        </div>
        <div class="enthkamtheme" id="theme-theme/winstyle/style3.css">
            <center>
                <div class="nametheme"><p>استایل 3</p></div>
                <img class="fancybox" data-big="<?php echo(url('theme/winstyle/style3.png')); ?>" title="استایل 3" src="<?php echo(url('theme/winstyle/style3.png')); ?>">
            </center>
        </div>
        <div class="enthkamtheme" id="theme-theme/winstyle/style4.css">
            <center>
                <div class="nametheme"><p>استایل 4</p></div>
                <img class="fancybox" data-big="<?php echo(url('theme/winstyle/style4.png')); ?>" title="استایل 4" src="<?php echo(url('theme/winstyle/style4.png')); ?>">
            </center>
        </div>
        <div class="enthkamtheme" id="theme-theme/winstyle/style5.css">
            <center>
                <div class="nametheme"><p>استایل 5</p></div>
                <img class="fancybox" data-big="<?php echo(url('theme/winstyle/style5.png')); ?>" title="استایل 5" src="<?php echo(url('theme/winstyle/style5.png')); ?>">
            </center>
        </div>
        <div class="enthkamtheme" id="theme-theme/winstyle/style6.css">
            <center>
                <div class="nametheme"><p>استایل 6</p></div>
                <img class="fancybox" data-big="<?php echo(url('theme/winstyle/style6.png')); ?>" title="استایل 6" src="<?php echo(url('theme/winstyle/style6.png')); ?>">
            </center>
        </div>
        <div class="enthkamtheme" id="theme-theme/winstyle/style7.css">
            <center>
                <div class="nametheme"><p>استایل 7</p></div>
                <img class="fancybox" data-big="<?php echo(url('theme/winstyle/style7.png')); ?>" title="استایل 7" src="<?php echo(url('theme/winstyle/style7.png')); ?>">
            </center>
        </div>
        <div class="enthkamtheme" id="theme-theme/winstyle/style8.css">
            <center>
                <div class="nametheme"><p>استایل 8</p></div>
                <img class="fancybox" data-big="<?php echo(url('theme/winstyle/style8.png')); ?>" title="استایل 8" src="<?php echo(url('theme/winstyle/style8.png')); ?>">
            </center>
        </div>
        <div class="enthkamtheme" id="theme-theme/winstyle/style9.css">
            <center>
                <div class="nametheme"><p>استایل 9</p></div>
                <img class="fancybox" data-big="<?php echo(url('theme/winstyle/style9.png')); ?>" title="استایل 9" src="<?php echo(url('theme/winstyle/style9.png')); ?>">
            </center>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>