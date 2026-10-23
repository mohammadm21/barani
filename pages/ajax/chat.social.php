<?php
$action = isset($_GET['action']) ? safe::string($_GET['action']) : '';
$del = isset($_REQUEST['del']) && $_REQUEST['del'] == 'true' ? true : false;
if($action=='likecm') { 
echo '<a style="text-decoration: none;color:red;margin-left:180px;" href="chat.social?del=true">مشاهده شد و حذف همگی از این لیست </a><br><br>';
$sql->where('to', session('id'));
$sql->where("username", "%" . 'LKCM' . "%", "like");
$query = $sql->get('message_archive', null, array('text'));
foreach($query as $rows )
    {
  echo $rows['text'].'<br>';
    } 
$sql->where('to', session('id'));
$sql->where("username", "%" . 'LKCM' . "%", "like");
$query = $sql->get('message', null, array('text'));
foreach($query as $rowss )
    {
  echo $rowss['text'].'<br>';
    } 
   return;
 } 
if($del) { 
  $sql->where('to', session('id'));
  $sql->where("username", "%" . 'LKCM' . "%", "like")->delete("message_archive");
  $sql->where('to', session('id'));
  $sql->where("username", "%" . 'LKCM' . "%", "like")->delete("message");
  return;
}
header::tojson();
$lastposts = nlastposts();


$sql->where('to', session('id'));
$sql->where("username", "%" . 'LKCM' . "%", "like");
$lkcm1 = $sql->getValue('message','count(*)');
$sql->where('to', session('id'));
$sql->where("username", "%" . 'LKCM' . "%", "like");
$lkcm2 = $sql->getValue('message_archive','count(*)');
$lkcm = $lkcm1+lkcm2;

$offlinemsg = $sql->where('userid', session('id'))->getValue('offline_message', 'count(*)');
$pending = Users::get('pending', session('id'));
$pending = count(json_decode($pending, true));

echo json_encode(array('lastposts' => (is_array($lastposts) ? $lastposts : array()), 'offlinemsg'=>$offlinemsg,'xpending'=>$pending,'lkcm'=>$lkcm), JSON_UNESCAPED_UNICODE);
function nlastposts() {
    global $usertype, $options, $sql;
    $friends = Users::get('friends', session('id'));
    $friends = json_decode($friends, true);
    $friends = is_array($friends)? array_values($friends): array();
    $lastposts = array();
     if (count($friends) !=0 ) {
    $x = 0; $gh=0; $gh1=0;
    $limit = isset($options['side_numposts']) && $options['side_numposts'] > 0 ? $options['side_numposts'] : "20";
       $sql->join('profiles pr','pr.id=p.userid','LEFT');
       $sql->join('users u', 'u.id=p.userid', 'LEFT');
       $sql->join('onlines o', 'o.id=p.userid', 'LEFT');
       $sql->orderBy('id', 'DESC');
       $sql->where('p.userid', $friends, 'in'); 
       foreach($sql->get('profile_posts p', $limit, array('o.hide','p.*', 'u.name', 'u.type', 'u.gender', 'pr.avatar', 'o.id as online')) as $row){
        $avatar = '/avatars/' . (is_file('theme/images/avatars/' . $row['avatar']) ? $row['avatar'] : ($row['gender'] == '1' ? 'no_avatar_male.png' : 'no_avatar_female.jpg')) . '/100x100';
        $on = $row['hide'] == '0' ? 'on' : 'off';
        $time = time_ago($row['time']);
        $gh=$row['userid'];
        if($gh != $gh1) {
        $lastposts[] = array(
            'id' => $row['userid'],
            'name' => $row['name'],
            'avatar' => $avatar,
            'type' => $row['type'],
            'online' => $on,
            'time' => $time,
            'rtime' => $row['time']
        );  } 
        $gh1=$gh; 
        $x++;
      } 
   }
    return $lastposts;
}
?>