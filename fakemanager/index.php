<?php
mysqli_report(MYSQLI_REPORT_OFF);

$con = mysqli_connect(
    "localhost",
    "mybaran_barani",
    "hXFZgMDs6ehsZEfzsCMS",
    "mybaran_barani"
);

if (!$con) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

mysqli_set_charset($con, "utf8");

$result = mysqli_query(
    $con,
    "SELECT id, ip, name, type, gender
     FROM ahyir_onlines
     WHERE ip = 0 AND type = 1"
);

$boyFile  = __DIR__ . "/boy.txt";
$girlFile = __DIR__ . "/girl.txt";

if (!file_exists($boyFile) || !file_exists($girlFile)) {
    die("boy.txt or girl.txt not found.");
}

$boys  = file($boyFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$girls = file($girlFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

while ($res = mysqli_fetch_assoc($result)) {

    $id = (int)$res['id'];
    $gender = (int)$res['gender'];

    if ($gender == 1) {
        $name = trim($boys[array_rand($boys)]);
    } else {
        $name = trim($girls[array_rand($girls)]);
    }

    $sql = "UPDATE ahyir_onlines
            SET name='".mysqli_real_escape_string($con,$name)."'
            WHERE id=$id";

    if (!mysqli_query($con,$sql)) {
        echo "<script>alert(".json_encode(mysqli_error($con)).");</script>";
        exit;
    }

    if (mysqli_affected_rows($con) == 0) {
        echo "<script>alert('هیچ ردیفی آپدیت نشد. id=".$id."');</script>";
    }
}
mysqli_close($con);
echo "<script>
alert('عملیات با موفقیت انجام شد');
window.history.back();
</script>";


exit;
?>