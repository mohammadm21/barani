<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<title><?php echo(site_title); ?></title>
<link rel="stylesheet" href="<?php echo(url('theme/ver2/css/login.css')); ?>" type="text/css" media="all">
<script type="text/javascript" src="<?php echo(url('theme/ver2/js/jquery.js')); ?>"></script>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script type="text/javascript">
function loginguest(form){
    var name = form.username.value;
    if(name == '' || name == ' '){alert('لطفا نام خود را وارد کنید');return false;}
    var gender = form.gender.value;
    $.ajax({
        type: "POST",
        url: '<?php echo(url('ajax/login/guests')); ?>',
        data:{user:name, gender:gender},
        success: function(data) {
            switch(data){
                case('ok'):top.location.href='<?php echo(url('chat')); ?>';break;
                case('badname'):alert('این نام در لیست سیاه قرار دارد');break;
                case('running'):alert('این نام در حال استفاده است');break;
                case('benn'):alert('شما اجازه ورود به چتروم را ندارید');break;
                case('capacity'):alert('ظرفیت چت روم پر است');break;
                default:alert(data);break;
            }
        }
    });
    return false;
}
function loginuser(form){
    var username = form.username.value;
    if(username == '' || username == ' '){alert('لطفا نام خود را وارد کنید');return false;}
    var password = form.password.value;
    $.ajax({
        type: "POST",
        url: '<?php echo(url('ajax/login/users')); ?>',
        data:{username:username, password:password},
        success: function(data) {
            switch(data){
                case('ok'):top.location.href='<?php echo(url('chat')); ?>';break;
                case('pass'):alert('کلمه عبور اشتباه است');break;
                case('user'):alert('این نام وجود ندارد');break;
                case('benn'):alert('شما اجازه ورود به چتروم را ندارید');break;
                case('capacity'):alert('ظرفیت چت روم پر است');break;
                default:alert(data);break;
            }
        }
    });
    return false;
}


function show(Element_id){
    if(Element_id == 'genderp'){
        document.getElementById('passp').style.display = 'none';
        document.getElementById('genderp').style.display = 'block';
    }else if(Element_id == 'passp'){
        document.getElementById('passp').style.display = 'block';
        document.getElementById('genderp').style.display = 'none';
    }
}
function repass(){
    var email = window.prompt('ایمیل خود را وارد کنید');
    if(email == '' || email == null){return false;}
    ajax.open("GET", "?i=repass&email="+email, true);ajax.send();
    ajax.onreadystatechange = function(){
        if(ajax.readyState == 4 || ajax.readyState == 0){
            var data = ajax.responseText;
            switch(data){
                case('send'):alert('کلمه عبور برایتان ارسال شد');break;
                case('user'):alert('این ایمیل در بانک اطلاعاتی یافت نشد');break;
                default:alert(data);break;
            }
        }
    }
    return false; 
}
</script>
<div id="top">
    <div id="top-1"><ul>
    <li><a href="<?php echo(url()); ?>">صفحه اصلی</a></li>
    <li><a href="<?php echo(url("register")); ?>">ثبت نام</a></li>
    <li><a href="<?php echo(url("faq")); ?>">سوالات متداول</a></li>
    <li><a href="<?php echo(url("canect")); ?>">تماس با ما</a></li>
    <li><a href="<?php echo(url("rank")); ?>">امتیازات</a></li>
    <li><a href="<?php echo(url("users/admins")); ?>">گروه مدیریت</a></li>
    </ul></div>
    <div id="top-2"><ul>
    <li><a href="<?php echo(url("users/online")); ?>">افراد آنلاین: <?php echo(db::num('onlines')); ?> نفر</a></li>
    <li>آی پی شما : <span><?php echo($_SERVER["REMOTE_ADDR"]); ?></span></li>
    </ul></div>
</div>
<div id="main">
    <div id="sidebar-right">
        <div id="logo"></div>
        <div id="login-users">
            <h3>ورود کاربران</h3>
            <form action="" method="post" onsubmit="return loginuser(this);">
                <p><label>نام کاربری: </label><input type="text" name="username"></p>
                <p><label>کلمه عبور: </label><input type="password" name="password"></p>
                <center><input type="submit" value="ورود"></center>
            </form>
        </div>
    </div>
    <div id="center">
        <div id="centerads" style="position: relative;">
            <a href="<?php echo(url('ads')); ?>"><img src="<?php echo(url("theme/ver1/images/ads.gif")); ?>" style="position: absolute;bottom:60px;"></a>
        
        </div>
        <div id="centerlogin">
        <h3>ورود مهمانان</h3>
            <div id="logoguest">
                <form action="" method="post" onsubmit="return loginguest(this);">
                    <p><label>نام کاربری: </label><input type="text" name="username"></p>
                    <p><label>جنسیت: </label><select name="gender">
                    <option value="1">مذکر</option>
                    <option value="2">مونث</option>
                    </select></p>
                    <center><input type="submit" value="ورود"></center>
                </form>
            </div>
        </div>
    </div>
    <div id="sidebar-left">
        <h3>کاربران آنلاین</h3>
        <ul id="onlines">
            <?php
            $query = db::query("SELECT * FROM {prefix}onlines");
            while($row = mysqli_fetch_array($query)){    
            ?>
            <li><img src="<?php echo(url("theme/ver1/images/user_$row[3].png")); ?>" alt="جنسیت" style="width: 25px;"><a href="<?php echo(url("profile/$row[0]/$row[3]")); ?>"><?php echo($row['name']); ?></a> <span class="infonline"><?php echo($row['roomname']); ?></span></li>
            <?php
            }
            mysqli_free_result($query);
            ?>
        </ul>
    </div>
</div>