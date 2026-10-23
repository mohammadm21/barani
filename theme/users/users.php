<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

if(!session('id')){header::Go(url('login'));exit();}


$el2 = giu('el2');
$el = 'el2';
$cols = "`id`, `username`,`name`, `type`, `gender` , `score` ,`online`";
switch($el2){
    case('admins'):case('admin'):$admins = implode(',', Users::AdminsType()); $where = "`type` IN ($admins) ORDER BY `id`  ASC";$page = 1;break;
    case('rank'):header::Go(url('users'));break;
    case('ban'):case('bann'):case('banns'):$cols = "`id`, `username`,`name`, `type`, `gender` ,`score`, `online`,`ip`";$where = "`active`='0'  ORDER BY `lastview`  DESC";$page = 1;break;
    default:
    $params = array();
    $el2 = explode(',', $el2);
    foreach($el2 as $el){
        $el = explode(':', $el);
        if(!isset($el[1]))$el[1] = '';
        $params[$el[0]] = $el[1];
    }

    if(!isset($params['page'])){
        $page = 1;
    }else{
        $page = max(1, (int)safe::number($params['page']));
    }
    
    if(!isset($params['type']))$type = 0;else $type = safe::number($params['type']);
    if($type == '0'){
		if($options['userranksort'] == 'name')
			$where = "`type`!='1' and  `score`!='0' ORDER BY `name` ASC";
		elseif($options['userranksort'] == 'like')
			$where = "`type`!='1' and  `score`!='0' ORDER BY `totallike` DESC";
		else 
			$where = "`type`!='1' and  `score`!='0' ORDER BY `score` DESC";
    }else{
		if($options['userranksort'] == 'name')
			$where = "`type`='$type' and `type`!='1' and `score`!='0' ORDER BY `name` ASC";
		elseif($options['userranksort'] == 'like')
			$where = "`type`='$type' and `type`!='1' and `score`!='0' ORDER BY `totallike` DESC";
		else 
			$where = "`type`='$type' and `type`!='1' and `score`!='0' ORDER BY `score` DESC";
    }
    break;
}

// اصلاح: متغیر msgshostory قبل از استفاده مقداردهی نشده بود
$msgshostory = isset($options['msgshostory']) ? max(1, (int)$options['msgshostory']) : 100;

if($page == 1){ $startpage = 0; }else{ $startpage = ($page-1)*$msgshostory;}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo(site_title); ?> | لیست کاربران</title>
<link rel="stylesheet" type="text/css" href="<?php echo(theme::url("css/fonts.css", $options['chat_users_theme'])); ?>"/>
<link rel="stylesheet" href="<?php echo(theme::url("css/users.css", $options['chat_users_theme'])); ?>?ver=3.1.4" type="text/css" media="all">
<script type="text/javascript" src="<?php echo(theme::url("js/jquery.js", $options['chat_users_theme'])); ?>"></script>
<script type="text/javascript" src="<?php echo(theme::url("js/users.js", $options['chat_users_theme'])); ?>"></script>
<script type="text/javascript" src="<?php echo(url('visit')); ?>"></script>
<script type="text/javascript">
function search(){
    var username = $('#username').val();
    if(username == '' || username == ' '){return;}
    $.ajax({
        type: "GET",
        url: '<?php echo(url('ajax/users/search')); ?>',
        data:{username:username},
        success: function(data) {
            $('#result').html('');
            data = $('user',data);
            for(x=0;x!=data.length;x++){
                addrow(data[x]);
            }
        },
        dataType:'xml'
    });
    return false;
}
function addrow(data){
    $('#result').append($(data).text());
}
function changepage(elem){

    var url = window.location.href;

    if(/page:\d+/i.test(url)){

        url = url.replace(/page:\d+/i, 'page:' + elem.value);

    }else{

        if(/users\//i.test(url)){

            if(url.slice(-1) == '/'){
                url = url + 'page:' + elem.value;
            }else{
                url = url + ',page:' + elem.value;
            }

        }else{

            url = url + '/page:' + elem.value;
        }
    }

    window.location.href = url;
}
</script>
  
 <script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());

gtag('config', 'G-MPHBME5Y64');
</script>
  
