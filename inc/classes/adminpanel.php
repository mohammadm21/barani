<?php

class adminpanel {

    public static $Permissions = array(
        'letters' => 'پیام ها',
        'delletters' => 'حذف پیام ها',
        'replayletters' => 'پاسخ به پیام ها',
        'users' => 'کاربران',
        'wlcchat' => 'تنظیمات پنجره خوش آمدگوی ',
        'cmdsettings' => 'تنظیمات دستورات',
        'logs' => 'رصدخانه',
        'analyzer' => 'آنالیزگر',
        'statss' => 'آمار کلی',
        'statslist' => 'ریز آمار',
        'statsanalyzer' => 'امار تحلیلی',
        'rollcall' => 'حضور و غیاب',
        'avatar' => 'لیست اواتارها',
        'cover' => 'لیست جلدها',
        'profile' => 'لیست مطلب ها',
        'logon' => 'فعالیت های داخل چت روم',
        'support' => 'پشتیبانی',
        'pmwlc' => 'پنجره خوش امدگویی',
        'specialactions' => 'اقدامات ویژه',
        'adminpanel' => 'مدیریت دسترسی ها',
        'silent' => 'خفه کن',
        'editusers' => 'ویرایش کاربران',
        'bann' => 'اخراج کاربران',
        'delusers' => 'حذف کاربران',
        'refreshusers' => 'رفرش کاربران',
        'alertuser' => 'اخطار به کاربران',
        'rooms' => 'اتاق ها',
        'addroom' => 'اضافه کردن اتاق',
        'editroom' => 'ویرایش اتاق ها',
        'deleteroom' => 'حذف اتاق ها',
        'smilies' => 'شکلک ها',
        'defualtssettings' => 'تنظیمات پیشفرض',
        'addsmily' => 'افزودن شکلک',
        'editsmily' => 'ویرایش شکلک',
        'deletesmily' => 'حذف شکلک',
        'badwords' => 'کلمات زشت',
        'filters' => 'کلمات فیلتر شده',
        'badwordsettings' => 'تنظیمات کلمات زشت',
        'badnames' => 'نام کاربری زشت',
        'report' => 'کاربران ریپورت شده',
        'editbadwords' => 'ویرایش کلمات زشت',
        'addbadwords' => 'افزودن کلمه زشت',
        'delbadwords' => 'حذف کلمات زشت',
        'editbadnames' => 'ویرایش نام کاربری زشت',
        'addbadnames' => 'افزودن نام کاربری زشت',
        'delbadnames' => 'حذف نام کاربری زشت',
        'stats' => 'آمارگیر',
        'news' => 'اخبار',
        'visualuser' => 'کاربران مجازی',
        'addvisualuser' => 'افزودن کاربر مجازی',
        'delvisualuser' => 'افزودن کاربر مجازی',
        'msgsender' => 'ارسال پیام گروهی',
        'msgreader' => 'خصوصی خوان',
        'adminmsgreader' => 'خواندن خصوصی مدیران',
        'modmsgreader' => 'خواندن خصوصی ناظران',
        'ipbanner' => 'تحریم ها',
        'addipbanner' => 'تحریم آی پی جدید',
        'delipbanner' => 'بازکردن تحریم',
        'player' => 'موسیقی',
        'addplayer' => 'افزودن موسیقی',
        'delplayer' => 'حذف موسیقی',
        'links' => 'پیوند ها',
        'addlinks' => 'افزودن پیوند',
        'editlinks' => 'ویرایش پیوند',
        'theme' => 'مدیریت قالب',
        'themechat' => '  مدیریت قالب داخل',
        'privatstyle' => 'استایل پنجره',
        'fontsin' => 'فونت ها',
        'headers' => 'سربرگ ها',
        'codejava' => 'کد جاوا و فلش',
        'logintheme' => 'مدیریت قالب بیرونی',
        'generalsettings' => 'تنظیمات اصلی',
        'msgsettings' => 'تنظیمات پیام ها',
        'loginsettings' => 'تنظیمات ورود',
        'rclicksettings' => 'تنظیمات کلیک راست',
        'useroptionsettings' => 'محدودیت های امکانات',
        'usertypes' => 'گروه های کاربری',
        'addusertypes' => 'افزودن گروه کاربری',
        'editusertypes' => 'ویرایش گروه کاربری',
        'delusertypes' => 'حذف گروه کاربری',
        'ads' => 'مدیریت تبلیغات',
        'adss' => 'تبلیغات',
        'adsbuy' => 'قیمت تبلیغات',
        'addads' => 'افزودن تبلیغ',
        'delads' => 'حذف تبلیغ',
        'themelogins' => 'انتخاب قالب',
        'themeselect' => 'نصب قالب',
        'editautoanswer' => 'ویرایش پاسخ اتوماتیک',
        'delautoanswer' => 'حذف پاسخ های اتوماتیک',
        'seepw' => 'مشاهده کلمه عبور کاربران',
        'seepwadminteam' => 'مشاهده کلمه عبور تیم مدیریتی',
        'changepw' => 'تغییر کلمه عبور کاربران',
        'changepwadminteam' => 'تغییر کلمه عبور تیم مدیریتی',
        'pays' => 'پرداخت ها',
        'sendedimages' => 'فایل های ارسالی در خصوصی',  
        'tlgsendedpm' => 'تنظیمات ربات تلگرام',
        'redirectsetting' => 'دسترسی به تنظیمات ریدایرکت',
        'deletestatos' => 'دسترسی به حذف وضعیت',
        'addfonts' => 'افزودن فونت',
     	'statos' => 'افزودن وضعیت',
        'deletefonts' => 'حذف فونت',
        'iconlist' => 'تغییر ایکون لیست انلاین',
        'iconmororgar' => 'تغییر ایکون مرورگر',
        'iconomoomi' => 'تغییر ایکون عمومی',
        'iconprivate' => 'تغییر ایکون چت خصوصی',
        'iconstatus' => 'تغییر ایکون وضعیت',
        'deleteavatar' => 'دسترسی به رصد خانه اواتار',
        'logscover' => 'دسترسی به رصد خانه کاور',
        'logsprofile' => 'دسترسی به رصد خانه پست کاربران',
        'settingsmod' => 'ویرایش محدودیت مدیریت',
        'loginsettingsbann' => 'محدودیت برای ایپی کشورها',
        'settingsprivate' => 'ویرایش محدودیت های چت خصوصی',
        'settingsprofile' => 'ویرایش محدودیت های پروفایل',
        'addstatos' => 'دسترسی به افزودن وضعیت',
        'settingssocial' => 'ویرایش تنظیمات شبکه اجتماعی',
        'textsetting' => 'ویرایش متون داخل چت روم',
        'settingsvip' => 'ویرایش محدودیت امکانات ویژه',     
        'settingsseo' => 'دسترسی به تنظیمات سئو',
        'settingsfillprint' => 'تنظیمات اثر انگشت دستگاه',
        'fillprint' => 'مشاهده موارد اثر انگشت مشکوک',
        'settingsmobilesite' => 'تنظیمات نسخه موبایل'
    );
   public static $GlobalPages = array('index', 'settings', 'restriction');

