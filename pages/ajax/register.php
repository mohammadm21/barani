<?php
$output = array();
$sql->where("code", 0, '!=');
$sql->where("time", time() - 86400, '<');
foreach($sql->get('register', null, array('id')) as $row){
    $sql->where("id", $row['id'])->delete('register');
    $sql->where("id", $row['id'])->delete('users');
    $sql->where("id", $row['id'])->delete('profiles');
}
$sql->where("ip", ip());
if (!$sql->has("register")) {
	if(!isset($_COOKIE['register'])){
		if(($options['ipfucker'] == 'false' and !isset($_SERVER['HTTP_X_D_FORWARDER'])) or $options['ipfucker'] == 'true'){
			$username = isset($_POST['username']) ? safe::string($_POST['username']) : '';
			if(session('id')){
				
				        $sql->where("id", session('id'));
				        if ($me = $sql->getOne('users', array('id', 'username', 'type', 'gender'))) {
				
					if($usertype[$me['type']]['guest'] == 1){
						$username = $me['username'];
						
					}
					
				}
			}
			if(function_exists('mb_substr'))$username = mb_substr($username, 0, $strname, "utf-8");
			if($username){
				$badname = checkbadname($username, 'login');
				if(!$badname){
					if(isset($me['id']) or !Users::Checkrunning($username)){
						$password = isset($_POST['pwd']) ? $_POST['pwd'] : '';
						if($password){
							$repass = isset($_POST['repwd']) ? $_POST['repwd'] : '';
							if($repass == $password){
								$firstname = isset($_POST['firstname']) ? safe::string($_POST['firstname']) : '';
								if($firstname){
									$lastname = isset($_POST['lastname']) ? safe::string($_POST['lastname']) : '';
									if($lastname){
										$activation = isset($_POST['activation']) ? safe::string($_POST['activation']) : '';
										if(!in_array($activation, array('mail', 'sms')))$activation = 'mail';
										$cellphone = isset($_POST['cellphone']) ? safe::string($_POST['cellphone']) : '';
										if($cellphone){
											if(safe::is_cellphone($cellphone)){
											$sql->where("cellphone", $cellphone);
											if (!$sql->has("register")) {
													$output = array(
														'status' => false,
														'error' => 'recellphone'
													);
												}
											}else{
												$output = array(
													'status' => false,
													'error' => 'cellphone'
												);
											}
										}
										$email = isset($_POST['email']) ? safe::string($_POST['email']) : '';
										if($email and safe::is_email($email)){
											$sql->where("email", $email);
											if (!$sql->has("register")) {
												$output = array(
													'status' => false,
													'error' => 'remail'
												);
											}
										}else{
											$email = '';
										}
										if(empty($output)){
											if($activation == 'sms' && $cellphone == ''){
												$output = array(
													'status' => false,
													'error' => 'cellphone'
												);
											}elseif($activation == 'mail' && $email == ''){
												$output = array(
													'status' => false,
													'error' => 'email'
												);
											}
										}
										
										
										if(empty($output)){
											
											$state = isset($_POST['state']) ? safe::string($_POST['state']) : '';
											if($state){
												$city = isset($_POST['city']) ? safe::string($_POST['city']) : '';
												if($city){
													$gender = isset($_POST['gender']) ? safe::number($_POST['gender']) : '';
													if(isset($me)){
														$gender = $me['gender'];
													}
													if(in_array($gender, array(1,2))){
														$code = rand(10000, 99999);
												
												
														WebRequest::SetOption('coockie',false);
														$data = array('dbname' => db_name, 'code' => $code,'cellphone' => $cellphone);
														if($activation == 'sms'){
															$data['cellphone'] = $cellphone;
															$uri = 'sendactivesms';
														}elseif($activation == 'mail'){
															$data['email'] = $email;
															$uri = 'sendactivemail';
														}
														$request = WebRequest::post('http://panel.TarhPardaz.Org'.$uri, $data);
														$data = remove_utf8_bom($request[1]);
													//	$request = json_decode(remove_utf8_bom($request[1]), true);
												//		if($request['status'] == true){
														if(preg_match('/{"status":true}/', $data)){
															if(!isset($me)){
																$id = Users::addUser($username, $username, Users::lowestlevelregister(), $gender, $email, md5(base64_encode(md5(base64_encode($password)))));
                                                                                                                                $sql->where("id", $id)->update('users', array(
                                                                                                                                'active' => 0,
                                                                                                                                'score' => 0
                                                                                                                                 ));
															}else{
																$id = $me['id'];
												                                 $sql->where("id", $id)->update('users', array(
                                                                                                                                    'active' => 0,
                                                                                                                                    'email' => $email,
                                                                                                                                    'password' => md5(base64_encode(md5(base64_encode($password)))),
                                                                                                                                    'score' => 0
                                                                                                                                ));
															
															}
                                                 						                                   $sql->insert("register", array(
                                                 						                                       'id' => $id,
                                                 						                                       'ip' => ip(),
                                                  						                                      'time' => time(),
                                                  						                                      'email' => $email,
                                                  						                                      'firstname' => $firstname,
                                                    						                                    'lastname' => $lastname,
                                                   						                                     'cellphone' => $cellphone,
                                                  						                                      'state' => $state,
                                                 						                                       'city' => $city,
                                                 						                                       'code' => $code
                                                						                                    ));
															setcookie('register', $id, time()+ 86300);
															session('ui', $id);
															$output = array(
																'status' => true,
																'action' => 'valid'
															);
															stats('register');
														}else{
															$output = array(
																'status' => false
															);
															if($activation == 'sms')$output['error'] = 'cannotsms';
															elseif($activation == 'mail')$output['error'] = 'cannotmail';
														}
													}else{
														$output = array(
															'status' => false,
															'error' => 'gender'
														);
													}
												}else{
													$output = array(
														'status' => false,
														'error' => 'city'
													);
												}
											}else{
												$output = array('status' => false,'error' => 'state');
											}
								
										}
									}else{
										$output = array(
											'status' => false,
											'error' => 'lastname'
										);
									}
								}else{
									$output = array(
										'status' => false,
										'error' => 'firstname'
									);
								}
						
					
							}else{
								$output = array('status'=> false, 'error' =>'repeatpassword');
							}
						}else{
							$output = array('status'=> false, 'error' =>'password');
						}  			
					}else{
						$output = array('status'=> false, 'error' =>'runningname');
					}
				}else{
					$output = array('status'=> false, 'error' =>'badname');
				}
			}else{
				$output = array('status'=> false, 'error' =>'username');
			}
		}else{
			$output = array('status'=> false, 'error' =>'proxyblock');
		}
	}else{
		$output = array('status'=> false, 'error' =>'every24');
	}	
}else{
	$output = array('status'=> false, 'error' =>'every24');
}
header::tojson();
echo json_encode($output);
?>
