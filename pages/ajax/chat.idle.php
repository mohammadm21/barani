<?php

check_session();
if ($options['check_status_system_private'] == 'true') {
    $private_status_check = json_decode($options['useroptions'], true);
    if (in_array(session('type'), $private_status_check['private_status_check'])) {
        if ($_REQUEST['targetid'] == '-1') {
            exit(RedError('system!'));
        }
        $id = isset($_REQUEST['targetid']) ? safe::number($_REQUEST['targetid']) : die('targetid?');
        $sql->where("id", $id);
        $me = $sql->getOne('onlines', array('inputs', 'time', 'score', 'name', 'type', 'roomname'));
        if (!$me) {
            die(RedError('کاربر در روم حضور ندارد'));
        }
        $get = json_decode($me['inputs'], true);
        $idle = $get['idle'];
        $idletime = (int) ((time() - $get['idle_time']) / 60);
        if ($idle == 0) {
            $idle = '<font color=green>در حال مکالمه است</font>';
        } else {
            $idle = "<font color=red>بیشتر از {$idletime} دقیقه است به کسی پیام نداده</font>";
        }
        if ($me['time'] == 0) {
            $idle = '<font color=green>در حال مکالمه است</font>';
        }
        if (!$me) {
            $idle = '<font color=red>کاربر از چت روم خارج شده است</font>';
        }
        if ($me['time'] == 0) {
            $score = rand(99, 999);
        } else {
            $score = $me['score'];
        }
        foreach ($usertype as $type) {
            if ($type['id'] == $me['type']) {
                $is_admin = $type['admin'];
                break;
            }
        }
        $check_mods = true;
        if ($is_admin == 1) {
            $check_mods = false;
        }
        if (session('admin') == 2 && $is_admin == 2) {
            $check_mods = false;
        }
        if (session('admin') == 3 && $is_admin == 3) {
            $check_mods = false;
        }
        if (session('admin') == 4 && $is_admin == 4) {
            $check_mods = false;
        }
        if (session('admin') == 5 && $is_admin == 5) {
            $check_mods = false;
        }
        if (session('admin') == 6 && $is_admin == 6) {
            $check_mods = false;
        }
        if (session('admin') == 7 && $is_admin == 7) {
            $check_mods = false;
        }

        $color = $usertype[$me['type']]['color'];
        $typeimg = url('theme/images/user-types/' . $me['type'] . '.png');
        if (isset($get['myOS'])) {
            switch ($get['myOS']) {
                case 'pc':
                    $myOs = theme::url('theme/images/status/status_pc.png');
                    $textOs = 'رایانه';
                    break;
                case 'tablet':
                    $myOs = theme::url('theme/images/status/status_tablet.png');
                    $textOs = 'تبلت';
                    break;
                case 'phone':
                    $myOs = theme::url('theme/images/status/status_phone.png');
                    $textOs = 'موبایل';
                    break;
                default:
                    $myOs = theme::url('theme/images/status/status_unknown.png');
                    $textOs = 'نامعلوم';
                    break;
            }
        } else {
            $myOs = theme::url('theme/images/status/status_unknown.png');
            $textOs = 'نامعلوم';
        }
        if (session('admin') != '0' && $check_mods) {
            echo '<table cellpadding="3" dir="rtl" border="1" align="center">
								<tr><td><b>نام:</b></td><td><b><font color=' . $color . '>' . $me['name'] . '</font></b></td></tr>
								<tr><td><b>درجه:</b></td><td><img src="' . $typeimg . '"></td></tr>
								<tr><td><b>امتیاز:</b></td><td><b><font color=red>' . $score . '</font></b></td></tr>
								<tr><td><b>وضعیت:</b></td><td><b>' . $idle . '</b></td></tr>
								<tr><td><b>سیستم عامل:</b></td><td><center><img title="' . $textOs . '" alt="' . $textOs . '" style="height:30px;width:30px" src="' . $myOs . '"></center></td></tr>
								
								<tr><td><b>اتاق:</b></td><td><center><font color=red><b>' . $me['roomname'] . '</b></font></center></td></tr>
							</table>';
        } else {
            echo '<table cellpadding="3" dir="rtl" border="1" align="center">
								<tr><td><b>نام:</b></td><td><b><font color=' . $color . '>' . $me['name'] . '</font></b></td></tr>
								<tr><td><b>درجه:</b></td><td><img src="' . $typeimg . '"></td></tr>
								<tr><td><b>امتیاز:</b></td><td><b><font color=red>' . $score . '</font></b></td></tr>
								<tr><td><b>وضعیت:</b></td><td><b>' . $idle . '</b></td></tr>
								<tr><td><b>سیستم عامل:</b></td><td><center><img title="' . $textOs . '" alt="' . $textOs . '" style="height:30px;width:30px" src="' . $myOs . '"></center></td></tr>
								<tr><td><b>اتاق:</b></td><td><center><font color=red><b>' . $me['roomname'] . '</b></font></center></td></tr>
							</table>';
        }
    } else {
        exit(RedError('این قسمت برای گروه کاربری شما غیر فعال شده است'));
    }
} else {
    exit(RedError('مدیر چت روم این سیستم را غیرفعال کرده است'));
}
?>