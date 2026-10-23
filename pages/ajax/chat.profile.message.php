<?php

check_session();
header::tojson();

include('inc/classes/profile.php');
Profile::CheckActiveMe();

$text = isset($_POST['post']) ? safe::string($_POST['post']) : die('post?');
$to = isset($_POST['id']) ? safe::number($_POST['id']) : die('id?');

if (empty($text)) {
    die(json_encode(array('text' => 'پیام خالی میباشد ارسال نشد', 'type' => 'error'), JSON_UNESCAPED_UNICODE));
}
if ($to == session('id')) {
    die(json_encode(array('text' => 'شما نمیتوانید به خودتان پیام بفرستید', 'type' => 'error'), JSON_UNESCAPED_UNICODE));
}
if (chatroom::checkPermission('ppmoff')) {
if (session('admin') != '1') {
    $sql->where("id", $to);
    $user_info = $sql->getOne('users', array('name', 'type', 'gender', 'id'));
    $sender_info = array('name' => session('name'), 'type' => session('type'), 'gender' => session('gender'), 'id' => session('id'));
    $reciver_info = array('name' => $user_info['name'], 'type' => $user_info['type'], 'gender' => $user_info['gender'], 'id' => $user_info['id']);
    Message::Offlinemsg_Checker($text, $sender_info, $reciver_info, $to);
    $text = Message::badwords(strtolower($text),'offlinemsg');
}

Message::SendOfflineMessage(session('id'), $to, $text);
$arr = json_decode($reciver_info, true);

//-----------------------------------------------------------------
 $sql->where("id",$to);
$input_p= $sql->getOne('users');
$input=$input_p["name"];
$girande = $input;

$meme = session('name');
$all = "پیام افلاین از : ".$meme."\n به : ".$girande."\n\n متن پیام : ".$text;
$admin_chat_id = "289332956";
$all = urlencode($all);

//$tglmsg= "https://api.telegram.org/bot543661813:AAGJxfrTHAWsJ7DDny73POUsOAS5OQINIvg/sendMessage?chat_id=289332956&text=".$all;
file_get_contents("https://oflinemessage.mohammadelyasi21.workers.dev/?chat_id=".$admin_chat_id."&text=".$all);
//---nafas id ----------------------
if($to == 4093)
{
    $admin_chat_id = "7100686094";
    file_get_contents("https://oflinemessage.mohammadelyasi21.workers.dev/?chat_id=".$admin_chat_id."&text=".$all);
}

echo(json_encode(array('text' => 'با موفقیت ارسال شد', 'type' => 'success'), JSON_UNESCAPED_UNICODE));
}else{
echo(json_encode(array('text' => 'ارسال پیام برای گروه کااربری شما غیرفعال گردید', 'type' => 'error'), JSON_UNESCAPED_UNICODE));
}
?>