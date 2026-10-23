<?php
if(isset($_POST['del'])){
	$d_id = safe::string($_POST['id']);
	$question = db::get("question", "ask", "`id` = '$d_id'");
	if($d_id == 'all'){
		chatroom::addlog('9', "تمامی سئوالات طرح شده را حذف کرد");
		db::delete("question");
		db::delete("question_reply");
	}else{
		chatroom::addlog('9', "سئوال زیر را حذف کرد<hr><br>$question");
		db::delete("question", "`id` = '$d_id'");
		db::delete("question_reply", "`qid` = '$d_id'");
	}
	echo("ok");
	exit;
}
if(isset($_POST['show_wins'])){
	$q_id = (int)safe::number($_POST['id']);
	$true_id = safe::string($_POST['truee']);
	$query = db::query("SELECT * FROM {prefix}question_reply WHERE `qid` = '$q_id' AND `input` = '$true_id' ORDER BY `time` ASC LIMIT 3");
	$x = 1;
	$count_wins = mysqli_num_rows($query);
	while($row = mysqli_fetch_array($query)){
		$user_wins = db::get("users", "*", "`id` = '{$row['user']}'");
		$name = $user_wins['name'];
		$type = '<img src="'.theme::url($usertype[$user_wins['type']]['icon']).'">';
		$text .= "<b><font color=red>نفر $x :</font> <font color=green>$name</font></b> $type<hr><br>";
		$x++;
	}
	mysqli_free_result($query);
	if($count_wins == 0){
		$text = "<b><font color=red>برنده ایی وجود ندارد !</font></b>";
	}
	$question = db::get("question", "*", "`id` = '$q_id'");
	$json_d = json_decode($question['answer'], true);
	$true_answer = $json_d[$question['true']];
	$note = '<table cellpadding="3" border="1" align="center" dir="rtl">
	<tr><td style="padding:10px;"><b>سئوال:</b></td><td> <font color="red"><b>'.$question['ask'].'</b></font></td></tr>
	<tr><td style="padding:10px;"><b>جواب:</b></td><td><font color="red"><b>'.$true_answer.'</b></font></td></tr>
	<tr><td style="padding:10px;"><b>برندگان:</b></td><td>'.$text.'</td></tr>
	<tr><td style="padding:10px;"><b>جایزه:</b></td><td> <font color="red"><b>'.$question['gift'].'</b></font></td></tr>
	</table><hr>';
	$query = db::query('select `id`,`type`,`roomid` from {prefix}onlines WHERE `type` NOT IN('.$question['blocked'].')');
	while($row = mysqli_fetch_array($query))
	{
		db::insert('message', '`user`, `username`, `color`, `room`, `text`, `date`, `to`, `into`, `type`',
		"'-1', '<center>SYSTEM [ <b><font color=red>نتیجه مسابقه</font></b> ]</center>',
		'000000', '".$row['roomid']."', '".$note."', '".time()."', '".$row['id']."', 'pm', '0'");
	}
	mysqli_free_result($query);
	echo("ok");
	exit;
}
include('header.php');
include('sidebar.php');
?>
<script type="text/javascript">
function delete_question(id){
    $.ajax({
        type:"POST",
        url:'<?php echo(url('admin/question')); ?>',
        data:{id:id,del:true},
        success: function(data){
            switch(data){
                case('ok'):
					$.jGrowl('حذف شد', { header: 'موفقیت آمیز' });
					$("#question-" + id).remove();
					if(id == "all"){
						location.reload();
					}
				break;
				default:alert(data);break;
            }
        }
    });
}
function show_wins(id,truee){
	if(truee==0){
		alert("برنده ایی وجود ندارد !");
		return false;
	}
    $.ajax({
        type:"POST",
        url:'<?php echo(url('admin/question')); ?>',
        data:{id:id, truee:truee,show_wins:true},
        success: function(data){
            switch(data){
                case('ok'):
					$.jGrowl('ارسال شد', { header: 'موفقیت آمیز' });
				break;
				default:alert(data);break;
            }
        }
    });
}
function open_question(id){
	$("#info_question_" + id).dialog('open');
}
function open_answer(id){
	$("#info_answer_" + id).dialog('open');
}
function open_slovers(id){
	$("#info_slovers_" + id).dialog('open');
}
function open_wins(id){
	$("#info_wins_" + id).dialog('open');
}
function open_more(id){
	$("#info_more_" + id).dialog('open');
}
$(document).ready(function(){
<?php 
	$query = db::query("SELECT * FROM {prefix}question");
	while($row = mysqli_fetch_array($query)){
?>
	$("#info_question_<?php echo($row['id']);?>").dialog({
        autoOpen:false,
        modal:true,
        width:600,
        buttons:[{text:"متوجه شدم",click:function(){$(this).dialog("close");}}]
    });
	$("#info_answer_<?php echo($row['id']);?>").dialog({
        autoOpen:false,
        modal:true,
        width:600,
        buttons:[{text:"متوجه شدم",click:function(){$(this).dialog("close");}}]
    });
	$("#info_slovers_<?php echo($row['id']);?>").dialog({
        autoOpen:false,
        modal:true,
        width:600,
        buttons:[{text:"متوجه شدم",click:function(){$(this).dialog("close");}}]
    });	
	$("#info_wins_<?php echo($row['id']);?>").dialog({
        autoOpen:false,
        modal:true,
        width:600,
        buttons:[{text:"متوجه شدم",click:function(){$(this).dialog("close");}}]
    });	
	$("#info_more_<?php echo($row['id']);?>").dialog({
        autoOpen:false,
        modal:true,
        width:600,
        buttons:[{text:"متوجه شدم",click:function(){$(this).dialog("close");}}]
    });
<?php } ?>
})
</script>
<section id="content" class="container_12 clearfix" data-sort=true>
	<h1 class="grid_12 margin-top no-margin-top-phone">طرح سئوال</h1>
    
    <div class="grid_12">
		<div class="box">
			<div class="header">
				<h2>
					<img class="icon" src="<?php echo(theme::url('img/icons/packs/fugue/16x16/question-shield.png')); ?>">
					طرح سئوال
				</h2>
                
			</div>
			<div class="content">
                <p style="text-align: center;">
                    <a href="../../../admin/question/add"><button class="button grey block">طرح سئوال جدید</button></a>
                    <button class="button red block" onclick="delete_question('all');return false;">حذف کل سئوالات</button>
                </p>
				<div class="tabletools">
				</div>
				<table class="styled" id="daynmic_table">
					<thead>
						<tr>
							<th>#</th>
                            <th>طراح</th>
                            <th>سئوال</th>
                            <th>جواب ها</th>
                            <th>شرکت کنندگان</th>
                            <th>برندگان</th>
                            <th>اطلاعات بیشتر</th>
						</tr>
					</thead>
					<tbody>
                    <?php
                    $query = db::query("SELECT * FROM {prefix}question");
                    while($row = mysqli_fetch_array($query)){
						$user = db::get("users", "*", "`id` = '{$row['user']}'");
						$answers = json_decode($row['answer'], true);
						
					?>
						<div style="display: none;" id="info_question_<?php echo($row['id']); ?>" title="سئوال">
							<p style="overflow: auto;"><?php echo($row['ask']);?></p>
						</div>
						<div style="display: none;" id="info_wins_<?php echo($row['id']); ?>" title="برندگان">
							<?php
								$x = 1;
								$query3 = db::query("SELECT * FROM {prefix}question_reply WHERE `qid` = '{$row['id']}' AND `input` = '{$row['true']}' ORDER BY `time` ASC LIMIT 3"); 
								while($row3 = mysqli_fetch_array($query3)){
								$user_wins = db::get("users", "*", "`id` = '{$row3['user']}'");
							?>
							<p>
								<b>نفر <?php echo $x++;?> : </b><span class="badge block green"><a target="_blank" href="<?php echo(url('admin/users/edit/'.$row3['user'])); ?>"><?php echo($user_wins['name']); ?><img src="<?php echo(theme::url($usertype[$user_wins['type']]['icon'])); ?>"></a></span>
							</p>
							<?php 
								}
								$nums = mysqli_num_rows($query3);
								
								if($nums == 0){
									echo("<b>برنده ایی وجود ندارد !</b>");
								}
								mysqli_free_result($query3);
							?>
							
						</div>
						<div style="display: none;" id="info_more_<?php echo($row['id']); ?>" title="اطلاعات بیشتر">
							<p><b>جایزه : </b><?php echo($row['gift']);?></p>
							<p><b>مدت زمان پاسخگویی : </b><?php echo($row['timer']);?> دقیقه</p>
							<p><b>تاریخ طرح سئوال : </b><?php echo(jdate::start('Y/m/d H:i:s', $row['time']));?></p>
						</div>
   						<div style="display: none;" id="info_answer_<?php echo($row['id']); ?>" title="جواب ها">
							<?php $y=1; foreach($answers as $key => $val){?>
								<p><b>گزینه <?php echo $y++; ?> : </b><span class="badge block <?php if($key == $row['true'])echo "green"; else echo "red"; ?>"><?php echo $val; ?></span></p>
							<?php } ?>
						</div> 
  						<div style="display: none;" id="info_slovers_<?php echo($row['id']); ?>" title="شرکت کنندگان">
							<p>
							<?php
								$query2 = db::query("SELECT * FROM {prefix}question_reply WHERE `qid` = '{$row['id']}'"); 
								while($row2 = mysqli_fetch_array($query2)){
									$user_reply = db::get("users", "*", "`id` = '{$row2['user']}'");
									//for($i=0;$i<10;$i++){
							?>
									<span class="badge block <?php if($row2['input'] == $row['true'])echo "green"; else echo "red"; ?>"><a target="_blank" href="<?php echo(url('admin/users/edit/'.$row2['user'])); ?>"><?php echo($user_reply['name']); ?></a></span>
							<?php 
								//}
								}
								$nums = mysqli_num_rows($query2);
								if($nums == 0){
									echo("<b>شرکت کننده ایی وجود ندارد !</b>");
								}
								mysqli_free_result($query2);
							?>
							</p>
						</div>                      
					    <tr id="question-<?php echo($row['id']); ?>">
                            <td>
								<a href="javascript:void(0);" onclick="delete_question('<?php echo($row['id']); ?>');">[حذف]</a><br>
								<a href="javascript:void(0);" onclick="show_wins(<?php echo($row['id']);?>,'<?php echo($row['true']);?>');">[اعلام برندگان]</a>
							</td>
                            <td><a target="_blank" href="<?php echo(url('admin/users/edit/'.$row['user'])); ?>"><?php echo($user['name']); ?><img src="<?php echo(theme::url($usertype[$user['type']]['icon'])); ?>"></a></td>
                            <td><button class="button blue block" onclick="open_question(<?php echo($row['id']); ?>);return false;">کلیک کنید</button></td>
                            <td><button class="button blue block" onclick="open_answer(<?php echo($row['id']); ?>);return false;">کلیک کنید</button></td>
                            <td><button class="button blue block" onclick="open_slovers(<?php echo($row['id']); ?>);return false;">کلیک کنید</button></td>
                            <td><button class="button blue block" onclick="open_wins(<?php echo($row['id']); ?>);return false;">کلیک کنید</button></td>
                            <td><button class="button blue block" onclick="open_more(<?php echo($row['id']); ?>);return false;">کلیک کنید</button></td>
                        </tr>
                    <?php
                    }
                    mysqli_free_result($query);
                    ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>
<?php include('footer.php'); ?>