</head>
<body>
<?php if(chatroom::checkPermission('users')) { ?>
	<header>
	<?php $options['thememanager'] = json_decode($options['thememanager'], true);
	if(isset($options['thememanager']['users']['header']['src']) && $options['thememanager']['users']['header']['src'] != ""){
		?>
 <center> <img src="<?php echo(url()); ?><?php echo(theme::url($options['thememanager']['users']['header']['src']));?>" ></center>	
 <?php } ?>
    	<nav>
        	<span class="search rad3"><span><input type="text" placeholder="نام کاربری ..." id="username"><input type="button" onClick="search();" class="rad-l3" value="جستجو"></span></span>
            
            <span class="pages">برو به صفحه :<span><select onchange="changepage(this);"><?php
             $num = db::num('users', $where);

                $totalpages = ceil($num / $msgshostory);

                if($totalpages < 1){
                    $totalpages = 1;
                }

                if($page > $totalpages){
                    $page = $totalpages;
                }

                $startpage = ($page - 1) * $msgshostory;

                for($x = 1; $x <= $totalpages; $x++){

                    $selected = ($x == $page)
                        ? ' selected="selected"'
                        : '';

                    echo '<option'.$selected.'>'.$x.'</option>';
                }
                ?></select>
            </span>
            </span>
        </nav>

        	<div class="order">
        	 <span class="showing">
            <a href="<?php echo(url('users/')); ?>"><img src="/theme/users/images/Darje.png"/></a> 
         <?php
            foreach($usertype as $type){
            if($type['id']!=1) { ?> 
            <a href="<?php echo(url('users/type:'.$type['id'])); ?>" title="<?php echo($type['title']); ?>"><b> | </b><img src="<?php echo(url($type['icon'])); ?>"/></a>
            <?php  } 
            }
            ?> 
            </span>
        </div>
    </header>
    <div class="content">
    	<div class="border rad3">
    	<table class="usershowing">
		<thead>
                    <tr>
                        <td>ردیف<i class="icon-list-ul"></i></td>
                        <td>نام نمایشی<i class="icon-smile"></i></td>
                        <td>درجه کاربر<i class="icon-star"></i></td>
						<td>جنسیت<i class="icon-certificate"></i></td>
                        <td>امتیاز کاربر<i class="icon-trophy"></i></td>
					    <td>پروفایل<i class="icon-certificate"></i></td>
                     
                        <?php if($el2=='ban'){?>
                        <td align="center">آی پی</td>
                        <?php }  ?>
                    </tr>
					</thead>
                    <tbody id="result">
                        <?php
                        $x = $startpage + 1; // بهتر شد +1
                        $query = db::query("SELECT $cols FROM {prefix}users WHERE $where LIMIT $startpage,$msgshostory");
                        while($row = mysqli_fetch_array($query)){
                        switch($row['gender']){
                            case '1':$gender = 'm';break;
                            case '2':$gender = 'w';break;
                            case '0':$gender = 'n';break;
                             }
                           $profile = db::get('profiles', '*', "`id`='".$row['id']."'");
						switch($row['online']){
                             case('0'):$online = 'offline_pro';break;
                             case('1'):$online = 'online';break;
                             }
                        ?>
                        <tr>
                            <td align="center"><?php echo($x); ?></td>
                            <td align="center"><?php echo($row['name']); ?></td>
                            <td align="center"><a href="<?php echo(url('users/type:'.$usertype[$row['type']]['id'])); ?>" title="کاربران <?php echo($usertype[$row['type']]['title']); ?>"><img src="<?php echo(theme::url($usertype[$row['type']]['icon'])); ?>" alt="<?php echo($usertype[$row['type']]['title']); ?>"></a></td>
							<td align="center"><img src="<?php echo(url('/theme/images/chat/chat.user_comment_' . $gender . '.png')); ?>"></td>
                            <td align="center"><?php echo($row['score']); ?></td>
							<td align="center"><a href="<?php echo(url("profile/".$row['id'])); ?>" title="پروفایل <?php echo($row['name']); ?>"><img class="avatar" src="<?php echo(url($options['avatar_dir'].'/'.str_replace('*', '-', $options['thumbavatarSize']).'/'.$profile['avatar'])); ?>" title="پروفایل <?php echo($row['name']); ?>" alt="پروفایل"></a></td>

                            <?php if($el2=='ban'){?>
                            <td align="center"><?php echo($row['ip']); ?></td>
                            <?php }  ?>
                        </tr>
                        <?php
                            $x++;
                        }
                        mysqli_free_result($query);
                        ?>
                    </tbody>
                </table>
        </div><br /><br /><br />
    </div>
        <?php }else{ ?>
            <h2 style="text-align: center;text-shadow: 0 0 10px #333;font-family: Tahoma;direction: rtl;color: #004040;padding-top:20px">این قابلیت برای گروه کاربری شما غیرفعال شده است!</h2>
            <?php } ?>
</body>
</html>