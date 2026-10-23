<?php

header::tojson();
$admin = session('admin');
if ($admin != '1' AND $admin != '2' AND $admin != '3') {
	if ($actions['benned'] == '1' or $banned = $sql->where("(`to` > UNIX_TIMESTAMP() or `to` = 0)")->where("(`user` = '{$id}' or `ip` = '" . ip() . "')")->getOne('banns', array('id', 'to', 'author', 'user'))) {
        session('banned', $banned['user']);
        setcookie('ChatBennid', $banned['user'], $banned['to']);
		setcookie('ChatBennip', ip(), $banned['to']);
        Users::Offline($banned['user']);
        session_write_close();
        die('blacklist');
    } elseif ($actions['exit'] == 'true') {
        die('exit');
    }
}
$cleanmsg = array();
$onlines = array();
$online = array();
$rooms = array();
$emptyrooms = array();
$messages = array();
$current_pin = 0;
// پینِ فعال فعلی برای نمایش بنرِ استیکی در بالای چت عمومی.
try {
	$pin_row = $sql->where('active', 1)->getOne('pinned', array('id','username','name','message'));
	if ($pin_row) {
		$current_pin = array('id'=>(int)$pin_row['id'], 'username'=>$pin_row['username'], 'name'=>$pin_row['name'], 'message'=>$pin_row['message']);
	}
	unset($pin_row);
} catch (Exception $e) {
	// جدول pinned هنوز ساخته نشده (migration اعمال نشده)؛ چت عادی بدون پین ادامه می‌یابد.
	$current_pin = 0;
}
$room = session('room');
$idmsg = session('idmsg');
if (!$idmsg) {
    $idmsg = 0;
}



if(session("msgactions") && session("msgactions") == "close"){
     $sql->where('into', 'main', '!=');
}

$sql->where("id", $last, '>');
$sql->where("user", $id, '!=');
$sql->where("room", array(-1, $room), 'in');
$sql->orderBy('date', "ASC");
$result = $sql->get('message', null, array('id', 'user', 'username', 'color', 'fontname','fontsize', 'room', 'text', 'date', 'to', 'into', 'type'));

// Batch-fetch all user info to eliminate N+1 queries
$user_ids = array();
foreach ($result as $row) {
    if (($row['id'] > $last) and ($row['to'] == '-1' or $row['to'] == $id or allow_cf($row['to'], $row['into'], $row['user']))) {
        $user_ids[] = $row['user'];
    }
}
$user_ids = array_unique($user_ids);
$online_map = array();
if (!empty($user_ids)) {
    $sql->where("id", $user_ids, 'in');
    $online_rows = $sql->get('onlines', null, array('id', 'gender', 'type', 'inputs'));
    foreach ($online_rows as $orow) {
        $online_map[$orow['id']] = $orow;
    }
}

