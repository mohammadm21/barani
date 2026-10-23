<?php
include('header.php');
include('sidebar.php');
$bgform = json_decode($options['bgform'], true);
$bgform['position'] = explode(' ', $bgform['position']);
?>
<script type="text/javascript" src="<?php echo(theme::url('js/ajaxfileupload.js')); ?>"></script>
<style>
    .cpicker{
        display: inline-block;
    }
</style>
<script type="text/javascript">

    function edit($inputfile) {
        var datetosend = {
            'part': 'chat',
            'subset': 'admin-icons'
        };
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
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'نوع فایل توسط سرور تایید نشد'});
                        break;
                    case('security'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'سرور در فایل شما مورد امنیتی یافت گرد فایل شما حذف گردید'});
                        break;
                    case('repeatname'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'این نام ویژه قبلا ثبت شده است'});
                        break;
                    default:
                        alert(data);
                        break;
                }
            },
            error: function (data, status, e)
            {
                alert('error' + data);
            }
        });
        return false;
    }
</script>
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">ویرایش قالب</h1>
    <div class="grid_12">
        <div class="grid_12">
            <form class="box " id="form-1" method="post" onsubmit="return false;">
                <div class="header">
                    <h2>ویرایش آیکون های مدیریت</h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>آیکون مدیر:</strong></label>
                        <div>
                            <input type="file" name="1" id="adminicon-1" />
                        </div>

                    </div>
                    <div class="row"> 
                        <p style="text-align: center;"><button class="button blue flat block" onclick="edit('adminicon-1');">ویرایش</button></p>
                    </div>
                </div>
            </form>
        </div>
        <div class="grid_12">
            <form class="box" id="form-3" method="post" onsubmit="return false;">
                <div class="header">
                    <h2>ویرایش آیکون های مدیریت</h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>آیکون ناظر ارشد:</strong></label>
                        <div>
                            <input type="file" name="3" id="adminicon-3" />
                        </div>

                    </div>
                    <div class="row"> 
                        <p style="text-align: center;"><button class="button blue flat block" onclick="edit('adminicon-3');">ویرایش</button></p>
                    </div>
                </div>
            </form>
        </div>
        <div class="grid_12">
            <form class="box" id="form-2" method="post" onsubmit="return false;">
                <div class="header">
                    <h2>ویرایش آیکون های مدیریت</h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>آیکون ناظر:</strong></label>
                        <div>
                            <input type="file" name="2" id="adminicon-2" />
                        </div>

                    </div>
                    <div class="row"> 
                        <p style="text-align: center;"><button class="button blue flat block" onclick="edit('adminicon-2');">ویرایش</button></p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>