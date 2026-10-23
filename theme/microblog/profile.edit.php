<?php
$check_perm_user = Users::Inputs();
$check_perm_user = in_array('profile', $check_perm_user['permission']) ? true : false;
if (!chatroom::checkPermission('profile') && !$check_perm_user) {
	?>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<div id="main"><h2 style="text-align: center;text-shadow: 0 0 10px red;padding-top:20px;">این قابلیت برای گروه کاربری شما غیرفعال شده است!</h2></div>
	<?php
	exit;
}
$user_id = session('id');
$profile = $sql->where('u.`id`', $user_id)->join('profiles as p', 'p.`id` = u.`id`', 'LEFT')->getOne('users as u', array('u.*', 'p.*'));
switch ($profile['gender']) {
	case '1':
		$gender = "مذکر";
		break;
	case '2':
		$gender = "مونث";
		break;
}
//------------------------effect----------------
switch ($profile['effect']) {
	case '1':
		$effect = "فعال";
		break;
	case '0':
		$effect = "غیرفعال";
		break;
}
//--------------------------


$user['registerDate'] = jdate::start("l d F Y", $profile['registerdate']);
$user['lastAcivity'] = jdate::start("l d F Y", $profile['lastview']);
$user['lastAcivityTime'] = date("H:i", $profile['lastview']);
$user['birthDate'] = explode('|', $profile['birthdate']);
if ($user['birthDate'][0] == '0') {
	$birthDate = "وارد نشده";
} else {
	switch ($user['birthDate'][1]) {
		case '1':
			$birthday['type']['day'] = 'checked="checked"';
			$birthday['type']['month'] = 'checked="checked"';
			$birthday['type']['year'] = 'checked="checked"';
			break;
		case '2':
			$birthday['type']['day'] = 'checked="checked"';
			$birthday['type']['month'] = '';
			$birthday['type']['year'] = '';
			break;
		case '3':
			$birthday['type']['day'] = '';
			$birthday['type']['month'] = 'checked="checked"';
			$birthday['type']['year'] = '';
			break;
		case '4':
			$birthday['type']['day'] = '';
			$birthday['type']['month'] = '';
			$birthday['type']['year'] = 'checked="checked"';
			break;
		case '5':
			$birthday['type']['day'] = 'checked="checked"';
			$birthday['type']['month'] = 'checked="checked"';
			$birthday['type']['year'] = '';
			break;
		case '6':
			$birthday['type']['day'] = 'checked="checked"';
			$birthday['type']['month'] = '';
			$birthday['type']['year'] = 'checked="checked"';
			break;
		case '7':
			$birthday['type']['day'] = '';
			$birthday['type']['month'] = 'checked="checked"';
			$birthday['type']['year'] = 'checked="checked"';
			break;
	}
}
switch ($profile['online']) {
	case '0':
		$online = 'offline';
		break;
	case '1':
		$online = 'online';
		break;
}
switch ($profile['type']) {
	case '1':
		$type = 'guest';
		break;
	case '2':
		$type = 'register';
		break;
	case '3':
		$type = 'special';
		break;
	case '4':
		$type = 'active';
		break;
	case '5':
		$type = 'senior';
		break;
	case '6':
		$type = 'supervisor';
		break;
	case '7':
		$type = 'mod';
		break;
	case '8':
		$type = 'admin';
		break;
}
$mysettings = json_decode($profile['settings'], true);
$showprofile_select_all = $mysettings['showprofile'] == 'all' ? 'selected' : '';
$showprofile_select_f = $mysettings['showprofile'] == 'friends' ? 'selected' : '';
$friend_select_all = $mysettings['friends'] == 'all' ? 'selected' : '';
$friend_select_f = $mysettings['friends'] == 'friends' ? 'selected' : '';
$friend_select_n = $mysettings['friends'] == 'nothing' ? 'selected' : '';
$lastview_select_all = $mysettings['lastview'] == 'all' ? 'selected' : '';
$lastview_select_f = $mysettings['lastview'] == 'friends' ? 'selected' : '';
$lastview_select_n = $mysettings['lastview'] == 'nothing' ? 'selected' : '';
$follower_select_all = $mysettings['follower'] == 'all' ? 'selected' : '';
$follower_select_f = $mysettings['follower'] == 'friends' ? 'selected' : '';
$follower_select_n = $mysettings['follower'] == 'nothing' ? 'selected' : '';
$info_select_all = $mysettings['info'] == 'all' ? 'selected' : '';
$info_select_f = $mysettings['info'] == 'friends' ? 'selected' : '';
$info_select_n = $mysettings['info'] == 'nothing' ? 'selected' : '';
$security_question_groups = json_decode($options['useroptions'], true);
$check_security_question = false;
if ($options['security_question'] == 'true' && in_array(session('type'), $security_question_groups['security_question'])) {

	$check_security_question = true;
}
$user_security = json_decode($profile['question'], true);
$myavatar = $profile['avatar'] != 'photo.png' && $profile['avatar'] != null && $profile['avatar'] != '' && is_file('theme/images/avatars/' . $profile['avatar']) && is_file('theme/images/avatars/' . MiniAvatarDir() . '/' . $profile['avatar']) ? $profile['avatar'] : ($profile['gender'] == '1' ? 'no_avatar_male.png' : 'no_avatar_female.jpg');
$birthday['type']['org'] = explode('|', $profile['birthdate']);
switch ($birthday['type']['org'][1]) {
	case '1':
		$birthday['type']['day'] = 'checked="checked"';
		$birthday['type']['month'] = 'checked="checked"';
		$birthday['type']['year'] = 'checked="checked"';
		break;
	case '2':
		$birthday['type']['day'] = 'checked="checked"';
		$birthday['type']['month'] = '';
		$birthday['type']['year'] = '';
		break;
	case '3':
		$birthday['type']['day'] = '';
		$birthday['type']['month'] = 'checked="checked"';
		$birthday['type']['year'] = '';
		break;
	case '4':
		$birthday['type']['day'] = '';
		$birthday['type']['month'] = '';
		$birthday['type']['year'] = 'checked="checked"';
		break;
	case '5':
		$birthday['type']['day'] = 'checked="checked"';
		$birthday['type']['month'] = 'checked="checked"';
		$birthday['type']['year'] = '';
		break;
	case '6':
		$birthday['type']['day'] = 'checked="checked"';
		$birthday['type']['month'] = '';
		$birthday['type']['year'] = 'checked="checked"';
		break;
	case '7':
		$birthday['type']['day'] = '';
		$birthday['type']['month'] = 'checked="checked"';
		$birthday['type']['year'] = 'checked="checked"';
		break;
}
include 'inc/classes/wolde.php';
$mymusic = json_decode($profile['music'], true);
?>
<!DOCTYPE html>
<html>
	<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>پروفایل <?php echo $profile['name'];?></title>
		<link href="<?php echo theme::url('theme/microblog/css/bootstrap.min.css');?>" rel="stylesheet">
		<link rel="stylesheet" href="<?php echo theme::url('theme/microblog/css/bootstrap-rtl.min.css');?>">
		<link href="<?php echo url('theme/microblog/css/profile-edit.css');?>" rel="stylesheet" type="text/css" />
		<link href='<?php echo url('theme/fonts/fonts.css');?>' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="<?php echo url('theme/microblog/css/jquery-ui.css');?>">
		<link href="<?php echo url('theme/microblog/css/microblog.css');?>" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="<?php echo url("theme/microblog/js/jquery.js");?>"></script>
		<script type="text/javascript" src="<?php echo url("theme/microblog/js/ajaxfileupload.js");?>"></script>
		<script type="text/javascript" src="<?php echo url("theme/microblog/js/jquery.form.min.js");?>"></script>
		<script type="text/javascript">
