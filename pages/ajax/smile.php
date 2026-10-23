<?php
header::NoCache();
$el4 = giu('el4');
if ($el4 != 'copy') {
	if (!session('id')) {
		return;
	}
} else {
	if ($options['permitCPsmlies'] == 'false') {
		die('permit');
	}
}

if(session('id') == 12458)
{
 die('شما اجازه استفاده از شکلک را ندارید. بوس'); 
}

$el3 = giu('el3');
$array = array();
$id = session('id');
$smile_users = $sql->where('user', $id)->where('active', '1')->getValue('user_smile', 'count(*)');
$query = $sql->where('user', $id)->where('active', '1')->get('user_smile', null, array('id', 'smilecode', 'active'));
foreach ($query as $row) {
	$array_list_user[] = "<img src=\"" . theme::url('theme/images/smilies/' . $row['smilecode'] . '.gif') . "\" id=\"{$row['smilecode']}\" style=\"cursor:pointer;\" >";
}
$top_smilies = explode(',', $options['top_smilies']);
$query = $sql->get('smilies', null, array('id', 'code', 'img', 'name' , 'priority'));
$smilies_dir = $options['smilies_dir'];
$array_list_one = array();
$array_list_two = array();
foreach ($query as $row) {
	if ($el3 == 'classic') {
		if ($row['name'] != '') {
			$c = $row['name'];
		} else {
			$c = $row['code'];
		}
		if (in_array($row['id'], $top_smilies)) {
			$array_list_one[] = "<b> | </b><img src=\"" . theme::url('theme/images/smilies/' . $row['img']) . "\" id=\"{$c}\" style=\"cursor:pointer;\" >";
		} else {
			$array_list_two[] = "<b> | </b><img src=\"" . theme::url('theme/images/smilies/' . $row['img']) . "\" id=\"{$c}\" style=\"cursor:pointer;\" >";
		}
	} elseif ($el3 == 'json') {
		if ($row['name'] == null) {
			unset($row['name']);
		}
		if (in_array($row['id'], $top_smilies)) {
			$row['type'] = 'top';
		} else {
			$row['type'] = 'bottom';
		}
		$array[] = $row;
	} else {
		if (in_array($row['id'], $top_smilies)) {
			$array_list_one[] = "<b> | </b><img src=\"" . theme::url('theme/images/smilies/' . $row['img']) . "\" id=\"{$c}\" style=\"cursor:pointer;\" >";
        
		} else {
			$array_list_two[] = "<b> | </b><img src=\"" . theme::url('theme/images/smilies/' . $row['img']) . "\" id=\"{$c}\" style=\"cursor:pointer;\" >";
          
		}
	}
}
if ($el3 == 'json') {
	header::tojson();
	echo json_encode(array(
		'smilies' => $array
	), JSON_UNESCAPED_UNICODE);
} else {
	echo "\n\t\t<style>.stitle{background: none repeat scroll 0 0 rgba(6, 0, 255, 0.5);box-shadow: 0 0 3px 0 #CF5858;color: #fff;direction: rtl;margin: 5px 0 0;padding: 6px 10px;}</style>";
	 echo "<br><center><a href='/smilie' target='_blank' style='color:white;' class=\"stitle\">دیدن کد های ویژه</a></center>";
  if ($top_smilies[0]) {
		echo "<div class=\"stitle\">شکلک های پرکاربرد</div>";
		echo implode('', $array_list_one);
	}
	echo "\n\t\t<style>.stitle{background: none repeat scroll 0 0 rgba(6, 0, 255, 0.5);box-shadow: 0 0 3px 0 #CF5858;color: #fff;direction: rtl;margin: 5px 0 0;padding: 6px 10px;}</style>";
	if ($smile_users != 0) {
		echo "<div class=\"stitle\">شکلک های شخصی</div>";
		echo implode('', $array_list_user);
	}
	if ($smile_users != 0) {
		echo "<div class=\"stitle\">شکلک ها</div>";
	}
}
echo implode('', $array_list_two);
?>
