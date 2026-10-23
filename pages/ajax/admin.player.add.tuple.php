<?php
if (!session('id') or session('admin') == '0') {
	exit;
}
adminpanel::check_Permission('exit', 'addplayer');
$msg = "";
if (isset($_FILES['file'])) {
	$type = $_FILES['file']['type'];
	$file = basename($_FILES['file']['name']);
	$ext = substr($file, strrpos($file, '.') + 1);
	$allowedExts = array("mp3", "wmv");
	if (!in_array($ext, $allowedExts)) {
		exit('type');
	} else {
		$format = file::get_ext($_FILES['file']['name']);
		if ($format != 'zip') {
			$folder = 'theme/music';
			$namecode = '';
			$nextid = $sql->where('table_name', db_prefix . 'player')->where('table_schema', db_name)->getValue('information_schema.tables', 'AUTO_INCREMENT');
			$name = "music{$nextid}.{$format}";
			$filename = "{$folder}/{$name}";
			if (move_uploaded_file($_FILES['file']['tmp_name'], $filename)) {
				$music = file_get_contents($filename);
				if (strpos((string) $music, '<?php') !== false) {
					unlink($filename);
					echo 'security';
					return;
				} else {
					chatroom::addmusic($name, $filename, $nextid);
					$sql->where("id", session('id'));
					$hide = $sql->getOne('onlines', array('id','hide'));
					if ($hide['hide'] == '1' and session("admin")==1){
					}elseif ($hide['hide'] == '0' and session("admin")!=0){
						chatroom::addlog('9', "آهنگی اضافه کرد:{$name}");
					}
					echo 'ok';
				}
			} else {
				echo 'noup';
			}
		} elseif ($format == 'zip') {
			$logs = array();
			$folder = 'temp' . rand(1, 5000);
			if (!is_dir($folder)) {
				mkdir($folder, 0777);
			}
			$unzipfolder = $folder . '/' . rand(0, 1000);
			$filename = $unzipfolder . '.zip';
			if (move_uploaded_file($_FILES['file']['tmp_name'], $filename)) {
				$logs[] = 'uploaded';
				file::unzip($filename, $unzipfolder);
				$logs[] = 'unziped';
				if (is_dir($unzipfolder)) {
					$files = file::listDirectory($unzipfolder, false, true);
					$nextid = $sql->where('table_name', 'player')->where('table_schema', db_name)->getValue('information_schema.tables', 'AUTO_INCREMENT');
					$priority = 1;
					$namecode = '';
					$logs[] = 'nexedid';
					foreach ($files as $file) {
						$infoFile = pathinfo($file);
						if (isset($infoFile['extension']) and in_array($infoFile['extension'], array('mp3', 'wma', 'ogg', 'amr', 'mp2'))) {
							$img = file_get_contents($file);
							$logs[] = 'read contents';
							if (strpos((string) $img, '<?php') !== false || strpos((string) $img, '<script language="php"') !== false || strpos((string) $img, "<script language='php'>") !== false) {
								unlink($file);
								$logs[] = 'seacurity';
							} else {
								$filenewname = "music{$nextid}.{$infoFile['extension']}";
								$logs[] = 'newname:' . $filenewname;
								rename($file, "theme/music/{$filenewname}");
								$logs[] = 'renamed fom :' . $file;
								chatroom::addmusic($infoFile['basename'], "theme/music/{$filenewname}", $nextid);
								$nextid++;
							}
						}
					}
					file::Delete($unzipfolder);
					$logs[] = 'delete:' . $unzipfolder;
				}
				unlink($filename);
				$logs[] = 'delete:' . $filename;
			}
			file::Delete($unzipfolder);
			$logs[] = 'delete:' . $unzipfolder;
		}
	}
} else {
	$msg = 'noelemrnr';
}
echo "{$msg}";
?>
