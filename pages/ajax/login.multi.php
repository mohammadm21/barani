<?php
header('Content-Type: text/html; charset=utf-8');
if(session('id')){
    echo('ok'); 
    return; 
}
$token=safe::string($_POST["token"]);
$token1=safe::string($_POST[session('mositoken')]);
$token2=safe::string($_POST[session('mosirobot')]);
$token3=safe::string($_POST[$token]);
if(empty($token) || empty($token1) || empty($token2)){ exit("صفحه خود را رفرش کنید");}
if($token != token2() or $token1 != mrobot() or $token2 != rtoken() or $token3 != gtoken()){ exit("صفحه خود را رفرش کنید");}

         //---------------------------bad words manual-------------------------
                 
                $str=$_POST['username'];
                $hex=bin2hex($str);

                  if ( strpos($hex,"e2808c") == true) {
                      echo('از عبارات غیر مجاز استفاده نکیند');
                   
                        exit();
                          }


                $words = array("ک‌ص","سر‌هنگ","قر‌مصا","مد‌یر","میگا‌م","نتو","جند‌ه","رایگان","ما‌مان","کو‌3","سر‌د‌ار","پاره","کو‌‌ص","هوَا","بر‌فی","کص", "کوص", "ننه", "کون", "ابجی", "مامان","کیر","سردار","سرهنگ","مدیر","آ‌بجیت","کو‌ص","سر‌د‌ا‌ر"," ҉","کو‌ن","خو‌اهر");
                  $co= count($words);
				  $esm= " ".$_POST['username']." ";
				
                  for ($i = 0; $i < $co ; $i++) {
                       $mim= $words[$i];
                      if ( strpos($esm,$words[$i]) == true) {
                      echo('کص ننت');
                   
                        exit();
                          }

                   }
           //----------------------------------------------------------------------


$username_check = isset($_POST['username']) ? safe::string($_POST['username']) : die('لطفا نام خود را وارد کنید');
if($options['captcha_login'] == 'true'){
    $captchaPost = isset($_POST['captcha']) ? safe::string($_POST['captcha']) : '';
    if($captchaPost != $_SESSION['captcha-txt']){
        echo('captcha');
        return;
    }
}
    $lastlogin = false;
    if (session('id')) {
        Users::Offline(session('id'));
session_unset();
    $lastlogin = true;
}
elseif($options['1ip2user'] == 'false'){
	if ($sql->where('ip', ip())->getValue('onlines', 'count(*)') > 0) {
		$lastlogin = true;
		$query = $sql->where('ip', ip())->get('onlines', null, array('id'));
		foreach ($query as $row) {
			$inputs = Users::Inputs($row['id']);
			$inputs['exit'] = 'true';
			Users::Inputs($row['id'], $inputs);
		}
	}
}
// -------- Robots Ban -----------


	if ($sql->where('ip', ip())->getValue('onlines', 'count(*)') > 1) {
        echo "شما مجوز ورود به چت روم را به دلیل ورود بیش از حد ندارید";
        return;
        }

//-------------ip fake ban---------
       /* if(ip().length < 5){
         echo "You'r a fake user and we know it !";
           return;
         }*/
