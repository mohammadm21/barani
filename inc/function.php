<?php

function giu($re, $i = '')
{
    chatroom::checkWWW();
    chatroom::redirect();
    chatroom::redirect302();
    global $giu;
    if ($giu == null) {
        global $site_url, $options;
        if ($options['url_type'] == 'rewrite') {
            $i = strtolower($_SERVER['REQUEST_URI']);
            if (!empty($options['rewtite_suffix'])) {
                $i = explode($options['rewtite_suffix'], $i);
                $i = $i[0];
            }
            $i = substr($i, 1);
            $i = explode('?', $i);
            $i = explode('/', $i[0]);
            if ($i[0] == '') {
                $i[0] = 'index';
            } elseif ($i[0] == 'index') {
                header::Go(url());
                exit();
            } else {
                if ($i[0] != 'robots.txt' and $i[0] != 'sitemap.xml') {
                    $temp = explode('?', strtolower($_SERVER['REQUEST_URI']));
                    if (substr($temp[0], strlen($temp[0]) - strlen($options['rewtite_suffix'])) != $options['rewtite_suffix']) {
                        header::go("http://{$_SERVER['HTTP_HOST']}/" . implode('/', $i) . $options['rewtite_suffix']);
                        exit();
                    }
                }
            }
            $giu = $i;
        } elseif ($options['url_type'] == 'rewrite-i') {
            if (!isset($_GET['i'])) {
                $i = strtolower($_SERVER['REQUEST_URI']);
                $i = explode($options['rewtite_suffix'], $i);
                $i = $i[0];
                $i = substr($i, 1);
                $i = explode('/', $i);
                if ($i[0] == '') {
                    $i[0] = 'index';
                }
                $giu = $i;
            } else {
                $i = strtolower($_GET['i']);
                $i = explode('|', $i);
                if ($i[0] == '') {
                    $i[0] = 'index';
                }
                $giu = $i;
            }
        } elseif ($options['url_type'] == 'i') {
            if (isset($_GET['i'])) {
                $i = strtolower($_GET['i']);
                $i = explode('|', $i);
                if ($i[0] == '') {
                    $i[0] = 'index';
                }
                $giu = $i;
            }
        } elseif ($options['url_type'] == 'get') {
            $giu = array_keys($_GET);
        }
    }
    switch ($re) {
        case ('page'):
            return (isset($giu[0]) ? $giu[0] : 'index');
        case ('el2'):
            return (isset($giu[1]) ? $giu[1] : false);
        case ('el3'):
            return (isset($giu[2]) ? $giu[2] : false);
        case ('el4'):
            return (isset($giu[3]) ? $giu[3] : false);
        case ('el5'):
            return (isset($giu[4]) ? $giu[4] : false);
    }
}

function save_log($type, $input = null, $user = null)
{
    global $sql;
    if ($user == null) {
        if (isset($_SESSION['id'])) {
            $user = $_SESSION['id'];
        } else {
            $user = '0';
        }
    }
    $sql->insert("logs", array(
        'ip' => ip(),
        'date' => time(),
        'user' => $user,
        'type' => $type,
        'input' => $input
    ));
    $sql->insert("logs_archive", array(
        'ip' => ip(),
        'date' => time(),
        'user' => $user,
        'type' => $type,
        'input' => $input
    ));
}

