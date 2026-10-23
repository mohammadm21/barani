<?php

if (!session('id'))
    exit('login');
if (session('type') == 1)
    exit('ابتدا عضو شوید مهمان اجازه استفاده ندارد');

if (safe::string($_POST['switch']) == 'music' || safe::string($_POST['switch']) == 'film') {
    $allow_users = json_decode($options['useroptions'], true);

    if (safe::string($_POST['switch']) == 'music') {
        if ($options['allow_send_music'] == "true") {
            if (in_array(session('type'), $allow_users['send_music'])) {
                $sql->where("id", session('id'));
                $user = $sql->getOne('users', array('bank'));
                if ($user['bank'] - $options['music_score'] < 0) {
                    exit("امتیاز شما کافی نیست");
                } 
              //-----------anti tatalooo --------------
                 $words = array('Tataloo','pr3m.ir','jenabmusic','JENABMUSIC', 'tataloo', 'تتلو' ,'امیرتتلو', 'tatalooo', 'TATALOOO', 'TATALOO' , '_tataloo_', '_Tataloo_', '_TATALOO_', 'Tataloo_', '_Tataloo', '_TATALOO', 'TATALOO_', 'amirtataloo', 'Amirtataloo', 'AmirTataloo', 'Amir_Tataloo', 'amir_tataloo'); //   
                  $string =$_POST['url'];

    				$pattern = '/\b(' . implode('|', $words) . ')\b/i'; // آرایه کلمات را به رشته regex تبدیل می‌کنیم
                  $match = preg_match($pattern, $string); // جستجوی الگو در رشته

                  if ($match) {
                      exit("کصکش تتلو نفرست");
                  }
              //---------------------------------------
              else {
                    if (strstr($_POST['url'], 'http:') || strstr($_POST['url'], 'https:')) {
                        $url = safe::string(trim($_POST['url']));
                        $text = '<audio controls preload="none"><source src="' . $url . '" type="audio/mp3"></audio>';
                        $me_bank = $options['music_score'];
                      $sql->insert('message', array(
                            'user' => session('id'),
                            'username' => session('name'),
                            'color' => session('color'),
                            'room' => session('room'),
                            'text' => $text,
                            'date' => time(),
                            'to' => -1,
                            'into' => 'main',
                            'type' => session('admin')
                        ));
                        $sql->where("id", session('id'))->update('onlines', array(
                            'score' => $sql->dec($me_bank)
                        ));
                        $sql->where("id", session('id'))->update('users', array(
                            'score' => $sql->dec($me_bank)
                        ));
                        echo 'ok';
                        $admins = array();
                        $is_admin = array(1, 2, 3);
                        $allow_admins = json_decode($options['useroptions'], true);
                        foreach ($usertype as $types) {
                            if (in_array($types['admin'], $is_admin)) {
                                $admins[] = $types['id'];
                            }
                        }
                        $note = '<table dir="rtl" cellpadding="3" border="1" align="center">
											<tr><td><b>کاربر:</b></td><td><b><font color=red>' . session('name') . '</font></b></td></tr>
											<tr><td><b>درجه:</b></td><td><b><font color=red><img src="/' . $usertype[Users::get('type', session('id'))]['icon'] . '"></font></b></td></tr>
											<tr><td><b>فعالیت:</b></td><td><b><font color=red>ارسال موزیک در عمومی . از کاربر مقدار ' . $options['music_score'] . ' امتیاز بانکی کم شد</font></b></td></tr>
										</table><hr>';
                        $implode = implode(',', $admins);
                          $sql->where("type", $admins,'in');
                        foreach($sql->get('onlines', null, array('id', 'type', 'roomid')) as $row){
                            if ($row['roomid'] == '1') {
                                $row['roomid'] = '-1';
                            }
                            if (in_array($row['type'], $admins)) {
                                if (in_array($row['type'], $allow_admins['send_user2private_admins'])) {
                                  $sql->insert('message', array(
                                        'user' => -1,
                                        'username' => '<center>SYSTEM [ <b><font color=red>ارسال گزارش</font></b> ]</center>',
                                        'color' => '000000',
                                        'room' => $row['roomid'],
                                        'text' => $note,
                                        'date' => time(),
                                        'to' => $row['id'],
                                        'into' => 'pm',
                                        'type' => session('admin')
                                    ));
                                }
                            }
                        }
                    } else {
                        exit('ورودی ها نباید خالی باشند');
                    }
                }
            } else {
                exit('برای گروه کاربری شما محدودیت قرار داده شده است');
            }
        } else {
            exit('این قسمت توسط مدیر چت روم غیرفعال شده است');
        }
    }

    if (safe::string($_POST['switch']) == 'film') {
        if ($options['allow_send_film'] == "true") {
            if (in_array(session('type'), $allow_users['send_film'])) {
                      $sql->where("id", session('id'));
                $user = $sql->getOne('users', array('bank'));
                if ($user['bank'] - $options['film_score'] < 0) {
                    exit("امتیاز شما کافی نیست");
                } else {
                    if (strstr($_POST['url'], 'http:') || strstr($_POST['url'], 'https:')) {
                        $url = safe::string(trim($_POST['url']));
                        $text = '<video width="280" height="200" controls controls preload="none"><source src="' . $url . '" type="video/mp4"></video>';
                        $me_bank = $options['film_score'];
                      $sql->insert('message', array(
                            'user' => session('id'),
                            'username' => session('name'),
                            'color' => session('color'),
                            'room' => session('room'),
                            'text' => $text,
                            'date' => time(),
                            'to' => -1,
                            'into' => 'main',
                            'type' => session('admin')
                        ));
                        $sql->where("id", session('id'))->update('onlines', array(
                            'score' => $sql->dec($me_bank)
                        ));
                        $sql->where("id", session('id'))->update('users', array(
                            'score' => $sql->dec($me_bank)
                        ));
                        echo 'ok';
                        $admins = array();
                        $is_admin = array(1, 2, 3);
                        $allow_admins = json_decode($options['useroptions'], true);
                        foreach ($usertype as $types) {
                            if (in_array($types['admin'], $is_admin)) {
                                $admins[] = $types['id'];
                            }
                        }
                        $note = '<table dir="rtl" cellpadding="3" border="1" align="center">
											<tr><td><b>کاربر:</b></td><td><b><font color=red>' . session('name') . '</font></b></td></tr>
											<tr><td><b>درجه:</b></td><td><b><font color=red><img src="/' . $usertype[Users::get('type', session('id'))]['icon'] . '"></font></b></td></tr>
											<tr><td><b>فعالیت:</b></td><td><b><font color=red>ارسال فیلم در عمومی . از کاربر مقدار ' . $options['film_score'] . ' امتیاز بانکی کم شد</font></b></td></tr>
										</table><hr>';
                        $implode = implode(',', $admins);
                   $sql->where("type", $admins,'in');
                        foreach($sql->get('onlines', null, array('id', 'type', 'roomid')) as $row){
                            if ($row['roomid'] == '1') {
                                $row['roomid'] = '-1';
                            }
                            if (in_array($row['type'], $admins)) {
                                if (in_array($row['type'], $allow_admins['send_user2private_admins'])) {
                                  $sql->insert('message', array(
                                        'user' => -1,
                                        'username' => '<center>SYSTEM [ <b><font color=red>ارسال گزارش</font></b> ]</center>',
                                        'color' => '000000',
                                        'room' => $row['roomid'],
                                        'text' => $note,
                                        'date' => time(),
                                        'to' => $row['id'],
                                        'into' => 'pm',
                                        'type' => session('admin')
                                    ));
                                }
                            }
                        }
                    } else {
                        exit('ورودی ها نباید خالی باشند');
                    }
                }
            } else {
                exit('برای گروه کاربری شما محدودیت قرار داده شده است');
            }
        } else {
            exit('این قسمت توسط مدیر چت روم غیرفعال شده است');
        }
    }
} else {
    exit('خطا');
}
?>
