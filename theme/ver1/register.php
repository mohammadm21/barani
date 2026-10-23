<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<title><?php echo(site_title); ?></title>
<link rel="stylesheet" href="<?php echo(url('theme/ver1/css/post.css')); ?>" type="text/css" media="all">
<script type="text/javascript" src="<?php echo(url('theme/ver1/js/jquery.js')); ?>"></script>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script type="text/javascript">
function registerr(){
    var username = $('#username').val();if(username.length < 3 || username.length > 14){alert('لطفا یک نام کاربری معتبر وارد کنید!');return false;}
    var email = $('#email').val();
    var emailFilter = /^([0-9a-z]([-.\w]*[0-9a-z])*@(([0-9a-z])+([-\w]*[0-9a-z])*\.)+[a-z]{2,6})$/i;
    if (!emailFilter.test(email)){alert('لطفا یک ایمل معتبر وارد کنید.');return false;}
    var gender = $('#gender').val();
    var password = $('#password').val();if(password.length < 4 || password.length > 14){alert('لطفا یک پسورد با تعداد کارکتر هایی بین 4  تا 14 وارد کنید!');return false;}
    var captcha = $('#captcha').val();
    $.ajax({
        type: "POST",
        url: "<?php echo(url('ajax/register')); ?>",
        data:{username:username, email:email, gender:gender, password:password, captcha:captcha},
        success: function(data){
            switch(data){
                case('ok'):alert("با موفقیت انجام شد!");location.href="<?php echo(url('chat')); ?>";break;
                case('captcha'):alert('کد امنیتی را به درستی وارد نکردید!');break;
                case('usernamelen'):alert('نام کاربری با این تعداد حروف مورد قبول نمیباشد');break;
                case('username'):alert('نام کاربری در حال استفاده میباشد!');break;
                case('badname'):alert('این نام در لیست سیاه قرار دارد.');break;
                case('email'):alert('این ایمیل قبلا در پایگاه اطلاعاتی سامانه ثبت گردیده است');break;
                case('emailpattern'):alert('لطفا یک ایمل معتبر وارد کنید.');break;
                case('passwordlen'):alert('لطفا یک پسورد با تعداد کارکتر هایی بین 4  تا 14 وارد کنید');break;
                default:alert(data);break;
            }
        }
    });
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
    <div id="center">
        <div id="centerads"></div>
        <div id="post">
        <h3>ورود مهمانان</h3>
            <form action="" method="POST"  autocomplete="off" onsubmit="return registerr();">
            <p><label>نام کاربری: </label> <input type="text" id="username"></p>
            <p><label>ایمیل: </label> <input type="text" id="email"></p>
            <p><label>جنسیت: </label> <select id="gender">
            <option value="1">مذکر</option>
            <option value="2">مونث</option>
            </select></p>
            <p><label>کلمه عبور: </label> <input type="password" id="password"></p>
            <p><label>کد امنیتی: <img alt="کد انسان یاب!" src="<?php echo(url('captcha/100/38')); ?>" style="width: 100px;height: 38px;"></label> <input type="text" style="width: 100px;" id="captcha" maxlength="5" ></p>
            <center><input type="submit" value="ثبت نام"></center>
        </form>
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