function url($page = 'index', $encode = false)
{
    global $site_url, $options;
    if ($page == 'this') {
        return ("http://" . $_SERVER['HTTP_HOST'] . urldecode($_SERVER['REQUEST_URI']));
    } elseif ($page == 'ref') {
        return (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
    } elseif (url_type == 'rewrite' or url_type == 'rewrite-i') {
        $substr5 = substr($page, 0, 5);
        if ($substr5 == 'theme' or $substr5 == 'files') {
            return ("/$page");
        } else {
            if ($page == 'index') {
                return ("http://{$_SERVER['HTTP_HOST']}");
            }
            $len = strlen($page);
            if (substr($page, $len - 1) == '/') {
                $page = substr($page, 0, $len - 1);
            }
            if ($encode) {
                $page = urlencode($page);
                $page = str_replace('%2F', '/', $page);
            }
            $page = str_replace(' ', '-', $page);
            return ("http://{$_SERVER['HTTP_HOST']}/$page{$options['rewtite_suffix']}");
        }
    } elseif (url_type == 'i') {
        if (strpos($page, 'theme/') !== false or strpos($page, 'files/') !== false) {
            return ("http://$site_url[0]/$page");
        } else {
            $page = str_replace(array(
                '/',
                ' '
            ), array(
                '|',
                '-'
            ), $page);
            if ($encode) {
                $page = urlencode($page);
            }
            return ("http://$site_url[0]/?i=$page");
        }
    } elseif (url_type == 'get') {
        if (strpos($page, 'theme/') !== false or strpos($page, 'files/') !== false) {
            return ("http://$site_url[0]/$page");
        } else {
            $page = str_replace(array(
                '/',
                ' '
            ), array(
                '&',
                '-'
            ), $page);
            if ($encode) {
                $page = urlencode($page);
            }
            return ("http://$site_url[0]/?$page");
        }
    }
}

function stats($col)
{
    global $sql;
    $temp = strtotime('today');
    $sql->where("day", $temp);
    if ($sql->getValue("stats_num", "count(*)")) {
        $sql->where("day", $temp);
        $sql->update('stats_num', array(
            $col => $sql->inc()
        ));
    } else {
        $temp1 = strtotime('yesterday');
        $sql->where("date", $temp1, '>');
        $sql->where("date", $temp, '<');
        $nummessage = $sql->getValue("message", "count(*)");
        $sql->where("date", $temp1, '>');
        $sql->where("date", $temp, '<');
        $nummessage += $sql->getValue("message_archive", "count(*)");
        $sql->where("day", $temp1);
        $sql->update("stats_num", array(
            'msgs' => $nummessage
        ));
        $sql->insert("stats_num", array(
            'day' => $temp,
            $col => 1
        ));
    }
    $sql->where("day", 0);
    $sql->update('stats_num', array(
        $col => $sql->inc()
    ));
    unset($user, $temp);
}

function session($n, $v = null)
{
    if ($v == null) {
        if (isset($_SESSION['chat_' . $n])) {
            return ($_SESSION['chat_' . $n]);
        } else {
            return false;
        }
    } else {
        if ($v == 'array()') {
            $v = array();
        }
        $_SESSION['chat_' . $n] = $v;
    }
}

function sessionunset($n)
{
    unset($_SESSION['chat_' . $n]);
}

function checkipbann($ip, $index)
{
    global $checkip;
    $ipban = explode('.', $ip);
    $myip  = explode('.', ip());
    if ($ipban[$index] == '*') {
        $checkip[$index] = true;
    } else {
        if ($ipban[$index] == $myip[$index]) {
            $checkip[$index] = true;
        }
    }
}

/*function checkbadname($name, $re)
{
    global $sql,$options;
    if ($re == 'login') {
        $bad = false;
           include ('inc/badnames.php');
            if( $options['intelligently_badname'] == 'true' ){
              $names = Message::intelligently($name);
               } else { 
                  $names = $name;
               }
		foreach($badnames as $badname){
			if ((strpos($names, $badname['word']) !== false) && $badname['canlogin'] == '0' ) {
                            $bad = true;
                        }
		}
        return $bad;
    }
}*/




/**
 * ====================================================================
 *  سیستم ضد دور زدن فیلتر نام کاربری (Anti-Bypass Badname Filter)
 *  سازگار با PHP 5.6+
 * ====================================================================
 *
 * این فایل شامل:
 *  1) normalizeCore()          -> یکسان‌سازی حروف عربی/فارسی، اعداد، حذف نامرئی‌ها
 *  2) normalizeLeet()          -> تبدیل جایگزین‌های عددی/نمادین به حروف اصلی (fu(k, k1r ...)
 *  3) buildCompareVariants()   -> تولید نسخه‌های مختلف یک رشته برای مقایسه امن
 *  4) checkbadname()           -> فانکشن اصلی که جایگزین نسخه‌ی قبلی‌ات میشه
 *
 * نکته مهم: خروجی این توابع فقط برای «مقایسه» استفاده میشه.
 * نام واقعی کاربر که ذخیره/نمایش داده میشه دست‌نخورده باقی می‌مونه.
 */
 
// اطمینان از تنظیم بودن encoding داخلی (فقط یک‌بار در ابتدای اجرای اسکریپت لازم است،
// اما اینجا هم گذاشتمش که این فایل به تنهایی هم درست کار کند)
if (function_exists('mb_internal_encoding')) {
    mb_internal_encoding('UTF-8');
}
 
/**
 * یکسان‌سازی هسته‌ای: حروف عربی/فارسی، اعداد، حذف کاراکترهای نامرئی و تطویل
 */
function normalizeCore($text)
{
    if ($text === null || $text === '') {
        return '';
    }
 
    // 0) اطمینان از UTF-8 معتبر بودن رشته (جلوگیری از کرش mb_* روی encoding خراب)
    if (function_exists('mb_check_encoding') && !mb_check_encoding($text, 'UTF-8')) {
        $detected = function_exists('mb_detect_encoding') ? mb_detect_encoding($text) : 'UTF-8';
        $text = @mb_convert_encoding($text, 'UTF-8', $detected ? $detected : 'UTF-8');
    }
 
    // 1) حذف کاراکترهای نامرئی / صفر-عرض: نیم‌فاصله واقعی (ZWNJ)، ZWJ، ZWSP، BOM، RLM/LRM
    //    این‌ها معمولاً برای شکستن یک کلمه‌ی بد به دو تکه‌ی «به‌ظاهر جدا» استفاده می‌شن
    $invisibles = array(
        "\xE2\x80\x8B", // U+200B ZERO WIDTH SPACE
        "\xE2\x80\x8C", // U+200C ZERO WIDTH NON-JOINER (نیم‌فاصله)
        "\xE2\x80\x8D", // U+200D ZERO WIDTH JOINER
        "\xEF\xBB\xBF", // U+FEFF BOM
        "\xE2\x80\x8E", // U+200E LRM
        "\xE2\x80\x8F", // U+200F RLM
        "\xE2\x81\xA0", // U+2060 WORD JOINER
    );
    $text = str_replace($invisibles, '', $text);
 
    // 2) حذف تطویل/کشیده عربی-فارسی (ـ / U+0640) حتی اگر تکرار شده باشه (ـــــ)
    $text = preg_replace('/\x{0640}+/u', '', $text);
 
    // 3) حذف اعراب/حرکات عربی-فارسی (فتحه، ضمه، کسره، تشدید، سکون، تنوین و ...)
    $text = preg_replace('/[\x{0610}-\x{061A}\x{064B}-\x{065F}\x{0670}\x{06D6}-\x{06ED}]/u', '', $text);
 
    // 4) یکسان‌سازی حروف عربی با معادل رایج فارسی
    //    (شامل نکته‌ی خواسته شده: آ و ا هر دو معادل یکدیگر در نظر گرفته می‌شوند)
    $map = array(
        "\xD9\x8A" => "\xDB\x8C", // ي عربی      -> ی فارسی   (U+064A -> U+06CC)
        "\xD9\x83" => "\xDA\xA9", // ك عربی      -> ک فارسی   (U+0643 -> U+06A9)
        "\xD8\xA3" => "\xD8\xA7", // أ            -> ا
        "\xD8\xA5" => "\xD8\xA7", // إ            -> ا
        "\xD8\xA2" => "\xD8\xA7", // آ            -> ا
        "\xD9\x89" => "\xDB\x8C", // ى (الف مقصوره) -> ی
        "\xD8\xA9" => "\xD9\x87", // ة            -> ه
        "\xDA\xAF\xD9\x86" => "\xDA\xAF\xD9\x86", // (نگه‌داشتن گ بدون تغییر، صرفاً یادداشت)
    );
    $text = strtr($text, $map);
 
    // 5) یکسان‌سازی ارقام فارسی/عربی به لاتین
    //    (چون بعضی از کلمات بدِ خودِ سایت، مثل «کو3»، از عدد به‌جای حرف استفاده کرده‌اند،
    //     عمداً اعداد را در این مرحله حذف نمی‌کنیم؛ فقط یکسان‌سازی می‌کنیم)
    $digitsFaAr = array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹', '٠','١','٢','٣','٤','٥','٦','٧','٨','٩');
    $digitsEn   = array('0','1','2','3','4','5','6','7','8','9', '0','1','2','3','4','5','6','7','8','9');
    $text = str_replace($digitsFaAr, $digitsEn, $text);
 
    // 6) حذف جداکننده‌های رایج بین حروف که برای دور زدن استفاده می‌شن:
    //    فاصله، خط‌تیره، آندرلاین، نقطه، اسلش، کاما
    //    (توجه: چون کلمات بدِ چندبخشی مثل «مادر جنده» هم از همین تابع عبور می‌کنند،
    //     حذف فاصله مشکلی ایجاد نمی‌کند و مقایسه هنوز درست خواهد بود)
    $text = preg_replace('/[\s\-_\.\/\\\\,]+/u', '', $text);
 
    // 7) کوچک‌سازی حروف لاتین
    $text = mb_strtolower($text, 'UTF-8');
 
    return $text;
}
 
/**
 * تبدیل leetspeak و کاراکترهای هم‌شکل (homoglyph) لاتین/سیریلیک/یونانی
 * این تابع باید بعد از normalizeCore و به صورت جداگانه صدا زده شود
 * (چون در مقایسه‌ی سخت‌گیرانه‌تر استفاده می‌شود، نه همیشه)
 */
function normalizeLeet($text)
{
    if ($text === '') {
        return '';
    }
 
    // یکسان‌سازی حروف سیریلیک/یونانی هم‌شکل با لاتین (حملات homoglyph رایج)
    $homoglyphMap = array(
        "\xD0\xB0" => "a", // а سیریلیک
        "\xD0\xB5" => "e", // е سیریلیک
        "\xD0\xBE" => "o", // о سیریلیک
        "\xD1\x80" => "p", // р سیریلیک
        "\xD1\x81" => "c", // с سیریلیک
        "\xD1\x83" => "y", // у سیریلیک
        "\xD1\x85" => "x", // х سیریلیک
        "\xCE\xBF" => "o", // ο یونانی
        "\xCE\xB1" => "a", // α یونانی
    );
    $text = strtr($text, $homoglyphMap);
 
    // leetspeak چندکاراکتری باید قبل از تک‌کاراکتری جایگزین شود
    $multiCharLeet = array(
        '|_|' => 'u',
        '|)'  => 'd',
        '|3'  => 'b',
        'vv'  => 'w',
        'ph'  => 'f',
    );
    $text = strtr($text, $multiCharLeet);
 
    // leetspeak تک‌کاراکتری (فقط حروف لاتین را تحت تاثیر قرار می‌دهد)
    $singleCharLeet = array(
        '4' => 'a',
        '@' => 'a',
        '3' => 'e',
        '€' => 'e',
        '1' => 'i',
        '!' => 'i',
        '|' => 'i',
        '0' => 'o',
        '$' => 's',
        '5' => 's',
        '7' => 't',
        '+' => 't',
        '9' => 'g',
        '8' => 'b',
    );
    $text = strtr($text, $singleCharLeet);
 
    return $text;
}
 
/**
 * جمع کردن حروف تکراری متوالی به یک حرف
 * (fuuuuck -> fuck ، کککک -> ک)
 * این کار روی نسخه‌ی مقایسه انجام می‌شود، نه روی نام اصلی کاربر
 */
function collapseRepeats($text)
{
    if ($text === '') {
        return '';
    }
    return preg_replace('/(.)\1+/u', '$1', $text);
}
 
/**
 * تولید تمام نسخه‌های مقایسه‌ای یک رشته (نام کاربر یا کلمه‌ی بد)
 * خروجی: آرایه‌ای از رشته‌های یکتا برای چک کردن
 */
function buildCompareVariants($rawText)
{
    $core = normalizeCore($rawText);
 
    $variants = array();
 
    // نسخه 1: فقط یکسان‌سازی هسته‌ای + جمع تکرار (بدون leet) — امن‌ترین حالت
    $v1 = collapseRepeats($core);
    if ($v1 !== '') {
        $variants[$v1] = true;
    }
 
    // نسخه 2: هسته + leet + جمع تکرار — برای گرفتن دور زدن با عدد/نماد
    $v2 = collapseRepeats(normalizeLeet($core));
    if ($v2 !== '') {
        $variants[$v2] = true;
    }
 
    return array_keys($variants);
}
 
/**
 * فانکشن اصلی — جایگزین نسخه‌ی قبلی شما
 *
 * @param string $name  نام کاربری برای بررسی
 * @param string $re    نوع بررسی (فعلاً فقط 'login' پشتیبانی می‌شود)
 * @return bool         true اگر نام مسدود باشد
 */
function checkbadname($name, $re)
{
    global $sql, $options;
 
    if ($re !== 'login') {
        return false;
    }
 
    // بارگذاری لیست کلمات بد
    // توجه: مسیر را نسبت به ریشه‌ی پروژه تنظیم کن، نه نسبت به این فایل،
    // چون این include اصلی پروژه‌ی تو (inc/badnames.php) قبلاً هم به همین شکل نسبی بوده.
    // اگر این تابع را از صفحاتی با عمق پوشه‌ی متفاوت صدا می‌زنی و با ارور "failed to open stream"
    // مواجه شدی، خط زیر را با مسیر مطلق پروژه‌ات جایگزین کن، مثلاً:
    // include($_SERVER['DOCUMENT_ROOT'] . '/inc/badnames.php');
    include('inc/badnames.php');
    if (!isset($badnames) || !is_array($badnames) || empty($badnames)) {
        return false;
    }
 
    // اعمال پردازش هوشمند در صورت فعال بودن (منطق قبلی شما حفظ شده)
    $rawName = $name;
    if (isset($options['intelligently_badname']) && $options['intelligently_badname'] == 'true'
        && class_exists('Message') && method_exists('Message', 'intelligently')) {
        $rawName = Message::intelligently($name);
    }
 
    // تولید نسخه‌های مختلف نام کاربر برای مقایسه
    $nameVariants = buildCompareVariants($rawName);
    if (empty($nameVariants)) {
        return false;
    }
 
    // کش استاتیک برای جلوگیری از نرمال‌سازی مجدد کلمات بد در فراخوانی‌های بعدی
    // همان request (چون include هر بار اجرا می‌شود ولی محتوای فایل معمولاً ثابت است)
    static $badwordVariantsCache = null;
    static $cacheKey = null;
 
    // اگر تعداد کلمات فرق کرده (یعنی فایل عوض شده)، کش را دوباره بساز
    $currentKey = count($badnames);
    if ($badwordVariantsCache === null || $cacheKey !== $currentKey) {
        $badwordVariantsCache = array();
        foreach ($badnames as $index => $badname) {
            if (!isset($badname['word']) || !isset($badname['canlogin'])) {
                continue;
            }
            // فقط کلماتی که واقعاً جلوی ورود را می‌گیرند لازم است کش شوند
            if ($badname['canlogin'] != '0') {
                continue;
            }
            $variants = buildCompareVariants($badname['word']);
            if (!empty($variants)) {
                $badwordVariantsCache[$index] = $variants;
            }
        }
        $cacheKey = $currentKey;
    }
 
    // مقایسه‌ی نهایی: هر نسخه از نام با هر نسخه از هر کلمه‌ی بد
    foreach ($badwordVariantsCache as $index => $wordVariants) {
        foreach ($wordVariants as $wordVariant) {
            if ($wordVariant === '') {
                continue;
            }
            foreach ($nameVariants as $nameVariant) {
                if (mb_strpos($nameVariant, $wordVariant, 0, 'UTF-8') !== false) {
                    return true;
                }
            }
        }
    }
 
    return false;
}




//-----=================end of bad name function

function getchmod($path)
{
    return substr(sprintf('%o', fileperms($path)), -4);
}

function permissions($path, $defualt, $fix = true)
{
    if (substr(php_uname(), 0, 7) == "Windows") {
        return 'true';
    }
    $chmod  = getchmod($path);
    $return = '';
    if ($chmod != $defualt) {
        $return = 'false';
        if ($fix) {
            chmod($path, (int) $defualt);
            if (getchmod($path) == $defualt) {
                $return .= 'fix';
            }
        }
    } else {
        $return = 'true';
    }
    return $return;
}

function listDirectory($dir, $dirs = true, $subdirs = true)
{
    $result = array();
    if (substr($dir, strlen($dir) - 1) != '/') {
        $dir .= '/';
    }
    $root = scandir($dir);
    foreach ($root as $value) {
        if ($value === '.' || $value === '..') {
            continue;
        }
        if (is_file("$dir$value")) {
            $result[] = "$dir$value";
            continue;
        }
        if ($dirs) {
            if (is_dir("$dir$value")) {
                $result[] = "$dir$value/";
            }
        }
        if ($subdirs) {
            foreach (listDirectory("$dir$value/", $dirs, $subdirs) as $value) {
                $result[] = $value;
            }
        }
    }
    return $result;
}

function validate_ip($ip)
{
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
        return false;
    }
    return true;
}

