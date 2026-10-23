<?php
if (trim(session('type')) == "" || trim(session('type')) == 1) {
	exit('<p style="text-align:center;direction:rtl;"> <font style="color:red;"> !!!!کاربر مهمان اجازه افزودن شکلک شخصی را  ندارد </font> </p>');
}
if (!chatroom::checkPermission('usersmile')) {
	exit('<p style="text-align:center;direction:rtl;"> <font style="color:red;"> !!!!اين قابليت براي گروه کاربري شما غير فعال شده است </font> </p>');
}
if (!session('id')) {
	exit('login');
}
$user_id = session('id');
$smile_active = $sql->where('user', $user_id)->where('active', '1')->getValue('user_smile', 'count(*)');
$smile_not_active = $sql->where('user', $user_id)->where('active', '0')->getValue('user_smile', 'count(*)');
$smile_users = $sql->where('user', $user_id)->getValue('user_smile', 'count(*)');
function Upload_Smile($file_up) {
	global $options, $sql;
	global $smile_users;
	$nextid1 = $sql->where('table_name', db_prefix . 'smilies')->where('table_schema', db_name)->getValue('information_schema.tables', 'AUTO_INCREMENT');
	$nextid2 = $sql->where('table_name', db_prefix . 'user_smile')->where('table_schema', db_name)->getValue('information_schema.tables', 'AUTO_INCREMENT');
	$nextid = $nextid1+$nextid2;
        $id = session('id');
	if ($smile_users >= $options['num_smileuser']) {
		return "شما مجاز به اپلود {$options['num_smileuser']} شکلک بیشتر نیستید";
	}
	switch ($file_up['type']) {
		case 'image/png':
			$format = 'png';
			break;
		case 'image/jpeg':
		case 'image/jpg':
			$format = 'jpg';
			break;
		case 'image/gif':
			$format = 'gif';
			break;
		case 'image/webp':
			$format = 'webp';
			break;
		default:
			return 'فايل ارسالي مورد تاييد نمي باشد';
	}
	if (in_array($format, array('png', 'jpg', 'gif', 'webp'))) {
		$FORMAT = "gif";
		$folder = 'theme/images/smilies';
		$name = "(s{$nextid}" . ").{$FORMAT}";
		$filename = "{$folder}/{$name}";
		if (move_uploaded_file($file_up['tmp_name'], $filename)) {
			$file = strtolower(file_get_contents($filename));
			if (strpos((string) $file, '<?php') !== false) {
				unlink($filename);
				return 'فايل ارسالي مورد تاييد نمي باشد';
			} else {
				$sql->insert('user_smile', array(
					'user' => $id,
					'smilecode' => '(s' . $nextid . ')',
					'active' => '0'
				));
			}
			return 'شکلک شما با موفقیت اپلود شد و پس از تایید نمایش داده میشود';
		} else {
			return 'فايل بارگزاري نشد';
		}
	} else {
		return 'فايل ارسالي مورد تاييد نمي باشد';
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="<?php echo "/theme/fonts/fonts.css";?>">
		<style>
			*{
				font-family:Byekan;
			}
			.prim{
				border:0px;
				cursor:pointer;
				border-radius:4px;
				background:rgb(59, 136, 195);
				color:#fff;
				padding-top:4px;
				padding-bottom:4px;
				width:140px;
				margin-top:6px;

			}
			.dang{
				border:0px;
				cursor:pointer;
				border-radius:4px;
				background:rgb(200, 0, 0);
				color:#fff;
				padding-top:4px;
				padding-bottom:4px;
				width:140px;
				margin-top:6px;
			}
			.dangs{
				border:0px;
				cursor:pointer;
				border-radius:4px;
				background:rgb(132, 0, 200);
				color:#fff;
				padding-top:4px;
				padding-bottom:4px;
				width: auto;
				margin-top:6px;
			}
			.dang2{
				border:0px;
				cursor:pointer;
				border-radius:4px;
				background:#1AB71C;
				color:#fff;
				padding-top:4px;
				padding-bottom:4px;
				width:140px;
				margin-top:6px;
			}

			.av{
				width:60px;
				height:60px;
				border-radius: 50%;
				border:4px solid rgba(0,0,0,0.08);
			}
		</style>
	</head>
	<body>
		<?php
if ($usertype[session('type')]['guest'] == 1) {
	echo '<div style="text-align: center; font-weight: bolder; text-shadow: 0px 0px 5px #ff0000">براي تغيير آواتار ابتدا عضو شويد</div>';
} else {
	?>
		<center>
		<input type='submit' class="dangs" id="btn_submit" value="حداکثر تعداد اپلود شکلک: <?php echo $options['num_smileuser'];?>" />
		<br>
		<input type='submit' class="dangs" id="btn_submit" value="شکلک های تایید شده شما: <?php echo $smile_active;?>" />
		<br>
		<input type='submit' class="dangs" id="btn_submit" value="شکلک های در انتظار تایید شما: <?php echo $smile_not_active;?>" />
		<br>
			<form action="<?php echo url('ajax/chat.smile.upload');?>" method="POST" enctype="multipart/form-data">
				 <input type='file' style="display:none;" id='fileToUpload' name='fileToUpload' /><br/><br/>
				<input class="prim" type="button" onclick="fileToUpload.click();" value="انتخاب شکلک">
				<input type='submit' class="dang" id="btn_submit" value="آپلود شکلک" />
				<?php
	if ($_FILES['fileToUpload']) {
		echo '<div style="text-align: center; font-weight: bolder; text-shadow: 0px 0px 5px #ff0000">' . Upload_Smile($_FILES['fileToUpload']) . '</div>';
	}
}
?>
		</form>
	</center>
</body>
</html>
