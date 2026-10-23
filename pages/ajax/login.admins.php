<?php
check_session();
if (session('admin') != '0') {
$keyss = false;
    $t = time()- $options['chat_historytime'];
	$sql->where('date', $t, '<')->delete('message');
	$sql->where('date', $t, '<')->delete('message_archive');
	$query = $sql->where('time', $t, '<')->get('sendedimages', null, array('filename'));
	foreach ($query as $link) {
        @unlink('theme/sendedimages/' . $link['filename']);
               }
	$sql->where('time', $t, '<')->delete('sendedimages');
	
 if((time() - $options['checkdp']) > 150000) {  
          $lee=$options['checkdp'];
		$sql->where('type', '1')->where('online', '0')->where('lastlogin', $lee, '<')->delete('users');
		$sql->where('date', $lee, '<')->delete('logs');
		$sql->where('time', $lee, '<')->delete('stats');
       $le=$options['checkdp'] = time();    
		$sql->where('name', 'checkdp')->update('options', array(
			'value' => $le
		));
       chatroom::reloadconfig();   
     }


if($options['pw2_adminpanel'] == '1'){
	$keyss = isset($_REQUEST['key']) ? safe::string($_REQUEST['key']) : false;	
}
$block = isset($_POST['unblock']) && session('islogged') == 'true' ? false : true; 

$username = safe::string($_REQUEST['username']);
$md5 = md5($_REQUEST['password']);
$md55 = md5(base64_encode(md5(base64_encode($_POST['password']))));
$sender_type = isset($_POST['sender_type']) ? safe::string($_POST['sender_type']) : '';
$base64_encode = base64_encode($_REQUEST['password']);

if ($user = $sql->where('username', $username)->where('type', Users::AdminsType(), 'in')->getOne('users', array('id', 'type', 'name', 'gender', 'password'))) {
    if($user['id'] != session('id')){exit('user');}
		if ($admin = $sql->where('user', $user['id'])->getOne('adminpanel')) {
			if($admin['password'] == ''){
				$admin['password'] = $user['password'];
			}
		
        if(($admin['password'] == $base64_encode or $admin['password'] == $md5 or $admin['password'] == $md55)){
			if($options['pw2_adminpanel'] == '1' && $block){
				$keys = rand(9999,999999);
				$key = json_encode(array('key' => $keys, 'access' => '1'));
				$j = json_decode($admin['access_key'], true);
				if($j['key'] == $keyss){
                                        $sql->where('user',session('id'))->update('adminpanel',array(
                                        'access_key' => $key
                                        ));
				}else{
					if($sender_type == 'sms'){
						$tel = $sql->where('id', session('id'))->getValue('users', 'mobile');
						if(empty($tel['mobile']))exit('شماره موبایل شما ثبت نشده است');
                                        $sql->where('user',session('id'))->update('adminpanel',array(
                                        'access_key' => $key
                                        ));
						$data = array('key' => $keys, 'dbname' => db_name, 'cellphone' => $tel['mobile'], 'ip' => ip());
						$res = post('http://panel.TarhPardaz.Org', $data);
						$json = json_decode(remove_utf8_bom($res), true);
						if($json['status'] == true){
							$resualt = 'رمز دوم برای شما اس ام اس شد';
						}elseif($json['status'] == false && isset($json['error'])){
							$resualt = $json['error'];
						}else{
							$resualt = 'خطایی نا معلوم رخ داده است ! رمز دوم ارسال نشد';
						}
 					}else{
							$email = $sql->where('id', session('id'))->getOne('users', array('username', 'email'));
						if(empty($email['email']))exit('ایمیل شما ثبت نشده است');
                                        $sql->where('user',session('id'))->update('adminpanel',array(
                                        'access_key' => $key
                                        ));
						$data = array('key' => $keys, 'dbname' => db_name, 'email' => $email['email'], 'ip' => ip(), 'username' => $email['username'], 'url' => $_SERVER['SERVER_NAME']);
							$resualt = post('http://panel.TarhPardaz.Org', $data);
							$json = json_decode(remove_utf8_bom($resualt), true);
							if($json['status'] == true){
								$resualt = 'رمز دوم به ایمیل شما ارسال شد';
							}else{
								$resualt = 'خطایی نا معلوم رخ داده است ! رمز دوم ارسال نشد';
							}
					}
					exit($resualt);
				}
			}
            $roomdefault = chatroom::defaultRoom();
            session('id', $user['id']);
            session('room', $roomdefault);
            session('color', chatroom::defaultcolor());
            session('name', $user['name']);
            session('ip', ip());
            session('hide', '0');
            session('admin',$usertype[$user['type']]['admin']);
            session('user', '1');
	    chatroom::addlog('1', "کاربری با نام کاربری {$username} و کد کاربری {$user['id']} همراه با آی پی " . $_SERVER["REMOTE_ADDR"] . " وارد شد");
            session('passwordadmin', 'true');
            session('adminpanelogin', 'true');
            session('islogged', 'true');
           $tel = json_decode($options['teloptions'], true);
            $text = "ورود به پنل مدیریت\n";
            $text .='ای پی : '.ip()."\n";
            $text .= "آدرس : ".url()."\n";
            $text .= "نام نمایشی :".session('name')."\t";
           
           if($tel['sendloginlog'] == 'Sure'){
             tlg($text,'chat');
                 }
            if($usertype[$user['type']]['admin'] == '1'){
                if(!is_file('inc/config.tmp'))file_put_contents('inc/config.tmp', '');
                $config = file_get_contents('inc/config.tmp');
                $config = json_decode($config, true);
                
                //if(isset($config['validtion']) and $config['validtion'] == true)session('validtion', 'true');else session('validtion', 'false');
				session('validtion', 'true');
            }else{
                session('validtion', 'true');
            }
            //include('inc/classes/host.php');
            host::refresh();
            host::expiration();
            echo('ok');
			if($options['report_login'] == 1){
				$data = array('name' => session('name'), 'dbname' => db_name, 'cellphone' => $options['admin_mobile'], 'ip' => ip());
				post('http://panel.TarhPardaz.org', $data);
			}
		}else{
            echo('pass');
        }
    }else{
        echo('user');
    }
}else{
    echo('user');
}
} else {
    include("pages/noaccess.php");
}
function post($url,$data){
	$vals = http_build_query($data);
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_POSTFIELDS,$vals);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	$res = curl_exec($ch);
	curl_close($ch);
	return $res;
}
?>