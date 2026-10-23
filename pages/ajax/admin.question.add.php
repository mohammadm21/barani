<?php
if(!session('id') or session('admin') == '0'){exit();}
adminpanel::check_Permission('exit', 'question');

$ask    = 	   safe::string($_POST['ask']);
$answer = 	   safe::arrayString($_POST['answer']);
$radioQ =  	  (int)safe::number($_POST['radioQ']);
$types  =      safe::string($_POST['types']);
$gift   =	   safe::string($_POST['gift']);
$min    =     (int)safe::number($_POST['min']);
$sec    =     (int)safe::number($_POST['sec']);

if(empty($ask)){
	echo("هیچ سئوالی طرح نشده است !");exit;
}
foreach($answer as $key => $val){
	if(empty($val)){
		echo("لطفا جواب ها را به طور کامل پر کنید !");
		exit;
	}
}
if(!isset($_POST['radioQ'])){
	echo("لطفا گزینه صحیح را مشخص کنید !");exit;
}
if(empty($gift)){
	echo("قسمت جایزه خالی میباشد !");exit;
}

$minutes = $min < 10 ? "0" . $min : $min;
$seconds = $sec < 10 ? "0" . $sec : $sec;

$realTimer = "$minutes:$seconds";
$timer = ($min * 60) + $sec;

$answer_j = json_encode($answer, JSON_UNESCAPED_UNICODE);

db::insert("question", "`timer`, `user`, `ask`, `answer`, `gift`, `blocked`, `true`, `time`, `status`", "'$realTimer', '".session('id')."', '$ask', '$answer_j', '$gift', '$types', '$radioQ', '".time()."', '0'");

$remove = array("\n", "\r\n", "\r");
$ask = str_replace($remove, "\\n", trim($ask));

foreach($answer as $key => $val){
	$answer[$key] = str_replace($remove, "\\n", trim($val));
}

$query = db::query("SELECT * FROM {prefix}onlines WHERE `type` NOT IN($types) AND `time` != '0'");
while($row = mysqli_fetch_array($query)){
	$inputs = json_decode($row['inputs'], true);
	$inputs['marshal_question'] = array("id" => db::$id, "ask" => $ask, "answer" => $answer, "gift" => $gift, "realTimer" => $realTimer, "timer" => $timer);
	Users::Inputs($row['id'], $inputs);
}
mysqli_free_result($query);
chatroom::addlog('9', "سئوال زیر را طرح کرد<hr><br>" . safe::string($_POST['ask']));
echo("ok");

?>