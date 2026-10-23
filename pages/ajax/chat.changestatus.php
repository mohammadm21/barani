<?php

check_session();
if (session('type') == '1') {
    die('مهمان اجازه ندارد لطفا عضو شوید');
}
$status = isset($_POST['status']) ? safe::string($_POST['status']) : '';
$array = array();

$array = array('simg' => '', 'stext' => '');

$array['status_busy'] = array('simg' => 'status_busy', 'stext' => 'مشغول');

$array['status_away'] = array('simg' => 'status_away', 'stext' => 'نيستم');

$array['status_smil'] = array('simg' => 'status_smil', 'stext' => 'مايل به چت');

$array['status_controller'] = array('simg' => 'status_controller', 'stext' => 'در حال بازي');

$array['status_lasing'] = array('simg' => 'status_lasing', 'stext' => 'در حال لاس زدن');

$array['status_study'] = array('simg' => 'status_study', 'stext' => 'در حال درس خوندن');

$array['status_badinternet'] = array('simg' => 'status_badinternet', 'stext' => 'هنگــم');

$array['status_telephone'] = array('simg' => 'status_telephone', 'stext' => 'در حال صحبت با تلفن');

$array['status_heart'] = array('simg' => 'status_heart', 'stext' => 'عاشقم');

$array['status_sleep'] = array('simg' => 'status_sleep', 'stext' => 'خوابم میاد');

$array['status_angry'] = array('simg' => 'status_angry', 'stext' => 'عصبانی هستم');

$array['status_happy'] = array('simg' => 'status_happy', 'stext' => 'همه چی آرومه من چقد خوشحالم');

$array['status_sad'] = array('simg' => 'status_sad', 'stext' => 'ناراحتم');

$array['status_cry'] = array('simg' => 'status_cry', 'stext' => 'دارم گریه میکنم');

$array['status_chell'] = array('simg' => 'status_chell', 'stext' => 'ِشیطونم');

$array['status_music'] = array('simg' => 'status_music', 'stext' => 'دارم آهنگ گوش میدم');

$array['status_broken'] = array('simg' => 'status_broken', 'stext' => 'دلشکسته');

$array['status_eating'] = array('simg' => 'status_eating', 'stext' => 'دارم غذا میخورم');

$array['status_cooking'] = array('simg' => 'status_cooking', 'stext' => 'دارم آشپزی میکنم');

$array['status_angel'] = array('simg' => 'status_angel', 'stext' => 'فقط خدا');

$array['status_sick'] = array('simg' => 'status_sick', 'stext' => 'سرما خوردم');

$array['status_working'] = array('simg' => 'status_working', 'stext' => 'دارم کار میکنم');

$array['status_shy'] = array('simg' => 'status_shy', 'stext' => 'خجالتی هستم');

$array['status_cool'] = array('simg' => 'status_cool', 'stext' => 'جذاب هستم');

$array['status_fm'] = array('simg' => 'status_fm', 'stext' => 'متاهل هستم');

$array['status_zm'] = array('simg' => 'status_zm', 'stext' => 'دنبال دختر خوب برای آشنایی');

$array['status_shm'] = array('simg' => 'status_shm', 'stext' => 'دنبال پسر خوب برای آشنایی');

$array['status_lat'] = array('simg' => 'status_lat', 'stext' => 'لات چت روم');

$array['status_money'] = array('simg' => 'status_money', 'stext' => 'پولدار چت روم');

$array['status_pr'] = array('simg' => 'status_pr', 'stext' => 'بچه پررو');

$array['status_mokh'] = array('simg' => 'status_mokh', 'stext' => 'در حال مخ زدن');

$array['status_nh'] = array('simg' => 'status_nh', 'stext' => 'بی حالم');

$array['status_csgamer'] = array('simg' => 'status_csgamer', 'stext' => 'کانتر باز');

$array['status_tcs'] = array('simg' => 'status_tcs', 'stext' => 'کانتر تروریست');

$array['status_ctcs'] = array('simg' => 'status_ctcs', 'stext' => 'کانتر پلیس');

$array['status_help'] = array('simg' => 'status_help', 'stext' => 'کمک میخوام');

$array['status_beauty'] = array('simg' => 'status_beauty', 'stext' => 'خوشگل روم');

$array['status_za'] = array('simg' => 'status_za', 'stext' => 'زیر آبم');

$array['status_birthday'] = array('simg' => 'status_birthday', 'stext' => 'روز تولدمه');

$array['status_personable'] = array('simg' => 'status_personable', 'stext' => 'با شخصیت');

$array['status_baj'] = array('simg' => 'status_baj', 'stext' => 'باجنبه');

$array['status_bij'] = array('simg' => 'status_bij', 'stext' => 'بی جنبه');

$array['status_jm'] = array('simg' => 'status_jm', 'stext' => 'جدی و مغرور');

$array['status_lovely'] = array('simg' => 'status_lovely', 'stext' => 'دوست داشتنی');

$array['status_baby'] = array('simg' => 'status_baby', 'stext' => 'در حال بچه داری');

$array['status_likeme'] = array('simg' => 'status_likeme', 'stext' => 'لایکم کن تا لایکت کنم');

$array['status_ezraeil'] = array('simg' => 'status_ezraeil', 'stext' => 'عزراییل');

$array['status_rammal'] = array('simg' => 'status_rammal', 'stext' => 'جادوگر روم');

$array['status_spider'] = array('simg' => 'status_spider', 'stext' => 'در حال تور کردن');

$array['status_spider'] = array('simg' => 'status_spider', 'stext' => 'در حال تور کردن');

$array['status_skull'] = array('simg' => 'status_skull', 'stext' => 'اسکلتم');

$array['status_bt'] = array('simg' => 'status_bt', 'stext' => 'خفاش روم');

$array['status_fangel'] = array('simg' => 'status_fangel', 'stext' => 'فرشته روم');


$sql->orderBy('id', 'DESC');
foreach($sql->get('statos', null, array('id', 'img', 'text')) as $row){
    $array[status_ . $row['id']] = array('simg' => status_ . $row['id'], 'stext' => $row['text']);
}
if ($status == 'status_invisible') {
    if ($usertype[session('type')]['loginhidden'] == 1) {
        Users::hide(session('id'));
        echo('ok');
    } else {
        echo('شما نمیتوانید مخفی شوید');
    }
} else {
    Users::show(session('id'));
    $inputs = Users::Inputs();
    $inputs['simg'] = $array[$status]['simg'];
    $inputs['stext'] = $array[$status]['stext'];
    Users::Inputs(null, $inputs);
    echo('ok');
}
?>