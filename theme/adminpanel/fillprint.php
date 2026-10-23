<?php include('header.php'); include('sidebar.php'); ?>
<script type="text/javascript">
function ConfirmAbuser(fpId){
    if(!confirm('آیا از این کاربر به عنوان کاربر اخراجی/مشکوک تایید میکنید؟')){
        return;
    }
    $.ajax({
        type:"POST",
        url:'<?php echo(url('ajax/admin.fillprint')); ?>',
        data:{fp_id:fpId, token:$('#fptoken').val()},
        success: function(data){
            switch(data){
                case('ok'):$.msgGrowl({type: 'success', title: 'تایید شد', text: 'این اثر انگشت به عنوان مورد مشکوک تایید شد'});location.reload();break;
                default: $.msgGrowl({type: 'warning', title: 'خطا', text: data});break;
            }
        }
    });
}
</script>
<section id="content" class="container_12 clearfix" data-sort=true>
	<h1 class="grid_12 margin-top no-margin-top-phone">موارد اثر انگشت مشکوک</h1>
    <div class="grid_12">
		<div class="box">
			<div class="header">
				<h2>
					<img class="icon" src="<?php echo(theme::url('img/icons/packs/fugue/16x16/balloons-box.png')); ?>">
					اثر انگشت مشکوک
				</h2>
			</div>
			<div class="content">
				<input type="hidden" id="fptoken" value="<?php echo(session('changepw')); ?>" />
				<table class="styled" id="daynmic_table">
					<thead>
						<tr>
							<th>ردیف</th>
							<th>کاربر</th>
							<th>IP</th>
							<th>مرورگر</th>
							<th>شباهت</th>
							<th>اثر انگشت مشابه</th>
							<th>اقدام انجام شده</th>
							<th>زمان</th>
							<th>مدیریت</th>
						</tr>
					</thead>
					<tbody>
                    <?php
                    $x = 0;
                    $alerts = $sql->orderBy('id', 'DESC')->get('admin_alerts', null, array('id','fingerprint_id','user_id','match_score','matched_fingerprint_id','action_taken','message','is_read','created_at'));
                    if (!is_array($alerts)) $alerts = array();
                    foreach($alerts as $row){
                        $x++;
                        $usr = $sql->where('id', $row['user_id'])->getOne('users', array('username','name'));
                        $username = $usr ? $usr['username'] : ('#' . $row['user_id']);
                        $fpRow = $row['fingerprint_id'] ? $sql->where('id', $row['fingerprint_id'])->getOne('fingerprints', array('ip','browser_hint','browser_signals')) : false;
                        $fpIp = $fpRow && !empty($fpRow['ip']) ? $fpRow['ip'] : '—';
                        $browserLabels = array(
                            'chrome_or_chromium'              => 'کروم/کرومیومی',
                            'brave'                            => 'Brave',
                            'tor_or_hardened_firefox_likely'   => 'Tor / فایرفاکسِ سخت‌شده (احتمالی)',
                            'spoofed_ua_not_chromium'          => 'UA جعلی (احتمالاً غیرکرومیومی)',
                        );
                        $rawHint = $fpRow ? $fpRow['browser_hint'] : '';
                        $isWebdriver = strpos($rawHint, '+webdriver') !== false;
                        $baseHint = str_replace('+webdriver', '', $rawHint);
                        $browserText = isset($browserLabels[$baseHint]) ? $browserLabels[$baseHint] : ($baseHint ? $baseHint : '—');
                        if ($isWebdriver) $browserText .= ' + ربات؟';
                        $browserWarn = in_array($baseHint, array('tor_or_hardened_firefox_likely', 'spoofed_ua_not_chromium')) || $isWebdriver;
                        $matchId = $row['matched_fingerprint_id'] ? $row['matched_fingerprint_id'] : '—';
                        $actionLabels = array(
                            'blocked'    => 'اخراج و مسدود',
                            'private'    => 'چت خصوصی فقط',
                            'admin_only' => 'فقط پیام به مدیر',
                            'none'       => 'فقط ثبت',
                        );
                        $actionText = isset($actionLabels[$row['action_taken']]) ? $actionLabels[$row['action_taken']] : $row['action_taken'];
                    ?>
						<tr id="fp-<?php echo($row['id']); ?>">
                            <td align="center"><?php echo($x); ?></td>
                            <td align="center"><?php echo(htmlspecialchars($username)); ?></td>
                            <td align="center" dir="ltr"><?php echo(htmlspecialchars($fpIp)); ?></td>
                            <td align="center" <?php if ($browserWarn) echo('style="color:#F00;font-weight:bold"'); ?> title="<?php echo(htmlspecialchars($fpRow ? $fpRow['browser_signals'] : '')); ?>"><?php echo(htmlspecialchars($browserText)); ?></td>
                            <td align="center"><?php echo($row['match_score']); ?> ٪</td>
                            <td align="center" dir="ltr"><?php echo($matchId); ?></td>
                            <td align="center"><?php echo($actionText); ?></td>
                            <td align="center"><?php echo(jdate::start('Y/m/d H:i:s', strtotime($row['created_at']))); ?></td>
                            <td align="center">
                                <a onclick="ConfirmAbuser('<?php echo($row['fingerprint_id']); ?>')" style="cursor:pointer; color:#F00">تایید به عنوان مشکوک</a>
                            </td>
                        </tr>
                        <?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>
<?php include('footer.php'); ?>