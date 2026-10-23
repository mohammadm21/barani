<?php

$id = session('id');
if (!$id) {
    exit('login');
}
header::tojson();
$admin = session('admin');

function CheckAllowed($password) {
    global $admin;
    switch ($password) {
        case(''):return(1);
            break;
        case('7'):if ($admin == '2' or $admin == '3') {
                return(1);
            } else
                return(0);break;
        case('8'):if ($admin == '1') {
                return(1);
            } else {
                return(0);
            }break;
        case('78'):if ($admin != '0') {
                return(1);
            } else {
                return(0);
            }break;
        default:return(2);
            break;
    }
}

$output = array();
$rooms = $roomsonline = array();
$output['r'] = array();
foreach($sql->get('rooms', null, array('id', 'title', 'password')) as $row){
    $rooms[$row['id']] = array('i' => $row['id'],
        't' => $row['title'],
        'a' => CheckAllowed($row['password'])
    );
    $roomsonline[$row['id']] = 0;
    unset($row);
}
$userOpt = json_decode($options['useroptions'], true);
$SeeOsUsers = in_array(session('type'), $userOpt['show_userOs']) ? true : false;
$output['o'] = array();
$orders = array();
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
foreach ($orders as $order) {
	call_user_func_array(array($sql, 'orderBy'), $order);
}
$sql->where("o.hide", 0);
$sql->join('usertypes u', 'u.id=o.type', 'LEFT');
$result = $sql->get('onlines o', null, array('o.*', 'u.sort'));
foreach($result as $row){
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
    $row['inputs'] = json_decode($row['inputs'], true);
    $showos = 'null';

    if ($row['time'] != '0') {
        $showos = $options['show_userOs'] == 'true' && $SeeOsUsers ? (isset($row['inputs']['myOS']) ? $row['inputs']['myOS'] : 'null') : 'null';
    } else {
        $showos = $options['show_userOs'] == 'true' && $SeeOsUsers ? (isset($row['inputs']['myOS']) ? $row['inputs']['myOS'] : 'null') : 'null';
    }
    
    $o = array('i' => (int) $row['id'],
        'n' => $row['name'],
        'g' => (int) $row['gender'],
        't' => (int) $row['type'],
        'r' => (int) $row['roomid'],
        'o' => $showos,
        'sp' => isset($row['inputs']['status_private']) ? $row['inputs']['status_private'] : 'pmme',
    );
    if ((isset($row['inputs']['avatar']) ? $row['inputs']['avatar'] : '') != $options['defaultavatar'] && $row['inputs']['avatar'] != null) {
        $o['v'] = $row['inputs']['avatar'];
    } else {
        if ($row['gender'] == '1') {
            $o['v'] = 'maledefualt.png';
        } else {
            $o['v'] = 'femaledefualt.jpg';
        }
    }
    if (isset($row['inputs']['simg']) && $row['inputs']['simg'] != '') {
        $o['s'] = $row['inputs']['simg'];
    }
    $output['o'][] = $o;
    if (isset($roomsonline[$row['roomid']])) {
        $roomsonline[$row['roomid']] ++;
    }
    unset($row, $o);
}
arsort($roomsonline);
foreach ($roomsonline as $room => $online) {
    $room = $rooms[$room];
    $room['o'] = $online;
    $output['r'][] = $room;
}


$output['status'] = true;

echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>
