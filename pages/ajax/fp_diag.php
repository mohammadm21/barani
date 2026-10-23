<?php
/**
 * fp_diag.php — تشخیصیِ اثر انگشت (برای ردیابیِ چرا ضبطِ فینگرپرینت کار نمی‌کند).
 * از طریقِ همان مسیرِ ajax لود می‌شود تا bootstrap (session, $sql, $options) درست باشد.
 * فقط برای دیباگ؛ بعد از رفع مشکل از سرور حذفش کن.
 * فراخوانی: با POST یا GET. مستقیماً خروجی می‌دهد (بدون JSON).
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "<pre style='direction:ltr;text-align:left'>";
echo "===== fp_diag =====" . "\n\n";

echo "--- PHP ---\n";
echo "php-version: " . PHP_VERSION . "\n";
echo "display_errors: " . ini_get('display_errors') . "\n";
echo "error_log: " . ini_get('error_log') . "\n\n";

echo "--- method / input ---\n";
echo "REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'] . "\n";
$raw = @file_get_contents('php://input');
echo "raw input (length ".strlen($raw)."): " . var_export($raw, true) . "\n\n";

echo "--- bootstrap globals ---\n";
echo "class_exists('MysqliDb'): " . (class_exists('MysqliDb') ? 'yes' : 'NO') . "\n";
global $sql, $options;
echo "isset(GLOBALS['sql']): " . (isset($GLOBALS['sql']) ? 'yes' : 'NO') . "\n";
echo "isset(GLOBALS['options']): " . (isset($GLOBALS['options']) ? 'yes' : 'NO') . "\n";
if (isset($GLOBALS['options'])) {
    echo "options is type: " . gettype($GLOBALS['options']) . "\n";
    echo "count(options): " . count($GLOBALS['options']) . "\n";
    $fe = isset($GLOBALS['options']['fillprint_enabled']) ? $GLOBALS['options']['fillprint_enabled'] : 'KEY-MISSING';
    echo "options['fillprint_enabled']: " . var_export($fe, true) . "\n";
}
echo "function_exists('session'): " . (function_exists('session') ? 'yes' : 'NO') . "\n";
echo "function_exists('ip'): " . (function_exists('ip') ? 'yes' : 'NO') . "\n";
if (function_exists('session')) {
    echo "session('id'): " . var_export(session('id'), true) . "\n";
}
echo "\n";

echo "--- DB connectivity / schema ---\n";
if (isset($GLOBALS['sql'])) {
    try {
        $q = $GLOBALS['sql']->rawQuery("SELECT 1 AS ok");
        echo "SELECT 1: " . var_export($q, true) . "\n";
    } catch (\Throwable $e) {
        echo "SELECT 1 FATAL: " . $e->getMessage() . "\n";
    }
    foreach (array('fingerprints', 'fp_capture', 'banns', 'admin_alerts') as $t) {
        try {
            $exists = $GLOBALS['sql']->tableExists($t);
            echo "tableExists('$t'): " . ($exists ? 'yes' : 'NO') . "\n";
            if ($exists) {
                $cols = $GLOBALS['sql']->rawQuery("SHOW COLUMNS FROM `ahyir_$t`");
                $names = array();
                foreach ($cols as $c) { $names[] = $c['Field']; }
                echo "   cols: " . implode(', ', $names) . "\n";
                $isAb = in_array('is_confirmed_abuser', $names) ? 'Y' : 'n';
                $dt  = in_array('device_token', $names) ? 'Y' : 'n';
                $ct  = in_array('capture_token', $names) ? 'Y' : 'n';
                echo "   [is_confirmed_abuser=$isAb] [device_token=$dt] [capture_token=$ct]\n";
            }
        } catch (\Throwable $e) {
            echo "tableExists('$t') FATAL: " . $e->getMessage() . "\n";
        }
    }
}
echo "</pre>";
