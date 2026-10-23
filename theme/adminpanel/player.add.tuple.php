<?php
include('header.php');
include('sidebar.php');
?>
<script type="text/javascript">
    $(document).ready(function () {
        $(".uploader").each(function () {
            var k = $(this);
            k.pluploadQueue($.extend({
                runtimes: "html5,flash,html4",
                url: "<?php echo(url('ajax/admin.player.add.tuple')); ?>",
                max_file_size: "10mb",
                chunk_size: "10mb",
                unique_names: true,
                filters: [
                    {title: "فایل های صوتی", extensions: "mp3,wma,ogg,amr,mp2"},
                    {title: "فایل های فشرده", extensions: "zip"}
                ], flash_swf_url: "js/mylibs/forms/uploader/plupload.flash.swf"
            },
                    k.data()
                    ));
            k.find(".plupload_button").addClass("button grey btn");
            k.find(".plupload_add").addClass("icon-plus");
            k.find(".plupload_start").addClass("icon-ok")
        });
    });
</script>
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">موزیک جدید</h1>
    <div class="grid_12">
        <div class="grid_12">
            <form class="box  validate" id="formaddsmily">
                <div class="header">
                    <h2>آپلود دست جمعی موزیک ها</h2>
                </div>
                <div class="content tabbed">
                    <p style="color: red;text-decoration: blink;text-align: center;">شما در قسمت هم میتوانید فایل های فشرده (.zip) ارسال کنید هم موزیک ها را گروهی آپلود کنید</p>


                    <div class="uploader"></div>
                </div>

            </form>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>