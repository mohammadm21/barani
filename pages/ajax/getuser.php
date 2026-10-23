<?php
$id = session('id');
if (!$id) {
    exit('login');
}
header::tojson();
$time = time();
$admin = session('admin');
$type = session('type');
function CheckAllowed($password) {
    global $admin;
    switch ($password) {
        case(''):return('1');
            break;
        case('7'):if ($admin != '0' or $admin == '1' or $admin == '2') {
                return('1');
            } else {
                return('0');
            }break;
        case('8'):if ($admin == '1' or $admin == '2') {
                return('1');
            } else {
                return('0');
            }break;
        case('78'):if ($admin != '0') {
                return('1');
            } else {
                return('0');
            }break;
        default:return('2');
            break;
    }
}

$onlines = array();
$online = array();
$rooms = array();
$emptyrooms = array();
if ($options['chat_ipformods'] == 'true' and ($admin != '0')) {
    $options['chat_ipformods'] = true;
} else {
    $options['chat_ipformods'] = false;
}
if ($admin != '0' and $usertype[$type]['loginhidden'] == 1) {
    $hide = true;
    $options['chat_ipformods'] = true;
} else {
    $hide = false;
}
$result = $sql->get('rooms', null, array('id', 'title', 'password', 'vip_user','timeroom'));
foreach ($result as $row) {
	$vip_user = json_decode($row['vip_user'], true);
	$rooms[$row['id']] = array(
		'id' => $row['id'],
		'title' => $row['title'],
		'password' => $row['password'],
		'vip_user' => isset($vip_user['vip_user']) ? $vip_user['vip_user'] : '',
		'is_admin' => $vip_user['is_admin'],
		'onlines' => 0 ,
                'timeroom' => $row['timeroom']
	);
if ($row['timeroom'] != "") {
    $timeroom = json_decode($row['timeroom'], true);
    $nowtr = time();
    if ($timeroom['time'] != '0' && $timeroom['time'] < $nowtr) {
        $sql->where("id", $row['id']);
        $sql->where("id","1","!=");
        $sql->delete('rooms');
    }
  }
}

$orders = array(array('o.roomid', 'ASC'));
switch ($options['method_online_sort']) {
	case 'sort_score':
		$orders[] = array('u.sort', 'ASC');
		$orders[] = array('o.score', 'DESC');
		break;
	case 'sort_name':
		$orders[] = array('u.sort', 'ASC');
		$orders[] = array('o.name', 'DESC');
		break;
	case 'type_score':
		$orders[] = array('o.type', 'DESC');
		$orders[] = array('o.score', 'DESC');
		break;
	case 'type_name':
		$orders[] = array('o.type', 'DESC');
		$orders[] = array('o.name', 'DESC');
		break;
	default:
		$orders[] = array('o.name', 'ASC');
		break;
}
$userOpt = json_decode($options['useroptions'], true);
$SeeOsUsers = in_array(session('type'), $userOpt['show_userOs']) ? true : false;
$SeeBRUsers = in_array(session('type'), $userOpt['show_browser']) ? true : false;
$SeeIdleUsers = in_array(session('type'), $userOpt['show_idleTime_onlinelist']) ? true : false;