foreach ($result as $row) {
    if (($row['id'] > $last) and ( $row['to'] == '-1' or $row['to'] == $id or allow_cf($row['to'], $row['into'], $row['user']))) {
        $avatar = $options['defaultavatar'];
        $row['to'] = $row['to'] == '-1' ? '0' : $row['user'];
        $online = isset($online_map[$row['user']]) ? $online_map[$row['user']] : null;
        $sex = isset($online['gender']) ? $online['gender'] : $options['defaultusertype'];
        $priv = isset($online['type']) ? $online['type'] : $options['defaultusertype'];
        if (isset($online['inputs'])) {
            $inputs = json_decode($online['inputs'], true);
            $avatar = isset($inputs['avatar']) && $inputs['avatar'] != 'photo.png' ? (is_file('theme/images/avatars/' . $inputs['avatar']) && is_file('theme/images/avatars/' . MiniAvatarDir() . '/' . $inputs['avatar']) ? $inputs['avatar'] : ($online['gender'] == '1' ? 'maledefualt.png' : 'femaledefualt.jpg')) : ($online['gender'] == '1' ? 'maledefualt.png' : 'femaledefualt.jpg');
        }
        if ($row['into'] != 'pm') {
            $idmsg++;
        }
        $temp = array(
            'id' => ($row['into'] != 'pm' and $row['into'] != 'allowchat') ? "$idmsg" : '',
            'user' => $row['username'],
            'user_id' => $row['user'],
            'isSystem' => (int)$row['user'] === -1 ? 1 : 0,
            'message' => $row['text'],
            'time' => date('H:i', $row['date']),
            'privat' => $row['to'],
            'css' => "color:#{$row['color']};font-family:{$row['fontname']};font-size:{$row['fontsize']}",
            'priv' => $priv,
            'sex' => $sex,
            'type' => 'get',
            'cf' => $row['into'] == 'pm' || $row['into'] == 'main' || $row['into'] == 'allowchat' ? '0' : '1',
            'avatar' => $avatar,
            'into' => $row['into'],
            'msgid' => $row['id'],
        );
        if ($temp['cf'] == '1') {
            $temp['cfname'] = Users::get('name', $row['into']);
        }
        $jmsg = str_replace(array('<b>', '</b>', '<i>', '</i>'), "", $temp['message']);
        if (isJson($jmsg)) {
            $jd = json_decode($jmsg, true);
            if (isset($jd['action'])) {
                if ($jd['action'] == 'reply') {
                    $temp['reply'] = $jd['umsg'];
                    $temp['name'] = $jd['uname'];
                    $temp['message'] = $jd['mymsg'];
                    if (isset($jd['isimg'])) {
                        $temp['reply'] = '<img width="50" height="50" id="nocheck" src="' . $jd['umsg'] . '">';
                    }
                } else if ($jd['action'] == 'delete' and $row['into'] == 'deletmsg') {
                    $cleanmsg[] = $jd['msgid'];
                }else if ($jd['action'] == 'forward') {
                    $temp['forward'] = $jd['msg'];
                    $temp['name'] = $jd['name'];
                    if (isset($jd['isimg'])) {
                        $temp['forward'] = '<img width="50" height="50" id="nocheck" src="' . $jd['msg'] . '">';
                    }
                }
				else if ($jd['action'] == 'editonemsg' or $row['into'] == "editonemsg") {
				        $editmsg[] = array("id"=>$jd['msgid'],"message"=>$jd['message'],"act"=>$jd['act']);
						// ردیف لاگِ ویرایش فقط برای به‌روزرسانیِ پیام واقعی استفاده می‌شود؛
						// نباید به‌عنوان یک پیامِ ماکتی (با متنِ JSON) در چت ظاهر شود.
						$temp['message'] = '';
	                }
					else if ($row['into'] == "pinmsg" or $row['into'] == "unpinmsg") {
						// ردیف لاگِ پین فقط برای انتشارِ به‌روزرسانیِ بنر است؛
						// نباید به‌صورت پیامِ ماکتی (متنِ JSON) در چت دیده شود.
						$temp['message'] = '';
	                }
            }
        }
        $messages[] = $temp;
        $last = (int) $row['id'];
        if ($row['into'] == 'allowchat' and $row['text'] == "request") {
            $req_allow = json_decode(session('req_allow'), true);
            $req_allow = array_values($req_allow);
            if (!in_array($row['user'], $req_allow)) {
                $req_allow[] = $row['user'];
            }
            session('req_allow', json_decode(session($req_allow)));
        }
    }
    unset($row, $temp);
}
session('idmsg', $idmsg);

