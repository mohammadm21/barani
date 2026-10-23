<?php
//AutoUpdate ChatRoom Coded By MahdiLotfi 

$action = isset($_POST['action']) ? safe::string($_POST['action']) : exit("action");
$pass = isset($_POST['passupdate']) ? safe::string($_POST['passupdate']) : exit("pns");
$request = safe::string($_POST['request']);
if (md5($pass) != '5fff2dd2c55ba1f6e95fc732f3610240') {exit('عه عمویی الکی تست نکن');}

function mahdilotfi($url, $parametr) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $parametr);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
}
function GetRand($length = 25) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $len = strlen($chars);
    $pw = '';
    for ($i = 0; $i < $length; $i++) {
        $pw .= substr($chars, rand(0, $len - 1), 1);
    }
    $pw = str_shuffle($pw);
    return($pw);
}

if(md5($action) == '4387efc2604e0b7a8b6bde89c70c52e7' and md5($request) == 'a0b1b41691833dce0bf0c3cc7b1bc0d3'){
	     $ver = file_get_contents('inc/update/lastversion.txt');
	     $out = array('title'=>site_title,'version'=>$ver,'status'=>'ok');
             exit(json_encode($out));return;
}elseif(md5($action)== '3195b33a04b18642d3f841befd4663b0'){
	     $ver = file_get_contents('inc/update/lastversion.txt');
	     $data = array('action'=>'chack','url'=>url(),'token'=>'c475ac84e8897415f41f3b276e6946f6','version'=>$ver);
         $chack = mahdilotfi('http://shomaportal.ir',$data);
if($chack == "nouser"){
		 exit("noup");return;
}elseif($chack == "isupdate"){
		 exit("این چتروم قبلا بروزرسانی شده است");return;
}elseif($chack == "ok"){
		 $data = array('action'=>'sendfile','url'=>url(),'token'=>'c475ac84e8897415f41f3b276e6946f6');
		 $file = mahdilotfi('http://shomaportal.ir',$data);
		 session("b5b479a0d816b1d8994be1b56a622924","upfiles".GetRand().'.zip');
		 file_put_contents(session("b5b479a0d816b1d8994be1b56a622924"), file_get_contents($file));
		 $zip = new ZipArchive;
         $res = $zip->open(session("b5b479a0d816b1d8994be1b56a622924"));
         $zip->extractTo('../public_html');
         $zip->close();
if (file_exists("inc/update/install.php")) {
         include("inc/update/install.php");
         mahdilotfi::install();
		 unlink("inc/update/install.php");
       }
		 $data = array('action'=>'version','url'=>url(),'token'=>'c475ac84e8897415f41f3b276e6946f6');
         $ver = mahdilotfi('http://shomaportal.ir',$data);
		 $Fileversion= fopen('inc/update/lastversion.txt', 'w');
         fwrite($Fileversion, $ver);
         fclose($Fileversion);
		 unlink(session("b5b479a0d816b1d8994be1b56a622924"));
		 $data = array('action'=>'update','title'=>site_title,'url'=>url(),'time'=>time(),'ip'=>$_SERVER['REMOTE_ADDR'],'status'=>'ok','version'=>$ver,'token'=>'c475ac84e8897415f41f3b276e6946f6');
		 $status = mahdilotfi('http://shomaportal.ir',$data);
		 exit($status);
   }
}
?>