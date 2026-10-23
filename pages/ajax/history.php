<?php

check_session();
if(!chatroom::checkPermission("history")): exit("perm"); endif;
$defualtroom = chatroom::defaultRoom();
$wheres = array();
if (isset($_REQUEST['room'])) {
    $order = 'DESC';
    $room = safe::number($_REQUEST['room']);
    if ($_REQUEST['room'] == 'khososi') {
		$wheres[] = array('(`to` = ' . session('id') . ' OR `user` = ' . session('id') . ") and `to` != -1");
    } elseif (chatroom::checkroomid($room)) {
	$password = $sql->where('id', $room)->getValue('rooms', 'password');
        if ($password != '') {
            if ($password == '7') {
                if (session('admin') != '2' and session('admin') != '3' and session('admin') != '4' and session('admin') != '5' and session('admin') != '6' and session('admin') != '7') {
                    echo('permissien');
                    return;
                }
            } elseif ($password == '8') {
                if (session('admin') != '1') {
                    echo('permissien');
                    return;
                }
            } elseif ($password == '78') {
                if (session('admin') != '1' and session('admin') != '2' and session('admin') != '3' and session('admin') != '4' and session('admin') != '5' and session('admin') != '6' and session('admin') != '7') {
                    echo('permissien');
                    return;
                }
            } else {
                if (isset($_REQUEST['password']) and $_REQUEST['password'] != '') {
                    $password2 = base64_encode($_REQUEST['password']);
                    if ($password != $password2) {
                        echo('pass');
                        return;
                    }
                } else {
                    echo('getpass');
                    return;
                }
            }
        }
	$wheres[] = array('room', $room);
	$query = $sql->get('rooms');
        $rooms = array();
		foreach ($query as $room) {
			$rooms[$room['id']] = $room['title'];
        }
    } else {
        exit();
    }
} else {
    exit();
}
$page = isset($_REQUEST['page']) ? ((int) safe::string($_REQUEST['page'])) : 1;
if ($page == 1) {
    $startpage = 0;
} else {
    $startpage = ($page - 1) * $msgshostory;
}
$rowsadd = 0;
$rows = array();
foreach($wheres as $where){
	call_user_func_array(array($sql, 'where'), $where);
}
$sql->orderBy('id', 'DESC');
$query = $sql->get('message', array($startpage, $msgshostory));
header::toxml();
$color = 'D5D5D7';
$admin = session('admin');
echo('<?xml version="1.0" ?><msgs>');
foreach ($query as $row) {
	$rowsadd++;
	$rows[$row['id']] = $row;
}
if ($rowsadd < $msgshostory) {
	foreach($wheres as $where){
		call_user_func_array(array($sql, 'where'), $where);
	}
	$sql->orderBy('date', 'DESC');
	$query = $sql->get('message_archive', array($startpage, $msgshostory));
	foreach ($query as $row) {
		$rowsadd++;
		$rows[$row['id']] = $row;
	}
}
krsort($rows);
foreach ($rows as $row) {
    $room = $rooms[$row['room']];
    if ($admin == '1') {
        $delmsg = "<br /> <a href='javascript:void(0);' onclick=\"deletemsg('{$row['id']}');\" >[حذف]</a>";
    } else {
        $delmsg = '';
    }
	if ($row['text'] == 'request') {
		continue;
	}
	if ($row['text'] == 'false') {
		continue;
	}
	if ($row['text'] == 'true') {
		continue;
	}
	if ($row['username'] == 'SYSTEM') {
		continue;
	}
	if ($row['username'] == $systemname) {
		continue;
	}
	if (strstr($row['username'], 'SYSTEM [ <b><font color=red>')) {
		continue;
	}
	if (strstr($row['username'], '<center>SYSTEM [ <b><font color=red>')) {
		continue;
	}
    $jmsg = str_replace(array('<b>', '</b>', '<i>', '</i>'), '', $row['7']);
    if (isJson($jmsg)) {
        $jd = json_decode($jmsg, true);
        if (isset($jd['action'])) {
            $replymsg = $jd['umsg'];
            $replyme = $jd['uname'];
            $mymsg = $jd['mymsg'];
            if ($jd['action'] == 'reply') {
                if ($color == 'D5D5D7') {
                    $color = 'EEEEFF';
                } else {
                    $color = 'D5D5D7';
                }
				//echo "<msg><![CDATA[<tr id=\"msg-{$row['id']}\" bgcolor=\"#{$color}\"><td width=\"50\" align=\"center\">{$row['id']}{$delmsg}</td><td width=\"180\" align=\"center\"><a href=\"/profile/{$row['user']}\" >{$row['username']}</a></td><td width=\"80\" align=\"center\">" . jdate::start('Y/m/d - H:i:s', $row['date']) . "</td><td align=\"center\" style=\"direction : rtl;word-break: break-all;max-width: 100px;font-family:{$row['fontname']};font-size:{$row['fontsize']}px;\"><div class=\"pmbox\"><div class=\"mess_back\"><div id=\"repbox\"><div id=\"khat\"><div id=\"name\">{$replyme}</div><div id=\"text\">{$replymsg}</div></div><div id=\"means\">{$mymsg}</div></div></div></div></td><td width=\"80\" align=\"center\">{$room}</td></tr>]]></msg>";
	$pid=$row['user'];
  $sql->where("id",$pid);
		$input_p= $sql->getOne('profiles');
		$input=$input_p["avatar"];
       $avatar = $input;
			echo"
	<msg><![CDATA[<!-- partial:index.partial.html -->

<div class=\"speech-wrapper\" id=\"msg-{$row['id']}\">
<a href=\"/profile/{$row['user']}\" target=\"_blank\" ><img src=\"/theme/images/avatars/37-37/$avatar\" class=\"profile\"></a>
  <div class=\"bubble\">
    <div class=\"txt\">
      <p class=\"name\">{$row['username']}</p>
      <p class=\"name\" style=\"float:right;color:brown;font-size:11px\"> {$room} </p>
      <p class=\"message\">".$row['text']." </p>
      <span class=\"timestamp\">" . jdate::start('H:i:s - Y/m/d', $row['date']) . " {$delmsg} </span>
    </div>
    <div class=\"bubble-arrow\"></div>
  </div><br>
  
  
<!-- partial -->
	]]></msg>";
	
	
	}
	
            if ($jd['action'] == 'delete') {
                continue;
            }
            if ($jd['action'] == 'reply') {
                continue;
            }
        }
    }
    if ($color == 'D5D5D7') {
        $color = 'EEEEFF';
    } else {
        $color = 'D5D5D7';
    }
	//echo "<msg><![CDATA[<tr id=\"msg-{$row['id']}\" bgcolor=\"#{$color}\"><td width=\"50\" align=\"center\">{$row['id']}{$delmsg}</td><td width=\"180\" align=\"center\"><a href=\"/profile/{$row['user']}\" >{$row['username']}</a></td><td width=\"80\" align=\"center\">" . jdate::start('Y/m/d H:i:s', $row['date']) . "</td><td align=\"center\" style=\"direction : rtl;word-break: break-all;max-width: 100px;font-family:{$row['fontname']};font-size:{$row['fontsize']}px;\">{$row['text']}</td><td width=\"80\" align=\"center\">{$room}</td></tr>]]></msg>";
		
  //---get users profile picture
  		$pid=$row['user'];
  		$sql->where("id",$pid);
		$input_p= $sql->getOne('profiles');
		$input=$input_p["avatar"];
       $avatar = $input;
  
  //-----------------------remove tags-------------
	   $mtext = str_replace(array('<b>', '</b>', '<i>', '</i>'), '', $row['text']);
  		
 
  //--------------------------Reply style option-----------
  	  if (isJson($mtext)) {
        $jd = json_decode($mtext, true);
        if (isset($jd['action'])) {
            $replymsg = $jd['umsg'];
            $replyme = $jd['uname'];
            $mymsg = $jd['mymsg'];
        }
            if ($jd['action'] == 'reply') {
              $mtext="
              <div id='box'>
				<div id='khat'>
				<div id='name'>".$replyme."</div>
				<div id='text'>".$replymsg."</div>
				</div>
			<div id='means'>
			<span>".$mymsg."</span>
			</div>
			</div>";

                }		
        }
  
  
  //--------------------show smilies in history-----------------------------------------------------------
   $mtext = preg_replace('/\(s(\d{1,5})\)/', '<img src="/theme/images/smilies/$0.gif">', $mtext ,-1);

  
  
  //-----------------show history result-------------------
  if(session('id')==$row['user'])
  {
     $Bcolor="#b1e4bd !important";
     $alt=" alt";
     $display="none !important";
  }
  else
  {
    $Bcolor="#f5f5f5 !important";
    $alt="";
    $display="block";
  }
	echo"
	<msg><![CDATA[<!-- partial:index.partial.html -->

<div class=\"speech-wrapper\" id=\"msg-{$row['id']}\">
<a href=\"/profile/{$row['user']}\" target=\"_blank\" ><img src=\"/theme/images/avatars/37-37/$avatar\" class=\"profile\"></a>
  <div class=\"bubble$alt\" style=\"background-color:$Bcolor\">
    <div class=\"txt\">
      <p class=\"name\">{$row['username']}</p>
      <p class=\"name\" style=\"float:right;color:brown;font-size:11px\"> {$room} </p>
      <p class=\"message\">$mtext</p>
      <span class=\"timestamp\">" . jdate::start('H:i:s - Y/m/d', $row['date']) . " {$delmsg} </span>
    </div>
    <div class=\"bubble-arrow\" style=\"display:$display\"></div>
  </div><br>
  
  
<!-- partial -->
	]]></msg>";
  

}
$numgs = $sql->getValue('message', 'count(*)');
$numgs += $sql->getValue('message_archive', 'count(*)');
$totalpages = ceil($numgs / $msgshostory);
$x = 0;
echo("<pages><![CDATA[");
while ($x < $totalpages) {
    $nextpage = $x + 1;
    $x++;
    $numgs -= $msgshostory;
    if ($nextpage == $page) {
        $selected = " selected=\"selected\"";
    } else {
        $selected = "";
    }
    echo("<option$selected>$nextpage</option>");
}
if ($x == 0) {
    echo("<option>1</option>");
}
echo(']]></pages></msgs>');
?>