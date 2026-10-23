<?php

check_session();

$myid = session('id');

if (Users::guestType(session('type'))) {
    die(RedError('لطفا عضو شوید تا پیام های افلاین خود را ببینید'));
}

$sql->where('userid', $myid);
$num = $sql->getValue('offline_message', 'count(*)');

if (isset($_REQUEST['profile']) && empty($num)) {
    die('پیام آفلاینی برای پاک کردن وجود ندارد');
}

if (empty($num)) {
    die(RedError('پیام آفلاینی وجود ندارد'));
}

$del = isset($_REQUEST['del']) ? $_REQUEST['del'] : '';

if ($del === 'true') {

    $sql->where('userid', $myid);
    $sql->delete('offline_message');

    if (isset($_REQUEST['profile'])) {
        exit('پاک شد');
    }

    header::Go(url('ajax/chat.offline.message'));
    exit();

} elseif (is_numeric($del)) {

    $id = (int)safe::number($del);

    if ($id <= 0) {
        header::Go(url('ajax/chat.offline.message'));
        exit();
    }

    $sql->where('id', $id);
    $sql->where('userid', $myid);
    $sql->delete('offline_message');

    header::Go(url('ajax/chat.offline.message'));
    exit();
}

echo '<center dir="rtl"><b><a style="text-decoration:none;" href="chat.offline.message?del=true">پاک کردن پیام ها</a></b></center><br>';

echo '<center dir="rtl">
<form method="post">
<input type="text" name="search" value="">
<input type="submit" name="searcher" value="سرچ کن">
</form>
</center><br>';

$pattern = isset($_POST['search'])
    ? safe::string($_POST['search'])
    : '';

$sql->join('users u', 'u.id=o.sender_id', 'LEFT');
$sql->where('userid', $myid);
$sql->orderBy('id', 'DESC');

$messages = $sql->get(
    'offline_message o',
    null,
    array('o.*', 'u.name', 'u.type')
);

foreach ($messages as $row) {

    $messagesText = explode(',', $row['text']);

    foreach ($messagesText as $text) {

        $text = trim($text);

        if ($text === '') {
            continue;
        }

        if (isset($_POST['searcher']) && $pattern != '') {

            if (@preg_match('/' . preg_quote($pattern, '/') . '/i', $text) == 0) {
                continue;
            }
        }

        echo "<div dir='rtl'>";

        echo "<a href='" . url('profile/' . $row['sender_id']) . "' target='_blank' title='پروفایل'>";
        echo "<img src='" . theme::url('theme/images/chat/chat.pr.png') . "' border='0'>";
        echo "</a> ";

        echo "<img src='/" . $usertype[$row['type']]['icon'] . "'> ";

        echo $row['name'] . ' : ' . $text;

        echo " <a href='?del=" . $row['id'] . "'>[حذف]</a><br>";

        echo jdate::start('Y-m-d ساعت H:i:s', $row['date']);

        echo "</div>";
    }

    echo '<br>';
}