//---------------------------------


    $query = $sql->where('to', time(), '<')->where('to', '0', '!=')->get('banns', null, array('id', 'user'));
    foreach ($query as $row) {
	$sql->where('id', $row['user'])->update('users', array(
		'active' => '1'
	));
	$sql->where('id', $row['user'])->update('profiles', array(
		'active' => '1'
	));
	$sql->where('id', $row['id'])->delete('banns');
    }
    if (isset($_COOKIE['ChatBennid']) or isset($_COOKIE['ChatBennip'])) {
        $bann = safe::number($_COOKIE['ChatBennid']);
		$ip = safe::number($_COOKIE['ChatBennip']);
	if ($bann = $sql->where('user', $bann)->orWhere('ip',$ip)->getOne('banns')) {
            if ($bann['to'] > time()) {
                echo('benn');
                return;
            }
        }
    }
    if (Users::CheckBann()) {
        echo('benn');
        return;
    }
    $query = $sql->where('to', time(), '>')->orWhere('to', '0')->get('banns');
    foreach ($query as $row) {
        if (ip() == $row['ip']) {
            echo('benn');
            exit();
        }
    }
    if (ipbann::is_enable()) {
        if (ipbann::check_bann()) {
            ipbann::addworng();
            echo(ipbann::ErrorText());
            return;
        }
    }
    $username = isset($_POST['username']) ? safe::string($_POST['username']) : '';
   if($options['loginwithsheklak']=='true'){
        $username_guest=preg_replace('/[\x00-\x1F\x80-\xFF]/','',$username);
        $username=preg_replace('/[^\w\d\p{L} ]/u','',$username);
     }
    if (function_exists('mb_substr')) {
        $username = mb_substr($username, 0, (int) $options['chat_maxlengthname'], "utf-8");
    }
    if (function_exists('mb_substr')) {
        $username = mb_substr($username, 0, 50, "utf-8");
    }
    if ($username == '') {
        echo('name');
        return;
    }
	if(function_exists('mb_strlen')){
    if(mb_strlen($username)<3){
        echo('حداقل طول نام کاربری 3 حرف می باشد');
        exit();
    }
}else{
    if(strlen($username)<3){
        echo('حداقل طول نام کاربری 3 حرف می باشد');
        exit();
    }

}
if(strpos($username,'{db_name}')!==false || strpos($username,'{db_user}')!==false || strpos($username,'{db_pass}')!==false || strpos($username,'{prefix}')!==false){
    echo('badname');	
    return;
}
$badname = checkbadname($username, 'login');
    if ($badname) {
        echo('badname');
        return;
    }
    Users::autoOffline();
    if (chatroom::checkCapacity()) {
        echo('capacity');
        return;
    }
    $Detect_MyOS = new Mobile_Detect;
    $MyOS = ($Detect_MyOS->isMobile() ? ($Detect_MyOS->isTablet() ? 'tablet' : 'phone') : 'pc');
    $ua = getBrowser();
    $yourbrowser = $ua['bname'];
    $countries = iptocountry(ip());
    if (Users::Checkrunning($username)) {
        if (isset($_POST['password']) and ! empty($_POST['password'])) {
			$md55 = md5($_POST['password']);
            $md5 = md5(base64_encode(md5(base64_encode($_POST['password']))));
            $base64_encode = base64_encode($_POST['password']);
	    $user = $sql->where('u.`username`', $username)->where('u.`type`', $options['defaultusertype'], '!=')->join('profiles as p', 'p.`id` = u.`id`', 'LEFT')->getOne('users as u', array('u.`id`', 'u.`password`', 'u.`name`', 'u.`type`', 'u.`gender`', 'u.`active`', 'u.`question`','u.`setting`', 'p.`avatar`'));
		if ($user) {
                if ($user['active'] == '0') {
                    echo('این نام کاربری توسط مدیر غیر فعال شده');
                    return;
                }
		if ($sql->where('user', $user['id'])->where('(`to` > '.time().' OR `to` = 0)')->getValue('banns', 'count(*)') > 0) {
                    echo('benn');
                    return;
                }

           
                if ($usertype[$user['type']]['canlogin'] == 0) {
                    echo('برای گروه کاربری شما محدودیت ورود قرار داده شده');
                    return;

                }
                if (Users::is_online(Users::getByName('id', $username_check)) && session('id') != Users::getByName('id', $username_check)) {
                    die('کاربر آنلاین میباشد');
                }
				if ($md5 == $user['password'] or $md55 == $user['password'] or $base64_encode == $user['password'] ) {
                             
                   $hide = isset($_POST['hide']) and safe::number($_POST['hide'])== '1' ? '1' : '0';
                      if($usertype[$user['type']]['loginhidden'] != 1 )$hide = safe::number('0');
                      $hide = safe::number($hide);
                    if (isset($_COOKIE['silent'])) {
			if ($sql->where('id', safe::number($_COOKIE['silent']))->getValue('silents', 'count(*)') > 0) {
                            $GLOBALS['silent'] = 1;
                        }
                    } elseif ($silent = $sql->where('ip', ip())->getOne('silents', array('id', 'ip'))) {
                        setcookie('silent', $silent['id'], time() + 86400);
                        $GLOBALS['silent'] = 1;
                    }
                    if ($options['flashchat'] == 'true') {
                        $roomdefault = '0';
                    }
                    $isDefaultRoom = chatroom::getNameRoom($roomdefault);
                    Users::addOnline($user['id'], $username, $user['name'], $user['gender'], $user['type'], $hide, $roomdefault, $isDefaultRoom);
					if(safe::is_md5($user['password']) or $options['passcoding'] == 'base64'){
			$sql->where('id', $user['id'])->update('users', array(
				'password' => md5(base64_encode(md5(base64_encode($_POST['password']))))
			));
					}
					$usersetting = json_decode($user['setting'],true);
                    session('id', $user['id']);
                    session('roomname', $isDefaultRoom);
                    session('room', $roomdefault);
                    session('color',$usersetting['color']=="" ? chatroom::defaultcolor() : $usersetting['color']);
                    session('fontname',$usersetting['fontname']==""?chatroom::defaultfontname():$usersetting['fontname']);
                    session('fontsize',$usersetting['fontsize']==""?chatroom::defaultfontsize():$usersetting['fontsize']);
                    session('bold',$usersetting['bold']==""?'':$usersetting['bold']);
                    session('name', $user['name']);
                    session('ip', ip());
                    session('type', $user['type']);
                    session('admin', $usertype[$user['type']]['admin']);
                    session('hide', $hide);
                    session('ubrowser', $yourbrowser);
                    session('user', '1');
                    session('myOS', $MyOS);
                    session('countries', $countries);
                    session('token', token());
                    session('robot', robot());
                    session('gender', $user['gender']);
                    session('myavatar', $user['avatar']);
                    session('changepw', md5('shoma@Ta!_#$@#_Ta@rh' . time() . pwcreator::start()));
                    Users::CheckProfile($user['id']);;
		    $last = $sql->orderBy("date", "DESC")->getValue('message', 'id');
			$sql->where('id', $user['id'])->update('onlines', array(
				'lastcheck' => $last
			));
                    if (!$lastlogin) {
                        if ($hide != '1') {
                            $textwellcome = "کاربر عزیز {$user['name']} وارد شد.";
                            if ($showiplogin == '1') {
                                $textwellcome.= " (ip: {" . ip() . "})";
                            }
                        }
                        $texttouserpv = $options['texttouserpv'];
                        if ($texttouserpv != '') {
						$sql->insert('message', array(
							'user' => '-1',
							'username' => $systemname,
							'color' => $systemcolor,
							'fontname' => $defualtfontname,
							'fontsize' => $defualtfontsize,
							'room' => '-1',
							'text' => $texttouserpv,
							'date' => $_SERVER['REQUEST_TIME'],
							'to' => session('id'),
							'into' => 'pm',
							'type' => '0'
						));
                        }
			Message::AddMasageSYS($sql->where('id', $roomdefault)->getValue('rooms', 'wellcomemsg'), '-1', session('id'));
			chatroom::addlog('1', "کاربری با نام کاربری {$username} و کد کاربری {$user['id']} همراه با آی پی " . ip() . " وارد شد");
                        Message::badname_Checker($user['name'], $user['id'], $user['gender'], $user['type']);
                    }
                  //-----------Add Username To COOKIE
		if(isset($_COOKIE["radar"])) {
   		 $old_value = $_COOKIE["radar"];
   		 // بررسی وجود $user['name'] در $_COOKIE["radar"]
    	if(strpos($old_value, $user['name']) === false) {
        $new_value = $old_value . "_" . $user['name'];
        setcookie("radar", $new_value, time() + (10 * 365 * 24 * 60 * 60), "/"); // 10 سال
 	  	 }
		} 
		else {
 		   setcookie("radar", $user['name'], time() + (10 * 365 * 24 * 60 * 60), "/"); // 10 سال 
		}

          //---------------------------------
                    Users::setIP($user['id']);
                    Message::Archive();
       if (Users::is_admin($user['type'])){
              if(!modpassword($_POST['password']) || strlen($_POST['password']) < 7 ) {
		        echo("admins");return;
		         }
                    } 
       if (Users::is_admin($user['type']) && ($MyOS == 'phone' || $MyOS == 'tablet') && $options['mobiledetect'] == 'true' ){
              if(!modpassword($_POST['password']) || strlen($_POST['password']) < 7 ) {
		        echo("adminsmob");return;
		         }
                    } 
                    if (($MyOS == 'phone' || $MyOS == 'tablet') && $MyOS != 'pc' && $options['mobiledetect'] == 'true') {
                        echo('mobile');  
                    }else {
                    echo('ok');  }
                } else {
                    ipbann::addworng();
                    echo('pass');
                }
            }
        } else {
		$type = $sql->where('username', $username)->where('type', $options['defaultusertype'], '!=')->getValue('users', 'type');
             if($options['lwm'] == 'true'){
		if ($sql->where('admin', '0', '!=')->where('time', '0', '!=')->getValue('onlines', 'count(*)') == 0 and $usertype[$type]['admin'] == '0') {
					echo("به دلیل حضور نداشتن ناظرین در چت روم شما اجازه ورود به چت روم را ندارید");
					exit;
				}
			}
	  if ($usertype[$type]['loginhidden'] == 1) {
                echo('passwh');
            } else {
                echo('running');
            }

            return;
        }
    } else {
                 if($options['lwm'] == 'true'){
		if ($sql->where('admin', '0', '!=')->where('time', '0', '!=')->getValue('onlines', 'count(*)') == 0) {
			echo("به دلیل حضور نداشتن ناظرین در چت روم شما اجازه ورود به چت روم را ندارید");
			exit;
		}
	}

           $r_guest = json_decode($options['rename_guest'], true);
	if($r_guest['active'] == 'yes' AND $r_guest['prefix'] != ''){
		$username = $r_guest['prefix'].rand();
	}

        if ($usertype[$options['defaultusertype']]['canlogin'] == 0) {
            echo('برای گروه کاربری شما محدودیت ورود قرار داده شده');
            return;
        }
        $gender = isset($_POST['gender']) ? safe::number($_POST['gender']) : 1;
        if ($gender == '0') {
            $gender = '1';
        }
	// بی‌حساسیت به بزرگی/کوچکی اسم (collation utf8mb4_unicode_ci): اگر
        // ردیفی با همان نام وجود دارد همان را با نوعِ واقعی‌اش برگردان،
        // وگرنه بساز. از ایجاد ردیف شبحِ هم‌نام (مهمان) که اکانتِ عضو را
        // قفل می‌کند جلوگیری می‌شود.
        $found = $sql->where('username', $username)->getOne('users', array('id', 'type'));
        if ($found) {
            $id = (int)$found['id'];
            $utype = (int)$found['type'];
            if ($utype <= 0) { $utype = (int)$options['defaultusertype']; }
        } else {
            $id = Users::addUser($username, $username, $options['defaultusertype'], $gender);
            $utype = (int)$options['defaultusertype'];
        }
        if (isset($_COOKIE['silent'])) {
		if ($sql->where('id', safe::number($_COOKIE['silent']))->getValue('silents', 'count(*)') > 0) {
                $GLOBALS['silent'] = 1;
            }
	} elseif ($silent = $sql->where('ip', ip())->getOne('silents', array('id', 'ip'))) {
            setcookie('silent', $silent['id'], time() + 86400);
            $GLOBALS['silent'] = 1;
        }
        $isDefaultRoom = chatroom::getNameRoom($roomdefault);
        Users::addOnline($id, $username, $username, $gender, $utype, '0', $roomdefault, $isDefaultRoom);
        session('id', $id);
        session('roomname', $isDefaultRoom);
        session('room', $roomdefault);
        session('color',chatroom::defaultcolor());
        session('fontname', chatroom::defaultfontname());
        session('fontsize', chatroom::defaultfontsize());
        session('name', $username);
        session('ip', ip());
        session('token', token());
        session('robot', robot());
        session('hide', '0');
        session('admin', '0');
        session('ubrowser', $yourbrowser);
        session('user', '0');
        session('myOS', $MyOS);
        session('countries', $countries);
        session('gender', $gender);
        session('type', $utype);
        session('changepw', md5('Tarh@!_###$$@@##_P@rdaz' . time() . pwcreator::start()));
        if (!$lastlogin) {
            $textwellcome = "کاربر عزیز $username وارد شد.";
            Message::AddMasageSYS($textwellcome, '-1');
            chatroom::addlog('1', "مهمانی با نام $username و با آی پی " . ip() . " وارد شد");
            Message::badname_Checker($username, $id, $gender,$type);
            $texttouserpv = $options['texttouserpv'];
            if ($texttouserpv != '') {
			$sql->insert('message', array(
				'user' => '-1',
				'username' => $systemname,
				'color' => $systemcolor,
				'fontname' => $defualtfontname,
				'fontsize' => $defualtfontsize,
				'room' => '-1',
				'text' => $texttouserpv,
				'date' => $_SERVER['REQUEST_TIME'],
				'to' => session('id'),
				'into' => 'pm',
				'type' => '0'
			));
            }
        }
        Message::Archive();
        if (($MyOS == 'phone' || $MyOS == 'tablet') && $options['mobiledetect'] == 'true') {
              echo('mobile');  
        }else {
        echo('ok');  }
    }

?>