foreach ($orders as $order) {
	call_user_func_array(array($sql, 'orderBy'), $order);
}
$sql->join('users ur', 'ur.id=o.id', 'LEFT');
$sql->join('profiles p', 'p.id=o.id', 'LEFT');
$sql->join('usertypes u', 'u.id=o.type', 'LEFT');
$result = $sql->get('onlines o', null, array('o.*', 'u.sort', 'p.shout','ur.typesetting'));
foreach ($result as $row) {
    if($row['time']  == 0){
        $inputsVisual = json_decode($row['inputs'], true);
        if(isset($inputsVisual['firstTime']) && isset($inputsVisual['lastTime'])){
            $visualTime = jdate::start('H:i', time());
            if(0 != $inputsVisual['firstTime'] && 0 != $inputsVisual['lastTime']){
                if(!(($visualTime >= $inputsVisual['firstTime']) && ($visualTime < $inputsVisual['lastTime']))){
                    continue;
                }
            }
        }
    }
if ($row['typesetting'] != "") {
    $type_setting = json_decode($row['typesetting'], true);
    $now_time = time();
    if ($type_setting['time'] != '0' && $type_setting['time'] < $now_time) {
        $typeinfo = $row['score'];
        $type = $type_setting['type'];
        $typesetting = '';
        $sql->where("id", $row['id']);
        $sql->update('users', array(
            'type' => $type,
            'score' => $typeinfo,
            'typesetting' => $typesetting
        ));
        $sql->where("id", $row['id']);
        $sql->update('onlines', array(
            'type' => $type,
            'score' => $typeinfo
        ));
    }
}
    $userleft = (int) (($time - $row['time']) / 60);
    if ($row['time'] != 0 and $userleft >= 3) {
        Users::Offline($row['id']);
    } else {
        $vip_is = 0;
        if (!empty($rooms[$row['roomid']]['vip_user'])) {
            $vip_is = 1;
        }
        $inputsUser = json_decode($row['inputs'], true);
        if ($rooms[$row['roomid']]['vip_user'] == null) {
            $rooms[$row['roomid']]['vip_user'] = 0;
        }
        $sidleTime = $inputsUser['silent'] == '0' && (isset($inputsUser['idle']) ? $inputsUser['idle'] == '1' : '0') ? (int) (($time - $inputsUser['idle_time']) / 60) : '';
        $sidletext = $sidleTime != 0 && $inputsUser['idle'] == '1' ? $sidleTime . ' دقيقه است به کسی پیام نداده' : '0';
        if ($options['show_idleTime_onlinelist'] != 'true') {
            $inputsUser['idle'] = '0';
            $sidletext = '0';
        }
        $suOS = 'null';
        if ($row['time'] != '0') {
            $suOS = $options['show_userOs'] == 'true' && $SeeOsUsers ? (isset($inputsUser['myOS']) ? $inputsUser['myOS'] : 'null') : 'null';
        } else {
            $suOS = $options['show_userOs'] == 'true' && $SeeOsUsers ? (isset($inputsUser['myOS']) ? $inputsUser['myOS'] : 'null') : 'null';
        }
        $suBr = 'null';
        if ($row['time'] != '0') {
            $suBr = $options['showubrowseronlist'] == 'true' && $SeeBRUsers ? (isset($inputsUser['ubrowser']) ? $inputsUser['ubrowser'] : 'null') : 'null';
        } else {
            $suBr = $options['showubrowseronlist'] == 'true' && $SeeBRUsers ? (isset($inputsUser['ubrowser']) ? $inputsUser['ubrowser'] : 'null') : 'null';
        }
        $suBrad = $admin != '0' ? (isset($inputsUser['ubrowser']) ? $inputsUser['ubrowser'] : 'Unknown') : 'Unknown';
        $adsuOS = $admin != '0' ? (isset($inputsUser['myOS']) ? $inputsUser['myOS'] : 'Unknown') : 'Unknown';
        $adcountriesfull = $admin != '0' ? (isset($countries[$inputsUser['countries']]['1']) ? $countries[$inputsUser['countries']]['1'] : 'Unknown') : 'Unknown';
        $adcountries = $admin != '0' ? (isset($inputsUser['countries']) ? $inputsUser['countries'] : 'Unknown') : 'Unknown';
        $array = array(
            'un' => (mb_substr($row['name'], 0, 4, 'utf-8') == '////' ? $row['name'] : safe::string($row['name'])),
            'unto' => (mb_substr($row['username'], 0, 4, 'utf-8') == '////' ? $row['username'] : safe::string($row['username'])),
            'ui' => $row['id'],
            'up' => $row['type'],
	    'us' => (string)$row['gender'],
            'ri' => $row['roomid'],
            'sc' => $row['score'],
            'ra' => CheckAllowed($rooms[$row['roomid']]['password']),
            'vu' => $rooms[$row['roomid']]['vip_user'],
            'vip' => $vip_is,
            'Os' => $suOS,
            'adcountriesfull' => $adcountriesfull,
            'adcountries' => $adcountries,
            'adOs' => $adsuOS,
            'spriv' => isset($inputsUser['status_private']) ? $inputsUser['status_private'] : 'pmme',
            'sidle' => $inputsUser['silent'] == '0' && $SeeIdleUsers ? $inputsUser['idle'] : '0',
            'sidleTime' => $sidletext,
            'ubrowser' => $suBr,
            'adubrowser' => $suBrad,
            'pvstatus' => isset($inputsUser['closepv']) ? $inputsUser['closepv'] : 'open',
            'hid'=>$row['hide'],
			'allonlines'=>$allonlines,
            'ln' => isset($inputsUser['onlinelist']) && $inputsUser['onlinelist'] == 'likeline' ? 'likeline' : 'default'
        );
        if (isset($inputsUser['simg'])) {
            $array['si'] = $inputsUser['simg'];
        }
        if (isset($inputsUser['stext'])) {
            $array['st'] = $inputsUser['stext'];
        }
        if ((isset($inputsUser['avatar']) ? $inputsUser['avatar'] : '') != $options['defaultavatar'] && $inputsUser['avatar'] != null) {
            $array['a'] = $inputsUser['avatar'];
        } else {
            if ($row['gender'] == '1') {
                $array['a'] = 'maledefualt.png';
            } else {
                $array['a'] = 'femaledefualt.jpg';
            }
        }
        if ($admin == '1' or  $options['chat_ipformods'] or $rooms[$row['roomid']]['vip_user'] == session('id') and $rooms[$row['roomid']]['is_admin'] == 1) {
            $array['uip'] = $row['ip'];
        }
        if ($row['hide'] == '1' && $hide == true) {
            $onlines[] = $array;
        } else if ($row['hide'] == '0') {
            $onlines[] = $array;
        }
        $online[$row['id']] = array('user' => safe::string($row['name']),
            'user_id' => $row['id'],
            'user_priv' => $row['type'],
            'user_sex' => $row['gender'],
            'room_id' => $row['roomid'],
            'room' => $row['roomname'],
            'avatar' => isset($inputsUser['avatar']) && $inputsUser['avatar'] != null ? $inputsUser['avatar'] : ($row['gender'] == '1' ? 'maledefualt.png' : 'femaledefualt.jpg')
        );
      //  if ($row['hide'] == '0') {
        $ons = $rooms[$row['roomid']]['onlines'] ++; $ons++; 
        $sql->where('id',$row['roomid'])->update('rooms',array('onlines' => $ons ));
        $allonlines = $ons;
        $ons = 0; 
      //  }
        unset($row);
    }
}
foreach ($rooms as $room) {
    if ($room['onlines'] == 0) {
        $is_vip = 0;

        if (!empty($room['vip_user'])) {
            $is_vip = 1;
        }
        $emptyrooms[] = array('room_id' => $room['id'],
            'room_allowed' => CheckAllowed($room['password']),
            'room' => $room['title'],
            'vip' => $is_vip
        );
    }
}
$r = array();
foreach ($rooms as $room) {
    if ($options['flashchat'] == 'true') {
        $r[] = array('ri' => $room['id'],
            'rn' => $room['title'],
            'ro' => $room['onlines']
        );
    } elseif ($room['onlines'] != 0) {
        if ($room['vip_user'] == null) {
            $room['vip_user'] = 0;
        }
        if ($room['is_admin'] == null) {
            $room['is_admin'] = 0;
        }
        $r[] = array('ri' => $room['id'],
            'rn' => $room['title'],
            'vu' => $room['vip_user'],
            'va' => $room['is_admin']
        );
    }
}
if ($options['roomstyle'] == 'true') {
    $query = db::query("SELECT `id` FROM {prefix}rooms");
    $s = array();
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        $s[$row['id']] = array('onlines' => (int) db::num('onlines', "`roomid`='" . $row['id'] . "'"));
    }
    mysqli_free_result($query);
    $output = array('userOnline' => $onlines, 'all_empty_rooms' => $emptyrooms, 'rooms' => $r, 'onlineroom' => $s);
} else {
    $output = array('userOnline' => $onlines, 'all_empty_rooms' => $emptyrooms, 'rooms' => $r);
}
if ($options['flashchat'] == 'true') {
    unset($output['all_empty_rooms']);
}
echo(json_encode($output, JSON_UNESCAPED_UNICODE));
?>
