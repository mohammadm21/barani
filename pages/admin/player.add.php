<?php
$title = "موزیک جدید";
$tabactive = 'addon';
include('header.php');
?>
<script type="text/javascript" src="<?php echo(url('theme/admin/js/ajaxfileupload.js')); ?>"></script>
<script type="text/javascript">
    $(document).ready(function () {
        var el4 = '<?php echo(safe::string(giu('el4'))); ?>';
        if (el4 == 'type')
            showmsg('error', 'انجام نشد', 'نوع فایل توسط سرور تائید نشد');
        else if (el4 == 'security')
            showmsg('error', 'انجام نشد', 'سرور در فایل شما مورد امنیتی پیدا کرد و فایل شما از سرور حذف گردید.');
        else if (el4 == 'ok')
            showmsg('success', 'انجام شد', 'با موفقیت اضافه شد');
    })
</script>
<div id="masthead">	
    <div class="container">	
        <div class="masthead-pad">	
            <div class="masthead-text">
                <h2>موزیک جدید</h2>
                <p>برای افزودن آهنگی به پلیر اقدامی بفرمائید!</p>
            </div>
        </div>
    </div>
</div>
<div id="content">
    <div class="container">		
        <div class="row">
            <form  method="POST" action="<?php echo(url('form/addmusic')); ?>" enctype="multipart/form-data">
                <p><label>عنوان موزیک:</label> <input type="text" id="title" name="title" value=""></p>
                <p><label>انتخاب فایل: </label><input id="fileToUpload" type="file" size="45" name="fileToUpload" class="input"></p>
                <p>پیشنهاد میکنیم از عنوانی <span  class="badge badge-secondary">با حروف انگلیسی</span> استفاده کنید</p>
                <p>پسوند های قابل استفاده: <span  class="badge badge-secondary">mp3</span> <span class="badge badge-secondary">wma</span> <span class="badge badge-secondary">ogg</span> <span class="badge badge-secondary">amr</span> <span class="badge badge-secondary">mp2</span></p>
                <p>حداکثر وزن آهنگ اضافه شده <span  class="badge badge-secondary">10 مگابایت</span> می باشد</p>
                <center><input type="submit" class="btn btn-primary" value="اضافه کردن"></center>
            </form>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>