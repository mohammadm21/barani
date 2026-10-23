<?php if ($_REQUEST['targetid'] == '-1') exit('<center><b><font color=red>غلام شما سیستم هستم !</font></b></center>'); ?>
<!doctype html>
<html manifest="">
    <head>
        <meta charset="utf-8">
        <link href="<?php echo(theme::url('theme/sendfile/css/up_form_styles.css')); ?>" rel="stylesheet" type="text/css">
        <script src="<?php echo(theme::url('theme/js/jquery.js')); ?>"></script>
        <script src="<?php echo(theme::url('theme/js/jquery.form.min.js')); ?>"></script>
        <link href="<?php echo(theme::url('theme/fonts/fonts.css')); ?>" rel="stylesheet" type="text/css">
        <style>
            form { display: block; margin: 20px auto; background: #eee; border-radius: 10px; padding: 15px }
            #progress { position:relative; width:400px; border: 1px solid #ddd; padding: 1px; border-radius: 3px; }
            #bar { background-color: #B4F5B4; width:0%; height:20px; border-radius: 3px; }
            #percent { position:absolute; display:inline-block; top:3px; left:48%; }
        </style>
        <script type="text/javascript">
            $(document).ready(function(){function t(t, o, r, i){e.width(i + "%"), n.html(i + "%"), i > 50 && n.css("color", "#fff")}function o(){$("#submit-btn").show(), $("#loading-img").hide()}function r(){if (!(window.File && window.FileReader && window.FileList && window.Blob))return $("#output").html("<font color='red'>مروگر شما قدیمی است واز این سیستم پشتیبانی نمیکند ! لطفا مرورگر خود را آپدیت کنید ویا عوض کنید !"), !1; if (!$("#imageInput").val())return $("#output").html("لطفا یک فایل انتخاب کنید."), !1; var t = $("#imageInput")[0].files[0].size; return t > 2921746?($("#output").html("<b>" + i(t) + " </b><font color='red'>حجم فایل شما خیلی زیاد است شما اجازه آپلود این فایل را ندارید"), !1):(u.show(), e.width(s), n.html(s), n.css("color", "#000"), $("#submit-btn").hide(), $("#loading-img").show(), void $("#output").html(""))}function i(t){var o = ["Bytes", "KB", "MB", "GB", "TB"]; if (0 == t)return"0 Bytes"; var r = parseInt(Math.floor(Math.log(t) / Math.log(1024))); return Math.round(t / Math.pow(1024, r), 2) + " " + o[r]}var u = $("#progressbox"), e = $("#progressbar"), n = $("#statustxt"), s = "0%", a = {target:"#output", beforeSubmit:r, uploadProgress:t, success:o, resetForm:!0}; $("#MyUploadForm").submit(function(){return $(this).ajaxSubmit(a), !1})});
        </script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-116692701-5"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-116692701-5');
</script>



    </head>
    <?php
    if ($options['allow_sendfile_in_private'] == 'false') {
        exit('<br /><br /><center><b><font color=red>این سیستم توسط مدیر چت روم غیرفعال شده است</font></b></center>');
    }
    $useroptions = json_decode($options['useroptions'], true);
    $check_perm_user = Users::Inputs();
    $check_perm_user = in_array('uploadfile', $check_perm_user['permission']) ? true : false;
    if (!in_array(session('type'), $useroptions['sendedimages']) && !$check_perm_user) {
        exit('<br /><br /><center><b><font color=red>متاسفانه شما اجازه ارسال فایل ندارید این قابلیت برای گروه کاربری شما غیرفعال شده است</font></b></center>');
    }
    ?>
    <body>
        <div id="upload-wrapper">
            <div align="center">
                <h3>ارسال فایل به دوستان</h3>

                <span class="">فرمت های مجاز  <?php
                    $allow_formats = json_decode($options['allow_formats'], true);
                    foreach ($allow_formats as $key => $val) {
                        if ($val == 1) {
                            echo $key . ',';
                        }
                    }
                    ?></span>
                <form action="<?php echo(url('ajax/upsendfile?targetid=') . $_REQUEST['targetid'] . '&into=' . $_REQUEST['into']); ?>" onSubmit="return false" method="post" enctype="multipart/form-data" id="MyUploadForm">
                    <input name="myfile" id="imageInput" type="file" />
                    <input type="submit"  id="submit-btn" value="آپلود" />
                    <img src="<?php echo(theme::url('theme/images/chat/ajax-loader.gif')); ?>" id="loading-img" style="display:none;" alt="لطفا صبر کنید"/>
                </form>
                <div id="progressbox" style="display:none;"><div id="progressbar"></div ><div id="statustxt">0%</div></div>
                <div id="output"></div>
            </div>
        </div>
    </body>
</html>
<?php
//}
?>