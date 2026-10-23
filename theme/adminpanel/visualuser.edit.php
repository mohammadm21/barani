<?php
include('header.php');
include('sidebar.php');
$id = safe::number(giu('el4'));
$visual = db::get("onlines","*", "`id` = '$id' AND `time`='0'");
$inputs = json_decode($visual['inputs'], true);
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
        'id':"<?php echo $id; ?>",
        'name':$('input[name=name]', $form).val(),
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
        url: "<?php echo(url('ajax/admin.visualuser.edit')); ?>",
        data: data,
        success: function(data){
            switch(data){
                case('ok'):$.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'ذخیره شد'});break;
                case('emptyname'):$.msgGrowl({type: 'warning', title: 'خطا', text: 'لطفا نامی وارد کنید'});break;
                case('roomid'):$.msgGrowl({type: 'warning', title: 'خطا', text: 'این اتاق وجود ندارد'});break;
                case('running'):$.msgGrowl({type: 'warning', title: 'خطا', text: 'این نام متعلق به کاربر دیگری است'});break;
                default:alert(data);break;
            }
        }
    });
}
</script>
<section id="content" class="container_12 clearfix" data-sort=true>
	<h1 class="grid_12 margin-top no-margin-top-phone">ویرایش کاربر مجازی</h1>
    <div class="grid_12">
        <div class="grid_12">
            <form class="box  validate" id="formaddvuser">
                <div class="header">
                    <h2>ویرایش کاربر مجازی</h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>نام:</strong></label>
                        <div> <input type="text" name="name" class="required" value="<?php echo($visual['name']); ?>"/> </div>
                    </div>
					<div class="row">
                        <label><strong>از ساعت:</strong><small>24 ساعته آنلاین = 00:00</small></label>
                        <div> <input type="time" name="firstTime" class="required" value="<?php echo $inputs['firstTime']; ?>"/> </div>
                    </div>
					<div class="row">
                        <label><strong>تا ساعت:</strong><small>24 ساعته آنلاین = 00:00</small></label>
                        <div> <input type="time" name="lastTime" class="required" value="<?php echo $inputs['lastTime']; ?>"/> </div>
                    </div>
                    <div class="row">
                        <label><strong>جنسیت:</strong></label>
                        <div><select name="gender" class="search">
						<?php foreach(array(0,1,2) as $gender){
							 switch($gender){
								case('0'):$gender_t  = 'نامشخص';break;
								case('1'):$gender_t  = 'مذکر';break;
								case('2'):$gender_t  = 'مونث';break;
							}
							
						?>
						<option value="<?php echo $gender;?>" <?php echo ($visual['gender']==$gender)?"selected=selected":"";?>><?php echo $gender_t;?></option>
						<?php }; ?>
						</select></div>
                    </div>
                    <div class="row">
                        <label><strong>سطح کاربری:</strong></label>
                        <div>
                            <select name="type" class="search">
                            <?php
                            foreach($usertype as $row){
                            ?>
                            <option value="<?php echo($row['id']); ?>" <?php echo ($visual['type']==$row['id'])?"selected=selected":"";?>><?php echo($row['title']); ?></option>
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
                                echo("<option value=\"$room[0]\" ".(($visual['roomid']==$room['id'])?"selected=selected":"").">$room[1]</option>");
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
							<?php foreach(array('pc','tablet','phone') as $myOS){
							 switch($myOS){
								case('pc'):$myOS_t  = 'رایانه';break;
								case('tablet'):$myOS_t  = 'تبلت';break;
								case('phone'):$myOS_t  = 'موبایل';break;
							}
							
						?>
                            <option value="<?php echo $myOS;?>" <?php echo ($inputs['myOS']==$myOS)?"selected=selected":"";?>><?php echo $myOS_t;?></option>
						<?php } ?>
						</select>
                        </div>
                    </div>
					<div class="row">
                        <label><strong>وضعیت خصوصی کاربر:</strong></label>
                        <div>
                            <select name="status_private" class="search">
							<?php foreach(array('pmme','nopm') as $status_private){
							 switch($status_private){
								case('pmme'):$status_private_t  = 'فعال';break;
								case('nopm'):$status_private_t  = 'غیر فعال';break;
							}
							
						?>
                            <option value="<?php echo $status_private;?>" <?php echo ($inputs['status_private']==$status_private)?"selected=selected":"";?>><?php echo $status_private_t;?></option>
						<?php } ?>
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
						<div> <input type="text" name="status" class="required" value="<?php echo $inputs['stext']; ?>"/> </div>
                    </div>
                    <div class="row">
                        <label><strong>پاسخگویی خودکار:</strong></label>
                        <div>
                            <input type="checkbox" name="autoanswer" <?php echo (isset($inputs['autoanswer']) and $inputs['autoanswer'] == true)?"checked=checked":""; ?>/>
                        </div>
                    </div>
                </div>
               
            </form>
            <center> <button class="button blue flat block" onclick="add();">ویرایش</button></center>
	   </div>
    </div>
</section>
<?php include('footer.php'); ?>