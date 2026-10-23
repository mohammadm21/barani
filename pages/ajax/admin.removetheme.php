<?php

if (!session('id') or session('admin') == '0') {
    exit();
}

$remove = isset($_POST['remove']) ? safe::string($_POST['remove']) : '';
if ($remove == $options['chat_theme']) {
    exit('notremov');
}
if (is_dir('theme/' . $remove)) {
    if ($remove != $options['chat_theme']) {
        $remove = safe::filename($remove);
        rm_rf("theme/$remove");
        echo('ok');
        chatroom::addlog('9', "قالب $remove .را از روی چت روم حذف کرد");
    } else {
        exit('notremov');
    }
} else {
    exit('nofile');
}
?>