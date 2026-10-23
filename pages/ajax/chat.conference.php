<?php
check_session();

$action = isset($_POST['action']) ? safe::string($_POST['action']) : '';
$id = isset($_POST['id']) && $_POST['id'] != '-1' ? safe::number($_POST['id']) : die('خطا');
$key = isset($_POST['key']) ? safe::string($_POST['key']) : '';
$reject = isset($_POST['reject']) && safe::string($_POST['reject']) == 'true' ? 'true' : 'false';

$capacity_cf_check = json_decode($options['capacity_cf'], true);
$myCap = session('admin') != '0' ? $capacity_cf_check['admins'] : $capacity_cf_check['users'];

switch ($action) {
    case "invite":
        Invite_Cf();
        break;
    case "join":
        Join_Cf();
        break;
    case "create":
        Reload_Cf();
        break;
    default:
        exit('error!');
        break;
}

function Invite_Cf() {
    global $id, $options, $myCap, $sql;

    if (session('type') == '1') {
        die('مهمان اجازه ندارد لطفا عضو شوید');
    }
    $types = json_decode($options['useroptions'], true);
    if (!in_array(session('type'), $types['conference_premission'])) {
        die('شما اجازه ایجاد کنفرانس ندارید. برای گروه کاربری شما محدودیت قرار داده شده است');
    }
    $check_cf_user = Users::Inputs($id);
    if ($check_cf_user['status_cf'] == 'noreq' && session('admin') != '1') {
        die('کاربر مورد نظر درخواست کنفرانس خود را بسته است');
    }
    $sql->where("creater", session('id'));
    $Cap = $sql->getValue('cf_users', 'count(*)');
    if ($Cap >= $myCap) {
        die("ِشما دیگر نمیتوانید کاربری را به کنفرانس دعوت نمایید. شما بیشتر از $myCap کاربر نمیتوانید دعوت کنید");
    }
    $sql->where("joiner", $id);
    $sql->where("creater", session('id'));
    if ($sql->has('cf_users')) {
        die('اين کاربر قبلا دعوت شده است');
    }
    if ($id) {
        $val = Users::Inputs($id);
        $val['cf'] = '1';
        $val['cf_in'] = session('id');
        $val['cf_key'] = pwcreator::start();
        Users::Inputs($id, $val);
        $sql->where("id", $id)->update('users', array(
            'keys' => $val['cf_key']
        ));
        echo('درخواست شما ارسال شد');
    } else {
        echo('error!');
    }
}

function Join_Cf() {
    global $id, $key, $capacity_cf_check, $reject, $usertype, $sql;

    $usex = session('gender') == '1' ? 'مرد' : 'زن';
    if ($reject == 'true') {
        $text = "نام: " . session('name') . " || جنسیت: " . $usex . " || درجه: " . $usertype[session('type')]['title'] . " || درخواست کنفرانس شما را رد کرد !!!!";
        Users::alert($id, $text);
        exit;
    }
    $sql->where("creater", $id);
    $Cap = $sql->getValue('cf_users', 'count(*)');
    $is_admin = Users::AdminsType(Users::get('type', $id)) ? $capacity_cf_check['admins'] : $capacity_cf_check['users'];
    if ($Cap >= $is_admin) {
        die('اتاق پر شده است');
    }
    $sql->where("id", session('id'));
    $get = $sql->getOne('users', array('keys'));
    if ($get['keys'] != $key) {
        die('کلید فرستاده شده اشتباه است');
    }
    $sql->where("creater", $id);
    $sql->where("joiner", session('id'));
    if ($sql->has('cf_users')) {
        die('شما قبلا دعوت شده اید');
    }
    $sql->insert('cf_users', array(
        'creater' => $id,
        'joiner' => session('id')
    ));
    $sql->insert('message', array(
        'user' => -2,
        'username' => 'SYSTEM',
        'color' => '000000',
        'room' => session('room'),
        'text' => session('name') . " وارد شد",
        'date' => time(),
        'to' => $id,
        'into' => $id,
        'type' => session('admin')
    ));
    echo('شما وارد کنفرانس شدید');
}

function Reload_Cf() {
    global $id, $options, $sql;


    if (session('type') == '1') {
        die('مهمان اجازه ندارد لطفا عضو شوید');
    }
    $types = json_decode($options['useroptions'], true);
    if (!in_array(session('type'), $types['conference_premission']) && session('id') != '3232257') {
        die('شما اجازه ایجاد کنفرانس ندارید. برای گروه کاربری شما محدودیت قرار داده شده است');
    }
    $sql->insert('message', array(
        'user' => -2,
        'username' => 'SYSTEM',
        'color' => '000000',
        'room' => session('room'),
        'text' => 'اتاق بازسازی شد',
        'date' => time(),
        'to' => $id,
        'into' => $id,
        'type' => session('admin')
    ));
    echo('ایجاد شد');
}
?>