    static function getNamePermissions($permissions) {
        if ($permissions == 'all') {
            return('همه');
        }
        $permissions = explode(',', $permissions);
        $NamePermissions = "";
        foreach ($permissions as $permission) {
            if ($NamePermissions != '') {
                $NamePermissions .= ',';
            }
            $NamePermissions.= self::getNamePermission($permission);
        }
        return $NamePermissions;
    }

    static function getNamePermission($permission) {
        return self::$Permissions[$permission];
    }

    static function check_Permission($action = 'notfound', $page = null, $permissions = null) {
        global $sql;
        if (!session('adminpanelogin')) {
            session('adminpanelogin', 'false');
        }
        if ($permissions == null) {
            $permissions = session('permissions');
        } else {
            if (is_string($permissions)) {
                if ($permissions == 'all') {
                    return true;
                }
                $permissions = explode(',', $permissions);
            }
        }
        if ($page == null) {
            $page = giu('el2');
        }
        if (!$page) {
            $page = 'index';
        } 
        if (!$permissions) { 
            $sql->where('user', session('id'));
            if ($permissions = $sql->getOne('adminpanel', array('permissions', 'password'))) {
                if ($permissions['password'] != '') {
                    if (session('adminpanelogin') != 'true') {
if(giu('page') === 'admin'){
                        header::Go(url('admin/login'));
                        }else{
                        	switch($action){
						        case('notfound'):case(null):include("pages/noaccess.php");exit();break;
						        case('bool'):return false;break;
						        case('exit'):exit();break;
						    }
                        }
						exit();
                    }
                }

                session('permissions', $permissions['permissions']);
                $permissions = $permissions['permissions'];
            } else {
                switch ($action) {
                    case('notfound'):case(null):include("pages/noaccess.php");
                        exit();
                        break;
                    case('bool'):return false;
                        break;
                    case('exit'):exit();
                        break;
                }
            }
        }
        if ($permissions == 'all')
            return true;
        $permissions = explode(',', $permissions);
        if (in_array($page, $permissions)) {
            return true;
        } else {
            switch ($action) {
                case('notfound'):case(null):include("pages/noaccess.php");
                    exit();
                    break;
                case('bool'):return false;
                    break;
                case('exit'):exit();
                    break;
            }
        }
    }

}

?>
