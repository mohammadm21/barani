<?php
if(!session('id'))exit('login');
if($options['buy_type'] == 1){
	if(session('type') == 1)exit('مهمان اجازه خرید درجه ندارد لطفا عضو شوید');
		$j = json_decode($options['useroptions'], true);
	if(in_array(session('type'), $j['buy_type'])){
		$type = safe::number($_POST['type']);
		if(session('type') == $type)exit('خطا');
		$access = false;
		$score  = 0;
                $sql->where("id", session('id'));
                $me = $sql->getOne('users', array('bank'));
			foreach($usertype as $t)
			{
				if($type == $t['id'])
				{
					$access = true;
					$score = $t['score'];
					break;
				}
			}
			if($score != 0){
				if($access)
				{
					if($me['bank'] >= $score)
					{
						$bank = $me['bank'] - $score;
                                      $sql->where("id", session('id'))->update('onlines', array(
                                           'bank' => $bank,
                                           'type' => $type,
                                           'score' => $score
                                       ));
                                       $sql->where("id", session('id'))->update('users', array(
                                           'bank' => $bank,
                                           'type' => $type,
                                           'score' => $score
                                       ));
						echo 'ok';
					}else{
						echo 'امتیاز بانکی شما کافی نمیباشد شما باید حداقل '.$score.' امتیاز تو حساب بانکی خود داشته باشید';
					}
				}else{
					echo 'این درجه وجود ندارد';
				}
			}else{
				echo 'شما نمیتوانید این درجه را بخرید مدیر چت روم این درجه را از لیست فروش حذف کرده است';
			}
		}else{
			echo 'این قسمت برای گروه کاربری شما غیرفعال شده است';
		}
}else{
	echo 'این قسمت توسط مدیر غیر فعال شده است';
}
?>