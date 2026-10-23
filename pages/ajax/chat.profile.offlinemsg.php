<?php
check_session();
if(Users::guestType(session('type')))return;
include('inc/classes/profile.php');

$myid = session('id');

// Handle delete request
if (isset($_POST['del']) && $_POST['del'] == 'true') {
    header::tojson();
    $sql->where("userid", $myid);
    $sql->delete('offline_message');
    echo json_encode(array('text' => 'پیام‌ها حذف شد', 'type' => 'success'));
    exit;
}

		echo '<script type="text/javascript">
		$(function () {
		  $(\'[data-toggle="tooltip"]\').tooltip();
		});</script>';
	$sql->join('users u', 'u.id=o.sender_id', 'LEFT');
	$sql->join('profiles p', 'p.id=o.sender_id', 'LEFT');
	$sql->join('onlines oo', 'oo.id=o.sender_id', 'LEFT');
	$sql->where("userid", $myid);
	$sql->orderBy('id', 'DESC');
	foreach($sql->get('offline_message o', null, array('o.*', 'u.name', 'u.type', 'u.gender', 'p.avatar', 'oo.id as online')) as $row){
		$expl = explode(',',$row['text']);
		asort($expl);
		foreach($expl as $expls)
		{
			$is_online = $row['online'] ? 'on' : 'off';
?>
		<div id="cmid" class="row">
			<div class="col-lg-2">
				<a target="_blank" href="/profile/<?=$row['sender_id'];?>"><img data-toggle="tooltip" data-placement="bottom" title="<?=$row['name'];?> - <?=$usertype[$row['type']]['title'];?>" class="<?=$is_online;?>" src="<?php echo(Profile::ShowResizedAvatar(is_file('theme/images/avatars/'.$row['avatar']) ? $row['avatar'] : ($row['gender'] == '1' ? 'no_avatar_male.png' : 'no_avatar_female.jpg'))); ?>"></a>
			</div>
			<div class="col-lg-10">
				<div class="panel panel-default arrow right">
					<div class="panel-body">
						<div class="comment-user"><b><?=$row['name'];?></b>
							<div class="pull-left"></div>
						</div>
						<span><div class="break-word"><?=$expls;?></div></span>
					</div>
				</div>
			</div>
		</div>	
<?php
		}
	}

?>