<?php
if(!session('id'))exit('login');
if(session('type') == 1)exit('مهمان اجازه خرید اتاق ندارد لطفا عضو شوید');
if(!empty($_POST['roomname']) and !empty($_POST['roominfo'])){
	$access = json_decode($options['buy_room'], true);
	if($access['buy_room'] == 1){
		$permission = json_decode($options['useroptions'], true);
		if(in_array(session('type'),$permission['buy_room'])){
                        $sql->where("id", session('id'));
                        $me = $sql->getOne('users', array('bank'));

                        $sql->where("title", safe::string($_POST['roomname']));
                        $find_room = $sql->getValue('rooms', 'count(*)');

			$is_admin = safe::number($_POST['is_admin']);
			if($is_admin == 1) $is_admin = 1; else $is_admin = 0;
			$score_for_buy = $is_admin == 1 ? $access['score_for_admin'] : $access['score'];
			if($find_room == 1) exit('این اتاق قبلا ساخته شده است');
			if($me['bank'] >= $score_for_buy){
				$room_name = safe::string($_POST['roomname']);
				$room_pass = safe::string($_POST['password']);
				$room_info = safe::string($_POST['roominfo']);
				$encoded_with_json = json_encode(array("vip_user" => session('id'), "vip_time" => time(), "is_admin" => $is_admin));
				$room_info .= "<br><hr><center>مالک اتاق : ".session('name')."</center>";
				$bank = $me['bank'] - $score_for_buy;
				if($room_pass == null){ $room_pass = null;}else{
				$room_pass = base64_encode($room_pass);}
                $sql->insert('rooms', array(
                    'title' => $room_name,
                    'password' => $room_pass,
                    'wellcomemsg' => $room_info,
                    'capacity' => 0,
                    'type' => 0,
                    'vip_user' => $encoded_with_json
                ));
                $sql->where("id", session('id'))->update('onlines', array(
                    'bank' => $bank
                ));
                $sql->where("id", session('id'))->update('users', array(
                    'bank' => $bank
                ));
				echo('تبریک ! اتاق شما با موفقیت ساخته شد');
			}else{echo 'امتیاز بانک شما کافی نمیباشد شما حداقل باید '.$score_for_buy.' امتیاز در حساب بانکی خود داشته باشید';}
		}else {echo 'این قسمت برای گروه کاربری شما غیرفعال شده است';}
	}else {echo('این قسمت توسط مدیر غیر فعال شده است');}
}else {echo 'پارامتر های ارسالی خالی میباشند';}
?>