// Batch-fetch reactions for the fetched messages
if (!empty($messages)) {
    $msg_ids = array();
    foreach ($messages as $m) { $msg_ids[] = (int)$m['msgid']; }
    $msg_ids = array_unique($msg_ids);

    $sql->where('message_id', $msg_ids, 'in');
    $reaction_rows = $sql->get('reactions', null, array('message_id', 'user_id', 'reaction'));

    $r_map = array();
    $all_ruids = array();
    foreach ($reaction_rows as $rr) {
        $mid = (int)$rr['message_id'];
        if (!isset($r_map[$mid])) $r_map[$mid] = array('counts' => array(), 'users' => array());
        $e = $rr['reaction'];
        if (!isset($r_map[$mid]['counts'][$e])) $r_map[$mid]['counts'][$e] = 0;
        $r_map[$mid]['counts'][$e]++;
        $r_map[$mid]['users'][(int)$rr['user_id']] = $e;
        $all_ruids[] = (int)$rr['user_id'];
    }
    // Build user id => (name, avatar) map for the reaction tooltip
    $ruser_map = array();
    $all_ruids = array_unique($all_ruids);
    if (!empty($all_ruids)) {
        $sql->where('id', $all_ruids, 'in');
        $rusers = $sql->get('onlines', null, array('id', 'name', 'gender', 'inputs'));
        foreach ($rusers as $ru) {
            $av = $options['defaultavatar'];
            $rinp = json_decode($ru['inputs'], true);
            if (is_array($rinp) && isset($rinp['avatar']) && $rinp['avatar'] != 'photo.png') {
                $av = is_file('theme/images/avatars/' . $rinp['avatar']) ? $rinp['avatar'] : (($ru['gender'] == '1') ? 'maledefualt.png' : 'femaledefualt.jpg');
            } else {
                $av = ($ru['gender'] == '1') ? 'maledefualt.png' : 'femaledefualt.jpg';
            }
            $ruser_map[(int)$ru['id']] = array('name' => $ru['name'], 'avatar' => $av);
        }
    }

    $uid = (int)$id;
    foreach ($messages as &$msg) {
        $mid = (int)$msg['msgid'];
        if (isset($r_map[$mid])) {
            $rd = $r_map[$mid];
            $msg['reactions'] = array();
            foreach ($rd['counts'] as $emoji => $cnt) {
                $usrs = array();
                foreach (array_keys($rd['users'], $emoji) as $uu) {
                    $info = isset($ruser_map[$uu]) ? $ruser_map[$uu] : array('name' => '', 'avatar' => $options['defaultavatar']);
                    $usrs[] = array('id' => $uu, 'name' => $info['name'], 'avatar' => $info['avatar']);
                }
                $msg['reactions'][] = array('reaction' => $emoji, 'count' => $cnt, 'users' => $usrs);
            }
            $msg['my_reaction'] = isset($rd['users'][$uid]) ? $rd['users'][$uid] : null;
        } else {
            $msg['reactions'] = array();
            $msg['my_reaction'] = null;
        }
    }
    unset($msg);
}

// --- Reaction updates for synchronization between users ---
$ruid = (int)$id;
$reaction_updates = array();

// Check if reaction_broadcast changed since last check (handles adds/updates/deletes)
$last_rb = session('last_reaction_broadcast');
$cur_rb = (int)($options['reaction_broadcast'] ?? 0);

