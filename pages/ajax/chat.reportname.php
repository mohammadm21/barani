<?php

if (!session('id')) {
    die('login');
}
if ($_POST['userid'] == '-1') {
    exit('من سیستم هستم !');
}
$inputs = Users::Inputs(session('id'));
	if($inputs['reportstatus'] == 'stop' or isset($_COOKIE["reportstatus"])=='block' or isset($_COOKIE["reporip"])==ip()){
		echo("ارسال گزارش شما مسدود شده است و شما مجاز به ارسال گزارش نمیباشد");return;
}
$targetid = isset($_POST['targetid']) ? safe::number($_POST['targetid']) : '';
send2admins($targetid);
echo 'گزارش شما ارسال شد';

function send2admins($targetid) {
    global $sql;
    global $usertype;
    global $options;
    $admins = array();
    $is_admin = array(1, 2, 3, 4, 5);
    $allow_admins = json_decode($options['useroptions'], true);
    foreach ($usertype as $types) {
        if (in_array($types['admin'], $is_admin)) {
            $admins[] = $types['id'];
        }
    }
    $note = '<table dir="rtl" cellpadding="3" border="1" align="center">
					<tr><td><b>ارسال کننده گزارش <img src="' . url('theme/images/chat/chat.wand.png') . '" onclick=changeUserEvent("adminu_' . session('id') . '")></b></td><td><b><font color=red>' . Users::get('username', session('id')) . '</font></b> <img src="' . url($usertype[Users::get('type', session('id'))]['icon']) . '"></td></tr>
					<tr><td><b>نوع گزارش</b></td><td><b><font color=red>گزارش نام کاربری نام مناسب در لیست</font></b></td></tr>
					<tr><td><b>نام نمایشی کاربری <img src="' . url('theme/images/chat/chat.wand.png') . '" onclick=changeUserEvent("adminu_' . $targetid . '")></b></td><td><b><font color=red>' . Users::get('name', $targetid) . '</font></b> <img src="' . url($usertype[Users::get('type', $targetid)]['icon']) . '"></td></tr>
					<tr><td><b>نام واقعی کاربر <img src="' . url('theme/images/chat/chat.wand.png') . '" onclick=changeUserEvent("adminu_' . $targetid . '")></b></td><td><b><font color=red>' . Users::get('username', $targetid) . '</font></b> <img src="' . url($usertype[Users::get('type', $targetid)]['icon']) . '"></td></tr>
					<tr><td><b>دادن امتیاز به گزارش دهنده</td><td> <button onClick="ScoreNameReporter('.session('id').')" style=" background-color:#FF0000;color:#FFF">بده</button></td></tr>
				</table><hr>';
    $implode = implode(',', $admins);
    $sql->where("type", $admins, 'in');
    foreach($sql->get('onlines', null, array('id', 'type', 'roomid')) as $row){
        if ($row['roomid'] == '1') {
            $row['roomid'] = '-1';
        }
        if (in_array($row['type'], $admins)) {
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
}

?>