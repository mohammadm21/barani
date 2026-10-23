<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>پروفایل <?php echo($user['name']); ?></title>
<link rel="stylesheet" href="<?php echo(url('theme/ver1/css/profile.css')); ?>" type="text/css" media="all">
<script type="text/javascript" src="<?php echo(url('theme/ver1/js/jquery.js')); ?>"></script>
<script type="text/javascript" src="<?php echo(url('visit')); ?>"></script>
<script type="text/javascript">
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
                document.getElementById('cont').innerHTML = "";
            });
        }
    });
}
function getevents(){
    $.ajax({
        type: "GET",
        url: "<?php echo(url('ajax/get')); ?>",
        data:{events:'f'},
        success: function(data){
        },
        dataType:'xml'
    });
    setTimeout("getevents();", 20000);
}
function showmapcountry(){
    var country = document.getElementById('showmapcountry').innerHTML;
    document.getElementById('cont').innerHTML = "<center><iframe width=\"425\" height=\"350\" frameborder=\"0\" scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\" src=\"http://maps.google.com/maps?oe=utf-8&amp;q="+country+"&amp;ie=UTF8&amp;hq=&amp;hnear="+country+"&amp;t=m&amp;z=6&amp;output=embed\"><\/iframe><\/center>";
    PopUp('show');
    return false;
}
function showmapcity(){
    var url = document.getElementById('showmapcity').href;
    document.getElementById('cont').innerHTML = "<center><iframe width=\"425\" height=\"350\" frameborder=\"0\" scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\" src=\""+url+"&amp;output=embed\"><\/iframe><\/center>";
    PopUp('show');
    return false;
}
$(document).ready(function(){
    $('#avatar img').click(function(){
        PopUp('show');
        $('#box #cont').html('<center><img alt="<?php echo($user['name']); ?>" src="<?php echo(url('theme/images/avatars/'.$profile['avatar'])); ?>" style="width: auto;height: auto;margin: 10px;" align="middle"><\/center>');
    });
    setTimeout("getevents();", 20000);
    
});
function edit(){
    $('#main').html("<h2>لطفا صبر کنید...</h2>");
    $.ajax({
      type: "GET",
      url: "<?php echo(url('ajax/htmleditprofile')); ?>",
      dataType: "HTML",
      success: function(data){
        $('body').html(data);
      }
    });
}
</script>
</head>
<div id="thickBlack"></div>
<div id="box">
    <a onclick="PopUp('hide');"  href="javascript: PopUp('hide')" id="close"></a>
    <div id="cont">
    </div>
</div>
    <div id="main">
    <a href="<?php echo(url()); ?>" id="logo"></a>
        <div id="avatar"><img src="<?php echo(url('theme/images/avatars/'.$profile['avatar'])); ?>"  alt="<?php echo($user['name']); ?>"></div>
        <div id="title"><img src="<?php echo(url("theme/ver1/images/$online.png")); ?>" alt="<?php echo($online); ?>" style="display: inline;"><h1 style="display: inline;"><a href="<?php echo(url("profile/$user[0]/".$user['name'])); ?>"><?php echo($user['name']); ?></a></h1><?php echo('['.$type.']'); ?></div>
        <div id="info">
            <p><span class="label">تاریخ عضویت:</span> <?php echo($user['registerDate']); ?></p>
            <p><span class="label">آخرین فعالیت در </span> <?php echo($user['lastAcivity']); ?> ساعت : <?php echo($user['lastAcivityTime']); ?></p>
            <p><span class="label">تاریخ تولد:</span> <?php echo($birthDate); ?></p>
            <p><span class="label">جنسیت:</span> <?php echo($gender); ?></p>
            <p><span class="label">کشور :</span> <?php echo($profile['country'] ? '<a id="showmapcountry" onclick="return showmapcountry();" href="http://maps.google.com/maps?q='.$profile['country'].'">'.$profile['country'].'</a>' : 'وارد نشده'); ?></p>
            
            <p><span class="label">شهر :</span> <?php echo($profile['city'] ? '<a id="showmapcity" onclick="return showmapcity();" href="http://maps.google.com/maps?q='.$profile['city'].',+'.$profile['country'].'">'.$profile['city'].'</a>' : 'وارد نشده'); ?></p>
            
            
            <p><span class="label">توضیحات:</span></p> <?php echo(str_replace("\n", '<br>', $profile['info'])); ?>
            <br><br>
            <p><span class="label">پایگاه اینترنتی:</span> <a href="http://<?php echo($profile['site']); ?>">http://www.<?php echo($profile['site']); ?></a></p>
            <p><span class="label">ایمیل:</span> <a href="<?php echo(url("panel/email/$user_id")); ?>">کلیک کنید</a></p>
        </div>
    
    <?php if(isset($_SESSION['id']) and $_SESSION['id'] == $user_id){ ?> <input style="position: absolute;left: 20px;margin-top:-50px;" type="button" value="ویرایش" onclick="edit();"> <?php } ?>
    
    
    </div>


<div id="w3">
    <a href="http://validator.w3.org/check?uri=referer"><img src="http://www.w3.org/Icons/valid-html401" alt="Valid HTML 4.01 Transitional" height="31" width="88"></a>
    <a href="http://jigsaw.w3.org/css-validator/check/referer"><img src="http://jigsaw.w3.org/css-validator/images/vcss"alt="!معتبر CSS" ></a>
</div>
</html>