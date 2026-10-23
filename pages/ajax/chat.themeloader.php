<?php

if (!session('id'))
    exit();
header::tojson();

$options['thememanager'] = json_decode($options['thememanager'], true);
foreach ($options['thememanager'] as $key => $val) {
    if ($key != 'chat') {
        unset($options['thememanager'][$key]);
    }
}
$options['thememanager']['chat']['status'] = array(
    'online' => 'آنلاین',
    'busy' => 'مشغول',
    'away' => 'نیستم',
    'smil' => 'مایل به چت',
    'sleep' => 'خوابم میاد',
    'angry' => 'عصبانی هستم',
    'happy' => 'همه چی آرومه من چقد خوشحالم',
    'sad' => 'ناراحتم',
    'cry' => 'دارم گریه میکنم',
    'chell' => 'شیطونم',
    'music' => 'دارم آهنگ گوش میدم',
    'broken' => 'دلشکسته',
    'eating' => 'دارم غذا میخورم',
    'cooking' => 'دارم آشپزی میکنم',
    'angel' => 'فقط خدا',
    'sick' => 'سرما خوردم',
    'working' => 'دارم کار میکنم',
    'shy' => 'خجالتی هستم',
    'cool' => 'جذاب هستم',
    'fm' => 'متاهل هستم',
    'zm' => 'دنبال دختر خوب برای آشنایی',
    'shm' => 'دنبال پسر خوب برای آشنایی',
    'lat' => 'لات چت روم',
    'money' => 'پولدار چت روم',
    'pr' => 'بچه پررو',
    'mokh' => 'در حال مخ زدن',
    'nh' => 'بی حالم',
    'csgamer' => 'کانتر باز',
    'tcs' => 'کانتر تروریست',
    'ctcs' => 'کانتر پلیس',
    'beauty' => 'خوشگل روم',
    'za' => 'زیر آبم',
    'help' => 'کمک میخوام',
    'birthday' => 'روز تولدمه',
    'personable' => 'با شخصیت',
    'baj' => 'باجنبه',
    'bij' => 'بی جنبه',
    'jm' => 'جدی و مغرور',
    'lovely' => 'دوست داشتنی',
    'baby' => 'در حال بچه داری',
    'likeme' => 'لایکم کن تا لایکت کنم',
    'ezraeil' => 'عزراییل',
    'rammal' => 'جادوگر روم',
    'spider' => 'در حال تور کردن',
    'skull' => 'اسکلتم',
    'bt' => 'خفاش روم',
    'fangel' => 'فرشته روم',
    'controller' => 'درحال بازی',
    'telephone' => 'تلفن',
    'heart' => 'عاشقم',
    'invisible' => 'مخفی شدن'
);
$options['thememanager'] = json_encode($options['thememanager'], JSON_UNESCAPED_UNICODE);
echo($options['thememanager']);
?>