function SmileArea(id){
	ShowW8('smilearea-loader-' + id);
	if($('#smilearea-' + id).css('display') == 'none'){
		$.ajax({
			  type: "GET",
			  url: "/ajax/chat.profile.loadsmiley",
			  success: function (e) {
				  $('#smilearea-loader-' + id).html('');
				  $('#smilearea-' + id).html(e.replace(/\[id\]/g, id));
				  $('#smilearea-' + id).css('display', 'block'); 
			  }
		 });
	}else{
		$('#smilearea-loader-' + id).html('');
		$('#smilearea-' + id).css('display', 'none');
	}
}
function SmileInner(id,element){
	var rel = $(element).attr('rel');
	var shout = $('#shout').val();
	$('#shout').val(shout + rel).focus();
}
function ShowW8(id){
	$("#" + id).html('<center><img src="<?=theme::url('theme/microblog/images/ajax-loader.gif?ver=1');?>"></center>');
}
function PopUp(re){
    $(document).ready(function(){
        if(re == 'show'){
            $('#thickBlack').fadeIn(1000, function(){
                $('#box').show(1000);
            });
        }
        if(re == 'hide'){
            $('#box').hide(1000, function(){
                $('#thickBlack').fadeOut(1000);
            });
        }
    });
}
function ajaxFileUpload(){
    $("#loading").ajaxStart(function(){
    	$(this).show();
    }).ajaxComplete(function(){
    	$(this).hide();
    });

    $.ajaxFileUpload({
    	url:'<?php echo(url('ajax/updateavatar')); ?>',
    	secureuri:false,
    	fileElementId:'fileToUpload',
        dataType: 'txt',
    	success: function (data){
    	   data = data.split('|');
    	   switch(data[0]){
    	       case('ok'):$('#avatarimg').attr('src', data[1]+'?');PopUp('hide');break;
               case('security'):alert('سرور در فایل شما مورد امنیتی پیدا کرد.');break;
               case('type'):alert('نوع فایل شما توسط سرور تائید نشد');break;
               default:alert(data[0]);break;
    	   }
    	}
    });
	return false;
}
function ajaxCoverUpload(){
    $("#loading").ajaxStart(function(){
    	$(this).show();
    }).ajaxComplete(function(){
    	$(this).hide();
    });

   $.ajaxFileUpload({
    	url:'<?php echo(url('ajax/chat.profile.uploadcover')); ?>',
                    secureuri: false,
                    fileElementId: 'CoverName',
                    dataType: 'txt',
                    success: function (data) {
                    data = data.split('|');
                    switch (data[0]) {
                    case('ok'):
                            $('#coverimg').attr('src', data[1] + '?');
                    PopUpAjaxCover('hide');
                    break;
                    case('security'):
                            alert('سرور در فایل شما مورد امنیتی پیدا کرد.');
                    break;
                    case('type'):
                            alert('نوع فایل شما توسط سرور تائید نشد');
                    break;
                    default:
                            alert(data[0]);
                    break;
                    }
                    }
    });
	return false;
}
$(document).ready(function(){

		var progressbox     = $('#progressbox');
		var progressbar     = $('#progressbar');
		var statustxt       = $('#statustxt');
		var completed       = '0%';

		var options = {
				target:   '#output',   // target element(s) to be updated with server response
				beforeSubmit:  beforeSubmit,  // pre-submit callback
				uploadProgress: OnProgress,
				success:       afterSuccess,  // post-submit callback
				resetForm: true        // reset the form after successful submit
			};

		 $('#MyUploadForm').submit(function() {
				$(this).ajaxSubmit(options);
				// return false to prevent standard browser submit and page navigation
				return false;
			});

	//when upload progresses
	function OnProgress(event, position, total, percentComplete)
	{
		//Progress bar
		progressbar.width(percentComplete + '%') //update progressbar percent complete
		statustxt.html(percentComplete + '%'); //update status text
		if(percentComplete>50)
			{
				statustxt.css('color','#fff'); //change status text to white after 50%
			}
	}

	//after succesful upload
	function afterSuccess()
	{
		$('#submit-btn').show(); //hide submit button
		$('#loading-img').hide(); //hide submit button

	}

	//function to check file size before uploading.
	function beforeSubmit(){
		//check whether browser fully supports all File API
	   if (window.File && window.FileReader && window.FileList && window.Blob)
		{

			if( !$('#imageInput').val()) //check empty input filed
			{
				$("#output").html("لطفا یک فایل انتخاب کنید.");
				return false
			}

			var fsize = $('#imageInput')[0].files[0].size; //get file size

			if(fsize>22271456)
			{
				$("#output").html("<b>"+bytesToSize(fsize) +" </b><font color='red'>حجم فایل شما خیلی زیاد است شما اجازه آپلود این فایل را ندارید");
				return false
			}

			//Progress bar
			progressbox.show(); //show progressbar
			progressbar.width(completed); //initial value 0% of progressbar
			statustxt.html(completed); //set status text
			statustxt.css('color','#000'); //initial color of status text


			$('#submit-btn').hide(); //hide submit button
			$('#loading-img').show(); //hide submit button
			$("#output").html("");
		}
		else
		{
			$("#output").html("<font color='red'>مروگر شما قدیمی است واز این سیستم پشتیبانی نمیکند ! لطفا مرورگر خود را آپدیت کنید ویا عوض کنید !");
			return false;
		}
	}


	function bytesToSize(bytes) {
	   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
	   if (bytes == 0) return '0 Bytes';
	   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
	   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
	}
	
    $('#avatar img').click(function(){
        PopUp('show');
    });
    $('#fileToUpload').change(function(){
        var file = this.files[0];
        size = file.size;
        type = file.type;
        if(size > 1048576){alert('این فایل بیش از حد سنگین است.لطفا فایل دیگری را انتخاب بکنید.');this.value = '';}
        if(type != 'image/png' && type != 'image/jpeg' && type != 'image/gif'){alert('این فایل مجاز نیست.لطفا عکس دیگری را انتخاب کنید.');this.value = '';}
    });
	
	
///////////////////////////////////////////////////////////
/////// Auto Complete for search users and profiles ///////
///////////////////////////////////////////////////////////
	
	$( "#autocomplete" ).autocomplete({
		source: "/ajax/chat.profile.search",
		minLength: 2,
		select: function( event, ui ) {
			if(ui.item)location.href = '/profile/'+ui.item.id;
		},
		search: function () {
			$("#loading-user").show();
			$(".track3").parent().remove();
			$(".ui-autocomplete").css("padding-right","0");
		},
		response: function () {
			$("#loading-user").hide();
			$('.ui-autocomplete').enscroll({
				showOnHover: false,
				verticalTrackClass: 'track3',
				verticalHandleClass: 'handle3',
				zIndex: 1050
			});
		}
	});

/////// End Auto Complete ///////
	
	
});

