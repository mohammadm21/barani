<?php

check_session();
$id= session('id');
if ($options['report_user'] == 'true') {
    if ($_POST['userid'] == '-1') {
        exit('من سیستم هستم !');
    }
  
  //----------------anti spam--------
  
 
	if ($sql->where('id', $id)->getValue('onlines', 'count(*)') != 1 || $sql->where('ip', ip())->getValue('banns','count(*)') == 1 ) {
           //session_destroy();
			echo "بس کن مسخره. زور بیخود نزن";
			exit();
    }
  //---------------------------------
  
$inputs = Users::Inputs(session('id'));
		if($inputs['reportstatus'] == 'stop' or isset($_COOKIE["reportstatus"])=='block' or isset($_COOKIE["reporip"])==ip()){
		echo("ارسال گزارش شما مسدود شده است و شما مجاز به ارسال گزارش نمیباشد");return;
}
    $targetid = isset($_POST['userid']) && $_POST['userid'] != session('id') ? safe::number($_POST['userid']) : die('error');

    $cp = isset($_POST['cp']) ? 'true' : 'false';
    $location = isset($_POST['location']) && safe::string($_POST['location']) == 'main' ? 'main' : 'private';
    $location == 'main' && session('type') == '1' ? die('مهمان اجازه ارسال گزارش در عمومی ندارد') : '';
    $reportText = isset($_POST['report']) ? safe::string($_POST['report']) : die('error');
    $reportText = str_replace(array('<b>', '</b>', '<i>', '</i>'), '', $reportText);
    $reportText = safe::string($reportText);
    if (empty($reportText)) {
        die('ارسال نشد');
    }
    send2admins($reportText, session('id'), $targetid, $cp, $location);
    echo 'ok';
} else {
    echo 'offline';
}

function send2admins($text, $sender, $reciver, $cp, $location) {
    global $sql;
    global $usertype;
    global $options;

    $allow_admins = json_decode($options['useroptions'], true);

    $sender_name = array('name' => session('name'), 'type' => session('type'), 'gender' => session('gender'));
    $sql->where("id", $reciver);
    $reciver_name = $sql->getOne('onlines', array('name', 'type', 'gender'));
    if (!$reciver_name) {;
        $sql->where("id", $reciver);
        $reciver_name = $sql->getOne('users', array('name', 'type', 'gender'));
    }

    $type_sender = $usertype[$sender_name['type']]['icon'];
    $type_reciver = $usertype[$reciver_name['type']]['icon'];
    $sex_sender = $sender_name['gender'] == '2' ? theme::url('theme/images/chat/chat.user_comment_w.png') : theme::url('theme/images/chat/chat.user_comment_m.png');
    $sex_reciver = $reciver_name['gender'] == '2' ? theme::url('theme/images/chat/chat.user_comment_w.png') : theme::url('theme/images/chat/chat.user_comment_m.png');
    $sender_color = $usertype[$sender_name['type']]['color'];
    $reciver_color = $usertype[$reciver_name['type']]['color'];
    $textColor = session('color') == '000000' || !session('color') ? 'red' : '#' . session('color');
    $cp2text = $cp == 'true' ? '<tr><td><b>متن مستقیم از:</b></td><td><b><font color=' . $reciver_color . '>' . $reciver_name['name'] . '</font></b> <img src="/' . $type_reciver . '"><img src="' . $sex_reciver . '"></td></tr>' : '';
    $loc = $location == 'main' ? '<tr><td><b>محل گزارش شده:</b></td><td><b><font color=' . $textColor . '>' . session('roomname') . '</font></b></td></tr>' : '';
    $note = '<table dir="rtl" cellpadding="3" border="1" align="center">
					<tr><td><img style="cursor:pointer;" src="' . theme::url('theme/images/chat/chat.wand.png') . '" onclick=changeUserEvent("adminu_' . $sender . '")><b>ارسال کننده گزارش:</b></td><td><b><a href=javascript:void(0) onclick=changeUserEvent("privatwin_' . $sender . '")><font color=' . $sender_color . '>' . $sender_name['name'] . '</font></a></b> <img src="/' . $type_sender . '"><img src="' . $sex_sender . '"></td></tr>
					<tr><td><img style="cursor:pointer;" src="' . theme::url('theme/images/chat/chat.wand.png') . '" onclick=changeUserEvent("adminu_' . $reciver . '")><b>کاربر متخلف:</b></td><td><b><a href=javascript:void(0) onclick=changeUserEvent("privatwin_' . $reciver . '")><font color=' . $reciver_color . '>' . $reciver_name['name'] . '</font></a></b> <img src="/' . $type_reciver . '"><img src="' . $sex_reciver . '"></td></tr>
					<tr><td><b>متن گزارش:</b></td><td><b><font color=' . $textColor . '>' . $text . '</font></b></td></tr>
					<tr><td><b>دادن امتیاز به گزارش دهنده</td><td> <button onClick="ScoreNameReporter('.session('id').')" style=" background-color:#FF0000;color:#FFF">بده</button></td></tr>' . $cp2text . $loc . '
				</table><hr>';
    $sql->where("admin", 0, '!=');
    foreach($sql->get('onlines', null, array('id', 'type', 'roomid')) as $row){
        if ($row['roomid'] == '1') {
            $row['roomid'] = '-1';
        }
        if (in_array($row['type'], $allow_admins['send_user2private_admins'])) {
            $sql->insert('message', array(
                'user' => -1,
                'username' => 'SYSTEM [ <b><font color=red>ارسال گزارش</font></b> ]',
                'color' => '000000',
                'room' => $row['roomid'],
                'text' => $note,
                'date' => time(),
                'to' => $row['id'],
                'into' => 'pm',
                'type' => session('admin')
            ));
        }
    }
}

?>
