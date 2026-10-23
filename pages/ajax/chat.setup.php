<?php

check_session();
$id = session('id');
$sql->where("id",$id);
$me = $sql->getOne("onlines", array('id', 'gender', 'score', 'type', 'inputs', 'name'));
if (!$me) {
    exit();
}
$temp = json_decode($me['inputs'], true);
unset($temp['score']);
$me += $temp;



header::tojson();

$types = array();
foreach ($usertype as $type) {
    $types[] = array(
        'id' => $type['id'],
        'title' => $type['title'],
        'icon' => $type['icon'],
        'guest' => $type['guest'],
        'admin' => $type['admin'],
        'color' => $type['color'],
        'taj' => $type['taj']
    );
}
$useroptions = json_decode($options['useroptions'], true);
$options['thumbavatarSize'] = explode('*', $options['thumbavatarSize']);
$settings = array(
    'theme' => $options['chat_theme'],
    'textcolor' => $options['chat_defaultcolor'],
    'fontname' => $options['chat_defaultfontname'],
    'fontsize' => $options['chat_defaultfontsize'],
    'scoretosendmsg' => safe::bool($options['scoretosendmsg']),
    'speedtext' => (int) $options['chat_speedmsg'],
    'sendmsguest' => safe::bool($options['chat_sendmsguest']),
    'repeatmsg' => (int) $options['repeatmsg'],
    'SmiliespMsg' => (int) $options['SmiliespMsg'],
    'maxlengthmsg' => (int) $options['chat_maxlengthmsg'],
    'thumbavatar' => safe::bool($options['thumbavatar']),
    'useroptions' => $useroptions,
    'avatardir' => "theme/images/avatars/{$options['thumbavatarSize'][0]}-{$options['thumbavatarSize'][1]}/",
    'defaultavatar' => $options['defaultavatar'],
    'timemsg' => safe::bool($options['timemsg']),
    'profile_enable' => safe::bool($options['profile_enable']),
    'reg_btn' => safe::bool($options['registerbtn']),
    'numsgmain' => (int) $options['chat_numsgmain'],
    'colorlistonline' => safe::bool($options['colorlistonline']),
    'advcreg' => safe::bool((int) $options['advcreg'])
);
$me = array(
    'score' => $me['score'],
    'username' => $me['name'],
    'type' => $me['type'],
    'gender' => $me['gender'],
    'id' => $me['id'],
    'avatar' => $me['avatar']
);
echo json_encode(array('types' => $types, 'settings' => $settings, 'me' => $me), JSON_UNESCAPED_UNICODE);
?>