function ip()
{
    $ip_keys = array(
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR'
    );
    foreach ($ip_keys as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                // trim for safety measures
                $ip = trim($ip);
                // attempt to validate IP
                if (validate_ip($ip)) {
                    return $ip;
                }
            }
        }
    }
    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
}

function shutdown($a)
{
    db::close();
}

function token()
{
    $token = md5(sha1("TarhPaRdaZ_ToOKeN" . md5(base64_encode(str_rot13(date("U-Y-M-D")))) . "_" . time() . "_" . md5(base64_encode(str_rot13(date("U-Y-M-D"))))));
    session("token", $token);
    return $token;
}

function robot()
{
    $robot = md5(sha1("TarhPaRdaZ_ToOKeN" . md5(base64_encode(str_rot13(date("U-Y-M-D")))) . "_" . time() . "_" . md5(base64_encode(str_rot13(date("U-Y-M-D"))))));
    session("robot", $robot);
    return $robot;
}

function token2()
{
    $now_token = $_SESSION["token2"];
    if (!isset($now_token))
        $now_token = '';
    if ($now_token == "") {
        $token2             = md5(sha1("TarhPaRdaZ_ToOKeN2" . md5(base64_encode(str_rot13(date("U-Y-M-D22")))) . "_" . time() . "_" . md5(base64_encode(str_rot13(date("U-Y-M-D22"))))));
        $_SESSION["token2"] = $token2;
        return $token2;
    } else {
        return $now_token;
    }
}

