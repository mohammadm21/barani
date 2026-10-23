<?php
if(!session('id') or session('admin') == '0'){exit();}
adminpanel::check_Permission('exit', 'delvisualuser');


$user = isset($_POST['user']) ? safe::number($_POST['user']) : '';
if($user == ''){echo('user');return;}
$sql->where("id", $user);
if (!$user = $sql->getOne('users', array('id', 'username'))) {echo('user');return;}
$sql->where("id", $user['id']);
if (!$online = $sql->getOne('onlines', array('time'))){echo('novisual');return;}
if($online['time'] != '0'){echo('novisual');return;}

$sql->where("id", $user['id'])->delete('onlines');

$founded = false;
$sql->where("time", 0);
foreach($sql->get('onlines', null, array('id', 'inputs')) as $row){
    $inputs = json_decode($row['inputs'], true);
    if(isset($inputs['autoanswer']) and $inputs['autoanswer'] == true){
        if ($sql->has('autoanswer')) {
            $sql->where("name", "autoanswer")->update('options', array(
                'value' => 1
            ));
            chatroom::reloadconfig();
        }
        $founded = true;
        
        break;
        
    }
}
if(!$founded){
    $sql->where("name", "autoanswer")->update('options', array(
        'value' => 0
    ));
    chatroom::reloadconfig();
}

$sql->where("id", session('id'));
$hide = $sql->getOne('onlines', array('id','hide'));
if ($hide['hide'] == '1' and session("admin")==1){
}elseif ($hide['hide'] == '0' and session("admin")!=0){
chatroom::addlog('9', "کاربر مجازی \"{$user['username']}\" را حذف کرد");
}
echo('ok');

?>