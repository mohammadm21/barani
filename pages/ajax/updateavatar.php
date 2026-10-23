<?php
check_session();
if (Users::guestType(session('type'))) {
	return;
}
$id = session('id');
include 'inc/classes/profile.php';
Profile::CheckActiveMe();
if ($_FILES['fileToUpload']) {
	$file = basename($_FILES['fileToUpload']['name']);
	$ext = substr($file, strrpos($file, '.') + 1);
	$allowedExts = array("jpg", "gif", 'jpeg', 'png');
	if (in_array(strtolower($ext), $allowedExts)) {
		switch ($_FILES['fileToUpload']['type']) {
			case 'image/png':
				$format = 'png';
				break;
			case 'image/jpeg':
				$format = 'jpg';
				break;
			case 'image/gif':
				$format = 'gif';
				break;
			default:
				echo 'type';
				return;
				break;
		}
	} else {
		exit('type');
	}
	$folder = $options['avatar_dir'];
	$name = $id . "_" . time() . ".{$format}";
	$filename = "{$folder}/{$name}";
	if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $filename)) {
		$file = file_get_contents($filename);
		if (strpos((string) $file, '<?php') !== false) {
			unlink($filename);
			echo 'security';
		} else {
			$lastavatar = $sql->where('id', $id)->getValue('profiles', 'avatar');
			$profile = $sql->where('id', $id)->getOne('profiles');
			$last_avatars = $profile["last_avatars"] != "" ? (array) json_decode($profile["last_avatars"]) : array();
			$last_avatars[] = $lastavatar;
			$sql->where('id', $id)->update('profiles', array(
				'last_avatars' => json_encode($last_avatars, JSON_UNESCAPED_UNICODE)
			));
			$sql->where('id', $id)->update('profiles', array(
				'avatar' => $name,
				'cavatar' => '',
				'lduavatar' => ''
			));
			$sql->where('userid', $id)->update('profile_counts', array(
				'lavatar' => '',
				'davatar' => ''
			));
			if ($sql->where('id', $id)->getValue('onlines', 'count(*)') > 0) {
				$input = Users::Inputs($id);
				$input['avatar'] = $name;
				Users::Inputs($id, $input);
			}
			if ($options['thumbavatar'] == 'true') {
				$sizes = explode('*', $options['thumbavatarSize']);
				$newfilename = "{$sizes[0]}-{$sizes[1]}/" . $name;
				$image = new ImageResizer();
				$image->load($filename);
				$image->resize($sizes[0], $sizes[1]);
				$image->save("{$folder}/{$newfilename}");
			}
			session('myavatar', $name);
			Profile::Send2Friends('آواتار خود را عوض کرد');
			echo 'ok|' . url($filename) . '?' . rand(0, 10);
		}
	}
}
?>
