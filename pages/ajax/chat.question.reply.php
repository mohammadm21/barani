<?php
check_session();

$id 	= 	(int) safe::number($_POST['id']);
$answer = 	(int) safe::number($_POST['answer']);

if($question = db::get("question", "*", "`id` = '$id'")){
	$blocked = array();
	$blocked = explode(",", $question['blocked']);
	if(!in_array(session('type'), $blocked)){
		if(db::num("question_reply", "`user`= '".session('id')."' AND `qid` = '$id'") == 0){
			db::insert("question_reply", "`qid`,`user`,`input`,`time`", "'$id','".session('id')."','$answer','".time()."'");
			echo("ارسال شد");
		}else{
			echo("شما قبلا به این سئوال جواب داده اید");
		}
	}else{
		echo("شما اجازه جواب دادن به این سئوال را ندارید.");
	}
}else{
	echo("این سئوال وجود ندارد !");
}

?>