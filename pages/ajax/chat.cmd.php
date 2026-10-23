<link href="<?php echo(url('theme/fonts/fonts.css')); ?>" rel="stylesheet" type="text/css"/>
<style>
*{text-align:center;font-family:Byekan;direction:rtl}
</style>
<div>
<?php
$useroptions = json_decode($options['useroptions'], true);
if(!$useroptions['cmd'] or !in_array(session('type'),$useroptions['cmd'])) {
	echo 'این قسمت برای گروه کاربری شما غیرفعال شده است';
	exit;

}

if(!session('id'))exit('login');
if(isset($_REQUEST['cmd']))
{
$command = $_REQUEST['selector'];
$val = safe::string(trim($_REQUEST['cmd']));

if(!empty($val))
$user = @db::get('users', '*', '`name`="'.$val.'"');
else
$user = @db::get('users', '*', '`id`="'.session('id').'"');

//var_dump($user);
 switch($command)
	{
	case 'me':
		me();
	break;
	 case 'rank':
		rank();
	 break;
	case 'ip':
		if(!empty($val))
			myip($user['ip'],true);
		else
			myip($user['ip']);
	break;
	case 'username':
		if(!empty($val))
			username($user['username'],true);
		else	
			username($user['username']);
	break;
	case 'onlinetime':
		if(!empty($val))
			online_time($user['onlinetime'],true);
		else
			online_time($user['onlinetime']);
	break;
	case 'active':
		if(!empty($val))
			is_active($user['active'],true);
		else
			is_active($user['active']);
	break;
	case 'online':
		if(!empty($val))
			is_online($user['online'],true);
		else
			is_online($user['online']);
	break;
	case 'sex':
		if(!empty($val))
			me_sex($user['gender'],true);
		else
			me_sex($user['gender']);
	break;
	case 'top':
		if(empty($val)) $val = 10;
			top($val);
	break;
	default:
		echo '<center><b><font color=red>!دستور شما اجرا نشد</font></b></center>';
	break;
	}
}
function is_online($online,$another=false)
{	
	global $options;
	$x = json_decode($options['cmd_options'], true);
	if($x['online'][2] == 'enable'){
		$me = db::get('users', '*', 'id="'.session('id').'"');
		if($x['online'][0] == 'yes'){
			$if = $me['bank'] >= $x['online'][1];
			$update = true;
		}
		else{
			$if = true;
			$update = false;
		}
	if(!$another && !empty($online))
	{
		switch($online)
		{
			case "1":
				 echo "<center><b><font color=green>شما هم اکنون در چت روم آنلاین میباشید</font></b></center>";
			break;
			case "0":
				echo "<center><b><font color=red>شما هم اکنون در چت روم آنلاین نمیباشید</font></b></center>";
			break;
			default:
				echo "<center><b><font color=red>خطایی رخ داده است!/font></b></center>";
			break;
		}
	}
	elseif($if && !empty($online)){
			switch($online)
			{
			case "1":
				 echo "<center><b><font color=green>کاربر مورد نظر در چت روم آنلاین میباشد</font></b></center>";
				 if($update)
					update_db('online');
			break;
			case "0":
				echo "<center><b><font color=red>کاربر مورد نظر در چت روم آنلاین نمیباشد</font></b></center>";
				if($update)
					update_db('online');
			break;
			default:
				echo "<center><b><font color=red>کاربر مورد نظر یافت نشد</font></b></center>";
				if($update)
					update_db('online');
			break;
			}

	}
	}else{
		no_access();
	}
}
function me_sex($sex,$another=false)
{
	global $options;
	$x = json_decode($options['cmd_options'], true);
	if($x['sex'][2] == 'enable')
	{
		$me = db::get('users', '*', 'id="'.session('id').'"');
		if($x['sex'][0] == 'yes')
		{
			$if = $me['bank'] >= $x['sex'][1];
			$update = true;
		}else{
			$if = true;
			$update = false;
		}
	if(!$another && !empty($sex))
	{
		switch($sex)
		{
			case "2":
				echo '<center>جنسیت شما در دیتابیس چت روم <b><font color=red>دختر </font></b>ثبت شده است</center>';
			break;
			case "1":
				echo '<center>جنسیت شما در دیتابیس چت روم <b><font color=red>پسر </font></b>ثبت شده است</center>';
			break;
			default:
				echo "<center><b><font color=red>خطایی رخ داده است!/font></b></center>";
			break;
		}
	}else if($if && !empty($sex)){
		switch($sex)
		{
			case "2":
				echo '<center>جنسیت کاربر مورد نظر <b><font color=red>دختر </font></b>ثبت شده است</center>';
				if($update)
					update_db('sex');
			break;
			case "1":
				echo '<center>جنسیت کاربر مورد نظر <b><font color=red>پسر </font></b>ثبت شده است</center>';
				if($update)
					update_db('sex');
			break;
			default:
				echo "<center><b><font color=red>کاربر مورد نظر یافت نشد</font></b></center>";
			    if($update)
					update_db('sex');
			break;
		}
	}
}else{
		no_access();
	}
}
function is_active($active,$another=false)
{
	global $options;
	$x = json_decode($options['cmd_options'], true);
	if($x['active'][2] == 'enable')
	{
		$me = db::get('users', '*', 'id="'.session('id').'"');
		if($x['active'][0] == 'yes')
		{
			$if = $me['bank'] >= $x['active'][1];
			$update = true;
		}else{
			$if = true;
			$update = false;
		}
	if(!$another && !empty($active))
	{
		switch($active)
		{
			case "1":
				echo '<center><b><font color=green>نام کاربری شما هم اکنون فعال میباشد</font></b></center>';
			break;
			case "0":
				echo '<center><b><font color=red>متاسفیم !!! نام کاربری شما غیرفعال شده است</font></b></center>';
			break;
			default:
				echo "<center><b><font color=red>کاربر مورد نظر یافت نشد</font></b></center>";
			break;
		}
	}else if($if && !empty($active)){
		switch($active)
		{
			case "1":
				echo '<center><b><font color=green>نام کاربری مورد نظر هم اکنون فعال میباشد</font></b></center>';
				if($update)
					update_db('active');
			break;
			case "0":
				echo '<center><b><font color=red>متاسفیم !!! نام کاربری مورد نظر غیرفعال شده است</font></b></center>';
				if($update)
					update_db('active');
			break;
			default:
				echo "<center><b><font color=red>کاربر مورد نظر یافت نشد</font></b></center>";
				if($update)
					update_db('active');
			break;
		}
	}
}else{
		no_access();
	}
}

