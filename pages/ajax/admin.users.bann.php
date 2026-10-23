<?php

if (!session('id') or session('admin') == '0') {
    exit();
}
if (isset($_POST['user'])) {
    adminpanel::check_Permission('exit', 'bann');
    $token = isset($_POST['token']) ? $_POST['token'] : false;
    if (!$token || $token != session('changepw')) {
        die('لطفا صفحه خود را رفرش کنيد');
    }

    $user = safe::number($_POST['user']);
    $sql->where("id", $user);
    if ($user = $sql->getOne('users')) {
        $type = $user['type'];
        if (Users::AdminsType($type)) {
            echo('admin');
            return;
        }
        $date = $_REQUEST['date'];
        $reason = isset($_POST['reason']) ? safe::string($_POST['reason']) : die('وارد کردن دلیل اجباریست');
        switch ($date) {
            case('exit'):Users::exitt($user['id'], 'true');
                echo('ok');
                return;
                break;
            case('1min'):$time = strtotime('+1 minute');
                break;
            case('10min'):$time = strtotime('+10 minute');
                break;
            case('30min'):$time = strtotime('+30 minute');
                break;
            case('1hour'):$time = strtotime('+1 hour');
                break;
            case('12hour'):$time = strtotime('+12 hour');
                break;
            case('1day'):$time = strtotime('+1 day');
                break;
            case('3day'):$time = strtotime('+3 day');
                break;
            case('1week'):$time = strtotime('+1 week');
                break;
            case('2week'):$time = strtotime('+2 week');
                break;
            case('1month'):$time = strtotime('+1 month');
                break;
            case('6month'):$time = strtotime('+6 month');
                break;
            case('1year'):$time = strtotime('+1 year');
                break;
            case('always'):$time = 0;
                break;
            default:die('date');
                break;
        }
        Users::bann($user['id'], $time, $reason);
        stats('bann');
        echo('ok');
        unset($date, $type);
    } else {
        echo('user');
    }
    unset($user);
} elseif (isset($_POST['ip'])) {
    adminpanel::check_Permission('exit', 'ipbanner');
    $ip = safe::string($_POST['ip']);
    $sql->where("ip", $ip);
    if ($sql->has("banns")) {
        echo('repeat');
        return;
    }
    $date = $_REQUEST['date'];
    switch ($date) {
        case('exit'):$time = time() + 3;
            break;
        case('1min'):$time = strtotime('+1 minute');
            break;
        case('30min'):$time = strtotime('+30 minute');
            break;
        case('1hour'):$time = strtotime('+1 hour');
            break;
        case('12hour'):$time = strtotime('+12 hour');
            break;
        case('1day'):$time = strtotime('+1 day');
            break;
        case('3day'):$time = strtotime('+3 day');
            break;
        case('1week'):$time = strtotime('+1 week');
            break;
        case('2week'):$time = strtotime('+2 week');
            break;
        case('1month'):$time = strtotime('+1 month');
            break;
        case('6month'):$time = strtotime('+6 month');
            break;
        case('1year'):$time = strtotime('+6 year');
            break;
        case('always'):$time = 0;
            break;
        default:die('date');
            break;
    }
    $sql->insert('banns', array(
        'ip' => $ip,
        'date' => time(),
        'to' => $to,
        'author' => session('id'),
        'user' => null
    ));   
    $sql->where("ip", '', '!=');
    $sql->where("time", 0, '!=');
    foreach($sql->get('onlines', null, array('id', 'ip', 'inputs')) as $row){
        if (substr($row['ip'], 0, strlen($ip)) == $ip) {
            $inputs = json_decode($row['inputs'], true);
            $inputs['benned'] = '1';
            Users::Inputs($row['id'], $inputs);
        }
    }
    echo('ok');
}
unset($admin);
?>