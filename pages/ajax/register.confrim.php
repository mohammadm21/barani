<?php

$output = array();
$sql->where("code", 0, '!=');
$sql->where("time", time() - 86400, '<');
foreach($sql->get('register', null, array('id')) as $row){
    $sql->where("id", $row['id'])->delete('register');
    $sql->where("id", $row['id'])->delete('users');
    $sql->where("id", $row['id'])->delete('profiles');
}
if (session('ui') or isset($_COOKIE['register'])) {
    $id = session('ui');
    if (!$id) {
        $id = safe::number($_COOKIE['register']);
    }
    $code = isset($_POST['code']) ? safe::string($_POST['code']) : '';
    if ($code) {
        $sql->where("id",$id);
        $sql->where("time", time() - 86400, '>');
        if($register = $sql->getOne('register', array('code'))){
            if ($register['code'] == $code) {
                $sql->where("id", $id);
                $user = $sql->getOne('users');
                session('id', $user['id']);
                session('room', $roomdefault);
                session('color', chatroom::defaultcolor());
                session('name', $user['name']);
                session('ip', ip());
                session('type', $user['type']);
                session('admin', $usertype[Users::lowestlevelregister()]['admin']);
                session('hide', '0');
                session('user', '1');
                $sql->where("id", $id)->update('users', array(
                    'active' => 1,
                    'type' => Users::lowestlevelregister()
                    'score' => 0
                ));
                $sql->where("id", $id)->update('onlines', array(
                    'type' => Users::lowestlevelregister()
                    'score' => 0
                ));
                $sql->where("id", $id)->update('register', array(
                    'code' => null
                ));
                sessionunset('ui');
                $output = array('status' => true);
            } else {
                $output = array('status' => false, 'error' => 'invalid');
            }
        } else {
            $output = array('status' => false, 'error' => 'timeout');
        }
    } else {
        $output = array('status' => false, 'error' => 'code');
    }
} else {
    $output = array('status' => false, 'error' => 'register');
}
header::tojson();
echo json_encode($output);
?>
