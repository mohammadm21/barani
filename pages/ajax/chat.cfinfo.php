<?php

check_session();

$delid = !isset($_GET['del']) ? false : safe::number($_GET['del']);
$silent = !isset($_GET['silent']) ? false : safe::number($_GET['silent']);
$delcf = isset($_GET['delcf']) ?  true : false;
$cfid = isset($_GET['cfid']) ? safe::number($_GET['cfid']) : '';


if ($delcf) {
    $sql->where("creater", session('id'))->delete('cf_users');
    die(RedError('تمامی کاربران حذف شدند'));
}

if (!$sql->where("creater", $cfid)->has('cf_users') AND $cfid != session('id')) {
    die(RedError('این اتاق وجود ندارد'));
}

if ($silent AND $cfid == session('id')) {
    $sql->join('users u', 'u.id = c.joiner', 'LEFT');
    $sql->where("creater", $cfid);
    $sql->where("joiner", $silent);
    $g = $sql->getOne('cf_users c', array('c.silent','u.name'));
    if ($g['silent'] == '1') {
        $g['silent'] = '0';
    } else {
        $g['silent'] = '1';
    }
    $sysmsg = $g['silent'] == '1' ? $g['name'] . ' توسط مدیر اتاق ساکت شد' : $g['name'] . ' توسط مدیر اتاق از سکوت آزاد شد';
    $sql->where("creater", $cfid);
    $sql->where("joiner", $silent);
    $sql->update('cf_users', array(
        'silent' =>  $g['silent']
    ));
    $sql->insert('message', array(
        'user' => -2,
        'username' => 'SYSTEM',
        'color' => '000000',
        'room' => session('room'),
        'text' => $sysmsg,
        'date' => time(),
        'to' => $cfid,
        'into' => $cfid,
        'type' => session('admin')
    ));

}

if ($delid || $delid == session('id')) {
    $deluser = $cfid == session('id') ? $delid : session('id');
    $sysmsg = $cfid == session('id') ? Users::get('name', $delid) . ' توسط مدیر اتاق حذف شد' : session('name') . ' از اتاق خارج شد';
    $sql->where("creater", $cfid);
    $sql->where("joiner", $deluser);
    $sql->delete('cf_users');
    $sql->insert('message', array(
        'user' => -2,
        'username' => 'SYSTEM',
        'color' => '000000',
        'room' => session('room'),
        'text' => $sysmsg,
        'date' => time(),
        'to' => $cfid,
        'into' => $cfid,
        'type' => session('admin')
    ));
}

if (allow_cf($cfid) || $cfid == session('id')) {
    $sql->join('users u', 'u.id=o.id', 'LEFT');
    $sql->where("u.id", $cfid);
    $admininfo = $sql->getOne('onlines o', array('u.name', 'u.type', 'u.score', 'o.id as online'));
    echo '<table dir="rtl" border="1" align="center">
				<tr>
				<td align="center">مدیریت اتاق کنفرانس</td>
				<td align="center">درجه</td>
				<td align="center">امتیاز</td>
				<td align="center">وضعیت</td>
				</tr>';
    $status_admin = $admininfo['online'] ? '<font color="green">آنلاین</font>' : '<font color="red">آفلاین</font>';
    echo "<td align='center'><b><font color='" . $usertype[$admininfo['type']]['color'] . "'>" . $admininfo['name'] . "</font></b></td>";
    echo "<td align='center'><img src='/" . $usertype[$admininfo['type']]['icon'] . "'></td>";
    echo "<td align='center'><b><font color='red'>" . $admininfo['score'] . "</font></b></td>";
    echo "<td align='center'><b>" . $status_admin . "</b></td>";
    echo '</table><hr>';
    if ($cfid == session('id')) {
        echo "<center><a style='text-decoration: none;' href=chat.cfinfo?delcf>حذف تمامی کاربران اتاق کنفرانس</a></center>";
    } else {
        echo "<center><a style='text-decoration: none;' href=chat.cfinfo?cfid=" . $cfid . "&del=" . session('id') . ">برای خروج از اتاق کلیک کنید</a></center>";
    }
    echo '<hr><center><b>کاربران عضو کنفرانس</b></center>';
    echo '<table dir="rtl" border="1" align="center">
				<tr>
				<td align="center">#</td>
				<td align="center">نام</td>
				<td align="center">درجه</td>
				<td align="center">امتیاز</td>
				<td align="center">وضعیت</td>
				<td align="center">سکوت</td>';
    echo $cfid == session('id') ? '<td align="center">ساکت کردن</td><td align="center">حذف</td></tr>' : '</tr>';
    $x = 1;
    $sql->join('users u', 'u.id=c.joiner', 'LEFT');
    $sql->join('onlines o', 'o.id=c.joiner', 'LEFT');
    $sql->where("creater", $cfid);
    foreach($sql->get('cf_users c', null, array(
        'c.joiner',
        'c.silent',
        'u.name',
        'u.type',
        'u.score as uscore',
        'o.score as oscore',
        'o.id as online',
    )) as $row){
        $status = $row['online'] ? '<font color="green">آنلاین</font>' : '<font color="red">آفلاین</font>';
        $sss = $row['silent'] == '1' ? '<img src="' . theme::url('images/status/status_silent.png') . '">' : '<font color="green">خیر</font>';
        $score = $row['online'] ? $row['oscore'] : $row['uscore'];
        echo '<tr><td align="center">' . $x . '</td>
				  <td align="center"><b><font color="' . $usertype[$row['type']]['color'] . '">' . $row['name'] . '</font></b></td>
				  <td align="center"><img src="/' . $usertype[$row['type']]['icon'] . '"></td>
				  <td align="center"><b><font color="red">' . $score . '</font></b></td>
				  <td align="center"><b>' . $status . '</b></td>
				  <td align="center"><b>' . $sss . '</b></td>';
        $silentText = $row['silent'] == '1' ? 'آزاد کردن' : 'ساکت';
        echo $cfid == session('id') ? '<td align="center"><a style="text-decoration: none;" href=chat.cfinfo?cfid=' . $cfid . '&silent=' . $row['joiner'] . '>' . $silentText . '</a></td><td align="center"><a style="text-decoration: none;" href=chat.cfinfo?cfid=' . $cfid . '&del=' . $row['joiner'] . '>حذف از اتاق</a></td></tr>' : '';
        $x++;
    }
    echo '</table>';
} else {
    echo RedError('شما حذف شده اید و به اطلاعات این اتاق دسترسی ندارید');
}
?>