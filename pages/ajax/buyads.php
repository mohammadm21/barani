<?php
check_session();
if($options['buyscore_status'] == 'true'){
	$key = md5(time()+session('id'));
	$_POST['amount'] = isset($_POST['amount']) ? safe::number($_POST['amount']) : die('خطا');
	if($_POST['amount'] < $options['buyscore_amount']){
		echo("حداقل خرید امتیاز {$options['buyscore_amount']} تومان میباشد");
		exit;
	}
	$data = array("userid"=>session('id'),"amount"=>$_POST['amount'],"url"=>$_SERVER['HTTP_HOST'],"api"=>$options['buyscore_code'],"key"=>$key);
	$get = send('https://panel.TarhPardaz.Org/ajax/buyscore',$data);
	if($get == 'error'){
		echo('اطلاعات فرستاده شده توسط سرور طرح پرداز تایید نشد');
		exit;
	}
	if(preg_match('/~~(.*?)~~/',$get,$ref)){
		echo($ref[1]);
       $sql->insert('pays', array(
            'user' => session('id'),
            'amount' => $_POST['amount'],
            'date' => time(),
            'key' => $key
        ));
	}
}else{
	exit('سیستم خرید امتیاز غیرفعال شده است');
}

function send($url,$data=array()){
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	$res = curl_exec($ch);
	curl_close($ch);
	return $res;
}
?>
