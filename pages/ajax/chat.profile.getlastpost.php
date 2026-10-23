<?php 
check_session();

include('inc/classes/profile.php');
include('inc/classes/bbcode.php');

if(session('type') == '1')die();

	echo '<script type="text/javascript">
		$(function () {
		  $(\'[data-toggle="tooltip"]\').tooltip();
		});</script>';
		
$meAdmin = session('admin') != '0' ? true : false;
$meid = session('id');	

$sql->join('users u', 'u.id=p.userid', 'LEFT');
$sql->join('profiles pr', 'pr.id=p.userid', 'LEFT');
$sql->join('onlines o', 'o.id=p.userid', 'LEFT');
$sql->where("p.userid", $meid);
$sql->orderBy('p.id', 'DESC');
$row = $sql->getOne('profile_posts p', array('p.*', 'u.name', 'u.type', 'u.gender', 'pr.avatar','o.id as online'));
	$comments = count(json_decode($row['comments'],true));
	$online = $row['online'] ? 'class="on"' : '';
	$Access2DeletePost = $row['userid'] != $meid ? CheckAdminType($row['type']) : true;
	$pdisabled = false;
	if($row['disabled'] == '1'){
		$pdisabled = true;
?>
	<style type="text/css">
		#panelid-<?=$row['id']?> > .panel-heading {
			background-color: #FFE6E6;
		}
		#panelid-<?=$row['id']?> > .panel-footer {
			background-color: #FFE6E6;
		}
	</style>
	<?php } ?>
					  <div id="panelid-<?= $row['id'] ?>" class="panel panel-default">
                            <div class="panel-heading"><img <?= $uonline; ?> data-toggle="tooltip" data-placement="bottom" title="<?= $row['name']; ?> - <?= $usertype[$row['type']]['title']; ?>" src="<?php echo(Profile::ShowAvatarPostSender($row['avatar'], $row['gender'])); ?>"><span> <a target="_blank" href="/profile/<?= $row['userid']; ?>"><?= $row['name'] ?></a> در <?= jdate::start('l d F Y ساعت H:i:s', $row['time']) ?> نوشت:</span>
                                <div class="btn-toolbar navbar-left">
                                    <div class="btn-group">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><img src="/theme/microblog/images/gears.png" alt=""></a>
                                        <ul class="dropdown-menu">
                                            <?php if ($row['userid'] != $MySessionId) { ?>
                                                <li><a onclick="PostReport('<?= $row['id'] ?>');" href="javascript:void(0);"><center>گزارش تخلف به مدیران</center></a></li>
                                            <?php } if ($row['userid'] == $MySessionId || $Access2DeletePost) { ?>
                                                <!--<li><a onclick="editpost('<?= $row['id'] ?>');" href="javascript:void(0);">ویرایش</a></li>-->
                                                <!--<li class="divider"></li>-->
                                                <?php if ($pdisabled) { ?>
                                                    <li><a id="postmanager-<?= $row['id'] ?>" onclick="ProfilePostManager('<?= $row['id'] ?>', '0');" href="javascript:void(0);"><center><font color="green">فعال کردن نظرات</font></center></a></li>
                                                <?php } else { ?>
                                                    <li><a id="postmanager-<?= $row['id'] ?>" onclick="ProfilePostManager('<?= $row['id'] ?>', '1');" href="javascript:void(0);"><center><font color="red">غیر فعال کردن نظرات</font></center></a></li>
                                                <?php } ?>
                                                <li><a onclick="ProfileDeletePost('<?= $row['id'] ?>');" href="javascript:void(0);"><center>حذف این مطلب</center></a></li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <span><div class="break-word"><?php echo bbcode::tohtml($row['content'], true); ?></div></span>
                            </div>
     <div class="panel-footer">
						<div class="row">
					<div class="col-lg-12 likes">
								<div class="p-comments" data-toggle="collapse" href="#comments-<?= $row['id']; ?>" aria-expanded="true" aria-controls="#comments-<?= $row['id']; ?>">
									<a data-toggle="collapse" href="#comments-<?= $row['id']; ?>" aria-expanded="false" aria-controls="#comments-<?= $row['id']; ?>" class="collapsed">
									<i class="fa fa-send fa-lg"></i></a><span>نظرات [<?= $comments; ?>]</span>
								</div>
                                        <div class="pull-left post-pull">
									<span class="postlikes" data-html="true" data-toggle="tooltip" data-placement="top" title="<?= $row['likes']; ?>" onclick="ShowLDUser('<?= $row['id']; ?>','liked','posts');" id="plikeid-<?= $row['id']; ?>"><?= $row['likes']; ?></span>
									<a class="post-prlikes" onclick="postlike('<?= $row['id']; ?>','like');return false;"><i class="fa fa-thumbs-up fa-lg"></i></a>
									<a class="post-pllikes" onclick="postlike('<?= $row['id']; ?>','dislike');return false;"><i class="fa fa-thumbs-down fa-lg"></i></a>
									<span class="postdislikes" data-html="true" data-toggle="tooltip" data-placement="top" title="<?= $row['dislikes']; ?>" onclick="ShowLDUser('<?= $row['id']; ?>','disliked','posts');" id="pdislikeid-<?= $row['id']; ?>"><?= $row['dislikes']; ?></span>
						
                                  	</div>
							</div>
						</div>
                                <div class="row">
                                    <div id="comments-<?= $row['id']; ?>" class="collapse commentsbox">
                                        <div class="col-lg-12 comments" id="comments-list-<?= $row['id']; ?>">
                                            <div class="pull-right"><a class="cursor" onclick="ShowCommentsProfile('<?= $row['id']; ?>', '');
                                                    return false;"><li class="fa fa-comments fa-lg"></li> (<?= $comments; ?>)</a></div>
                                            <hr>
                                            <div id="append-comments-<?= $row['id']; ?>"></div>
                                            <div id="showw8-<?= $row['id']; ?>"></div>

                                            <div id="hidden-show-post-<?= $row['id']; ?>" style="<?php echo $pdisabled ? 'display:none;' : 'display:block;'; ?>">
                                                <div class="pull-right"><a id="reloadtime-<?= $row['id'] ?>" class="cursor" onclick="ShowCommentsProfile('<?= $row['id']; ?>', '');
                                                        return false;"><i class="fa fa-refresh fa-lg"></i></a></div>
                                                <hr>
                                                <div id="reply-<?= $row['id']; ?>"></div>
                                                <div class="row" id="comments-form-<?= $row['id']; ?>">
                                                    <div class="send-upload">
                                                        <form class="commentsend" id="postid-<?= $row['id']; ?>">
                                                            <textarea placeholder="نظر خود را وارد کنید ..." ></textarea>
                                                            <button type="submit" onclick="SendCommentUser('<?= $row['id'] ?>', '');
                                                                    return false;" class="btn btn-success green"><i class="fa fa-share"></i> ارسال</button>
                                                            <button onclick="SmileArea('<?php echo $row['id']; ?>');
                                                                    return false;" class="btn btn-warning"><i class="fa fa-smile-o"></i>شکلک</button>
                                                        </form>
                                                    </div>
                                                </div><br>
                                                <div id="smilearea-loader-<?php echo $row['id']; ?>"></div>
                                                <div class="loadsmiley" id="smilearea-<?php echo $row['id']; ?>"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
<?php
?>
<script type="text/javascript">
	$('.panel-default .panel-body img').each(
	function(){
		var width = $(this).width();
		var height = $(this).height();
		if(width > 500){
			$(this).width(500);
		}
		if(height > 500){
			$(this).height(500);
		}
	});
</script>