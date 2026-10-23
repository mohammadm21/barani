<?php
check_session();
if(Users::guestType(session("type"))): exit(""); endif;
if(!chatroom::checkPermission("viewprofile")||!chatroom::checkPermission("profile")): exit("شما به این امکان دسترسی ندارید"); endif;
$user_id=session("id");
$action=safe::string($_POST["action"]);
if($action==""): exit("وجود خطا !");
elseif($action=="set_avatar"):
   $avatar=safe::string($_POST["avatar"]);
   if($avatar==""): exit("آواتار را انتخاب کنید"); endif;
   $sql->where("id", $user_id);
   $profile=$sql->getOne("profiles");
   if($avatar==$profile["avatar"]): exit("این آواتار آواتار فعلی شماست و نمی توانید مجددا آن را برای آواتار اصلی انتخاب کنید"); endif;
   $last_avatars=array();
   $last_avatars[]=$profile["avatar"];
   $now_last_avatars=($profile["last_avatars"]!==""?(array)json_decode($profile["last_avatars"]):array());
   foreach($now_last_avatars as $avatarr):
      if($avatarr==""||$avatarr=="photo.png"): continue; endif;
      if($avatarr==$avatar): continue; endif;
      $last_avatars[]=$avatarr;
   endforeach;
   $sql->where("id", $user_id)->update('profiles', array(
       'avatar' => $avatar,
       'last_avatars' => json_encode($last_avatars)
   ));
   exit("refresh");
elseif($action=="delete_avatar"):
   $avatar=safe::string($_POST["avatar"]);
   if($avatar==""): exit("آواتار را انتخاب کنید"); endif;
   $sql->where("id", $user_id);
   $profile=$sql->getOne("profiles");
   if($avatar==$profile["avatar"]): exit("این آواتار آواتار فعلی شماست و امکان حذف آن وجود ندارد"); endif;
   $last_avatars=array();
   $now_last_avatars=($profile["last_avatars"]!==""?(array)json_decode($profile["last_avatars"]):array());
   foreach($now_last_avatars as $avatarr):
      if($avatarr==""||$avatarr=="photo.png"): continue; endif;
      if($avatarr==$avatar): continue; endif;
      $last_avatars[]=$avatarr;
   endforeach;
   $sql->where("id", $user_id)->update('profiles', array(
       'last_avatars' => json_encode($last_avatars)
   ));
   exit("refresh");
     
endif;
?>