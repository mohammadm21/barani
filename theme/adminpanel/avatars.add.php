<?php
$el4 = giu('el4');
switch($el4){
    case('single'):include(theme::file('avatars.add.single.php'));break;
    case('tuple'):include(theme::file('avatars.add.tuple.php'));break;

    default:
include('header.php');
include('sidebar.php');
?>
<section id="content" class="container_12 clearfix" data-sort=true>
	<h1 class="grid_12 margin-top no-margin-top-phone">آواتار جدید</h1>
    <div class="grid_12">
        <div class="grid_12">
        <p style="text-align: center;">ما دو روش برای افزودن  آواتار برای شما محیا کرده ایم!:</p>
            <center>
                <a href="<?php echo(url('admin/avatars/add/single')); ?>"><button class="button blue flat block">افزودن تکی</button></a>
                <a href="<?php echo(url('admin/avatars/add/tuple')); ?>"><button class="button blue flat block">افزودن دسته جمعی</button></a>
            </center>
	   </div>
    </div>
</section>
<?php include('footer.php');
    break;
}

?>