<?php
date_default_timezone_set('Asia/Tehran');
session_start();
include('inc/config.php');
include('inc/function.php');
include('inc/classes.php');
include('inc/plugin.php');
$chat_id =$options['tlg_send_admins'];
include('inc/tlg.php');
$giu = null;
$sql = new MysqliDb(db_server, db_user, db_pass, db_name);
$sql->setPrefix(db_prefix);
$page = giu('page');
if ($options['antiDDos'] == 'enable') {antiDDos::start();}
if ($options['chat_close'] == 'true') {
    if ($page != 'admin' && $page != 'ajax') {
        $options['chat_closeparam'] = (array) json_decode($options['chat_closeparam']);
        $theme = $options['chat_closeparam']['theme'];
        if (is_file("theme/{$theme}/index.php")) {include("theme/{$theme}/index.php");}
        db::close();
        exit();
    }
}
register_shutdown_function('shutdown', getcwd());
$chat_prefix = $options['chat_prefix'] == '' ? 'chat' : $options['chat_prefix'];
if(strtolower($chat_prefix) == $page){
	if(is_file("pages/chat.php"))include("pages/chat.php");else include('pages/notfound.php');
}else{
	if(is_file("pages/$page.php"))include("pages/$page.php");else include('pages/notfound.php');
}


?>
