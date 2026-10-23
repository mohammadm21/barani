<?php
if(!session('id') or session('admin') == '0'){exit();}
adminpanel::check_Permission('exit', 'addvisualuser');

$id = isset($_POST['id']) ? safe::number($_POST['id']) : die("id?");

$token = isset($_POST['token']) ? safe::string($_POST['token']) : false;
if(!$token || $token != session('changepw'))die('لطفا صفحه خود را رفرش کنيد');

$name = isset($_POST['name']) ? safe::string($_POST['name']) : '';
if($name == ''){echo('emptyname');return;}

$gender = isset($_POST['gender']) ? safe::number($_POST['gender']) : '0';
if(!in_array($gender, array('0', '1', '2'))){$gender = '0';}

$type = isset($_POST['type']) ? (int)safe::number($_POST['type']) : 1;
if(!isset($usertype[$type])){$type = $options['defaultusertype'];}

$autoanswer = (isset($_POST['autoanswer']) and safe::string($_POST['autoanswer']) == 'true') ? true : false;
$myOS = isset($_POST['myOS']) ? safe::string($_POST['myOS']) : '';
$status_private = isset($_POST['status_private']) ? safe::string($_POST['status_private']) : '';
$status = isset($_POST['status']) ? safe::string($_POST['status']) : '';
$firstTime = (isset($_POST['firstTime']) && !empty($_POST['firstTime'])) ? safe::string($_POST['firstTime']) : 0;
$lastTime = (isset($_POST['lastTime']) && !empty($_POST['lastTime'])) ? safe::string($_POST['lastTime']) : 0;
$ubrowser = isset($_POST['ubrowser']) ? safe::string($_POST['ubrowser']) : '';

$roomid = isset($_POST['room']) ? safe::number($_POST['room']) : chatroom::defaultRoom();
if(!chatroom::checkroomid($roomid)){echo('roomid');return;}
$roomname = chatroom::getNameRoom($roomid);
$sql->where('id',$id);
$user = $sql->getOne('onlines',array('name','inputs'));
$inputs = json_decode($user["inputs"], true);
$inputs['autoanswer'] = $autoanswer;
$inputs['myOS'] = $myOS;
if($status != ''){
$inputs['simg']='status_notification';
$inputs['stext']=Message::badwords($status);
}
$inputs['status_private'] = $status_private;
$inputs['firstTime'] = $firstTime;
$inputs['lastTime'] = $lastTime;
$inputs['ubrowser'] = $ubrowser;
$inputs = json_encode($inputs, JSON_UNESCAPED_UNICODE);

$sql->where('id',$id)->update('onlines',array(
        'name' => $name,
        'gender' => $gender,
        'type' => $type,
        'roomid' => $roomid,
        'roomname' => $roomname,
        'inputs' => $inputs
    ));
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
chatroom::addlog('9', "کاربر مجازی \"{$user['name']}\" را ویرایش کرد");}
echo('ok');
?>
