<?php
check_session();
if(Users::guestType(session('type')))return;

include('inc/classes/profile.php');
include('inc/classes/bbcode.php');
Profile::CheckActiveMe();

	$pid = isset($_POST['pid']) ? safe::number($_POST['pid']) : die('pid?');
	$cid = isset($_POST['cid']) ? safe::string($_POST['cid']) : die('cid?');
	
	$sql->where("id", $pid);
	$getC = $sql->getOne('profile_posts', array('reply_cm'));

	if(!$getC)return;
	$reply_comment = json_decode($getC['reply_cm'],true);
	
	$uid = array();
	$name = array();
	$type = array();
	$avatar = array();
	$gender = array();
	$online = array();
	
	foreach($reply_comment[$cid] as $key => $val){
		$uid[] = $val['uid'];
	}
	if(empty($uid))return;
	$implode = implode(',', $uid);
	$sql->join('profiles p', 'p.id=u.id', 'LEFT');
	$sql->join('onlines o', 'o.id=u.id', 'LEFT');
	$sql->where("u.id", $uid, 'in');
	foreach($sql->get('users u', null, array('u.id', 'u.name', 'u.gender', 'u.type', 'p.avatar', 'o.id as online')) as $row){
		$name[$row['id']] = $row['name'];
		$gender[$row['id']] = $row['gender'];
		$type[$row['id']] = $row['type'];
		$avatar[$row['id']] = $row['avatar'];
		$online[$row['id']] = $row['online'] ? 'on' : 'off';
	}
	
	echo '<script type="text/javascript">
		$(function () {
		  $(\'[data-toggle="tooltip"]\').tooltip();
		});</script>';
		
	$myid = session('id');
	$admin = session('admin');
	
	foreach($reply_comment[$cid] as $key => $val){
		$Access2DeleteComment = $val['uid'] != $myid ? CheckAdminType($type[$val['uid']]) : true;
		
?>
		<div id="replyid-<?=$val['cid'];?>" class="row">
			<div class="col-lg-2">
				<a target="_blank" href="/profile/<?=$val['uid'];?>"><img data-toggle="tooltip" data-placement="bottom" title="<?=$name[$val['uid']];?> - <?=$usertype[$type[$val['uid']]]['title'];?>" class="<?=$online[$val['uid']]?>" src="<?php echo(Profile::ShowResizedAvatar(is_file('theme/images/avatars/'.$avatar[$val['uid']]) ? $avatar[$val['uid']] : ($gender[$val['uid']] == '1' ? 'no_avatar_male.png' : 'no_avatar_female.jpg'))); ?>"></a>
			</div>
			<div class="col-lg-10">
				<div class="panel panel-default arrow right">
					<div style="background: #F9F9F9;" class="panel-body">
						<div class="comment-user"><small><b><?=$name[$val['uid']];?></b> :</small>
							<div class="pull-left"><small><?=jdate::start('l d F Y ساعت H:i:s', $val['time'])?></small></div>
						</div>
						<?php if($Access2DeleteComment || $val['uid'] == $myid){?><div class="pull-left"><a class="cursor" onclick="DeleteCommentReply('<?=$pid;?>','<?=$val['cid'];?>','<?=$cid;?>','<?=$val['uid'];?>','<?=$val['time'];?>');"><i class="fa fa-remove"></i></a></div><?php } ?>
						<span><div class="break-word"><?php echo bbcode::SmileComment($val['comment']); ?></div></span>
					</div>
				</div>
			</div>
		</div>		
<?php
	}
	unset($comments, $name, $type, $avatar, $gender, $uid, $implode, $online);
?>