function allow_cf($to, $into = 1, $system = 1)
{
    $check = $into == 'pm' || $into == 'main' ? false : true;
    $sysid = $system == '-2' ? true : false;
    if ($sysid) {
        if (db::num('cf_users', "`creater`='$to' AND `joiner`='" . session('id') . "'") > 0) {
            return true;
        } else {
            return false;
        }
    }
    if ($check) {
        if (db::num('cf_users', "`creater`='$to' AND `joiner`='" . session('id') . "'") > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function check_session($txt = false)
{
    $txt = $txt ? $txt : 'login';
    if (!session('id')) {
        die($txt);
    }
    return true;
}

function RedError($txt, $color = false)
{
    $colors = $color ? $color : 'red';
    return '<center><font color="' . $colors . '"><b>' . $txt . '</b></font></center>';
}

function ArrayUnsetValue($array, $delval)
{
    $x     = 0;
    $array = array_values($array);
    foreach ($array as $val) {
        if ($val == $delval) {
            unset($array[$x]);
        }
        $x++;
    }
    return $array;
}

function remove_utf8_bom($text)
{
    $bom  = pack('H*', 'EFBBBF');
    $text = preg_replace("/^$bom/", '', $text);
    return $text;
}

function TokenIsValid($token)
{
    return $token == session('token') ? true : false;
}

function CheckAdminType($type)
{
    global $usertype;
    $admin   = (int) safe::number($usertype[$type]['admin']);
    $meadmin = (int) safe::number(session('admin'));
    /*
    if($meadmin == '1' && $admin != '1')return true;
    if($meadmin == '2' && $admin == '0')return true;
    if($meadmin == '3' && $admin != '1' && $admin != '3')return true;
    */
    if ($meadmin != '0' && $admin == '0') {
        return true;
    } else if ($meadmin != 0 && $admin != 0 && $admin <= $meadmin) {
        return false;
    } elseif ($admin > $meadmin) {
        return true;
    } else {
        return false;
    }
    return false;
}

function Access2Manage($id, $text = false, $table = 'onlines')
{
    global $sql;
    $sql->where("id", $id);
    $type = $sql->getValue("users", 'type');
    $text = !$text ? 'شما اجازه ویرایش این کاربر را ندارید' : $text;
    !CheckAdminType($type) ? die($text) : '';
}

function MiniAvatarDir()
{
    global $options;
    return str_replace(array(
        '*',
        'x'
    ), '-', $options['thumbavatarSize']);
}
function time_ago($time)
{
    $etime = time() - $time;
    if ($etime < 1)
        return 'هم اکنون';
    
    $a = array(
        365 * 24 * 60 * 60 => 'year',
        30 * 24 * 60 * 60 => 'month',
        24 * 60 * 60 => 'day',
        60 * 60 => 'hour',
        60 => 'minute',
        1 => 'second'
    );
    $b = array(
        'year' => 'سال',
        'month' => 'ماه',
        'day' => 'روز',
        'hour' => 'ساعت',
        'minute' => 'دقیقه',
        'second' => 'ثانیه'
    );
    
    foreach ($a as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            return round($d) . ' ' . $b[$str] . ' قبل';
        }
    }
}
function rm_rf($path)
{
    if (is_dir($path) && is_writable($path)) {
        $dp = opendir($path);
        while ($ent = readdir($dp)) {
            if ($ent == '.' || $ent == '..') {
                continue;
            }
            $file = $path . DIRECTORY_SEPARATOR . $ent;
            if (\is_dir($file)) {
                rm_rf($file);
            } elseif (is_writable($file)) {
                unlink($file);
            } else {
                echo $file . "is not writable and cannot be removed. Please fix the permission or select a new path.\n";
            }
        }
        closedir($dp);
        return rmdir($path);
    } else {
        return @unlink($path);
    }
}
function iptocountry($ip)
{
    $numbers = preg_split("/\./", $ip);
    include("pages/ips/" . $numbers[0] . ".php");
    $code = ($numbers[0] * 16777216) + ($numbers[1] * 65536) + ($numbers[2] * 256) + ($numbers[3]);
    foreach ($ranges as $key => $value) {
        if ($key <= $code) {
            if ($ranges[$key][0] >= $code) {
                $two_letter_country_code = $ranges[$key][1];
                break;
            }
        }
    }
    if ($two_letter_country_code == "") {
        $two_letter_country_code = "unknown";
    }
    return $two_letter_country_code;
}
function GetTokenPassword($length = 10)
{
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $len   = strlen($chars);
    $pw    = '';
    for ($i = 0; $i < $length; $i++) {
        $pw .= substr($chars, rand(0, $len - 1), 1);
    }
    $pw = str_shuffle($pw);
    return ($pw);
}


function is_mobile_detect()
{
    $agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";
    if (stripos($agent, 'Android') !== false || stripos($agent, 'iPad') !== false || stripos($agent, 'iPhone') !== false || stripos($agent, 'iPod') !== false || preg_match("/Nokia([^\/]+)\/([^ SP]+)/i", $agent) || stripos($agent, 'opera mini') !== false || (stripos($agent, 'mspie') !== false || stripos($agent, 'pocket') !== false) || stripos($agent, 'blackberry') !== false) {
        return true;
    }

    return false;
}

function getBrowser()
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname   = 'Unknown';
    if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
        $bname = 'IE';
        $ub    = "MSIE";
    } elseif (preg_match('/Trident/i', $u_agent)) {
        $bname = 'IE';
        $ub    = "rv";
    } elseif (preg_match('/Firefox/i', $u_agent)) {
        $bname = 'Firefox';
        $ub    = "Firefox";
    } elseif (preg_match('/Chrome/i', $u_agent)) {
        $bname = 'Chrome';
        $ub    = "Chrome";
    } elseif (preg_match('/Safari/i', $u_agent)) {
        $bname = 'Safari';
        $ub    = "Safari";
    } elseif (preg_match('/Opera/i', $u_agent)) {
        $bname = 'Opera';
        $ub    = "Opera";
    }
    return array(
        'bname' => $bname
    );
}
function modpassword($pass)
{
    $pattern      = '/(?=.*\d)(?=.*[a-z])/';
    $specialChars = array(
        '?',
        '_',
        '!',
        '@',
        '$',
        '*',
        '%',
        '<',
        '>',
        '[',
        ']',
        '{',
        '}',
        '|',
        ':',
        ';',
        ','
    );
    if (preg_match($pattern, $pass)) {
        foreach ($specialChars as $specialChar) {
            if (strstr($pass, $specialChar) !== false) {
                $test = true;
            }
        }
        return $test;
    }
}
function isJson($string)
{
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}
function rrobot()
{
    if (!session('mositoken') || !session('mosi1token')) {
        $robot = md5(sha1(GetTokenPassword() . md5(md5(md5(base64_encode(str_rot13(date("U-Y-M-D22")))) . "_" . time() . rand() . "_" . md5(base64_encode(str_rot13(date("U-Y-M-D22"))))))));
        session('mositoken', $robot);
        session('mosi1token', $robot);
        return $robot;
    } else {
        return session('mositoken');
    }
}
function mrobot()
{
    if (!session('mosi2')) {
        $robot = md5(sha1(GetTokenPassword(15) . md5(md5(md5(base64_encode(str_rot13(date("U-Y-M-D22")))) . "_" . time() . rand() . "_" . md5(base64_encode(str_rot13(date("U-Y-M-D22"))))))));
        session('mosi2', $robot);
        return $robot;
    } else {
        return session('mosi2');
    }
}
function trobot()
{
    if (!session('mosirobot')) {
        $robot = md5(sha1(GetTokenPassword(20) . md5(md5(md5(base64_encode(str_rot13(date("U-Y-M-D22")))) . "_" . time() . rand() . "_" . md5(base64_encode(str_rot13(date("U-Y-M-D22"))))))));
        session('mosirobot', $robot);
        return $robot;
    } else {
        return session('mosirobot');
    }
}
function rtoken()
{
    if (!session('mosi10d')) {
        $robot = md5(md5(sha1(GetTokenPassword(30) . md5(md5(md5(base64_encode(str_rot13(date("U-Y-M-D22")))) . "_" . time() . rand() . "_" . md5(base64_encode(str_rot13(date("U-Y-M-D22")))))))));
        session('mosi10d', $robot);
        return $robot;
    } else {
        return session('mosi10d');
    }
}
function gtoken()
{
    if (!session('mosisss')) {
        $robot = md5(md5(sha1(GetTokenPassword(40) . md5(md5(md5(base64_encode(str_rot13(date("U-Y-M-D22")))) . "_" . time() . rand() . "_" . md5(base64_encode(str_rot13(date("U-Y-M-D22")))))))));
        session('mosisss', $robot);
        return $robot;
    } else {
        return session('mosisss');
    }
}
token2();
rrobot();
mrobot();
trobot();
rtoken();
gtoken();
?>