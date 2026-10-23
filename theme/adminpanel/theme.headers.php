<?php
include('header.php');
include('sidebar.php');
$thememanager = json_decode($options['thememanager'], true);
?>
<style>
    .cpicker{
        display: inline-block;
    }
</style>
<script type="text/javascript">
    function progressHandlingFunction(e) {
        if (e.lengthComputable) {
            //$('progress').attr({value:e.loaded,max:e.total});
        }
    }
    function edit(form) {
        var formData = new FormData(form);
        $.ajax({
            url: '<?php echo(url('ajax/admin.theme')); ?>',
            type: 'POST',
            xhr: function () {
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) {
                    myXhr.upload.addEventListener('progress', progressHandlingFunction, false);
                }
                return myXhr;
            },
            success: function (data) {
                switch (data) {
                    case('ok'):
                        $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'ذخیره شد'});
                        break;
                    case('title'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'لطفا عنوان را بدرستی وارد کنید'});
                        break;
                    case('page'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'لطفا صفحه کد را مشخص کنید'});
                        break;
                    case('location'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'مکان مشخص نشده است'});
                        break;
                    default:
                        alert(data);
                        break;
                }
            },
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        });
        return false;
    }
</script>
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">ویرایش قالب</h1>
    <div class="grid_12">
        <div class="grid_12">
            <form class="box " id="form-1" method="post" onsubmit="return edit(this);">
                <input type="hidden" name="part" value="chat" />
                <input type="hidden" name="subset" value="header" />
                <div class="header">
                    <h2>ویرایش سربرگ داخل چتروم</h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>هدر:</strong></label>
                        <div>
                            <span>عکس فعلی: <?php if($thememanager['chat']['header']['src'] != ""){ ?>
                                  <img src="<?=url($thememanager['chat']['header']['src']);?>" width="100" height="100">
   
                                   <?php }else{?> 
                               <img src="/theme/adminpanel/img/noimg.png" width="100" height="100">
                                     <?php } ?></span>
                            <input type="file" name="src" />
                        </div>
                    </div>
                    <div class="row"> 
                        <p style="text-align: center;"><input type="submit" value="ویرایش" class="button blue flat block" /></p>
                    </div>
                </div>
            </form>
        </div>
        <div class="grid_12">
            <form class="box " id="form-2" method="post" onsubmit="return edit(this);">
                <input type="hidden" name="part" value="users" />
                <input type="hidden" name="subset" value="header" />
                <div class="header">
                    <h2>ویرایش سربرگ امتیازات</h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>هدر:</strong></label>
                        <div>
                            <span>عکس فعلی:<?php if($thememanager['users']['header']['src'] != ""){ ?>
                                  <img src="<?=url($thememanager['users']['header']['src']);?>" width="100" height="100">
   
                                   <?php }else{?> 
                               <img src="/theme/adminpanel/img/noimg.png" width="100" height="100">
                                     <?php } ?></span>
                            <input type="file" name="src" />
                        </div>
                    </div>
                    <div class="row"> 
                        <p style="text-align: center;"><input type="submit" value="ویرایش" class="button blue flat block" /></p>
                    </div>
                </div>
            </form>
        </div>
        <div class="grid_12">
            <form class="box " id="form-3" method="post" onsubmit="return edit(this);">
                <input type="hidden" name="part" value="history" />
                <input type="hidden" name="subset" value="header" />
                <div class="header">
                    <h2>ویرایش سربرگ تاریخچه</h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>هدر:</strong></label>
                        <div>
                            <span>عکس فعلی: <?php if($thememanager['history']['header']['src'] != ""){ ?>
                                  <img src="<?=url($thememanager['history']['header']['src']);?>" width="100" height="100">
   
                                   <?php }else{?> 
                               <img src="/theme/adminpanel/img/noimg.png" width="100" height="100">
                                     <?php } ?></span>
                            <input type="file" name="src" />
                        </div>
                    </div>
                    <div class="row"> 
                        <p style="text-align: center;"><input type="submit" value="ویرایش" class="button blue flat block" /></p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>