if (!$last_rb || $cur_rb != (int)$last_rb) {
    // Reactions changed - re-fetch for all recently active messages
    $sql->orderBy('created_at', 'DESC');
    $sql->where('created_at', $last_rb ? (int)$last_rb : 0, '>');
    $recent = $sql->get('reactions', array(0, 50), array('message_id', 'user_id', 'reaction', 'created_at'));
    
    if (!empty($recent)) {
        $changed_ids = array();
        foreach ($recent as $rr) { 
            $mid_int = (int)$rr['message_id'];
            if (!in_array($mid_int, $changed_ids)) $changed_ids[] = $mid_int;
        }
        
        // Also check for messages where all reactions were deleted (no rows left)
        // by checking the message IDs that had reactions at last check
        $last_updates = session('last_reaction_msgids');
        if ($last_updates && is_array($last_updates)) {
            foreach ($last_updates as $lmid) {
                if (!in_array($lmid, $changed_ids)) {
                    $sql->where('message_id', $lmid);
                    if ($sql->has('reactions')) {
                        if (!in_array($lmid, $changed_ids)) $changed_ids[] = $lmid;
                    } else {
                        // All reactions deleted - still report update
                        if (!in_array($lmid, $changed_ids)) $changed_ids[] = $lmid;
                    }
                }
            }
        }
        
        foreach ($changed_ids as $cid) {
            $sql->where('message_id', $cid);
            $all_r = $sql->get('reactions', null, array('reaction', 'user_id'));
            $cnts = array();
            $users_by_emoji = array();
            $my = null;
            $truids = array();
            foreach ($all_r as $ar) {
                $em = $ar['reaction'];
                if (!isset($cnts[$em])) { $cnts[$em] = 0; $users_by_emoji[$em] = array(); }
                $cnts[$em]++;
                $users_by_emoji[$em][] = (int)$ar['user_id'];
                $truids[] = (int)$ar['user_id'];
                if ((int)$ar['user_id'] === $ruid) $my = $em;
            }
            // Build user map for this message's reactions
            $tmap = array();
            $truids = array_unique($truids);
            if (!empty($truids)) {
                $sql->where('id', $truids, 'in');
                $trs = $sql->get('onlines', null, array('id', 'name', 'gender', 'inputs'));
                foreach ($trs as $ruu) {
                    $tav = $options['defaultavatar'];
                    $tinp = json_decode($ruu['inputs'], true);
                    if (is_array($tinp) && isset($tinp['avatar']) && $tinp['avatar'] != 'photo.png') {
                        $tav = is_file('theme/images/avatars/' . $tinp['avatar']) ? $tinp['avatar'] : (($ruu['gender'] == '1') ? 'maledefualt.png' : 'femaledefualt.jpg');
                    } else {
                        $tav = ($ruu['gender'] == '1') ? 'maledefualt.png' : 'femaledefualt.jpg';
                    }
                    $tmap[(int)$ruu['id']] = array('name' => $ruu['name'], 'avatar' => $tav);
                }
            }
            $lst = array();
            foreach ($cnts as $em => $ct) {
                $usrs = array();
                foreach ($users_by_emoji[$em] as $uuu) {
                    $info = isset($tmap[$uuu]) ? $tmap[$uuu] : array('name' => '', 'avatar' => $options['defaultavatar']);
                    $usrs[] = array('id' => $uuu, 'name' => $info['name'], 'avatar' => $info['avatar']);
                }
                $lst[] = array('reaction' => $em, 'count' => $ct, 'users' => $usrs);
            }
            // Mark system messages (user = -1) as not reactionable
            $sql->where('id', $cid);
            $is_sys = $sql->getValue('message', 'user');
            $reaction_updates[$cid] = array('list' => $lst, 'user_reaction' => $my, 'isSystem' => ((int)$is_sys === -1 ? 1 : 0));
        }
        
        session('last_reaction_msgids', $changed_ids);
    }
}

session('last_reaction_broadcast', $cur_rb);

echo(json_encode(array(
    'data' => $messages,
    'mid'=>isset($mymsgid) ? $mymsgid : 0,
    'reaction_updates' => $reaction_updates,
    'clear_s' => $cleanmsg,
    'edit_s' => isset($editmsg) ? $editmsg : 0,
    'pin' => $current_pin,
    'refresh' => $actions['refresh'], 
    'alert' => $actions['alert'],
    'exit' => $actions['exit'], 
    'clear' => $actions['clear'],
    'silent' => $actions['silent'],
    'cf' => isset($actions['cf']) ? $actions['cf'] : '0',
    'cf_in' => isset($actions['cf_in']) ? $actions['cf_in'] : '0',
    'cf_key' => isset($actions['cf_key']) ? $actions['cf_key'] : '0',
    'move' => isset($actions['move']) ? $actions['move'] : '0',
    'move_key' => isset($actions['move_key']) ? $actions['move_key'] : '0',
    'addfriend' => isset($actions['addfriend']) ? $actions['addfriend'] : '0',
    'vc' => isset($actions['vc']) ? $actions['vc'] : '0',
    'vc_chid' => isset($actions['vc_chid']) ? $actions['vc_chid'] : '0',
    'vc_rid' => isset($actions['vc_rid']) ? $actions['vc_rid'] : '0',
    'cvc' => isset($actions['cvc']) ? $actions['cvc'] : '0',
    'cvc_chid' => isset($actions['cvc_chid']) ? $actions['cvc_chid'] : '0',
    'cvc_rid' => isset($actions['cvc_rid']) ? $actions['cvc_rid'] : '0'
        ), JSON_UNESCAPED_UNICODE));
