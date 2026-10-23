<?php
$token = safe::string($_POST['token']);
if($token != "mahdilotfi_451245745dfg??%@#" or empty($token)){
    exit();
}
$value = 'theme'.safe::string($_POST['theme']);
$value = trim($value);
if (substr($value, 0, 5) != 'chat_theme') {
	copy("http://theme.tarhpardaz.org/theme/file/{$value}.zip", "{$value}.zip");
        $zip = new ZipArchive;
        $res = $zip->open("{$value}.zip");
        if ($res) {
            $zip->extractTo($_SERVER['DOCUMENT_ROOT'] . '/theme/');
            $zip->close();
            unlink("{$value}.zip");
            copy($_SERVER['DOCUMENT_ROOT'] . '/theme/' . $options['chat_theme'] .'/chat.php', $_SERVER['DOCUMENT_ROOT'] . '/theme/'.$value.'/chat.php');
			db::update('options', "`value`='$value'", "`name`='chat_theme'");
            db::update('options', "`value`='theme/$value/'", "`name`='rootdirmanager'");
			chatroom::reloadconfig();
            exit('ok');
        } else {
            exit('error');
        }
    
}
chatroom::addlog('9', "قالب  \{$value}\را بر روی چت روم نصب نمود");
echo('ok');
?>