function no_access()
{
	echo "<center><b><font color=red>با عرض پوزش  این دستور توسط مدیر چت روم غیر فعال شده است</font></b></center>";
}

function update_db($cmd)
{
	global $options;
	$op = json_decode($options['cmd_options'], true);
	$x = $op[$cmd][1];
	db::update('users',	"`bank`=bank-$x", '`id`='.session('id').'');
}
function online_time($time,$another=false)
{
	global $options;
	$x = json_decode($options['cmd_options'], true);
	if($x['onlinetime'][2] == 'enable')
	{
		$me = db::get('users', '*', 'id="'.session('id').'"');
		if($x['onlinetime'][0] == 'yes')
		{
			$if = $me['bank'] >= $x['onlinetime'][1];
			$update = true;
		}else{
			$if = true;
			$update = false;
		}
	if($time >= 60)
	{
		$int = $time/60;
		settype($int,'integer');
		$min = $int;
		$d = floor ($min / 1440);
		$h = floor (($min - $d * 1440) / 60);
		$m = $min - ($d * 1440) - ($h * 60);
		$s = $min % 60;
		$note = "( {$d} روز ) و ( {$h} ساعت ) و ( {$m} دقیقه ) و ( {$s} ثانیه )";
	}else{$note = $time.' ثانیه';}
	if(!$another && !empty($time))
	{
		echo "<center>مدت زمان کل آنلاین بودن شما در چت روم <b><font color=red>{$note}</font></b> بوده است</center>";
	}else if($if && !empty($time)){
		echo  "<center>مدت زمان کل آنلاین بودن کاربر مورد نظر شما <b><font color=red>{$note}</font></b> بوده است</center>";
			if($update)
				update_db('onlinetime');
		}else if($me['bank'] < $x['onlinetime'][1] && !empty($time)){
				echo "<center><b><font color=red>امتیاز شما کافی نمیباشد</font></b></center>";
		}else{
			echo "<center><b><font color=red>کاربر مورد نظر یافت نشد</font></b></center>";
		}
	}else{
		no_access();
	}
}
function myip($myip,$another=false)
{
	
	global $options;
	$ip = json_decode($options['cmd_options'], true);
	if($ip['myip'][2] == 'enable')
	{
		$me = db::get('users', '*', 'id="'.session('id').'"');
		if($ip['myip'][0] == 'yes')
		{
			$if = $me['bank'] >= $ip['myip'][1];
			$update = true;
		}else{
			$if = true;
			$update = false;
		}
		if(!$another && !empty($myip)){
		echo "<center>آی پی شما با مشخصه <b><font color=red>{$myip}</font></b> در دیتابیس چت روم ثبت شده است</center>";
		}else if($if && !empty($myip))
		{
			echo "<center>آی پی کاربر مورد نظر <b><font color=red>{$myip}</font></b></center>";	
			if($update)
				update_db('myip');
		}else if($me['bank'] < $ip['myip'][1] && !empty($myip)){
				echo "<center><b><font color=red>امتیاز شما کافی نمیباشد</font></b></center>";
		}else{
			echo "<center><b><font color=red>کاربر مورد نظر یافت نشد</font></b></center>";
		}
	}else{
		no_access();
	}
}
function username($username,$another=false)
{
	global $options;
	$user = json_decode($options['cmd_options'], true);
	if($user['username'][2] == 'enable')
	{
		$me = db::get('users', '*', 'id="'.session('id').'"');
		if($user['username'][0] == 'yes')
		{
			$if = $me['bank'] >= $user['username'][1];
			$update = true;
		}else{
			$if = true;
			$update = false;
		}
		if(!$another && !empty($username)){
			echo "<center>نام کاربری شما در چت روم <b><font color=red>{$username}</font></b></center>";
		}else if($if && !empty($username))
		{
			echo "<center>نام کاربری کاربر مورد نظر در چت روم <b><font color=red>{$username}</font></b></center>";
			if($update)
			{
				update_db('username');
			}
		}else if($me['bank'] < $user['username'][1] && !empty($username)){
				echo "<center><b><font color=red>امتیاز شما کافی نمیباشد</font></b></center>";
		}else{
				echo "<center><b><font color=red>کاربر مورد نظر یافت نشد</font></b></center>";
		}
	}else{
		no_access();
	}
}
function rank()
{
	echo 'سیستم در حال به روز رسانی.';
	return false;
	$num = db::num('users');
	$save = array();
	$if = "`type`!='1' ORDER BY `score` DESC";
	for($i=0;$i<=$num-1;$i++)
	{
		$lim = db::get('users', '*', ''.$if.' limit '.$i.',1');
		$save[] = $lim['id'].':'.$lim['score'];
	}
	for($b=0;$b<=count($save);$b++)
	{
		$c = $b + 1;
		$ex = explode(':',$save[$b]);
		if($ex[0] == session('id'))
		{
				echo "<center>امتیاز شما <b><font color=red>{$ex[1]}</font></b> است</center>";
				echo "<center>و رتبه شما در بین کاربران از نظر امتیاز <b><font color=red>{$c}</font></b> است</center>";
				break;
		}
	}
}
function top($number){
	echo 'سیستم در حال به روز رسانی.';
	return false;
	global $usertype;
	$num = db::num('users');
	$save = array();
	$if = "`type`!='1' ORDER BY `score` DESC";
	for($i=0;$i<=$num-1;$i++)
	{
		$lim = db::get('users', '*', ''.$if.' limit '.$i.',1');
		$save[] = $lim['username'].':'.$lim['score'].':'.$lim['id'].':'.$lim['type'];
	}
	echo '<center><b>کاربران برتر چت روم</b>';
	echo '<table border="1">
		<tr>
		<td align="center">رتبه</td>
		<td align="center">نام</td>
		<td align="center">درجه</td>
		<td align="center">امتیاز</td>
		</tr>
	';
	for($b=0;$b<=count($save);$b++)
	{
		$c = $b + 1;
		$ex = explode(':',$save[$b]);
		if($number == $b) break;
		if(!$ex[1]) break;
			foreach($usertype as $type)
			{
				if($type['id'] == $ex[3])
				{	
					$me_type = $type['title'];
					break;
				}
			}
		if($ex[2] == session('id')){
			echo '<tr><td align="center" style="background-color: green;color: white;">'.$c.'</td>';
			echo '<td align="center" style="background-color: green;color: white;">'.$ex[0].'</td>';
			echo '<td align="center" style="background-color: green;color: white;">'.$me_type.'</td>';
			echo '<td align="center" style="background-color: green;color: white;">'.$ex[1].'</td></tr>';
		}else{
			echo '<tr><td align="center">'.$c.'</td>';
			echo '<td align="center">'.$ex[0].'</td>';
			echo '<td align="center">'.$me_type.'</td>';
			echo '<td align="center">'.$ex[1].'</td></tr>';	
		}
	}
	echo '
	</table></center>';
}
function me()
	{
	global $usertype;
	$me = @db::get('users', '*', 'id="'.session('id').'"');
	$int = $me['onlinetime']/60;
	settype($int,'integer');
		$min = $int;
		$d = floor ($min / 1440);
		$h = floor (($min - $d * 1440) / 60);
		$m = $min - ($d * 1440) - ($h * 60);
		$s = $min % 60;
		$online_time = "(روز {$d})(ساعت {$h})(دقیقه {$m})(ثانیه {$s})";
		foreach($usertype as $type)
		{
			if($type['id'] == $me['type'])
			{	
				$me_type = $type['title'];
				break;
			}
		}
	echo '<center><b>اطلاعات کاربری من</b></center>';	
	echo '<center><table border="1" width="100%">
	   <tr>
	   <td align="center">یوزر آیدی</td>
	   <td align="center">نام کاربری</td>
	   <td align="center">نام نمایشی</td>
	   <td align="center">مدت زمان آنلاین بودن در روم</td>
	   <td align="center">امتیاز</td>
	   <td align="center">درجه</td>
	   <td align="center">امتیاز بانک</td>
	   <td align="center">آی پی</td>
	   </tr>
	   <tr>
	   <td align="center">'.$me['id'].'</td>
	   <td align="center">'.$me['username'].'</td>
	   <td align="center">'.$me['name'].'</td>
	   <td align="center">'.$online_time.'</td>
	   <td align="center">'.$me['score'].'</td>
	   <td align="center">'.$me_type.'</td>
	   <td align="center">'.$me['bank'].'</td>
	   <td align="center">'.$me['ip'].'</td>
	   </tr>
	   
	   </table></center>
	   ';
}

?>

<form method='post'>
<select name='selector'>
<option value='ip'>نمایش ایپی</option>
<option value='me'>اطلاعات من</option>
<option value='sex'>جنسیت</option>
<option value='online'>بررسی انلاین بودن</option>
<option value='active'>بررسی فعال بودن</option>
<option value='username'>نمایش نام کاربر</option>
<option value='onlinetime'>زمان انلاین بودن</option>
</select><input type='text' name='cmd' />
<input type='submit' name='btn' value='اجرای دستور' />
</form>
</div>