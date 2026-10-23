<?php
include('header.php');
include('sidebar.php');
?>
<script type="text/javascript">
var count = 4;
function send(){
	var serialize = $('#sendQuestion').serialize();
	var options  = $('.dualselect .right select option');
    var types ='';
	for(i = 0;i!=options.length;i++){
		if(types != '')types +=',';
		types += $(options[i]).attr('name');
    }
	serialize += '&types=' + types;
    $.ajax({
        type:"POST",
        url:'<?php echo(url('ajax/admin.question.add')); ?>',
        data: serialize,
        success: function(data){
            switch(data){
                case('ok'):$.jGrowl('ارسال شد', { header: 'موفقیت آمیز' });break;
                default:alert(data);break;
            }
        }
    });
}
function delAnswer(id){
	$("#answer_" + id).remove();
}
function marshalAdd(){
	var input = '<div id="answer_{count}"><input type="text" name="answer[{count}]" class="required" size="50" value=""/>  <input type="radio" name="radioQ" value="{count}"/> گزینه صحیح <button onclick="delAnswer({count});return false;" class="button red">حذف</button></div>';
	$("#marshalArea").append(input.replace(/{count}/g, ++count));
}

</script>

<section id="content" class="container_12 clearfix" data-sort=true>
	<h1 class="grid_12 margin-top no-margin-top-phone">طرح سئوال جدید</h1>
    
    <div class="grid_12">
		<div class="box">
			<div class="header">
				<h2>
					<img class="icon" src="<?php echo(theme::url('img/icons/packs/fugue/16x16/users.png')); ?>">
					طرح سئوال جدید
				</h2>
                
			</div>
			<form class="box  validate" id="sendQuestion">
				<div class="content tabbed">
					<div class="row">
						<label><strong>سئوال:</strong></label>
						<div><textarea class="required" name="ask" rows="5"></textarea></div>
					</div>
					<div class="row">
						<label><strong>جواب ها:</strong></label>
						<div>
							<div><div><input type="text" name="answer[1]" class="required" size="50" value=""/><input type="radio" name="radioQ" value="1"/> گزینه صحیح</div></div>
							<div><div><input type="text" name="answer[2]" class="required" size="50" value=""/><input type="radio" name="radioQ" value="2"/> گزینه صحیح</div></div>
							<div><div><input type="text" name="answer[3]" class="required" size="50" value=""/><input type="radio" name="radioQ" value="3"/> گزینه صحیح</div></div>
							<div><div><input type="text" name="answer[4]" class="required" size="50" value=""/><input type="radio" name="radioQ" value="4"/> گزینه صحیح</div></div>
						</div>
						<div id="marshalArea"></div>
						<div><hr><center> <button class="button blue flat block" onclick="marshalAdd();return false;">اضافه کردت جواب جدید</button></center></div>
					</div>
					<div class="row">
					<label><strong>جایزه:</strong></label>
						<div><input type="text" name="gift" class="required" size="50" value=""/></div>
					</div>
					<div class="row">
					<label><strong>مدت زمان پاسخگویی:</strong><small>0 = مدت زمان نامحدود</small></label>
						<div><div><input name="min" type="number" min="0" class="required" max="60" value="0" style="text-align:center;" /> دقیقه <input name="sec" type="number" min="0" class="required" max="60" value="0" style="text-align:center;" /> ثانیه</div></div>
					</div>
					<div class="row">
						<label><strong>گروه های کاربری مجاز:</strong><small>سمت راست مجاز هستند</small><small>سمت چپ غیرمجاز هستند</small></label>
						<div id="customList">
							<select multiple class="dualselects" data-size="small" id="list_ips">
								<?php
								foreach($usertype as $type){
								?>
									<option name="<?php echo($type['id']); ?>" <?php if($type['id']==1)echo('selected');?>><?php echo($type['title']); ?></option>
								<?php
								}
								?>
							</select>
						</div>
					</div>
				</div>
			</form>
		</div>
		<center> <button class="button blue flat block" onclick="send();">ارسال</button></center>
	</div>
</section>
<?php include('footer.php'); ?>