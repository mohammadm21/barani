<?php
$el3 = giu('el3');
include(theme::file('analyzer.php'));
exit();
$title = "آنالیزگر";
$tabactive = 'addon';
include("header.php");
?>
<div id="masthead">	
    <div class="container">	
        <div class="masthead-pad">	
            <div class="masthead-text">
                <h2>آنالیزگر</h2>
                <p>آمار و ارقام ها را محسوس ببینید!</p>
            </div>
        </div>
    </div>
</div>
<div id="content">
    <div class="container">
        <div class="row">
            <iframe src="<?php echo(url("admin/chart/workin7days")); ?>" frameborder="0" width="620" height="350" scrolling="no" ></iframe>



            <iframe src="<?php echo(url("admin/chart/typesonlines")); ?>" frameborder="0" width="620" height="350" scrolling="no" ></iframe>
            <iframe src="<?php echo(url("admin/chart/genderonlines")); ?>" frameborder="0" width="620" height="350" scrolling="no" ></iframe>
            <iframe src="<?php echo(url("admin/chart/genderusers")); ?>" frameborder="0" width="620" height="350" scrolling="no" ></iframe>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>