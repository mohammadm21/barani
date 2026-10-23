<?php
   check_session();
   $user = safe::number($_POST['user']);
   $text = safe::string($_POST['text']);
   $action = safe::string($_POST['action']);
   if(session('admin') == 0){exit('Permissions');}
   $sql->where("id", $user);
   $input = $sql->getOne('onlines');
   $sql->where("id", $user);
   $users = $sql->getOne('onlines', array('inputs'));
     $usersoptions = json_decode($options['useroptions'],true);
    
   $sql->where("id", $user);
   if(!$row = $sql->getOne('onlines')){
	   exit('این کاربر انلاین نیست');
	   }elseif($action == "derestatus"){	
 if(in_array(session('type'),$usersoptions['chengestatosuserdel'])){	   
        $value = json_decode($users['inputs'], true);
        $value['simg'] = '';
        $value['stext'] =  '';
        Users::Inputs($user, $value);
        chatroom::addlog('7', "حذف استاتوس کاربر ".Users::get('username',$user)."");
        echo('استاتوس کاربر با موفقیت حذف گردید');
}else{
	echo('شما دسترسی لازم برای حذف استاتوس کاربر را دارا نمیباشد');
	}

}else{
	    if(empty($user)){exit('Error');}


        if(empty($text)){exit('متن استاتوس وارد نشده است');}
 if(in_array(session('type'),$usersoptions['modchangeuserstatos'])){
        $value = json_decode($users['inputs'], true);
        $value['simg'] = 'status_notification';
        $value['stext'] =  Message::badwords($text);
        Users::Inputs($user, $value);
        chatroom::addlog('7', "تغییر استاتوس کاربر ".Users::get('username',$user)." به ". Message::badwords($text));
        echo('استاتوس کاربر با موفقیت تغییر کرد');
	}else{
	echo('شما دسترسی لازم برای تغییر استاتوس کاربر را دارا نمیباشد');
	}
}
?>