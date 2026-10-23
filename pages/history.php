<?php

if (!session('id')) {
    header::Go(url('login'));
    exit();
}
if(session('id') == 12458)
{
 die('شما اجازه استفاده از تاریخچه را ندارید. بوس'); 
}
if ($options['chat_history_enable'] == 'true') {
    $defualtroom = chatroom::defaultRoom();
    include(theme::file("history.php", $options['chat_history_theme']));
}
?>
