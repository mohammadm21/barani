<?php

// نسخه موبایل تلگرامی — endpoint خواندنی برای ساخت «لیست گفتگوها» و تاریخچهٔ یک گفتگو.
// از همان backend و table های موجود استفاده می‌کند؛ دسکتاپ دست‌نخورده می‌ماند.
header::tojson();
$id = (int) session('id');
if (!$id) {
    echo(json_encode(array('status' => 'error', 'e' => 'login')));
    exit;
}
try {

// جدول آرشیو پیام‌های قدیمی‌تر از ۱۵ دقیقه
$has_archive = false;
try {
    $has_archive = $sql->tableExists('message_archive');
} catch (Exception $e) {
    $has_archive = false;
}
$tables = array('message');
if ($has_archive) {
    $tables[] = 'message_archive';
}

// پینِ فعال فعلی
$out_pin = 0;
try {
    $pin_row = $sql->where('active', 1)->getOne('pinned', array('id', 'user_id', 'username', 'name', 'message', 'time'));
    if ($pin_row) {
        $out_pin = array('id' => (int) $pin_row['id'], 'user_id' => (int) $pin_row['user_id'], 'username' => $pin_row['username'], 'name' => $pin_row['name'], 'message' => $pin_row['message'], 'time' => (int) $pin_row['time']);
    }
    unset($pin_row);
} catch (Exception $e) {
    $out_pin = 0;
}

$out = array('status' => 'ok', 'pin' => $out_pin);$out['me'] = array(
    'id' => $id,
    'name' => session('name'),
    'type' => session('type'),
    'admin' => session('admin'),
    'room' => (int) session('room'),
    'chpw' => session('changepw'),
);

// --- کاربران آنلاین ---
$out['o'] = array();
$sql->where("o.hide", 0);
$sql->join('usertypes u', 'u.id=o.type', 'LEFT');
$resO = $sql->get('onlines o', null, array('o.*', 'u.sort', 'u.admin AS utadmin'));
$online_by_room = array();
foreach ($resO as $row) {
    $inp = json_decode($row['inputs'], true);
    if (!isset($online_by_room[$row['roomid']])) {
        $online_by_room[$row['roomid']] = 0;
    }
    $online_by_room[$row['roomid']]++;
    $o = array(
        'i' => (int) $row['id'],
        'n' => $row['name'],
        'g' => (int) $row['gender'],
        't' => (int) $row['type'],
        'r' => (int) $row['roomid'],
        'o' => ($row['time'] != '0' && $row['time'] != '') ? 1 : 0,
        'ad' => (isset($row['utadmin']) && $row['utadmin'] != '0' && $row['utadmin'] != '') ? 1 : 0,
        'sp' => isset($inp['status_private']) ? $inp['status_private'] : 'pmme',
    );
    if (is_array($inp) && isset($inp['avatar']) && $inp['avatar'] != '' && $inp['avatar'] != $options['defaultavatar']) {
        $o['v'] = $inp['avatar'];
    } else {
        $o['v'] = ($row['gender'] == '1') ? 'maledefualt.png' : 'femaledefualt.jpg';
    }
    $out['o'][] = $o;
}

// --- خاموش/ساکت‌شدگان: نقشه‌ی کاربر(یا ip) → موعد خاموشی، برای نمایش آیکون + زمانِ باقی‌مانده ---
// دسکتاپ همین‌طور از جدول silents (users=id یا ip=ip) می‌خواند؛ اینجا هم برای کل لیست یک‌جا.
$sil_to = array(); // id → موعد (unix)
try {
    $sils = $sql->get('silents', null, array('users', 'ip', 'time'));
    foreach ($sils as $sil) {
        $sl = (int) $sil['time'];
        if ($sl > time()) {
            $members = array_filter(array_map('intval', explode(',', (string) $sil['users'])));
            foreach ($members as $mid) {
                if (!isset($sil_to[$mid]) || $sl > $sil_to[$mid]) {
                    $sil_to[$mid] = $sl;
                }
            }
        }
    }
} catch (Exception $e) {
    $sil_to = array();
}
foreach ($out['o'] as $k => $ou) {
    $deadline = isset($sil_to[(int) $ou['i']]) ? $sil_to[(int) $ou['i']] : 0;
    $out['o'][$k]['sil'] = $deadline; // موعدِ خاموشی (0 = ساکت نیست) — کلاینت زمانِ باقی‌مانده را حساب می‌کند
}

// --- روم‌ها با تعداد آنلاین ---
$out['r'] = array();
$rooms = $sql->get('rooms', null, array('id', 'title', 'password'));
foreach ($rooms as $row) {
    $out['r'][] = array(
        'i' => (int) $row['id'],
        't' => $row['title'],
        'pr' => (string) $row['password'],
        'o' => isset($online_by_room[$row['id']]) ? $online_by_room[$row['id']] : 0,
    );
}

// --- گفتگوهای خصوصی (لیست مکالمه‌ها) ---
// peer برای هر ردیفِ pm: طرفِ مقابلِ $id. آخرین پیام (max id) + «آخرین پیامِ ورودی» (max idِ ردیف‌های to=id).
// شمارشِ unread روی سرور دیگر انجام نمی‌شود؛ کلاینت با last_in و حافظه‌ی محلی‌اش (localStorage) آن را می‌شمرد.
$peer_last = array();
$peer_in_max = array();
$since = time() - 30 * 24 * 3600; // ۳۰ روز اخیر برای لیست
foreach ($tables as $tbl) {
    $sql->where('`date`', $since, '>');
    $sql->where('into', 'pm');
    // بدون فیلترِ room: پیام‌های سیستمی (سیستم→مدیر) در roomِ مدیر ذخیره می‌شوند نه «-1».
    // پیام‌های pm واقعی هیچ‌وقت room غیر از -1 ندارند، پس این امن است.
    $prows = $sql->where("(`user` = $id OR `to` = $id)")->get($tbl, null, array('id', 'user', 'to', 'text'));
    foreach ($prows as $prow) {
        $u = (int) $prow['user'];
        $t = (int) $prow['to'];
        if ($u === $id) {
            $peer = $t;
        } else {
            $peer = $u;
        }
        // پیام‌های سیستمیِ خصوصی (from/to=-1) به مدیرها: به‌جای حذف، یک گفتگوی «سیستم» بساز
        if ($peer === -1) {
            $peer = 0; // گفتگوی سیستمی؛ idِ 0 = سیستم
        } else if ($peer <= 0) {
            continue;
        }
        if (!isset($peer_last[$peer]) || (int) $prow['id'] > (int) $peer_last[$peer]['id']) {
            $peer_last[$peer] = array('id' => (int) $prow['id'], 'text' => $prow['text'], 'date' => (int) $prow['date']);
        }
        if ($t === $id && $u !== $id) {
            if (!isset($peer_in_max[$peer]) || (int) $prow['id'] > $peer_in_max[$peer]) {
                $peer_in_max[$peer] = (int) $prow['id'];
            }
        }
    }
}

// --- آخرین پیامِ ورودیِ رومِ اصلیِ فعال (برای badge گروه) ---
$room_last_in = 0;
$my_room = (int) session('room');
if ($my_room > 0) {
    foreach ($tables as $tbl) {
        $sql->where('room', $my_room);
        $sql->where('into', 'main');
        $sql->where('user', $id, '!=');
        $rl = $sql->getOne($tbl, 'max(`id`) AS mx');
        if ($rl && isset($rl['mx']) && (int) $rl['mx'] > $room_last_in) {
            $room_last_in = (int) $rl['mx'];
        }
    }
}

function phone_disp_text($raw) {
    $t = str_replace(array('<b>', '</b>', '<i>', '</i>'), '', $raw);
    if (isJson($t)) {
        $jd = json_decode($t, true);
        if (is_array($jd) && isset($jd['action'])) {
            if (isset($jd['mymsg'])) {
                return $jd['mymsg'];
            }
            if (isset($jd['msg'])) {
                return $jd['msg'];
            }
            if ($jd['action'] == 'editonemsg' && isset($jd['message'])) {
                return $jd['message'];
            }
            return '';
        }
    }
    return $t;
}

$conv_list = array();
foreach ($peer_last as $peer => $m) {
    // گفتگوی سیستمی (id=0): یک نام/آواتار ثابت، بدون جستجوی کاربر در دیتابیس
    if ($peer === 0) {
        $conv_list[] = array(
            'peer' => -1,
            'name' => 'سیستم',
            'avatar' => '',
            'is_sys' => 1,
            'ad' => 0,
            'online' => 0,
            'last_id' => $m['id'],
            'last_time' => jdate::start('H:i', $m['date']),
            'last_date' => jdate::start('Y/m/d', $m['date']),
            'last_text' => phone_disp_text($m['text']),
            'last_in' => isset($peer_in_max[$peer]) ? $peer_in_max[$peer] : 0,
        );
        continue;
    }
    $sql->where('id', $peer);
    $pu = $sql->getOne('onlines', array('name', 'gender', 'type', 'inputs'));
    $online = 1;
    if (!$pu) {
        $sql->where('id', $peer);
        $pu = $sql->getOne('users', array('name', 'gender', 'type', 'inputs'));
        $online = 0;
    }
    if (!$pu) {
        $pu = array('name' => 'G' . $peer, 'gender' => 0, 'inputs' => '');
    }
    $inp = isset($pu['inputs']) ? json_decode($pu['inputs'], true) : array();
    if (is_array($inp) && isset($inp['avatar']) && $inp['avatar'] != '' && $inp['avatar'] != $options['defaultavatar']) {
        $av = $inp['avatar'];
    } else {
        $g = isset($pu['gender']) ? $pu['gender'] : 0;
        $av = ($g == '1') ? 'maledefualt.png' : 'femaledefualt.jpg';
    }
    $peer_admin = 0;
    if (isset($pu['type'])) {
        $sql->where('id', (int) $pu['type']);
        $ptype = $sql->getOne('usertypes', array('admin'));
        if ($ptype && $ptype['admin'] != '0' && $ptype['admin'] != '') {
            $peer_admin = 1;
        }
    }
    $conv_list[] = array(
        'peer' => $peer,
        'name' => isset($pu['name']) ? $pu['name'] : 'G' . $peer,
        'avatar' => $av,
        'ad' => (int) $peer_admin,
        'online' => (int) $online,
        'last_id' => $m['id'],
        'last_time' => jdate::start('H:i', $m['date']),
        'last_date' => jdate::start('Y/m/d', $m['date']),
        'last_text' => phone_disp_text($m['text']),
        'last_in' => isset($peer_in_max[$peer]) ? $peer_in_max[$peer] : 0,
        'sil' => isset($sil_to[$peer]) ? $sil_to[$peer] : 0,
        'sp' => isset($inp['status_private']) ? $inp['status_private'] : 'pmme',
    );
}
usort($conv_list, function ($a, $b) {
    return $b['last_id'] - $a['last_id'];
});
$out['c'] = $conv_list;
$out['room_last_in'] = $room_last_in;

// --- تاریخچهٔ یک گفتگوی خاص (بازکردن چت) ---
// POST: into=pm|main|cf ، to=<peer | -1 | id روم/کنفرانس> ، before=<id> (به‌سمت قدیمی‌تر)
if (isset($_POST['into']) && isset($_POST['to'])) {
    $into = preg_replace('/[^a-z]/', '', safe::string($_POST['into']));
    // مقدارِ خامِ «to» برای تشخیص -1 لازم است: safe::number('-1') به‌جای '' مقدارِ '1' برمی‌گرداند
    // (ددجیت «1» را می‌خواند) و -1 (چتِ سیستم / رومِ عمومی) را به کاربرِ id=1 تبدیل می‌کند.
    $raw_to = trim((string) $_POST['to']);
    $to = $raw_to !== '-1' ? (safe::number($raw_to) ? safe::number($raw_to) : -1) : -1;
    $before = isset($_POST['before']) ? safe::number($_POST['before']) : 0;
    $after = isset($_POST['after']) ? safe::number($_POST['after']) : 0;
    $limit = isset($_POST['limit']) ? min(50, max(1, safe::number($_POST['limit']))) : 50;
    $order = $after > 0 ? 'ASC' : 'DESC';

    $hist = array();
    if ($into == 'pm') {
        // گفتگوی سیستمی (to=-1): پیام‌های خصوصیِ سیستم (user=-1) که به این مدیر رسیده
        $is_sys_chat = ($to == -1);
        foreach ($tables as $tbl) {
            $sql->where('into', 'pm');
            // سیستم: پیام‌های sys به «room» مدیرِ مقصد ذخیره می‌شوند (نه لزوماً -1)؛ پس فیلترِ room را نکش
            if (!$is_sys_chat) {
                $sql->where('room', '-1');
            }
            if ($is_sys_chat) {
                $sql->where('`user`', -1);
                $sql->where('`to`', $id);
            } else {
                $sql->where("(`user` = $id AND `to` = $to) OR (`to` = $id AND `user` = $to)");
            }
            if ($before > 0) {
                $sql->where('id', $before, '<');
            } elseif ($after > 0) {
                $sql->where('id', $after, '>');
            }
            $rows = $sql->orderBy('id', $order)->get($tbl, $limit, array('id', 'user', 'username', 'color', 'text', 'date', 'into', 'type'));
            $hist = array_merge($hist, $rows);
            if (count($hist) >= $limit) {
                break;
            }
        }
    } elseif ($into == 'main') {
        $room = $to == -1 ? (int) session('room') : $to;
        foreach ($tables as $tbl) {
            $sql->where('room', $room);
            $sql->where('into', 'main');
            $sql->where('user', -1, '!=');
            if ($before > 0) {
                $sql->where('id', $before, '<');
            } elseif ($after > 0) {
                $sql->where('id', $after, '>');
            }
            $rows = $sql->orderBy('id', $order)->get($tbl, $limit, array('id', 'user', 'username', 'color', 'text', 'date', 'into', 'type'));
            $hist = array_merge($hist, $rows);
            if (count($hist) >= $limit) {
                break;
            }
        }
    } else {
        // کنفرانس: each room/cf را با into یافت شده نزدیک a به id
        foreach ($tables as $tbl) {
            $sql->where('into', $into);
            $sql->where('user', $id, '!=');
            $sql->where('to', $to);
            if ($before > 0) {
                $sql->where('id', $before, '<');
            } elseif ($after > 0) {
                $sql->where('id', $after, '>');
            }
            $rows = $sql->orderBy('id', $order)->get($tbl, $limit, array('id', 'user', 'username', 'color', 'text', 'date', 'into', 'type'));
            $hist = array_merge($hist, $rows);
            if (count($hist) >= $limit) {
                break;
            }
        }
    }
    // merge از message + archive ممکن است تکراری/نامرتب باشد؛ سورت و dedupe بر اساس id
    $seen = array();
    $dedup = array();
    foreach ($hist as $row) {
        if (isset($seen[(int) $row['id']])) {
            continue;
        }
        $seen[(int) $row['id']] = 1;
        $dedup[] = $row;
    }
    usort($dedup, function ($a, $b) {
        return (int) $a['id'] - (int) $b['id'];
    });
    $hist = $dedup;

    // واکنش‌ها: batch از جدول reactions روی همه msg id ها، گروه‌بندی message_id/user_id
    $react_by_msg = array();
    $mid_list = array();
    foreach ($hist as $row) {
        $mid_list[] = (int) $row['id'];
    }
    if (!empty($mid_list)) {
        try {
            $sql->where('message_id', $mid_list, 'in');
            $rrows = $sql->get('reactions', null, array('message_id', 'user_id', 'reaction'));
            foreach ($rrows as $rr) {
                $mm = (int) $rr['message_id'];
                if (!isset($react_by_msg[$mm])) {
                    $react_by_msg[$mm] = array();
                }
                $react_by_msg[$mm][] = array('user_id' => (int) $rr['user_id'], 'reaction' => $rr['reaction']);
            }
        } catch (Exception $e) {
            $react_by_msg = array();
        }
    }

    // batch آواتارها
    $uids = array();
    foreach ($hist as $row) {
        if ((int) $row['user'] > 0) {
            $uids[] = (int) $row['user'];
        }
    }
    $uids = array_unique($uids);

    // شناسه‌های کاربرِ واقعاً آنلاین (time != 0) برای حلقهٔ سبز پروفایل
    $online_ids = array();
    if (isset($resO) && is_array($resO)) {
        foreach ($resO as $orow) {
            if ($orow['time'] != '0' && $orow['time'] != '') {
                $online_ids[(int) $orow['id']] = 1;
            }
        }
    }
    // تایپِ هر فرستنده در تاریخچه (برای نمایشِ اسمِ تایپ کنار نام در حباب پیام) + سطحِ مدیریت
    $utype_map = array();
    $admin_map = array();
    if (!empty($uids)) {
        $sql->where('id', $uids, 'in');
        $utyps = $sql->get('users', null, array('id', 'type'));
        foreach ($utyps as $up) {
            $utype_map[(int) $up['id']] = (int) $up['type'];
        }
        // سطحِ مدیریتِ هر تایپ از جدول usertypes
        $type_ids = array_values(array_unique($utype_map));
        $ut_admin_lookup = array();
        if (!empty($type_ids)) {
            $sql->where('id', $type_ids, 'in');
            $usertype_rows = $sql->get('usertypes', null, array('id', 'admin'));
            foreach ($usertype_rows as $ur) {
                $ut_admin_lookup[(int) $ur['id']] = $ur['admin'];
            }
        }
        foreach ($utype_map as $uid_ => $tid) {
            if (isset($ut_admin_lookup[$tid]) && $ut_admin_lookup[$tid] != '0' && $ut_admin_lookup[$tid] != '') {
                $admin_map[$uid_] = 1;
            }
        }
    }

    $avmap = array();
    if (!empty($uids)) {
        $sql->where('id', $uids, 'in');
        $prs = $sql->get('profiles', null, array('id', 'avatar', 'gender'));
        foreach ($prs as $p) {
            $a = $options['defaultavatar'];
            if (!empty($p['avatar']) && $p['avatar'] != 'photo.png' && is_file('theme/images/avatars/' . $p['avatar'])) {
                $a = $p['avatar'];
            } else {
                $a = ($p['gender'] == '1') ? 'maledefualt.png' : 'femaledefualt.jpg';
            }
            $avmap[(int) $p['id']] = $a;
        }
        $missing = array();
        foreach ($uids as $uid) {
            if (!isset($avmap[$uid])) {
                $missing[] = $uid;
            }
        }
        if (!empty($missing)) {
            $sql->where('id', $missing, 'in');
            $ols = $sql->get('onlines', null, array('id', 'gender', 'inputs'));
            foreach ($ols as $ol) {
                $inp = json_decode($ol['inputs'], true);
                if (is_array($inp) && isset($inp['avatar']) && $inp['avatar'] != '' && is_file('theme/images/avatars/' . $inp['avatar'])) {
                    $avmap[(int) $ol['id']] = $inp['avatar'];
                } else {
                    $avmap[(int) $ol['id']] = ($ol['gender'] == '1') ? 'maledefualt.png' : 'femaledefualt.jpg';
                }
            }
        }
    }

    function phone_msg($row, $options, $avmap, $react_by_msg, $id, $online_ids, $utype_map, $admin_map) {
        $t = str_replace(array('<b>', '</b>', '<i>', '</i>'), '', $row['text']);
        $reply = null;
        $reply_isimg = null;
        $fwd = null;
        $fwd_isimg = null;
        $hidden = 0;
        if (isJson($t)) {
            $jd = json_decode($t, true);
            if (is_array($jd) && isset($jd['action'])) {
                if ($jd['action'] == 'reply') {
                    $reply = isset($jd['umsg']) ? $jd['umsg'] : '';
                    $reply_sender = isset($jd['uname']) ? $jd['uname'] : '';
                    $reply_isimg = isset($jd['isimg']) ? 1 : null;
                    $row['text'] = isset($jd['mymsg']) ? $jd['mymsg'] : '';
                } elseif ($jd['action'] == 'forward') {
                    $fwd = isset($jd['msg']) ? $jd['msg'] : '';
                    $fwd_sender = isset($jd['name']) ? $jd['name'] : '';
                    $fwd_isimg = isset($jd['isimg']) ? 1 : null;
                    $row['text'] = '';
                } elseif ($jd['action'] == 'editonemsg') {
                    $hidden = 1;
                } elseif ($jd['action'] == 'delete' && $row['into'] == 'deletmsg') {
                    $hidden = 1;
                } elseif ($row['into'] == 'pinmsg' || $row['into'] == 'unpinmsg') {
                    $hidden = 1;
                }
            }
        }
        $uid = (int) $row['user'];
        // تجمیع واکنش‌ها برای این msg
        $agg = array();
        $mine = null;
        if (isset($react_by_msg[(int) $row['id']])) {
            $cnt = array();
            foreach ($react_by_msg[(int) $row['id']] as $rk) {
                $rid = $rk['user_id'];
                $rr = $rk['reaction'];
                if (!isset($cnt[$rr])) {
                    $cnt[$rr] = array('count' => 0, 'users' => array());
                }
                $cnt[$rr]['count']++;
                $cnt[$rr]['users'][] = $rid;
                if ($rid === $id) {
                    $mine = $rr;
                }
            }
            foreach ($cnt as $rr => $info) {
                $agg[] = array(
                    'reaction' => $rr,
                    'count' => $info['count'],
                    'users' => $info['users'],
                    'mine' => ($mine === $rr) ? 1 : 0,
                );
            }
        }
        return array(
            'id' => (int) $row['id'],
            'user_id' => $uid,
            'reactions' => $agg,
            'my_reaction' => $mine,
            'ol' => ($uid > 0 && isset($online_ids[$uid])) ? 1 : 0,
            'ad' => ($uid > 0 && isset($admin_map[$uid])) ? 1 : 0,
            'ut' => ($uid > 0 && isset($utype_map[$uid])) ? $utype_map[$uid] : 0,
            'username' => $row['username'],
            'text' => $row['text'],
            'color' => $row['color'],
            'avatar' => isset($avmap[$uid]) ? $avmap[$uid] : $options['defaultavatar'],
            'time' => jdate::start('H:i', $row['date']),
            'date' => jdate::start('Y/m/d', $row['date']),
            'isSystem' => ($uid === -1) ? 1 : 0,
            'into' => $row['into'],
            'reply' => $reply,
            'reply_sender' => isset($reply_sender) ? $reply_sender : '',
            'reply_isimg' => $reply_isimg,
            'forward' => $fwd,
            'forward_sender' => isset($fwd_sender) ? $fwd_sender : '',
            'forward_isimg' => $fwd_isimg,
            'hidden' => $hidden,
        );
    }

    $msgs = array();
    foreach ($hist as $row) {
        $msgs[] = phone_msg($row, $options, $avmap, $react_by_msg, $id, $online_ids, $utype_map, $admin_map);
    }
    $out['h'] = $msgs;

    // --- is typing... : چه کسی در این گفتگو در حال تایپ است (از آخرین ~۸ ثانیه) ---
    $out['typing'] = array();
    try {
        $now = time();
        $t_cutoff = $now - 10;
        $typing_rows = array();
        if ($into == 'pm') {
            $sql->where('user_id', $to);
            $sql->where('time', $t_cutoff, '>');
            $tr = $sql->getOne('typing', array('user_id', 'time'));
            if ($tr) {
                $typing_rows[] = $tr;
            }
        } else {
            $sql->where('target', $to, '=');
            $sql->where('user_id', $id, '!=');
            $sql->where('time', $t_cutoff, '>');
            $typing_rows = $sql->get('typing', null, array('user_id', 'time'));
        }
        $t_users = array();
        foreach ($typing_rows as $trow) {
            if ((int) $trow['user_id'] === $id) {
                continue;
            }
            $t_users[] = (int) $trow['user_id'];
        }
        if (!empty($t_users)) {
            $sql->where('id', $t_users, 'in');
            $tnames = $sql->get('onlines', null, array('id', 'name'));
            $t_names = array();
            foreach ($tnames as $tn) {
                $t_names[] = $tn['name'];
            }
            // اگر کاربرِ تایپ‌کننده در onlines نبود، از users بخوان
            if (count($t_names) < count($t_users)) {
                $sql->where('id', $t_users, 'in');
                $tusers2 = $sql->get('users', null, array('id', 'name'));
                foreach ($tusers2 as $tu2) {
                    $t_names[] = $tu2['name'];
                }
            }
            $out['typing'] = array_values(array_unique($t_names));
        }
    } catch (Exception $e) {
        $out['typing'] = array();
    }
}

// --- اثر انگشت: اگر مدیر کاربر را درحالِ اخراج flag زده (fp_capture=1) و هنوز
// اثر انگشتِ abuser ثبت نشده، از کلاینت بخواه فوراً اثر انگشتش را send کند.
// فلگ روی onlines ممکن است با حذفِ بن (benned.php) از بین برود؛ پس به جدولِ
// ماندگار ahyir_fp_capture هم نگاه می‌کنیم که بعد از ورود دوباره/ایپی‌عوض، سرپا باشد.
$out['fp_capture'] = 0;
try {
    $sql->where('id', $id);
    $me_line = $sql->getOne('onlines', array('inputs'));
    if ($me_line) {
        $inp = json_decode($me_line['inputs'], true);
        if (is_array($inp) && isset($inp['fp_capture']) && $inp['fp_capture'] == 1) {
            $out['fp_capture'] = 1;
        }
    }
    if ($out['fp_capture'] == 0) {
        $sql->where('user_id', $id);
        if ($sql->getOne('fp_capture', array('id'))) {
            $out['fp_capture'] = 1;
        }
    }
} catch (Exception $e) {
    $out['fp_capture'] = 0;
}

echo json_encode($out, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    echo(json_encode(array('status' => 'error', 'e' => $e->getMessage())));
}
