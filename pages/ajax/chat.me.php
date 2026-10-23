<?php

check_session();
header::tojson();
$me = array();
$id = session('id');

//while ($row = mysqli_fetch_array($query)) {

$sql->where("id", $id);
$bank = $sql->getValue('users', 'bank');
$sql->where("id", $id);
if($row = $sql->getOne('onlines')) {
    $inputsUser = json_decode($row['inputs'], true);
    //if($inputsUser['avatar'] != $options['defaultavatar'])$array['a'] = $inputsUser['avatar'];
    //if($inputsUser['avatar'] == 'photo.png') $inputsUser['avatar'] = 'anonymous.png';
    $me_info = array(
        'name' => safe::string($row['name']),
        'group' => $row['type'],
        'score' => $row['score'],
        'bank' => $bank,
        'avatar' => isset($inputsUser['avatar']) && $inputsUser['avatar'] != 'photo.png' ? (is_file('theme/images/avatars/' . $inputsUser['avatar']) && is_file('theme/images/avatars/' . MiniAvatarDir() . '/' . $inputsUser['avatar']) ? $inputsUser['avatar'] : ($row['gender'] == '1' ? 'no_avatar_male.png' : 'no_avatar_female.jpg')) : ($row['gender'] == '1' ? 'no_avatar_male.png' : 'no_avatar_female.jpg')
    );
}

echo(json_encode($me_info, JSON_UNESCAPED_UNICODE));
?>
