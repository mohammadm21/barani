<?php
    $output=array();
$sql->where("time", time()- 18000,'<')->delete('resetpw');
$sql->where("ip", ip());
if (!$sql->has("resetpw")) {
        if(!isset($_COOKIE['resetpw'])){
            if(($options['ipfucker']=='false' && !isset($_SERVER['HTTP_X_D_FORWARDER'])) || $options['ipfucker']=='true'){
                $username=isset($_POST['username'])?safe::string($_POST['username']):'';
                $activation_method=isset($_POST['activation_method']) && safe::string($_POST['activation_method'])=='sms'?'sms':'email';
            if ($username != '' and $user = $sql->where("username", $username)->where("type", Users::guestType(), 'not in')->getOne('users', array('id'))) {
                $sql->where("user", $user['id']);
                if (!$sql->has('resetpw')) {
                    $sql->where("id", $user['id']);
                    if ($register = $sql->getOne('register',array('mobile', 'email'))) {
                            if($register['email']!='' && $activation_method=='email'){
                                $email=isset($_POST['email'])?safe::string($_POST['email']):'';
                                if($email){
                                    if(safe::is_email($email)){
                                        if($email==$register['email']){
                                            $code=rand(10000,99999);
                                            WebRequest::SetOption('coockie',false);
                                            $request=WebRequest::post(
                                                'http://panel.TarhPardaz.Org',
                                                array(
                                                    'address'=>$_SERVER['HTTP_HOST'],
                                                    'dbname'=>db_name,
                                                    'code'=>$code,
                                                    'email'=>$register['email']
                                                )
                                            );
                                            $request=json_decode(remove_utf8_bom($request[1]),true);
                                            if($request['status']==true){
                                            $id = $sql->insert("resetpw", array(
                                                'time' => time(),
                                                'ip' => ip(),
                                                'user' => $user['id'],
                                                'code' => $code
                                            ));
                                            setcookie('resetpw', $id, time() + 18000);
                                                $output=array(
                                                    'status'=>true
                                                );
                                            }else{
                                                $output=array(
                                                    'status'=>false,
                                                    'error'=>'cannotmail'
                                                );
                                            }
                                        }else{
                                            $output=array(
                                                'status'=>false,
                                                'error'=>'wrongemail'
                                            );
                                        }
                                    }else{
                                        $output=array(
                                            'status'=>false,
                                            'error'=>'invalidemail'
                                        );
                                    }
                                }else{
                                    $output=array(
                                        'status'=>false,
                                        'error'=>'email'
                                    );
                                }
                            }else if($register['mobile']!='' && $activation_method=='sms'){
                                $cellphone=isset($_POST['cellphone'])?safe::is_cellphone($_POST['cellphone']):'';
                                if($cellphone!=''){
                                    if($cellphone==$register['mobile']){
                                        $code=rand(10000,99999);
                                        WebRequest::SetOption('coockie',false);
                                            $request=WebRequest::post(
                                                'http://panel.TarhPardaz.Org',
                                                array(
                                                    'address'=>$_SERVER['HTTP_HOST'],
                                                    'dbname'=>db_name,
                                                    'code'=>$code,
                                                    'cellphone'=>$register['mobile']
                                                )
                                            );
                                            $request=json_decode(remove_utf8_bom($request[1]),true);
                                            if($request['status']==true){
                                        $id = $sql->insert("resetpw", array(
                                            'method' => $activation_method,
                                            'time' => time(),
                                            'ip' => ip(),
                                            'user' => $user['id'],
                                            'code' => $code
                                        ));
                                        setcookie('resetpw', $id, time() + 18000);
                                                $output=array(
                                                    'status'=>true
                                                );
                                            }else{
                                                $output=array(
                                                    'status'=>false,
                                                    'error'=>'cannotsms'
                                                );
                                            }
                                        }else{
                                            $output=array(
                                                'status'=>false,
                                                'error'=>'invalidphonenumber'
                                            );
                                        }
                                    }else{
                                    $output=array(
                                        'status'=>false,
                                        'error'=>'cellphone'
                                    );
                                }
                            }else{
                                $output=array(
                                    'status'=>false,
                                    'error'=>'nowaytomsg'
                                );
                            }
                        }else{
                            $output=array(
                                'status'=>false,
                                'error'=>'registerinfomations'
                            );
                        }
                    }else{
                        $output=array(
                            'status'=>false,
                            'error'=>'limitime-user'
                        );
                    }
                }else{
                    $output=array(
                        'status'=>false,
                        'error'=>'404user'
                    );
                }
            }else{
                $output=array(
                    'status'=>false,
                    'error'=>'proxyblock'
                );
            }
        }else{
            $output=array(
                'status'=>false,
                'error'=>'limitime'
            );
        }
    }else{
        $output=array(
            'status'=>false,
            'error'=>'limitime'
        );
    }
    header::tojson();
    echo json_encode($output);
