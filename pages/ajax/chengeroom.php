<?php

if (!session('id')) {
    exit();
}

function gotoroom($room) {
    db::update('onlines', "`roomid`='$room[0]', `roomname`='$room[1]'", "`id`='" . session('id') . "'");
    if (session('room') != $room[0]) {
        if ($GLOBALS['options']['flashchat'] == 'false') {
            Message::AddMasageSYS(session('name') . " به $room[1] وارد شد.", $room[0]);
            Message::AddMasageSYS($room['wellcomemsg'], '-1', session('id'));
        }
        session('room', $room[0]);
        session('roomname', $room[1]);
    }
    echo('ok');
}

$room = safe::number($_REQUEST['room_id']);
if (session('admin') != '1') {
    $room_row = db::get('rooms', "`capacity`, `type`", "`id`='$room'");
    $onlines = db::num('onlines', "`roomid`='$room' and time!='0'");
    $expl = explode(',', $room_row[1]);
    if (!in_array(session('type'), $expl) && $room_row[1] != 0) {
        exit('no access');
    }
    if ($room_row[0] != 0) {
        if ($onlines >= $room_row[0] && session('admin') == '0') {
            exit('cap is full');
        }
    }
}
if ($room = db::get('rooms', "`id`, `title`, `password`, `wellcomemsg`", "`id`='$room'")) {
    if ($room['password'] != '') {
        if ($room['password'] == '7') {
            if (session('admin') == '2' or session('admin') == '3' or session('admin') == '4' or session('admin') == '5' or session('admin') == '6') {
                gotoroom($room);
            } else {
                echo('permissien');
                return;
            }
        } elseif ($room['password'] == '8') {
            if (session('admin') == '1') {
                gotoroom($room);
            } else {
                echo('permissien');
                return;
            }
        } elseif ($room['password'] == '78') {
            if (session('admin') != '0') {
                gotoroom($room);
            } else {
                echo('permissien');
                return;
            }
        } else {
            if (isset($_REQUEST['password']) and $_REQUEST['password'] != '') {
                $password = base64_encode($_REQUEST['password']);
                if ($room['password'] == $password) {
                    gotoroom($room);
                } else {
                    echo('pass');
                    return;
                }
            } else {
                echo('getpass');
                return;
            }
        }
    } else {
        gotoroom($room);
    }
}
?>