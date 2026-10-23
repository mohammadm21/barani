<meta charset="utf-8"/>
<?php
if(isset($_POST['submit']))
{

$name=$_POST['name'];
$subject=$_POST['subject'];
$pm=$_POST['pm'];
$text="پیام ارسال شده از :".$name." | موضوع : ".$subject." | متن : ".$pm;
//echo $text;
//-----------------------filter text -----------------


if (strpos($text, 'کیر') or strpos($text, 'کص') or strpos($text, 'ننت') or strpos($text, 'خواهر')) {
    header('Location: http://www.barani0.ir/chat?bad');
    exit;
    die("");
}
//----------------------------------------------------

   $tglmsg= "https://api.telegram.org/bot543661813:AAGJxfrTHAWsJ7DDny73POUsOAS5OQINIvg/sendMessage?chat_id=289332956&text=$text";
file_get_contents($tglmsg);
//----------------------------------

$myfile = fopen("pm.txt", "a");
$txt = "Name: ".$name."\n"."Subject: ".$subject."\n"."Text: ".$pm."\n"."============== \n";
fwrite($myfile, $txt);
fclose($myfile);

//----------------------------------
header('Location: http://www.barani0.ir/chat?ok');
exit;
//echo "<script> alert('پیام شما با موفقیت ارسال شد'); </script>";

}
ELSE {
	ECHO "خطا در ارسال. دوباره تلاش کنید ...";
}




?>