$actions['cvc'] = '0';
$actions['cvc_chid'] = '0';
$actions['cvc_rid'] = '0';
$actions['vc'] = '0';
$actions['vc_chid'] = '0';
$actions['vc_rid'] = '0';
$actions['refresh'] = '0';
$actions['alert'] = 'null';
$actions['clear'] = '0';
$actions['idle'] = '0';
$actions['idle_time'] = '0';
$actions['cf'] = '0';
$actions['cf_in'] = '0';
$actions['cf_key'] = '0';
$actions['move_key'] = '0';
$actions['move'] = '0';
$actions['addfriend'] = '0';
$actions['status_private'] = !isset($actions['status_private']) ? 'pmme' : $actions['status_private'];
$actions['status_cf'] = !isset($actions['status_cf']) ? 'reqme' : $actions['status_cf'];
$actions['status_addfriend'] = !isset($actions['status_addfriend']) ? 'reqme' : $actions['status_addfriend'];
$actions['onlinelist'] = !isset($actions['onlinelist']) ? 'default' : $actions['onlinelist'];
$actions['status_c2c'] = !isset($actions['status_c2c']) ? 'reqme' : $actions['status_c2c'];
$actions['my_blacklist'] = !isset($actions['my_blacklist']) ? array() : $actions['my_blacklist'];
$actions['permission'] = !isset($actions['permission']) ? array() : $actions['permission'];
$actions['allowchat'] = !isset($actions['allowchat']) ? array() : $actions['allowchat'];
$actions['reportstatus'] == !isset($actions['reportstatus']) ? 'on' : $actions['reportstatus'];
$actions['closepv'] == !isset($actions['closepv']) ? 'open' : $actions['closepv'];
if($actions['reportstatus'] == 'stop' and !isset($_COOKIE["reportstatus"])){
	$ip = ip();
	setcookie("reportstatus", "block", time()+86400);
        setcookie("reporip","{$ip}", time()+86400);

}elseif($actions['reportstatus'] == 'on' and isset($_COOKIE["reportstatus"])=='block'){
	setcookie("reportstatus", "", time()-86400);
        setcookie("reporip", "", time()-86400);
}
if (!session('myOS')) {
    session('myOS', 'Unknown');
}
$actions['myOS'] = session('myOS');
if (!session('ubrowser')) {
    session('ubrowser', 'Unknown');
}
$actions['ubrowser'] = session('ubrowser');
if (!session('countries')) {
    session('countries', 'Unknown');
}
$actions['countries'] = session('countries');
if (!session('idle_time')) {
    session('idle_time', $time + $options['check_idle'] * 60);
}
$sessIdle = session('idle_time');
$check_idle_time = $time - $sessIdle;
if (!empty($_REQUEST['text'])) {
    session('idle_time', $time);
} elseif (true && $check_idle_time > ($options['check_idle'] * 60)) {
    $actions['idle'] = '1';
    $actions['idle_time'] = $sessIdle;
}
if ($sql->where("users", $id)->orWhere("ip", ip())->has('silents') || $actions['silent'] == '1' || $actions['silent'] == '2' || $actions['silent'] == '3') {
    $sql->where("users", $id);
    $sql->orWhere("ip", ip());
    $sql->orderBy('id', 'DESC');
    $get_time = $sql->getOne('silents', 'time');
    if (!$get_time) {
        $get_time['time'] = '1';
    }
	$timeleft = $time - $get_time['time'];
    $timeleft = str_replace('-', '', $timeleft);
    $int = $timeleft / 60;
    settype($int, 'integer');
    $min = $int;
    $d = floor($min / 1440);
    $h = floor(($min - $d * 1440) / 60);
    $m = $min - ($d * 1440) - ($h * 60);
    $s = $min % 60;
    $text = $d . ' Day | ' . $h . ':' . $m . ':' . $s;
    if ($time >= $get_time['time']) {
        $actions['simg'] = '';
        $actions['stext'] = '';
        $sql->where("users", $id)->delete('silents');
        $actions['silent'] = '0';
    } else {
		$silent = $sql->where("users", $id)->orWhere("ip", ip())->getOne('silents', array('`where`'));
		$actions['simg'] = 'status_silent';
		$actions['stext'] = $text;
		$actions['silent'] = $silent['where'];
	     if (!$sql->where("users", $id)->has('silents')) {
	     $gt = $sql->where("users", $id)->orWhere("ip", ip())->orderBy('id', 'DESC')->getOne('silents', array('id', 'ip', 'muter', 'time', 'users', '`where`'));
            $sql->insert('silents', array(
               'ip' => ip(),
               'muter' => $gt['muter'],
                'time' => $gt['time'],
                'users' => $id,
                'where' => $gt['where']
            )); 
        }
    }
}
if ($sql->where("user", $id)->orWhere("ip", ip())->has('report_users')) {
	$get_time = $sql->where("user", $id)->orWhere("ip", ip())->getOne('report_users', 'time');
    if (!$get_time) {
		$get_time['time'] = 1;
    }
    if ($time >= $get_time['time']) {
        $actions['alert'] = 'شما از ریپورت خارج شدید';
        $sql->where("user", $id)->delete('report_users');
        $sql->where("id", $id)->update('users', array(
        'report' => 0
    ));
    }
}
if ($actions['exit'] != 'true') {
    $inputs = array('onlinelist' => $actions['onlinelist'],
        'permission' => $actions['permission'],
        'cvc' => $actions['cvc'],
        'cvc_chid' => $actions['cvc_chid'],
        'cvc_rid' => $actions['cvc_rid'],
        'vc' => $actions['vc'],
        'vc_key' => $actions['vc_key'],
        'status_c2c' => $actions['status_c2c'],
        'refresh' => $actions['refresh'],
        'alert' => $actions['alert'],
        'exit' => $actions['exit'],
        'benned' => $actions['benned'],
        'clear' => $actions['clear'],
        'avatar' => $actions['avatar'],
        'score' => (int) $scorecol,
        'lastsendtxt' => $actions['lastsendtxt'],
        'lastsendnum' => $actions['lastsendnum'],
        'lastcheckajax' => $actions['lastcheckajax'],
        'lastgetrank' => $actions['lastgetrank'],
        'simg' => $actions['simg'],
        'cf' => $actions['cf'],
        'cf_in' => $actions['cf_in'],
        'cf_key' => $actions['cf_key'],
        'move' => $actions['move'],
        'move_key' => $actions['move_key'],
        'stext' => $actions['stext'],
        'idle' => $actions['idle'],
        'countries' => $actions['countries'],
        'addfriend' => $actions['addfriend'],
        'idle_time' => $actions['idle_time'],
        'status_private' => $actions['status_private'],
        'status_cf' => $actions['status_cf'],
        'status_addfriend' => $actions['status_addfriend'],
        'my_blacklist' => $actions['my_blacklist'],
        'myOS' => $actions['myOS'],
        'ubrowser' => $actions['ubrowser'],
        'allowchat' => $actions['allowchat'],
	'reportstatus' => $actions['reportstatus'],
        'closepv' => $actions['closepv'],
        'silent' => $actions['silent']
    );
    $inputs = json_encode($inputs, JSON_UNESCAPED_UNICODE);
    $sql->where("id", $id)->update('onlines', array(
        'time' => $time,
        'ip' => ip(),
        'lastcheck' => $last,
        'inputs' => $inputs,
        'score' => $scorecol,
        'admin' => $admin
    ));
}
?>
