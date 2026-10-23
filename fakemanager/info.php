<?php

$time = time();

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

$sql = "INSERT INTO ahyir_message
(id, user, username, color, fontname, fontsize, room, text, date, `to`, `into`, type)
VALUES
(NULL, '7794', 'لیانا', 'FF3399', 'myriadarabicregular', '16px', '1',
'<b>سلام عشقام</b>', '$time', '-1', 'main', '0')";

$go = mysqli_query($con, $sql);

if (!$go) {
    echo "Query Failed: " . mysqli_error($con);
} else {
    echo "true";
}

mysqli_close($con);
?>