function save(){
    var name = $('#name').val();
    if(name == ''){name = '<?php echo($profile['username']); ?>';$('#name').val(name);}
    var berthday = chberthDay2();
    if(berthday == false){
        return false;
    }
    var day = document.getElementById('tt.day').value;
    var month = document.getElementById('tt.month').value;
    var year = document.getElementById('tt.year').value;
    berthday = 13+year+'/'+month + "/"+day;
    var YearType = document.getElementById('tt.type.year').checked;
    var MonthType = document.getElementById('tt.type.month').checked;
    var DayType = document.getElementById('tt.type.day').checked;
    var typeshowberthday;
    if(YearType && MonthType && DayType)typeshowberthday='1';
    if(DayType && !MonthType && !YearType)typeshowberthday='2';
    if(MonthType && !DayType && !YearType)typeshowberthday='3';
    if(YearType && !DayType && !MonthType)typeshowberthday='4';
    if(!YearType && MonthType && DayType)typeshowberthday='5';
    if(YearType && !MonthType && DayType)typeshowberthday='6';
    if(YearType && MonthType && !DayType)typeshowberthday='7';
    var gender = document.getElementById('gender').value;
    var country = document.getElementById('country').value;
    var city = document.getElementById('city').value;
    var info = $('#infos').val();
    var site = document.getElementById('site').value;
    var email = document.getElementById('email').value;
    var music_title = document.getElementById('music_title').value;
    var music = document.getElementById('music').value;
    var music_info = document.getElementById('music_info').value;
    var list_friends = document.getElementById('myfriends').value;
    var list_lastview = document.getElementById('mylastview').value;
	var effect = document.getElementById('effect').value;
    var list_follower = document.getElementById('myfollower').value;
    var info_config = document.getElementById('info_config').value;
    var shout = document.getElementById('shout').value;
    var ldconfig = document.getElementById('ldconfig').checked ? '1' : '0';
    var notification = document.getElementById('notification').checked ? '1' : '0';
    var sfriendp = document.getElementById('sfriendp').checked ? '1' : '0';
    var sfollowp = document.getElementById('sfollowp').checked ? '1' : '0';
	var music_config = document.getElementById('music_config').checked ? '1' : '0';
    $.ajax({
      type: "POST",
      url: "<?php echo(url('ajax/chat.profile.editprofile')); ?>",
      data: {
			 'settings[music_config]': music_config,
			 'settings[sfriendp]': sfriendp,
			 'settings[sfollowp]': sfollowp,
			 'settings[notification]': notification,
			 'settings[ldconfig]': ldconfig,
			 'settings[friends]': list_friends, 
			 'settings[lastview]': list_lastview, 
			 'settings[follower]': list_follower,
			 'settings[info]': info_config,
			 'name': name,
			 'berthday': berthday,
			 'typeberthday': typeshowberthday,
			 'gender': gender,
			 'country': country,
			 'city': city,
			 'effect': effect,			 
			 'info': info,
			 'site': site, 
			 'email': email,
			 'music_info': music_info,
			 'music_title': music_title, 
			 'music': music,
			 'shout': shout
		   },
      success: function(data){
        switch(data){
            case('ok'):alert('با موفقیت انجام شد');top.location.href = '/profile/<?=$profile['id'];?>';break;
			case('name'):alert('این نام مطعلق به کاربر دیگری است!');break;
            default:alert(data);break;
        }
      }
    });
}
function chberthDay(){
    
    var YearType = document.getElementById('tt.type.year').checked;
    var MonthType = document.getElementById('tt.type.month').checked;
    var DayType = document.getElementById('tt.type.day').checked;
    if(YearType == false && MonthType == false && DayType == false){
        document.getElementById('tt.day').disabled = true;
        document.getElementById('tt.month').disabled = true;
        document.getElementById('tt.year').disabled = true;
        
        document.getElementById('tt.day').value = '';
        document.getElementById('tt.month').value = '';
        document.getElementById('tt.year').value = '';
    }else{
        document.getElementById('tt.day').disabled = false;
        document.getElementById('tt.month').disabled = false;
        document.getElementById('tt.year').disabled = false;
    }
}
function chberthDay2(){
    var strFilter = /^[-+]?\d*\.?\d*$/;
    var day = document.getElementById('tt.day').value;
    var month = document.getElementById('tt.month').value;
    var year = document.getElementById('tt.year').value;
    
    var YearType = document.getElementById('tt.type.year').checked;
    var MonthType = document.getElementById('tt.type.month').checked;
    var DayType = document.getElementById('tt.type.day').checked;
    if(!strFilter.test(day)){alert("در قسمت روز تاریخ تولد کارکتر غیر عددی وارد شده");return false;}
    if(!strFilter.test(month)){alert("در قسمت ماه تاریخ تولد کارکتر غیر عددی وارد شده");return false;}
    if(!strFilter.test(year)){alert("در قسمت سال تاریخ تولد کارکتر غیر عددی وارد شده");return false;}
    if(day.length == 1){document.getElementById('tt.day').value =  '0' + day;}
    if(month.length == 1){document.getElementById('tt.month').value =  '0' + month;}
    if(year.length == 1){document.getElementById('tt.year').value =  '0' + year;}
    if(DayType == true){
        if(day == '' || month == '' || year == ''){alert("شما انتخاب کردید تاریخ تولد شما نمایش داده شود ولی تاریخ را بطور کامل وارد نکردید");return false;}
    }
    if(MonthType == true){
        if(month == '' || year == ''){alert("شما انتخاب کردید تاریخ تولد شما نمایش داده شود ولی تاریخ را بطور کامل وارد نکردید");return false;}
    }
    if(YearType == true){
        if(year == ''){alert("شما انتخاب کردید تاریخ تولد شما نمایش داده شود ولی تاریخ را بطور کامل وارد نکردید");return false;}
    }
    
    return true;
}
function PopUpAjax(e) {
	$(document)
		.ready(function () {
			if (e == "show") {
				$("#thickBlack")
					.fadeIn(1e3, function () {
						$("#upload-wrapper")
							.show(1e3)
					})
					document.getElementById('music_title').value = '';
					document.getElementById('music_info').value = '';
					document.getElementById('music').value = '';
					document.getElementById('music_title').disabled = true;
					document.getElementById('music_info').disabled = true;
					document.getElementById('music').disabled = true;
			}
			if (e == "hide") {
				$("#upload-wrapper")
					.hide(1e3, function () {
						$("#thickBlack")
							.fadeOut(1e3);

					})
			}
		})
}
function PopUpAjaxCover(e) {
	$(document).ready(function () {
			if (e == "show") {
				$("#thickBlack").fadeIn(1e3, function () {
						$("#upload-cover").show(1e3)
					})
			}
			if (e == "hide") {
				$("#upload-cover")
					.hide(1e3, function () {
						$("#thickBlack")
							.fadeOut(1e3);

					})
			}
		})
}
</script>
<style>
#cover {
    background: #f5f5f5 none repeat scroll 0 0;
    border: 10px solid #ffffff;
    height: 200px;
    margin: 10px 0 0;
    position: relative;
	box-shadow: 0 3px 0 0 EB5F5F;
}
#cover img {
    cursor: pointer;
    display: block;
    height: 100%;
    width: 100%;
}
</style>
	</head>
	<body>
		<nav role="navigation" class="navbar navbar-default navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
					<button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
						<span class="sr-only">منو</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" target="_blank" href="<?php echo "http://".$_SERVER['HTTP_HOST']; ?>"><b style="color:yellow;">بارانی چت</b></a>
				</div>
				<div id="navbarCollapse" class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<li class="active"><a href="/profile/<?php echo $MySessionId;?>">پروفایل من</a></li>
						<li><a href="/profile/edit">ویرایش پروفایل</a></li>
					</ul>
					<form role="search" class="navbar-form navbar-right">
						<div class="form-group">
							<input type="text" id="autocomplete" placeholder="جستجوی کاربر" class="form-control">
							<span id="loading-user"><img src="<?php echo url('theme/microblog/images/ajax-loader.gif?ver=1');?>"></span>
						</div>
					</form>
					<ul class="nav navbar-nav">
						<li><a target="_blank" href="/rank/likes">برترین کاربران پروفایل</a></li>
					</ul>
				</div>
			</div>
		</nav>
		<div id="thickBlack"></div>
		<div id="box">
			<a onclick="PopUp('hide');" href="javascript: PopUp('hide')" id="close"><br><span aria-hidden="true">&times;</a></span>
			<div id="cont">

				<img id="loading" src="<?php echo url('theme/microblog/images/ajax-loader.gif?ver=1');?>" style="display:none;">
				<form name="form" action="" method="POST" enctype="multipart/form-data">
					<p><label>انتخاب فایل: </label><input id="fileToUpload" type="file" size="45" name="fileToUpload" class="input"></p>
					<center><input type="button" value="آپلود" onclick="return ajaxFileUpload();"></center>
				</form>
				<br><br>
			</div>
		</div>
		<div id="upload-cover">
			<a onclick="PopUpAjaxCover('hide');"  href="javascript: PopUpAjaxCover('hide')" id="close"><br><span aria-hidden="true">&times;</a></span>
			<div id="upcover">
				<img id="loading" src="<?php echo url('theme/microblog/images/ajax-loader.gif?ver=1');?>" style="display:none;">
				<form name="form" action="" method="POST" enctype="multipart/form-data">
					<p><label>انتخاب فایل: </label><input id="CoverName" type="file" size="45" name="CoverName" class="input"></p>
					<center><input type="button" value="آپلود" onclick="return ajaxCoverUpload();"></center>
				</form>
				<br><br>
			</div>
		</div>

		<div id="upload-wrapper">
				<a onclick="PopUpAjax('hide');"  href="javascript: PopUpAjax('hide')" id="close"><br><span aria-hidden="true">&times;</a></span>
				<div id="upmusic" align="center">
					<h3>آپلود آهنگ</h3>
					<span class=""><?php echo RedError('فرمت آهنگ شما باید mp3 باشد');?></span>
					<form action="<?php echo url('ajax/chat.profile.upmusic');?>" onSubmit="return false" method="post" enctype="multipart/form-data" id="MyUploadForm">
						<input name="myfile" id="imageInput" type="file" />
						<input type="submit"  id="submit-btn" value="آپلود" />
						<img src="<?php echo theme::url('theme/microblog/images/ajax-loader.gif');?>" id="loading-img" style="display:none;" alt="لطفا صبر کنید"/>
					</form>
					<div id="progressbox" style="display:none;"><div id="progressbar"></div ><div id="statustxt">0%</div></div>
					<div id="output"></div>
				</div>
			</div>

		<div class="container hcon">
			<div class="row">
				<div class="col-md-12">
					<div onclick="PopUpAjaxCover('show');" id="cover"><img src="<?php echo is_file('theme/images/covers/' . $profile['cover']) ? '/theme/images/covers/' . $profile['cover'] : '/theme/microblog/images/cover.jpg';?>" id="coverimg"  alt="<?php echo $profile['name'];?>"></div>
				</div>
			</div>
			<div class="row main">
				<div class="col-md-4">
					<div class="panel panel-details">
						<div class="panel-heading panel-title-gray"><i class="fa fa-user"></i> <?php echo $profile['name'];?></div>
						<div class="panel-body bs-callout bs-callout-gray">
							<?php
