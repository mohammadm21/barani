<?php

// نسخه موبایل — سیگنال «در حال تایپ کردن» (is typing...).
// هر کاربرِ در حال تایپ، این را هر چند ثانیه می‌زند تا ردیفِ typing تازه بماند.
header::tojson();
$id = (int) session('id');
if (!$id) {
    echo json_encode(array('status' => 'error', 'e' => 'login'));
    exit;
}
$target = safe::string(isset($_POST['to']) ? $_POST['to'] : '0');
if ($target === '') {
    $target = '0';
}
try {
    // خوددرمانی: اگر جدول typing هنوز ساخته نشده بود، همین‌جا بساز که قابلیت بدونِ
    // اعمالِ migration جداگانه هم کار کند.
    if (!$sql->tableExists('typing')) {
        $make = "CREATE TABLE IF NOT EXISTS `ahyir_typing` (`user_id` int unsigned NOT NULL DEFAULT 0, `target` varchar(32) DEFAULT '0', `time` int unsigned DEFAULT 0, PRIMARY KEY (`user_id`))";
        $sql->rawQuery($make);
    }
} catch (Exception $e) {
    // در صورت خطا، بی‌صدا رد شو تا upsert بعدی تصمیم بگیرد
}
try {
    $sql->where('user_id', $id);
    $existing = $sql->getOne('typing', array('user_id', 'time'));
    $now = time();
    // جلوگیری از فلاود: فقط اگر بیش از ۲ ثانیه از آخرین پینگ گذشته باشد به‌روزرسانی کن
    if ($existing && ($now - (int) $existing['time']) < 2) {
        echo json_encode(array('status' => 'ok', 't' => $now));
        exit;
    }
    $sql->where('user_id', $id);
    if ($existing) {
        $sql->where('user_id', $id)->update('typing', array('target' => $target, 'time' => $now));
    } else {
        $sql->insert('typing', array('user_id' => $id, 'target' => $target, 'time' => $now));
    }
    // پاک‌سازیِ ردیف‌های کهنه (قدیمی‌تر از ۳۰ ثانیه) تا جدول ورم نکند
    $sql->where('time', $now - 30, '<')->delete('typing');
    echo json_encode(array('status' => 'ok', 't' => $now));
} catch (Exception $e) {
    // جدول typing هنوز ساخته نشده؛ بی‌صدا رد شو
    echo json_encode(array('status' => 'ok', 't' => 0));
}
