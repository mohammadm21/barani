<?php
$output = array();
$sql->where("time", time()- 18000,'<')->delete('resetpw');

if(isset($_COOKIE['resetpw'])){
    $sql->where("id", safe::number($_COOKIE['resetpw']));
    if ($resetpw = $sql->getOne('resetpw', array('ip', 'user', 'code'))) {
		$code = isset($_POST['code']) ? safe::number($_POST['code']) : '';
		if($code == $resetpw['code']){
						//include('inc/classes/pwcreator.php');
            					$sql->where("id", $resetpw['user']);
                				if ($user = $sql->getOne('users', array('id', 'password'))) {
                				    $sql->where("id", $user['id']);
                 				   if ($register = $sql->getOne('register',array('mobile', 'email'))) {
                 				   $sql->where("user", $user['id']);
                 				    $method = $sql->getOne('resetpw','method');
						$newPwd = rand(11111,99999);
						$data = array('address' => $_SERVER['HTTP_HOST'], 'dbname' => db_name, 'newpass' => $newPwd);
						if($method['method'] == 'email'){
							$uri = 'sendrepwmail';
							$data['email'] =$register['email'];
						}elseif($method['method'] == 'sms'){
							$uri = 'sendrepwsms';
							$data['cellphone'] = $register['mobile'];
						}else{
							exit('method?');
						}
						WebRequest::SetOption('coockie',false);
						$request = WebRequest::post('http://panel.TarhPardaz.Org'.$uri, $data);
						$request = json_decode(remove_utf8_bom($request[1]), true);
						if($request['status'] == true){
                         			   $sql->where("id", $user['id'])->update('users', array(
                         			       'password' => md5($newPwd),
                        			        'question' => null
                       			          ));
							$output = array(
								'status' => true
							);
						}else{
							$output = array('status' => false);
							if($register['email'] != '' && $method['method'] == 'email')$output['error']= 'cannotmail';
							elseif($register['mobile'] != '' && $method['method'] == 'sms')$output['error'] = 'cannotsms';
						}
					}else{
						$output = array(
							'status' => false,
							'error' => 'registerinfomations'
						);
					}
				}else{
					$output = array(
						'status' => false,
						'error' => '404user'
					);
				}
			}else{
				$output = array(
					'status' => false,
					'error' => 'changeip'
				);
			}
		}else{
			$output = array(
				'status' => false,
				'error' => 'wrongcode'
			);
		}
	}else{
		$output = array(
			'status' => false,
			'error' => 'noresetpw'
		);
	}
}else{
	$output = array(
		'status' => false,
		'error' => 'noresetpwcookie'
	);
}
header::tojson();
echo json_encode($output);
?>
