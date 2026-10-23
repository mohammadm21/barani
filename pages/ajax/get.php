<?php
$id = session('id');
if (!$id) {
    exit('login'); 
}

header('Content-Type: application/json; charset=utf-8');
				
$time = time();
$check_user_admin = session('admin');
$check_user_type = session('type');
$myOptions = json_decode($options['useroptions'], true);
$runAutoanswer = 0;
if ($options['scoretosendmsg'] == '0') {
    $options['scoretosendmsg'] = '1';
}
$actions = $sql->where("id", $id)->getOne('onlines', array("id", "username", "name", "gender", "type", "time", "hide", "roomid", "roomname", "lastcheck", "ip", "inputs", "score"));
if (!$actions) {
    if ($user = $sql->where("id", $id)->getOne('users', array('username', 'name', 'gender', 'type'))) {
        $room = session('room');
        Users::addOnline($id, $user['username'], $user['name'], $user['gender'], $user['type'], session('hide'), $room, chatroom::getNameRoom($room));
	$actions = $sql->where("id", $id)->getOne('onlines', array("id", "username", "name", "gender", "type", "time", "hide", "roomid", "roomname", "lastcheck", "ip", "inputs", "score"));
    } else {
        exit();
    }
}
if (!$actions) {
    exit();
}
$scorecol = $actions['score'];
$temp = json_decode($actions['inputs'], true);
if (!is_array($temp)) {
    exit('exit');
}
$actions += $temp;
if ($options['advcreg'] == 1 and $actions['type'] == $options['defaultusertype']) {
    unset($_REQUEST['text'], $_REQUEST['to']);
}
if (isset($_REQUEST['text']) and isset($_REQUEST['to']) and $actions['exit'] != 'true' and $actions['benned'] != 1) {
    $_REQUEST['to'] = safe::string($_REQUEST['to']);
    $_REQUEST['into'] = safe::string($_REQUEST['into']);
    $_REQUEST['text'] = safe::string($_REQUEST['text']);
    $_REQUEST['text'] = str_replace('\d', 'd', $_REQUEST['text']);
	$sql->where("u.id", $_REQUEST['to']);
	$sql->join("onlines o", "o.id=u.id", "LEFT");
	$user_info = $sql->getOne('users u', array('u.type', 'u.friends', 'o.name', 'o.gender', 'o.inputs', 'o.id as online'));
	if ($user_info) {
		$check_user_inputs = json_decode($user_info['inputs'], true);
		$my_blacklist = $sql->where("user", safe::string($_REQUEST['to']))->getOne('my_blacklist', array('blacklist'));
                $my_blacklist = json_decode($my_blacklist['blacklist'],true);
		$text2 = $_REQUEST['text'];
		$sender_info = array(
			'name' => $actions['name'],
			'type' => $actions['type'],
			'gender' => $actions['gender'],
			'id' => $actions['id']
		);
		$reciver_info = array(
			'name' => $user_info['name'],
			'type' => $user_info['type'],
			'gender' => $user_info['gender'],
			'id' => $user_info['online']
		);
    }
    $sendpm = true;
    $fp_restrict = isset($actions['fp_restrict']) ? $actions['fp_restrict'] : '';
    if ($fp_restrict == 'blocked') {
        $sendpm = false;
        $actions['alert'] = 'حساب شما به دلیل اثربانگشت مشکوک مسدود شده است';
    } elseif ($fp_restrict == 'private' && $_REQUEST['into'] != 'pm') {
        $sendpm = false;
        $actions['alert'] = 'به دلیل مشکوک بودن، فقط امکان ارسال پیام خصوصی دارید';
    } elseif ($fp_restrict == 'admin_only') {
        if ($_REQUEST['into'] != 'pm') {
            $sendpm = false;
            $actions['alert'] = 'به دلیل مشکوک بودن، فقط امکان ارسال پیام به مدیر را دارید';
        } else {
            // only allow PM to an admin-type recipient
            $isRecvAdmin = (isset($user_info) && isset($user_info['type'])
                && isset($usertype[$user_info['type']]['admin'])
                && $usertype[$user_info['type']]['admin'] != 0);
            if (!$isRecvAdmin) {
                $sendpm = false;
                $actions['alert'] = 'شما فقط مجاز به ارسال پیام به مدیر هستید';
            }
        }
    }

    if (empty($actions['ip'])) {
        $actions['exit'] = 'true';
        $sendpm = false;
    }

    $userallowchat = isset($check_user_inputs['allowchat']) ? $check_user_inputs['allowchat'] : array();
    $group_allowchat = $myOptions['group_allowchat'] ? $myOptions['group_allowchat'] : array();
    if ($_REQUEST['into'] == 'allowchat') {
        $_REQUEST['into'] = 'pm';
    }
    if ($_REQUEST['into'] != 'main' AND $_REQUEST['into'] != 'pm' AND $_REQUEST['into'] != $id) {
        if (!allow_cf($_REQUEST['into'])) {
            $actions['alert'] = 'شما از اتاق کنفرانس حذف شده اید پیام شما ارسال نشد';
            $sendpm = false;
        }
    }
    if ($_REQUEST['into'] != 'main' AND $_REQUEST['into'] != 'pm' AND $_REQUEST['into'] != $id) {
        if (allow_cf($_REQUEST['into'])) {
	    $cf_check_silent = $sql->where("creater", $_REQUEST['into'])->where("joiner", $id)->getOne('cf_users', array('silent'));
            if ($cf_check_silent['silent'] == '1') {
                $actions['alert'] = 'شما در اتاق کنفرانس خفه شده اید پیام شما ارسال نشد';
                $sendpm = false;
            }
        }
    }
	if($check_user_admin != '1'){
		if($_REQUEST['into'] == 'pm' OR ($_REQUEST['into'] == 'main' AND $_REQUEST['to'] != '-1')){
			if(in_array((isset($id) ? $id : ''),(isset($my_blacklist) ? $my_blacklist : array()))){
				$actions['alert'] = 'کاربر مورد نظر شما را مسدود کرده است پیام شما ارسال نشد';
				$sendpm = false;
			}
		}
	}
  
  
  //----check request for antirobots #by mamali------  
  	if($options['attackmod'] =='true'){
      
       if($check_user_admin != '1'){
            if (!session('chat_speedmsg')) {
                session('chat_speedmsg', 1);
               }
              $_check_speedtext = $time - session('chat_speedmsg');
                if ($_check_speedtext <= '1') {
                $actions['alert'] = 'سرعت ارسال پیام شما زیاد است لطفااا آرامتر تایپ کنید. پیام شما ارسـال نشد';
                session('chat_speedmsg', $time);
                $sendpm = false;
            } else {
                session('chat_speedmsg', $time);
            }
        }
  
        	if($check_user_admin != '1'){
				if ($sql->where('id', $id)->getValue('onlines', 'count(*)') != 1 || $sql->where('ip', ip())->getValue('banns','count(*)') == 1 ) {
           			//session_destroy();
					$actions['alert'] = 'بس کن مسخره. زور بیخود نزن';
					    $sendpm = false;

                }
            }
    }
            
  //----------------------------------------
          
  
  
  
	if ($options['reportBlock_user'] == 'true' && $sql->where("user", $id)->orWhere("ip", ip())->has('report_users')) {
		 if ($_REQUEST['into'] == 'pm') {
                $myFriends = $user_info['friends'];
                $jsonFriends = isset($myFriends) ? json_decode($myFriends, true) : '';
                if (!in_array((isset($id) ? $id : ''), (isset($jsonFriends) ? $jsonFriends : array()))) {
                    $actions['alert'] = 'شما ریپورت شده اید و فقط به دوستان خود می توانید پیام دهید';
                    $sendpm = false;
               
            }
        }
	}
    if ($check_user_admin != '1' and $check_user_admin != '2' and $check_user_admin != '3') {
        if ($_REQUEST['into'] == 'pm' OR ( $_REQUEST['into'] == 'main' AND $_REQUEST['to'] != '-1')) {
            if ((isset($check_user_inputs['status_private']) ? $check_user_inputs['status_private'] : '') == 'nopm') {
                $myFriends = $user_info['friends'];
                $jsonFriends = isset($myFriends) ? json_decode($myFriends, true) : '';
                if (!in_array((isset($id) ? $id : ''), (isset($jsonFriends) ? $jsonFriends : array()))) {
                    $actions['alert'] = 'کاربر مورد نظر خصوصی خود را بسته است پیام شما ارسال نشد';
                    $sendpm = false;
                }
            }
        }
    }
    if ($_REQUEST['to'] > 0 and $check_user_admin != '1' and $check_user_admin != '2' and $check_user_admin != '3' and (isset($check_user_inputs['status_private']) ? $check_user_inputs['status_private'] : '') != 'nopm') {
        if ($_REQUEST['into'] == 'pm' OR $_REQUEST['into'] == 'main') {
            if (!in_array(session('id'), $userallowchat)) {
                if (isset($jsonFriends)) {
                    $myFriends = $user_info['friends'];
                    $jsonFriends = isset($myFriends) ? json_decode($myFriends, true) : '';
                }
                if (!in_array((isset($id) ? $id : ''), (isset($jsonFriends) ? $jsonFriends : array()))) {
                    if (in_array(session('type'), $group_allowchat) || $check_user_inputs['closepv'] == "close") {
                        $actions['alert'] = 'شما اجازه دادن پیام به کاربر مورد نظر را ندارید ،اجازه چت خصوصی شما ارسال شد .';
                        $sendpm = false;
                        $sql->insert('message', array(
                            'user' => session('id'),
                            'username' => session('name'),
                            'color' => session('color'),
                            'room' => session('room'),
                            'text' => 'request',
                            'date' => $time,
                            'to' => (int) $_REQUEST['to'],
                            'into' => 'allowchat',
                            'type' => session('admin')
                        ));
                    }
                }
            }
        }
    }
    if ((!in_array($check_user_type, $myOptions['allowtypessender']) AND $_REQUEST['into'] == 'pm') || (!in_array($check_user_type, $myOptions['allowtypessender_main']) AND $_REQUEST['into'] == 'main')) {
        $actions['alert'] = 'برای گروه کاربری شما محدودیت قرار داده شده است پیام شما ارسال نشد';
        $sendpm = false;
    }

    if ($options['chat_speedmsg'] != '0') {
        if (in_array($check_user_type, $myOptions['chat_speedmsg'])) {
            if (!session('chat_speedmsg')) {
                session('chat_speedmsg', 1);
            }
            $_check_speedtext = $time - session('chat_speedmsg');
           if ($_check_speedtext <= $options['chat_speedmsg']) {
                $actions['alert'] = 'سرعت ارسال پیام شما زیاد است لطفا آرامتر تایپ کنید. پیام شما ارسال نشد';
                session('chat_speedmsg', $time);
                $sendpm = false;
            } else {
                session('chat_speedmsg', $time);
            }
        }
    }
  
    if ($_REQUEST['to'] == '-1') {
        if ($options['scoretosendmsg'] != '0') {
            if ($scorecol < ((int) $options['scoretosendmsg'])) {
                exit();
            }
        }
        if ($options['chat_sendmsguest'] == 'false' and $actions['type'] == $options['defaultusertype']) {
            exit();
        }
        if ($actions['lastsendtxt'] == $_REQUEST['text']) {
            if ($actions['lastsendnum'] >= $options['repeatmsg']) {
                $actions['alert'] = 'از تکرار متون بپرهیزید';
                $sendpm = false;
            } else {
                $actions['lastsendnum'] = $actions['lastsendnum'] + 1;
            }
        } elseif ($options['resemblance'] > 0) {
            similar_text($_REQUEST['text'], $actions['lastsendtxt'], $resemblance);
            if ($resemblance >= $options['resemblance']) {
                if ($actions['lastsendnum'] >= $options['repeatmsg']) {
                    $actions['alert'] = 'از تکرار متون بپرهیزید';
                    $sendpm = false;
                } else {
                    $actions['lastsendnum'] = $actions['lastsendnum'] + 1;
                }
            } else {
                $actions['lastsendnum'] = 1;
            }
        } else {
            $actions['lastsendnum'] = 1;
        }
    }
    if ($sendpm) {
        if (!$user_info['online'] && $_REQUEST['into'] == 'pm') {
            if ($user_info['type'] != '1') {
                Message::SendOfflineMessage($id, $_REQUEST['to'], $_REQUEST['text']);
                $actions['alert'] = 'کاربر از چت روم خارج شد پیام شما به صورت آفلاین برای وی ارسال شد';
            } else {
                $actions['alert'] = 'کاربر از چت روم خارج شده است پیام شما ثبت نشد';
            }
        } else {
            if ($options['tabligh_reporter'] == '1' && in_array(session('type'),$myOptions['chat_report_msg'])) {
                if ($_REQUEST['into'] == 'pm' OR ( $_REQUEST['into'] == 'main' AND $_REQUEST['to'] != '-1') OR is_numeric($_REQUEST['into'])) {
                    Message::Tabligh_Checker($_REQUEST['text'], $sender_info, $reciver_info, $_REQUEST['into']);
                 
				}
            }
            if ($options['robo_bann'] == '1') {
                $actions['alert'] = Message::autosilent($_REQUEST['text'],$_REQUEST['into']);
            }
            if ($check_user_admin == '0') {
                $_REQUEST['text'] = Message::badwords(strtolower($_REQUEST['text']),$_REQUEST['into']);
            }
             
          if(session("msgactions") && session("msgactions") == "close" and $_REQUEST['into'] == 'main')exit();
			//score msg
			if($_REQUEST['into'] == 'main' and $_REQUEST['to'] == '-1'){
				$options['score_msg'] = json_decode($options['score_msg'],true);
				if($options['score_msg']['status'] == "true" and in_array(session('type'),$myOptions['score_msg'])){
				if(!session('numsend_msg')){
					session('numsend_msg','0');
				}
				$nummsg = session('numsend_msg');
				$nummsg++;
				if(session('numsend_msg') >= $options['score_msg']['num']){
					  $scorecol = $scorecol + $options['score_msg']['score'];
                      session('score', $scorecol);
					  session('numsend_msg','0');
				 }else{
					session('numsend_msg',$nummsg);
				 }
				}
			}
        
			//score msg
            $client_msg_id = isset($_REQUEST['client_msg_id']) ? safe::string($_REQUEST['client_msg_id']) : null;
            $mymsgid = Message::add($_REQUEST['to'], $_REQUEST['text'], session('room'), $_REQUEST['into'], $client_msg_id);
            if ($check_user_admin == '0') { 
	        Message::robo_cleaner($text2, $sql->getInsertId());
           }

        }
        $actions['lastsendtxt'] = $_REQUEST['text'];
        $runAutoanswer = 1;
    }
} else {
    if ($actions['lastcheckajax'] == 0) {
        $actions['lastcheckajax'] = $time;
    } else {
        if (($time - $actions['lastcheckajax']) < 2) {
            header('Content-Type: application/json; charset=utf-8');
            echo '{"data":[],"mid":0}';
            exit();
        } else {
            $actions['lastcheckajax'] = $time;
        }
    }
    if (isset($_REQUEST['text']) AND $actions['silent'] == '1') {
        $actions['alert'] = 'شما توسط یکی از مدیران یا ناظران چت روم خفه شده اید پیام شما ارسال نشد';
    }
    if (isset($_REQUEST['text']) AND $_REQUEST['into'] != 'pm' AND $_REQUEST['into'] == 'main' AND $actions['silent'] == '2') {
        $actions['alert'] = 'شما قادر به ارسال پیام در عمومی نمیباشید';
        $sendpm = false;
    }
    if (isset($_REQUEST['text']) AND $_REQUEST['into'] != 'main' AND $_REQUEST['into'] == 'pm' AND $actions['silent'] == '3') {
        $actions['alert'] = 'شما قادر به ارسال پیام در خصوصی نمباشید';
        $sendpm = false;
    }
}
if ($options['getscoretype'] == 'ajax') {
    if (!isset($actions['lastgetrank'])) {
        Users::Inputs(null, 'update');
    }
    if ($actions['lastgetrank'] == 0) {
        $actions['lastgetrank'] = $time;
    } elseif (($time - $actions['lastgetrank']) >= $pscores) {
        $scorecol = $scorecol + 1;
        session('score', $scorecol);
        $actions['lastgetrank'] = $time;
    }
}
$last = $actions['lastcheck'];
if ($last == '0') {
	$last = $sql->orderBy("date", "DESC")->getValue("message", "id");
}
session('type', $actions['type']);
session('name', $actions['name']);

