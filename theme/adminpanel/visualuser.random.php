<?php
include('header.php');
include('sidebar.php');
?>
<script type="text/javascript">
function add(){
    
    var $form = $('#formaddvuser');
	
    var data = {
        'count':$('select[name=count]', $form).val(),
        'room':$('select[name=room]', $form).val(),
		'status':$('input[name=status]', $form).prop('checked'),
		'token':'<?php echo session('changepw');?>'
    };

    $.ajax({
        type: "POST",
        url: "<?php echo(url('ajax/admin.visualuser.random')); ?>",
        data: data,
        success: function(data){
            switch(data){
                case('ok'):$.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'ذخیره شد'});break;
				case('count'):$.msgGrowl({type: 'warning', title: 'خطا', text: 'لطفا یک عدد وارد کنید'});break;
				case('roomid'):$.msgGrowl({type: 'warning', title: 'خطا', text: 'این اتاق وجود ندارد'});break;
				case('max500'):$.msgGrowl({type: 'warning', title: 'خطا', text: 'از این سیستم نمیتوانید بیشتر از 500 کاربر مجازی اضافه کنید'});break;
                default:alert(data);break;
            }
        }
    });
    if($('select[name=count]', $form).val() >= 50)$.jGrowl('این امر ممکن کمی زمان بر باشد لطفا صبور باشید', { header: 'اندکی صبر کنید' });
}
</script>
<section id="content" class="container_12 clearfix" data-sort=true>
	<h1 class="grid_12 margin-top no-margin-top-phone">افزودن کاربر بصورت تصادفی</h1>
    <div class="grid_12">
        <div class="grid_12">
            <form class="box  validate" id="formaddvuser">
                <div class="header">
                    <h2>افزودن کاربر بصورت تصادفی</h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>تعداد:</strong></label>
                        <div> <select name="count" class="search"><option value="10">10</option><option value="20">20</option><option value="50">50</option><option value="70">70</option><option value="100">100</option><option value="130">130</option><option value="160">160</option><option value="180">180</option><option value="200">200</option><option value="220">220</option><option value="270">270</option><option value="320">320</option><option value="380">380</option><option value="400">400</option><option value="450">450</option><option value="500">500</option></select></div>
                     </div>
                    <div class="row">
                        <label><strong>اتاق:</strong></label>
                        <div>
                            <select name="room" class="search">
                            <?php
                            $query = db::query("SELECT `id`, `title` FROM {prefix}rooms");
                            while($room = mysqli_fetch_array($query)){
                                echo("<option value=\"$room[0]\">$room[1]</option>");
                            }
                            mysqli_free_result($query);
                            ?>
                            </select>
                        </div>
                    </div>
					<div class="row">
                        <label><strong>کاربر استاتوس داشته باشد؟ </strong></label>
                        <div>
                            <input type="checkbox" name="status" />
                        </div>
                    </div>
				</div>
            </form>
            <center> <button class="button blue flat block" onclick="add();">اضافه کردن</button></center>
	   </div>
    </div>
</section>
<?php include('footer.php'); ?>