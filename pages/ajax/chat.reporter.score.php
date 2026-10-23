<?php
check_session();
if(!session('id') or session('admin') == '0'){exit("PERM");}
$mo = safe::number($_POST['id']);
if($mo==session('id')){exit('شما نمیتوانید به خودتان امتیاز دهید');}
        if ($mo != "") {
	    $umo = $sql->where('id', $mo)->getValue('users', 'count(*)');
            if ($umo <= 0) {
                exit("کاربر مورد نظر وجود ندارد");
            } else {
		$usermo = $sql->where('username', $mo)->getOne('users', array('id', 'score'));
		if ($sql->where('id', $mo)->getValue('onlines', 'count(*)') > 0) {
		    $oscor = $sql->where('id', $mo)->getOne('onlines', array('id', 'score'));
                    $newscore = $oscor['score'] += $options['report_score'];
			$sql->where('id', $mo)->update('users', array(
				'score' => $newscore
			));
			$sql->where('id', $mo)->update('onlines', array(
				'score' => $newscore
			));
                } else {
                    $newscore = $usermo['score'] += $options['report_score'];
			$sql->where('id', $mo)->update('users', array(
				'score' => $newscore
			));
                }
	                 $txt = $options['report_score']."امتیاز به شما تعلق گرفت";	
	                     Users::warning($mo,$txt);
                       $name = Users::get('username',$mo);
				echo($options['report_score']."امتیاز با موفقیت به کاربر داده شد");
                               chatroom::addlog('7', "به کاربر {$name} به دلیل ارسال گزارش {$options['report_score']}امتیاز داد");
             }
 }
?>