if (chatroom::checkPermission('changenamepr')) {
	?>
								<div class="form-group"><input class="form-control" type="text" id="name" value="<?php echo $profile['name'];?>" maxlength="15">
								</div>
							<?php
}
?>
							<div id="avatar"><img src="<?php echo url('theme/images/avatars/' . $myavatar);?>" id="avatarimg"  alt="<?php echo $profile['name'];?>"></div>

							<div class="form-group">
								<label class="control-label">تاریخ عضویت:</label>
								<?php echo $user['registerDate'];?>
							</div>
							<div class="form-group">
								<label class="control-label">آخرین فعالیت در:</label>
								<?php echo $user['lastAcivity'];?> ساعت : <?php echo $user['lastAcivityTime'];?>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-8">
					<div>
						<div>
							<div>
								<div class="panel panel-details">
									<div class="panel-heading panel-title-blue"><i class="fa fa-birthday-cake"></i> تولد کاربر</div>
									<div class="panel-body bs-callout bs-callout-blue">
										<p class="form-inline">
											<label class="form-label">تاریخ تولد:</label>
											<span class="input-group has-info"><span class="input-group-addon">روز:</span><input class="form-control" type="text" value="<?php echo jdate::start('d', $birthday['type']['org'][0]);?>" style="width: 45px; text-align:center" id="tt.day" name="tt.day" maxlength="2"></span>
											<span class="input-group has-info"><span class="input-group-addon">ماه:</span><input class="form-control" type="text"  value="<?php echo jdate::start('m', $birthday['type']['org'][0]);?>" class="tt" style="width: 45px; text-align:center" id="tt.month" name="tt.month"  maxlength="2"></span>
											<span class="input-group has-info"><span class="input-group-addon">سال:</span><input class="form-control" type="text"  value="<?php echo substr(jdate::start('Y', $birthday['type']['org'][0]), 2);?>" class="tt" style="width: 45px; text-align:center"  name="tt.year" id="tt.year" maxlength="2"><span class="input-group-addon">13</span></span>
										</p>
										<p class="form-inline">
											<label class="form-label">نحوه نمایش:</label>
											<label>سال: <input <?php echo $birthday['type']['year'];?> onclick="chberthDay();" type="checkbox" name="tt.type[]" id="tt.type.year" value="year"></label>
											<label>ماه: <input <?php echo $birthday['type']['month'];?> onclick="chberthDay();" type="checkbox" name="tt.type[]" id="tt.type.month" value="month"></label>
											<label>روز: <input <?php echo $birthday['type']['day'];?> onclick="chberthDay();" type="checkbox" name="tt.type[]" id="tt.type.day" value="day"></label>
										</p>
									</div>
								</div>
								<div class="panel panel-details">
									<div class="panel-heading panel-title-red"><i class="fa fa-globe"></i> مشخصات کاربر</div>
									<div class="panel-body bs-callout bs-callout-red form-inline">
										<p class="form-inline"><label class="form-label">جنسیت:</label> <select class="form-control" id="gender">
												<option value="1"<?php if ($profile['gender'] == '1') {	echo ' selected="selected"';} ?>>مذکر</option>
												<option value="2"<?php if ($profile['gender'] == '2') {	echo ' selected="selected"'; } ?>>مونث</option>
											</select>
										</p>
										<p class="form-inline"><label class="form-label">کشور: </label><select class="form-control" id="country">
												<?php wolde::countries('check', $profile['country']);?>
											</select></p>
										<p class="form-inline"><label class="form-label">شهر :</label><select class="form-control" id="city">
												<?php wolde::cyties('check', $profile['city']);?>
											</select></p>
									</div>
								</div>
								<div class="panel panel-details">
									<div class="panel-heading panel-title-orange"><i class="fa fa-pencil"></i> توضیحات پروفایل</div>
									<div class="panel-body bs-callout bs-callout-orange form-inline"><label class="form-label">توضیحات:</label><textarea class="form-control" id="infos" cols="50" rows="5"><?php echo $profile['info'];?></textarea></div>
								</div>
								<div class="panel panel-details">
									<div class="panel-heading panel-title-green"><i class="fa fa-tachometer"></i> امکانات پروفایل</div>
									<div class="panel-body bs-callout bs-callout-green">

											<p><label class="form-label">اسم آهنگ شما:</label> <span style="direction: ltr;"><input class="form-control" style="text-align: left;" id="music_title" type="text" value="<?php echo $mymusic['music_title'];?>"></span></p>
											<p><label class="form-label">لینک مستقیم آهنگ مورد علاقه شما:</label><span dir="ltr">(http:// الزامی میباشد)<input class="form-control" style="text-align: left; font-size: 12px;" id="music" type="text" value="<?php echo $mymusic['music'];?>"></span></p>
											<p><label class="form-label">توضیحات آهنگ شما:</label><textarea class="form-control" id="music_info" cols="50" rows="5"><?php echo $mymusic['music_info'];?></textarea></p>
                                                                                        <p><b style="color:red;"> بهتر است بجای اپلود اهنگ لینک مستقیم آن را قرار دهید </b></p>

											<p><label class="form-label">آپلود آهنگ:</label><button type="button" class="btn btn-success" style="direction: ltr;" onclick="PopUpAjax('show');"> کلیک کنید</button></p>

										<p><label class="form-label">پایگاه اینترنتی:</label> <span class="input-group"><input class="form-control" style="text-align: left;" id="site" type="text" value="<?php echo $profile['site'];?>"><span class="input-group-addon" style="direction: ltr;">http://www.</span></span></p>
										<p><label class="form-label">ایمیل:</label> <input class="form-control" style="text-align: left;" id="email" type="text" value="<?php echo $profile['email'];?>"></p>
										<p><label class="form-label">فریاد: <button type="button" class="btn btn-warning" style="direction: ltr;" onclick="SmileArea('1');
											return false;"><i class="fa fa-smile-o"></i> شکلک</button></label> <input class="form-control" id="shout" type="text" value="<?php echo $profile['shout'];?>"></p>
										<div id="smilearea-loader-1"></div>
										<div class="loadsmiley" id="smilearea-1"></div>
									</div>
								</div>
								<div class="panel panel-details">
									<div class="panel-heading panel-title-yellow"><i class="fa fa-check-square"></i> تنظیمات پروفایل</div>
									<div class="panel-body bs-callout bs-callout-yellow">
                                                <p class="form-inline"><label class="form-label">افکت باران در پروفایل</label>
												<select class="form-control" id="effect">
												<option value="1" <?php if ($profile['effect'] == '1') {	echo ' selected="selected"';} ?> >فعال</option>
												<option value="0" <?php if ($profile['effect'] == '0') {	echo ' selected="selected"'; } ?> >غیرفعال</option>
											</select>
										</p>
									<p><label>مشاهده پروفایل</label>
											<select id="showprofile" class="form-control">
												<option value='all' <?php echo $showprofile_select_all;?>>همه</option>
												<option value='friends' <?php echo $showprofile_select_f;?>>فقط دوستانم</option>
											</select></p>
										<p><label>مشاهده لیست دوستان</label>
											<select id="myfriends" class="form-control">
												<option value='all' <?php echo $friend_select_all;?>>همه</option>
												<option value='friends' <?php echo $friend_select_f;?>>فقط دوستانم</option>
												<option value='nothing' <?php echo $friend_select_n;?>>هیچکس</option>
											</select></p>

										<p><label>مشاهده لیست بازدید ها</label>
											<select id="mylastview" class="form-control">
												<option value='all' <?php echo $lastview_select_all;?>>همه</option>
												<option value='friends' <?php echo $lastview_select_f;?>>فقط دوستانم</option>
												<option value='nothing' <?php echo $lastview_select_n;?>>هیچکس</option>
											</select></p>
										<p><label>مشاهده دنبال کنندگان</label>
											<select id="myfollower" class="form-control">
												<option value='all' <?php echo $follower_select_all;?>>همه</option>
												<option value='friends' <?php echo $follower_select_f;?>>فقط دوستانم</option>
												<option value='nothing' <?php echo $follower_select_n;?>>هیچکس</option>
											</select></p>
										<p><label>مشاهده اطلاعات شخصی ( تاریخ عضویت, آخرین فعالیت, تاریخ تولد, توضیحات ... )</label>
											<select id="info_config" class="form-control">
												<option value='all' <?php echo $info_select_all;?>>همه</option>
												<option value='friends' <?php echo $info_select_f;?>>فقط دوستانم</option>
												<option value='nothing' <?php echo $info_select_n;?>>هیچکس</option>
											</select></p>
										<p><label>فعال کردن لایک و دیسلایک:</label> <input id="ldconfig" type="checkbox" value="1" <?php
if (!isset($mysettings['ldconfig']) || $mysettings['ldconfig'] == '1') {
	echo 'checked';
}
?>></p>
										<p><label>فعال کردن موزیک پلیر:</label> <input id="music_config" type="checkbox" value="1" <?php
if (!isset($mysettings['music_config']) || $mysettings['music_config'] == '1') {
	echo 'checked';
}
?>></p>
										<p><label>مشاهده گزارشات سیستم:</label> <input id="notification" type="checkbox" value="1" <?php
if (!isset($mysettings['notification']) || $mysettings['notification'] == '1') {
	echo 'checked';
}
?>></p>
										<p><label>مشاهده مطالب دوستان در پروفایل من:</label> <input id="sfriendp" type="checkbox" value="1" <?php
if (!isset($mysettings['sfriendp']) || $mysettings['sfriendp'] == '1') {
	echo 'checked';
}
?>></p>
										<p><label>مشاهده مطالب کاربران دنبال شده در پروفایل من:</label> <input id="sfollowp" type="checkbox" value="1" <?php
if (!isset($mysettings['sfollowp']) || $mysettings['sfollowp'] == '1') {
	echo 'checked';
}
?>></p>
									</div>
								</div>
								<button style="margin:10px auto 40px;" type="button" class="btn btn-success btn-lg pull-left" onclick="save();">ذخیره</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script src="<?php echo theme::url("theme/microblog/js/jquery-ui.js");?>"></script>
		<script src="<?php echo theme::url("theme/microblog/js/bootstrap.min.js");?>"></script>
		<script src="<?php echo theme::url("theme/microblog/js/enscroll-0.6.1.min.js");?>"></script>
		
	</body>
</html>