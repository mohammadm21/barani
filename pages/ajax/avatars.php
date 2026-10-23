<?php
header::NoCache();
if (!session('id')) {
    exit();
}
$type        = session('type');
if ($type!=1) {
    if (safe::string($_POST['mode']) == 'change' and safe::string($_POST['image'])) {
        $avatar = safe::string($_POST['image']);
		$sql->where('id', session('id'))->update('profiles', array(
	          'avatar' => $avatar
	          ));
        if ($sql->where('id', session('id'))->has('onlines')) {
            $input           = Users::Inputs(session('id'));
            $input['avatar'] = $avatar;
            Users::Inputs(session('id'), $input);
        }
        exit;
    } else {
        $query = $sql->get('avatars');
        foreach ($query as $row) {
            $i = 0;
?>
       
            <img style="width:30px;height:30px;cursor:pointer;border:5px #eee solid;border-radius:100px;" id="<?php
            echo $row['image'];
?>"  title="<?php
            echo $row['name'];
?>" src="<?php
            echo url('theme/images/avatars/' . $row['image']);
?>" > 

        
        <?php
        }
    }
} else
    echo 'این گزینه برای گروه کاربری شما غیر فعال شده است.';
?>