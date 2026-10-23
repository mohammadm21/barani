<?php

$output = array();
$id = session('id');
if ($id) {
    $sql->where("id", $id);
    if(!$me = $sql->getOne('onlines', array('id', 'name', 'type', 'roomid', 'inputs', 'score', 'lastcheck'))){
        $sql->where("id", $id);
        if($user = $sql->getOne('users', array('username', 'name', 'gender', 'type'))){
            $room = session('room');
            Users::addOnline($id, $user['username'], $user['name'], $user['gender'], $user['type'], session('hide'), $room, chatroom::getNameRoom($room));
            $sql->where("id", $id);
            $me = $sql->getOne('onlines', array('id', 'name', 'type', 'roomid', 'inputs', 'score', 'lastcheck'));
        } else {
            $output = array('status' => false,
                'e' => 'usernotfound'
            );
        }
    }
    $idmsg = session('idmsg');
    if (!$idmsg) {
        $idmsg = 0;
    }
    if ($me) {
        $me['inputs'] = json_decode($me['inputs'], true);
        $me['admin'] = $usertype[$me['type']]['admin'];
        if (isset($_POST['text']) and isset($_POST['to'])) {
            if ($me['inputs']['silent'] == 0) {
                if ($me['inputs']['exit'] == 'false') {
                    if ($me['inputs']['benned'] == "0") {
                        if ((isset($me['inputs']['status_private']) ? $me['inputs']['status_private'] : '') == 'pmme') {
                            $text = safe::string($_POST['text']);
                            $to = safe::string($_POST['to']);
                            if (isset($_POST['into'])) {
                                $into = safe::string($_POST['into']);
                            } else {
                                if ($to != '-1') {
                                    $into = 'pm';
                                }
                            }
                            // ---- gate-haye ersal mesl desktop (get.php) ----
                            $send = true;
                            $myOptions = json_decode($options['useroptions'], true);
                            $scorecol = $me['score'];
                            $rstatus = 'pmme';
                            $rfriends = array();
                            if ($to != '-1') {
                                if ($me['admin'] != '1') {
                                    $ubl = $sql->where("user", $to)->getOne('my_blacklist', array('blacklist'));
                                    $my_blacklist = $ubl ? json_decode($ubl['blacklist'], true) : array();
                                    if (in_array($id, $my_blacklist)) {
                                        $send = false;
                                        $output = array('status' => false, 'e' => 'blme');
                                    }
                                }
                                $rrow = $sql->where("id", $to)->getOne('onlines', array('inputs'));
                                if ($rrow) {
                                    $rt = is_array($rrow['inputs']) ? $rrow['inputs'] : json_decode($rrow['inputs'], true);
                                    $rstatus = (isset($rt['status_private'])) ? $rt['status_private'] : 'pmme';
                                } else {
                                    $rrow = $sql->where("id", $to)->getOne('users', array('friends', 'inputs'));
                                    if ($rrow && isset($rrow['friends'])) {
                                        $rfriends = json_decode($rrow['friends'], true);
                                    }
                                    if ($rrow && isset($rrow['inputs'])) {
                                        $rt = is_array($rrow['inputs']) ? $rrow['inputs'] : json_decode($rrow['inputs'], true);
                                        if ($rt && isset($rt['status_private'])) {
                                            $rstatus = $rt['status_private'];
                                        }
                                    }
                                }
                                if ($send && $me['admin'] != '1' && $me['admin'] != '2' && $me['admin'] != '3') {
                                    if ($rstatus == 'nopm' && !in_array($id, $rfriends)) {
                                        $send = false;
                                        $output = array('status' => false, 'e' => 'nopm');
                                    }
                                }
                            }
                            // taeid vorud kasusi (allowchat) mesl desktop
                            if ($send && $to > 0 && $me['admin'] != '1' && $me['admin'] != '2' && $me['admin'] != '3' && $rstatus != 'nopm') {
                                $rrow = $sql->where("id", $to)->getOne('onlines', array('inputs'));
                                $userallowchat = array();
                                if ($rrow) {
                                    $rt = is_array($rrow['inputs']) ? $rrow['inputs'] : json_decode($rrow['inputs'], true);
                                    $userallowchat = isset($rt['allowchat']) ? $rt['allowchat'] : array();
                                }
                                $group_allowchat = isset($myOptions['group_allowchat']) ? $myOptions['group_allowchat'] : array();
                                if (!in_array($id, $userallowchat) && !in_array($id, $rfriends)) {
                                    if (in_array($me['type'], $group_allowchat)) {
                                        $send = false;
                                        $output = array('status' => false, 'e' => 'gpperm');
                                        $sql->insert('message', array(
                                            'user' => $id,
                                            'username' => $me['name'],
                                            'color' => session('color'),
                                            'room' => session('room'),
                                            'text' => 'request',
                                            'date' => $time,
                                            'to' => (int) $to,
                                            'into' => 'allowchat',
                                            'type' => $me['admin']
                                        ));
                                    }
                                }
                            }
                            // mahdudiat noe karbari (allowtypessender / allowtypessender_main)
                            if ($send &&
                                ((!in_array($me['type'], $myOptions['allowtypessender']) && $into == 'pm') ||
                                 (!in_array($me['type'], $myOptions['allowtypessender_main']) && $into == 'main'))) {
                                $send = false;
                                $output = array('status' => false, 'e' => 'gpperm');
                            }
                            // emtiaz / mehman baraye ersal omumi
                            if ($send && $to == '-1') {
                                if ($options['scoretosendmsg'] != '0' && $scorecol < ((int) $options['scoretosendmsg'])) {
                                    $send = false;
                                    $output = array('status' => false, 'e' => 'gpperm');
                                }
                                if ($send && $options['chat_sendmsguest'] == 'false' && $me['type'] == $options['defaultusertype']) {
                                    $send = false;
                                    $output = array('status' => false, 'e' => 'gpperm');
                                }
                            }
                            if ($me['admin'] != '1') {
                                $text = safe::string($text);
                                $text = Message::badwords($text, $into);
                            }
                            if ($send) {
                                if ($to == '-1') {
                                    $msgid = Message::add('-1', $text, session('room'), 'main');
                                    $output = array(
                                        'status' => true,
                                        'msgid' => $msgid
                                    );
                                } else {
                                    $msgid = Message::add($to, $text, '-1', $into);
                                    $output = array(
                                        'status' => true,
                                        'msgid' => $msgid
                                    );
                                }
                            }
                            unset($to, $text, $into);
                        } else {
                            $output = array('status' => false,
                                'e' => 'nopm'
                            );
                        }
                    } else {
                        $output = array('status' => false,
                            'e' => 'youbanned'
                        );
                    }
                } else {
                    $output = array('status' => false,
                        'e' => 'yououted'
                    );
                }
            } else {
                $output = array('status' => false,
                    'e' => 'youslient'
                );
            }
        }
        $block_all = session('block_all');
        if (!is_array($block_all)) {
            $block_all = array();
            session('block_all', array());
        }
        $block_priv = session('block_priv');
        if (!is_array($block_priv)) {
            $block_priv = array();
            session('block_priv', array());
        }
        $pc = isset($_GET['pc']) ? safe::string($_GET['pc']) : 'f';
        /*if ($pc == 't') {
            $pcQuery = "`user` != '$id' and (`room` = '-1' or `room`='" . session('room') . "') and (`to`='-1' or `to`='$id')";
        } else {
            $pcQuery = "`user` != '$id' and `room` = '-1' and `to`='$id'";
        }*/
		$sql->where("user", $id, '!=');
		if ($pc == 't') {
			$sql->where("room", array(-1, session('room')), 'in');
			$sql->where("to", array(-1, $id), 'in');
		} else {
			$sql->where("room", -1);
			$sql->where("to", $id);
		}
        $last = $me['lastcheck'];
		if ($last == '0') {
			$last = $sql->orderBy("id", "DESC")->getValue('message', 'id');
		}
		$result = $sql->where('id', $last, '>')->orderBy("date", "ASC")->get('message', null, array('id', 'user', 'username', 'color', 'room', 'text', 'date', 'to', 'into', 'type'));
		unset($pc);
		foreach ($result as $row) {
            if (in_array($row['user'], $block_all)) {
                continue;
            }
            if ($row['to'] == $id and in_array($row['user'], $block_priv)) {
                continue;
            }

            $row['to'] = $row['to'] == '-1' ? '0' : $row['user'];
			$avatar = $options['defaultavatar'];
            
            $sql->where("id", $row['user']);
            $online = $sql->getOne('onlines', array('gender', 'type', 'inputs'));
			$inputs = json_decode($online['inputs'], true);
		
            if ($row['into'] != 'pm') {
                $idmsg++;
            }
            $temp = array('un' => $row['username'],
                'ui' => $row['user'],
                't' => $row['text'],
                'mid' => $row['id'],
				'p'=>$row['to'],
                'into' => $row['into'],
				'cf' => $row['into'] == 'pm' || $row['into'] == 'main' ? '0' : '1',
                'd' => date('H:i', $row['date']),
            );
			if ((isset($inputs['avatar']) ? $inputs['avatar'] : '') != $options['defaultavatar'] && $inputs['avatar'] != null) {
        $temp['ua'] = $inputs['avatar'];
    } else {
        if ($row['gender'] == '1') {
            $temp['ua'] = 'maledefualt.png';
        } else {
            $temp['ua'] = 'femaledefualt.jpg';
        }
    }
            $jmsg = str_replace(array('<b>', '</b>', '<i>', '</i>'), "", $temp['t']);
            if (isJson($jmsg)) {
                $jd = json_decode($jmsg, true);
                if (isset($jd['action'])) {
                    if ($jd['action'] == 'reply') {
                        $temp['reply'] = $jd['umsg'];
                        $temp['name'] = $jd['uname'];
                        $temp['t'] = $jd['mymsg'];
                        if (isset($jd['isimg'])) {
                            $temp['reply'] = '<img width="50" height="50" id="nocheck" src="' . $jd['umsg'] . '">';
                        }
                    }
                    if ($jd['action'] == 'delete' and $row['into'] == 'deletmsg') {
                        $output['clear_s'][] = $jd['msgid'];
                    }
					if ($jd['action'] == 'editonemsg' or $row['into'] == "editonemsg") {
				        $output['edit_s'][] = array("id"=>$jd['msgid'],"message"=>$jd['message'],"act"=>$jd['act']);
						// ردیف لاگِ ویرایش نباید به‌صورت پیامِ ماکتی (متنِ JSON) در موبایل نمایش داده شود.
						$temp['message'] = '';
                  }
                  else if ($row['into'] == "pinmsg" or $row['into'] == "unpinmsg") {
			// ردیف لاگِ پین فقط برای انتشارِ به‌روزرسانیِ بنر است؛
			// نباید به‌صورت پیامِ ماکتی (متنِ JSON) در موبایل دیده شود.
			$temp['message'] = '';
                  }
                }
            }
            if (isset($online['inputs']['avatar']) and $online['inputs']['avatar'] != $options['defaultavatar']) {
              $temp['ua'] = $online['inputs']['avatar'];
              } 
            if ((isset($online['inputs']['avatar']) ? $online['inputs']['avatar'] : '') != $options['defaultavatar'] && $online['inputs']['avatar'] != null) {
                $temp['ua'] = $online['inputs']['avatar'];
            } else {
                if ($online['gender'] == '1') {
                    $temp['ua'] = 'maledefualt.png';
                } else {
                    $temp['ua'] = 'femaledefualt.jpg';
                }
            }
        
            if ($options['chat_defaultcolor'] != $row['color']) {
                $temp['c'] = $row['color'];
            }
            $output['m'][] = $temp;
            $last = (int) $row['id'];
			
            unset($temp, $row, $online);
        }
		session('idmsg',$idmsg);
        if (!session('myOS')) {
            session('myOS', 'Unknown');
        }
        $me['myOS'] = session('myOS');
        if (!session('ubrowser')) {
            session('ubrowser', 'Unknown');
        }
        $me['ubrowser'] = session('ubrowser');
        if (!session('countries')) {
            session('countries', 'Unknown');
        }
        if (!session('idle_time')) {
            session('idle_time', $time + $options['check_idle'] * 60);
        }
        $sessIdle = session('idle_time');
        $check_idle_time = $time - $sessIdle;
        if (!empty($_REQUEST['text'])) {
            session('idle_time', $time);
        } elseif (true && $check_idle_time > ($options['check_idle'] * 60)) {
            $me['idle'] = '1';
            $me['idle_time'] = $sessIdle;
        }
        $me['countries'] = session('countries');
        unset($result, $block_all, $block_priv);
        if (isset($me['inputs']['refresh']) and $me['inputs']['refresh'] == 1) {
            $output['r'] = 1;
        }
        if (isset($me['inputs']['alert']) and $me['inputs']['alert'] != 'null') {
            $output['a'] = $me['inputs']['alert'];
        }
        if (isset($me['inputs']['clear']) and $me['inputs']['clear'] == 1) {
            $output['c'] = 1;
        }
        if (isset($me['inputs']['exit']) and $me['inputs']['exit'] == 'true') {
            $output['e'] = 1;
        }
        if (isset($me['inputs']['silent']) and $me['inputs']['silent'] == 1) {
            $output['s'] = 1;
        }
        if (isset($me['inputs']['banned']) and $me['inputs']['banned'] == 1) {
            $output['s'] = 1;
        }
        if (isset($me['inputs']['status_private']) and $me['inputs']['status_private'] != 'null') {
            $output['status_private'] = $me['inputs']['status_private'];
        }
        // اثر انگشت: اگر مدیر درحالِ اخراج flag زده، از کلاینت بخواه اثر انگشتش را
        // فوراً بفرستد تا ردیفِ abuser تضمین شود (ضبطِ هنگامِ اخراج).
        // ممکن است فلگِ onlines با حذفِ بن از بین رفته باشد؛ پس به جدولِ ماندگار
        // ahyir_fp_capture هم نگاه می‌کنیم که بعد از ورود دوباره/ایپی‌عوض سرپا باشد.
        $fpCapture = 0;
        if (isset($me['inputs']['fp_capture']) and $me['inputs']['fp_capture'] == 1) {
            $fpCapture = 1;
        }
        if (!$fpCapture) {
            try {
                $sql->where('user_id', $id);
                if ($sql->tableExists('fp_capture') && $sql->getOne('fp_capture', array('id'))) {
                    $fpCapture = 1;
                }
            } catch (Exception $e) {
                $fpCapture = 0;
            }
        }
        if ($fpCapture) {
            $output['fp_capture'] = 1;
        }
        $me['status_private'] = !isset($me['inputs']['status_private']) ? 'pmme' : ($me['inputs']['status_private']);
        $me['inputs']['status_private'] = $me['status_private'];
        $me['inputs']['refresh'] = '0';
        $me['inputs']['idle'] = $me['idle'];
        $me['inputs']['idle_time'] = $me['idle_time'];
        $me['inputs']['status_c2c'] = !isset($me['status_c2c']) ? 'reqme' : $me['status_c2c'];
        $me['inputs']['countries'] = $me['countries'];
        $me['inputs']['alert'] = 'null';
        $me['inputs']['clear'] = '0';
        $me['inputs']['myOS'] = $me['myOS'];
        $me['inputs']['simg'] = $me['simg'];
        $me['inputs']['ubrowser'] = $me['ubrowser'];
        $me['inputs'] = json_encode($me['inputs'], JSON_UNESCAPED_UNICODE);
        $sql->where("id", $id)->update('onlines', array(
            'time' => time(),
            'ip' => ip(),
            'lastcheck' => $last,
            'inputs' => $me['inputs']
        ));
        $output['gstatus'] = true;
        // پینِ فعال فعلی برای نمایش بنرِ استیکی در بالای چت عمومی.
        $output['pin'] = 0;
        try {
            $pin_row = $sql->where('active', 1)->getOne('pinned', array('id','username','name','message'));
            if ($pin_row) {
                $output['pin'] = array('id'=>(int)$pin_row['id'], 'username'=>$pin_row['username'], 'name'=>$pin_row['name'], 'message'=>$pin_row['message']);
            }
            unset($pin_row);
        } catch (Exception $e) {
            // جدول pinned هنوز ساخته نشده (migration اعمال نشده)؛ چت عادی بدون پین ادامه می‌یابد.
            $output['pin'] = 0;
        }
    } else {
        $output = array('status' => false,
            'e' => 'cannotloadonline'
        );
    }
} else {
    $output = array('status' => false,
        'e' => 'login'
    );
}
header::tojson();
echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>
