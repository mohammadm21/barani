<?php
$output = array();
if(session('id')){
	$user = isset($_POST['user']) ? safe::number($_POST['user']) : '';
	if($user and $user != session('id') and $user = $sql->where('id',$user)->getOne('users', array('id','username'))){
		$blocktype = isset($_POST['blocktype']) ? safe::number($_POST['blocktype']) : '';
		switch($blocktype){
			case('1'):
				
			
				break;
			case('2'):
				
				break;
			case(''):
			
			
				break;
			default:
			
			
			
				break;
		
		}
	
	}
}
