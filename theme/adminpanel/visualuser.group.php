<?php
include('header.php');
include('sidebar.php');
?>
<script type="text/javascript">
function add(){
    
    var $form = $('#formaddvuser');
	var firstTime = $('input[name=firstTime]', $form).val();
    var lastTime = $('input[name=lastTime]', $form).val();
	var ftmp=firstTime.split(':'),ltmp=lastTime.split(':');
    if(ftmp[0]>23 || ftmp[0]<0 || ltmp[0]>23 || ltmp[0]<0){
        alert("ساعت باید بین 0 تا 23 باشد");
        return;
    }
    if(ftmp[1]<0 || ftmp[1]>59 || ltmp[1]<0 || ltmp[1]>59){
        alert("دقیقه باید بین 0 تا 23 باشد");
        return;
    }
    if(ftmp[0]!=0 && ftmp[1]!=0 && ltmp[0]!=0 && ltmp[1]!=0 && ftmp[0]==ltmp[0] && ftmp[1]==ltmp[1]){
        alert("از ساعت و تا ساعت نباید برابر هم باشند");
        return;
    }
    if(ftmp[0]>ltmp[0]){
        alert("تا ساعت باید بزرگتر از , از ساعت باشد");
        return;
    }else if(ltmp[0]==ftmp[0] && ltmp[1]<ftmp[1]){
        alert("تا ساعت باید بزرگتر از , از ساعت باشد");
        return;
    }
    var data = {
        'count':$('select[name=count]', $form).val(),
		'firstTime':firstTime,
        'lastTime':lastTime,
        'gender':$('select[name=gender]', $form).val(),
        'type':$('select[name=type]', $form).val(),
        'room':$('select[name=room]', $form).val(),
        'status': $('input[name=status]', $form).val(),
		'autoanswer':$('input[name=autoanswer]', $form).prop('checked'),
        'status_private': $('select[name=status_private]', $form).val(),
		'myOS': $('select[name=myOS]', $form).val(),
		'ubrowser': $('select[name=ubrowser]', $form).val(),
        'token':'<?php echo session('changepw');?>'
    };
    $.ajax({
        type: "POST",
        url: "<?php echo(url('ajax/admin.visualuser.group')); ?>",
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
    if($('select[name=count]', $form).val() >= 50)$.msgGrowl({type: 'success', title: 'اندکی صبر کنید', text: 'این امر ممکن کمی زمان بر باشد لطفا صبور باشید'});
}
</script>
<section id="content" class="container_12 clearfix" data-sort=true>
	<h1 class="grid_12 margin-top no-margin-top-phone">افزودن کاربر مجازی دسته جمعی</h1>
    <div class="grid_12">
        <div class="grid_12">
            <form class="box  validate" id="formaddvuser">
                <div class="header">
                    <h2>افزودن کاربر مجازی دسته جمعی</h2>
                </div>
                <div class="content tabbed">
					<div class="row">
                        <label><strong>تعداد:</strong></label>
                        <div> <select name="count" class="search"><option value="10">10</option><option value="20">20</option><option value="50">50</option><option value="70">70</option><option value="100">100</option><option value="130">130</option><option value="160">160</option><option value="180">180</option><option value="200">200</option><option value="220">220</option><option value="270">270</option><option value="320">320</option><option value="380">380</option><option value="400">400</option><option value="450">450</option><option value="500">500</option></select></div>
                     </div>
					<div class="row">
                        <label><strong>از ساعت:</strong><small>24 ساعته آنلاین = 0</small></label>
                        <div> <input type="time" name="firstTime" class="required" value="<?php echo jdate::start('H:i', time());?>"/> </div>
                    </div>
					<div class="row">
                        <label><strong>تا ساعت:</strong><small>24 ساعته آنلاین = 0</small></label>
                        <div> <input type="time" name="lastTime" class="required" value="<?php echo jdate::start('H:i', time());?>"/> </div>
                    </div>
                    <div class="row">
                        <label><strong>جنسیت:</strong><small>نامشخص = زن و مرد</small></label>
                        <div><select name="gender" class="search"><option value="0">نامشخص</option><option value="1">مذکر</option><option value="2">مونث</option></select></div>
                    </div>
                    <div class="row">
                        <label><strong>سطح کاربری:</strong></label>
                        <div>
                            <select name="type" class="search">
                            <?php
                            foreach($usertype as $row){
                            ?>
                            <option value="<?php echo($row['id']); ?>"><?php echo($row['title']); ?></option>
                            <?php
                            }
                            ?>
                            </select>
                        </div>
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
                        <label><strong>سیستم عامل کاربر:</strong></label>
                        <div>
                            <select name="myOS" class="search">
                                <option value="pc">رایانه</option>
                                <option value="tablet">تبلت</option>
                                <option value="phone">موبایل</option>
                            </select>
                        </div>
                    </div>
					<div class="row">
                        <label><strong>وضعیت خصوصی کاربر:</strong></label>
                        <div>
                            <select name="status_private" class="search">
                                <option value="pmme">فعال</option>
                                <option value="nopm">غیر فعال</option>
                            </select>
                        </div>
                    </div>
					<div class="row">
                        <label><strong>مرورگر کاربر:</strong></label>
                        <div>
                            <select name="ubrowser" class="search">
                                <option value="Camino">کمینو (( Camino ))</option>
                                <option value="Netscape">نت اسکپ (( Netscape ))</option>
                                <option value="Opera">اوپرا (( Opera ))</option>
                                <option value="Safari">سافاری (( Safari ))</option>
                                <option value="Chrome">کروم (( Chrome ))</option>
                                <option value="Firefox">فایر فاکس (( Firefox ))</option>
                                <option value="IE">ای ای (( IE ))</option>
                            </select>
                        </div>
                    </div>
					<div class="row">
                        <label><strong>استاتوس کاربر:</strong></label>
                        <div> <input type="text" name="status" class="required" value=""/> </div>
                    </div>
                    <div class="row">
                        <label><strong>پاسخگویی خودکار:</strong></label>
                        <div>
                            <input type="checkbox" name="autoanswer" />
                        </div>
                    </div>
                </div>
               
            </form>
            <center> <button class="button blue flat block" onclick="add();">اضافه کردن</button></center>
	   </div>
    </div>
</section>
<?php include('footer.php'); ?>