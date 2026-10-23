<?php
check_session();
if(Users::guestType(session('type')))return;

include('inc/classes/profile.php');
include('inc/classes/bbcode.php');
Profile::CheckActiveMe();

	$pid = isset($_POST['pid']) ? safe::number($_POST['pid']) : die('pid?');
	$action = isset($_POST['action']) ? safe::string($_POST['action']) : die('action?');
	$ltime = isset($_POST['time']) ? safe::number($_POST['time']) : '0';
	$show_reload = false;
	$show_pp = false; 
        switch ($action) {
	case 'avatar':
		$getC = $sql->where('id', $pid)->getOne('profiles', array('cavatar'));
		break;
	case 'cover':
		$getC = $sql->where('id', $pid)->getOne('profiles', array('ccover'));
		break;
	case 'info':
		$getC = $sql->where('id', $pid)->getOne('profiles', array('cinfo'));
		break;
	case 'music':
		$getC = $sql->where('id', $pid)->getOne('profiles', array('cmusic'));
		break;
	default:
		$getC = $sql->where('id', $pid)->getOne('profile_posts', array('comments', 'reply_cm'));
		$show_reload = true;
		$show_pp = true;
		break;
	}
	if (!$getC) {
	return;
	}
	$getC = array_values($getC);
	$comments = json_decode($getC[0],true);
	$reply_comment = json_decode($getC[1],true); 
	
	$uid = array();
	$name = array();
	$type = array();
	$avatar = array();
	$gender = array();
	$online = array();
	
	foreach($comments as $key => $val){
		$uid[] = $val['uid'];
	}
	if(empty($uid))return;
	$implode = implode(',', $uid);
        $query = $sql->where("u.id", $uid, 'in')->join('profiles as p', 'p.`id` = u.`id`', 'LEFT')->join('onlines as o', 'o.`id` = u.`id`', 'LEFT')->get('users as u', null, array('u.`id`', 'u.`name`', 'u.`gender`', 'u.`type`', 'p.`avatar`', 'o.`id` as online'));
        foreach ($query as $row) {
		$name[$row['id']] = $row['name'];
		$gender[$row['id']] = $row['gender'];
		$type[$row['id']] = $row['type'];
		$avatar[$row['id']] = $row['avatar'];
		$online[$row['id']] = $row['online'] ? 'on' : 'off';
	}
	
	//rsort($comments);
	//$comments = array_slice($comments, 0,10);
	
	echo '<script type="text/javascript">
		$(function () {
		  $(\'[data-toggle="tooltip"]\').tooltip();
		});</script>';
		
	$myid = session('id');
	$admin = session('admin');
	$lastcomment = '';
	
	foreach($comments as $key => $val){
	if($ltime != 0 && $val['time'] <= $ltime)continue;
	$Access2DeleteComment = $val['uid'] != $myid ? CheckAdminType($type[$val['uid']]) : true;
	$count_reply = count($reply_comment[$val['cid']]);
		
?>
		<div id="<?=$val['cid'];?>" class="row">
			<div class="col-lg-2">
				<a target="_blank" href="/profile/<?=$val['uid'];?>"><img data-toggle="tooltip" data-placement="bottom" title="<?=$name[$val['uid']];?> - <?=$usertype[$type[$val['uid']]]['title'];?>" class="<?=$online[$val['uid']]?>" src="<?php echo(Profile::ShowResizedAvatar(is_file('theme/images/avatars/'.$avatar[$val['uid']]) ? $avatar[$val['uid']] : ($gender[$val['uid']] == '1' ? 'no_avatar_male.png' : 'no_avatar_female.jpg'))); ?>"></a>
				<?php if($show_pp){?><br><center><button onclick="UserReplyComment('<?=$name[$val['uid']];?>','<?=$val['cid'];?>','<?=$pid;?>'); return false;" class="btn btn-primary btn-xs">پاسخ</button></center><?php } ?>
			</div>
			<div class="col-lg-10">
				<div class="panel panel-default arrow right">
					<div class="panel-body">
						<div class="comment-user"><b><?=$name[$val['uid']];?> :</b>
							<div class="pull-left"><?=jdate::start('l d F Y ساعت H:i:s', $val['time'])?></div>
						</div>
						<?php if($Access2DeleteComment || $val['uid'] == $myid){?><div class="pull-left"><a class="cursor" onclick="DeleteComment('<?=$pid;?>','<?=$val['cid'];?>','<?=$val['uid'];?>','<?=$val['time'];?>','<?=$action;?>');"><i class="fa fa-remove"></i></a></div><?php } ?>
						<span><div class="break-word"><?php echo bbcode::SmileComment($val['comment']); ?></div></span>
						<?php if($count_reply > 0){?>
						<hr><div class="pull-right"><a class="cursor" onclick="ShowReplies('<?=$pid;?>','<?=$val['cid'];?>');return false;"><i class="fa fa-mail-reply green"></i> <?=$count_reply;?> پاسخ</a></div>
						<div id="split-<?=$val['cid'];?>"></div>
						<div id="show-<?=$val['cid'];?>"></div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>		
<?php
	$lastcomment = $val['time'];
	}
	unset($comments, $name, $type, $avatar, $gender, $uid, $implode, $online);
?>
