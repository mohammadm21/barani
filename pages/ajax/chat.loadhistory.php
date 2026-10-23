<?php
header::toJson();
$messages = array();
$toid = safe::number($_POST['to']);
$myid = safe::number(session('id'));
$last = safe::number($_POST['msg_id']);
$t;
if(!$idmsg)$idmsg = 0;

$result = $sql->where('id', $last, '>')->orderBy("date", "ASC")->where("`user` = {$myid} and `to` = {$toid}")->orWhere("`user` = {$toid} and `to` = {$myid}")->get('message', 20, array('id', 'user', 'username', 'color', 'room', 'text', 'date', 'to', 'into', 'type'));
foreach ($result as $row) {
		$avatar = $options['defaultavatar'];
		$row['to'] = $row['to'] == '-1' ? '0' : $row['user'];
                $sql->where('id', $row['user']);
                $online = $sql->getOne('onlines', array('gender', 'type', 'inputs'));
		$sex = isset($online['gender']) ? $online['gender'] : $options['defaultusertype'] ;
		$priv = isset($online['type']) ? $online['type'] : $options['defaultusertype'] ;
		if(isset($online['inputs'])){
			$inputs = json_decode($online['inputs'], true);
			$avatar = isset($inputs['avatar']) && $inputs['avatar'] != 'photo.png' ? (is_file('theme/images/avatars/'.$inputs['avatar']) && is_file('theme/images/avatars/'.MiniAvatarDir().'/'.$inputs['avatar']) ? $inputs['avatar'] : ($online['gender'] == '1' ? 'maledefualt.png' : 'femaledefualt.jpg')) : ($online['gender'] == '1' ? 'maledefualt.png' : 'femaledefualt.jpg');
		}
		if($row['into'] != 'pm'){$idmsg++;}
		$temp = array(
			'id' => $row['id'],
			'user' => $row['username'],
			'user_id' => $row['user'],
			'time' => date('H:i', $row['date']),
			'privat' => $row['to'],
			'text' => $row['text'],
			'css' => "{$row['color']}",
			'priv' => $priv,
			'sex' => $sex,
			'type' => 'get',
			'avatar' => $avatar,
			'into' => $row['into'],
			'msgid' => $row['id']
		);
		$jmsg = str_replace(array('<b>', '</b>', '<i>', '</i>'), "", $row['text']);
		if(isJson($jmsg)){
		$jd = json_decode($jmsg, true);
			if ($jd['action'] == 'reply') {
                    $temp['reply'] = $jd['umsg'];
                    $temp['name'] = $jd['uname'];
                    $temp['text'] = $jd['mymsg'];
                    if (isset($jd['isimg'])) {
                        $temp['reply'] = '<img width="50" height="50" id="nocheck" src="' . $jd['umsg'] . '">';
                    }
					
		        } 
		elseif ($jd['action'] == 'forward') {
                    $temp['forward'] = $jd['msg'];
                    $temp['name'] = $jd['name'];
                    if (isset($jd['isimg'])) {
                        $temp['forward'] = '<img width="50" height="50" id="nocheck" src="' . $jd['msg'] . '">';
                    }
		}
}
		$last = (int)$row['id'];
		$messages[] = $temp;

}
echo(json_encode(array('message' => $messages,'count'=> count($messages),'last_id'=> $last)));
?>