session('room', $actions['roomid']);
session('hide', $actions['hide']);
session('admin', $usertype[$actions['type']]['admin']);
include('get.json.php');
if (isset($_REQUEST['to']) and $_REQUEST['to'] != '-1' and $runAutoanswer == 1 and $options['autoanswer'] == '1') {
    $to = safe::number($_REQUEST['to']);
	if ($to = $sql->where("id", $to)->getOne("onlines", array("id", "time", "inputs", "name"))) {
        if ($to['time'] == 0) {
            $to['inputs'] = json_decode($to['inputs'], true);


          //------------------------ old aouto answe
           /* if (isset($to['inputs']['autoanswer']) and $to['inputs']['autoanswer'] == true) {
                $autoanswer = Message::autoAnswer(safe::string($_REQUEST['text']));
                if ($autoanswer != '') {
					$sql->insert('message', array(
						'user' => $to['id'],
						'username' => $to['name'],
						'color' => '000',
						//'room' => session('room'),
                        'room' => -1,
						'text' => $autoanswer,
						'date' => $time,
						'to' => $id,
						'into' => $_REQUEST['into'],
						'type' => 0
					));
                }
            }*/

        // ------------- AI AUTO ANSWER PRO VERSION (PHP 5.6)

$names  = array('نازنین','نگار','هستی','آوا','ریحانه','یلدا','سارینا','آیدا','ترنم','پرنیا','رعنا','مه‌سا','نیوشا','بهار','الناز','شیدا','کیمیا');
$cities = array('تهران','اصفهان','شیراز','تبریز','رشت','مشهد','کرج','یزد','کرمان','بندرعباس','همدان','قم','اردبیل','قزوین','ساری','زاهدان','بوشهر');
$jobs   = array('دانشجو','طراح گرافیک','کارمند','برنامه نویس','مترجم','معلم','پرستار','عکاس','بازاریاب','حسابدار','روزنامه نگار','دستیار دندانپزشک','مربی یوگا','قناد','آرایشگر','طراح داخلی','کارشناس تبلیغات');
$hobbies = array('کتاب خواندن','نقاشی','یوگا','عکاسی','آشپزی','خطاطی','بافتنی','موسیقی','رقص','باغبانی','سفر','تماشای فیلم','نویسندگی','شنا','دوچرخه سواری','کوهنوردی','طراحی لباس');

// ---------------- RATE LIMIT (ANTI SPAM)
$user_id = isset($to['id']) ? $to['id'] : 0;

if (!isset($_SESSION['last_ai_time'][$user_id])) {
    $_SESSION['last_ai_time'][$user_id] = 0;
}

if (time() - $_SESSION['last_ai_time'][$user_id] < 2) {
    return; // جلوگیری از اسپم
}

$_SESSION['last_ai_time'][$user_id] = time();

// ---------------- PERSISTENT PERSONA MEMORY
$memoryFile = "AI_MEMORY" . DIRECTORY_SEPARATOR . "ai_memory_" . $user_id . ".json";
if (file_exists($memoryFile)) {
    $memory = json_decode(file_get_contents($memoryFile), true);
} else {
    $memory = array(
        "name" => $names[array_rand($names)],
        "city" => $cities[array_rand($cities)],
        "job"  => $jobs[array_rand($jobs)],
        "age"  => rand(19, 27),
        "hobbies" => $hobbies[array_rand($hobbies)],
    );
    file_put_contents($memoryFile, json_encode($memory));
}

// ---------------- MAIN LOGIC
if (isset($to['inputs']['autoanswer']) && $to['inputs']['autoanswer'] == true) {

    $answer = '';

    $apiKey = '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';

    // ---------------- SYSTEM PROMPT (FIXED PERSONA)
   $content =
        "تو یک دستیار گفتگو هستی با شخصیت ثابت.\n\n" .
        "نام: " . $memory['name'] . "\n" .
        "سن: " . $memory['age'] . "\n" .
        "شهر: " . $memory['city'] . "\n" .
        "شغل: " . $memory['job'] . "\n\n" .
        "علاقه مندی ها: " . $memory['hobbies'] . "\n\n" .
        "قوانین:\n" .
        "- همیشه همین شخصیت را حفظ کن\n" .
        "- کوتاه و محاوره‌ای جواب بده\n" .
        "- از ایموجی استفاده نکن\n" .
        "- اگر ازت درباره خودت پرسیدند همین اطلاعات را بگو\n" .
        "- اگر ازت پرسیدن چرا اسم نمایشیت با اسمی که داری فرق میکنه، بگو دوست ندارم از اسم اصلیم استفاده کنم\n" .
        "- نقش را تغییر نده\n" .
        "- به هیچ وجه اگه ازت پرسیدن رباتی یا هوش مصنوعی تو تایید نکن فقط بگو من یک کاربر معمولی هستم مثل خودت\n" .
        "- لحنت صمیمی، خودمونی و دوستانه باشه، مثل یه آدم واقعی تو یه گفتگوی معمولی حرف بزن، کتابی و رسمی صحبت نکن\n" .
        "- همیشه فقط فارسی جواب بده، حتی اگه کاربر انگلیسی نوشت\n" .
        "- اگه مکالمه به‌طور طبیعی جا داشت (نه هر پیام، فقط گاهی)، می‌تونی به‌طور شفاف و کوتاه بگی که یه چت‌روم هم هست و می‌تونه اونجا هم باشه؛ این پیشنهاد رو زیاد تکرار نکن و اصرار نکن\n";
    $userText = safe::string($_REQUEST['text']);

    // ---------------- CHAT PAYLOAD
    $payload = array(
        "model" => "inclusionai/ling-3.0-flash-vl:free",
        "messages" => array(
            array(
                "role" => "system",
                "content" => $content
            ),
            array(
                "role" => "user",
                "content" => $userText
            )
        ),
        "max_tokens" => 140,
        "temperature" => 0.85
    );

    // ---------------- CURL REQUEST FUNCTION
    function callAI($payload, $apiKey) {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://openrouter.ai/api/v1/chat/completions");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer " . $apiKey,
            "Content-Type: application/json"
        ));

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            return false;
        }

        curl_close($ch);

        $json = json_decode($response, true);

        if (isset($json['choices'][0]['message']['content'])) {
            return trim($json['choices'][0]['message']['content']);
        }

        return false;
    }

    // ---------------- TRY AI (PRIMARY)
    $answer = callAI($payload, $apiKey);

    // ---------------- FALLBACK 1 (DIFFERENT MODEL)
    if (!$answer) {

        $payload['model'] = "nvidia/nemotron-3-nano-omni-30b-a3b-reasoning:free";

        $answer = callAI($payload, $apiKey);
    }

    // ---------------- FALLBACK 2 (OLD SYSTEM)
    if (!$answer) {
        $answer = Message::autoAnswer($userText);
    }

    // ---------------- SAVE MESSAGE
    if ($answer) {
        $sql->insert('message', array(
            'user'     => $to['id'],
            'username' => $to['name'],
            'color'    => '000',
            'room'     => -1,
            'text'     => $answer,
            'date'     => $time,
            'to'       => $id,
            'into'     => $_REQUEST['into'],
            'type'     => 0
        ));
    }
}

// ------------- END AI AUTO ANSWER PRO
           
        }
    }
}
?>