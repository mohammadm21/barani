<!DOCTYPE html>
<?php
$login = true;
if (!session('id')) {
    exit('login');
}
if ($usertype[session('type')]['guest'] == '1') {
    $login = false;
}
?>
﻿<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <style type="text/css">
            .link:hover {
                background-color: #d72727;
                color: #fff;
                border: 2px solid #f8f8f8;
                -webkit-border-radius: 7px;
                -moz-border-radius: 7px;
                -o-border-radius: 7px;
                border-radius: 7px;
            }
            .link {
                background-color: pink;
                color: #797979;
                padding: 5px;
                line-height: 35px;
                border: 2px solid #f8f8f8;
                -webkit-border-radius: 7px;
                -moz-border-radius: 7px;
                -o-border-radius: 7px;
                border-radius: 7px;
            }
            .footer{
                background-color: rgba(22, 24, 24, 0.4);
                background: rgba(22, 24, 24, 0.4);
                color: rgba(22, 24, 24, 0.4);
                font-size:9px;
                position:fixed;
                bottom:0px;
                right:0px;
                width:100%;
                text-align:center;
                color:white;
            }
            .footer div{
                color:white;
            }
            .footer a{
                color:pink;
            }
        </style>
    </head>
    <body dir="rtl" background="/theme/comments/img/bg.jpg" style="font-family:'Yekan';">
    <center>
        <a href="/ajax/chat.comments.show" class="link">نمایش نظرات</a>
        <a href="/ajax/chat.comments" class="link">ارسال نظر</a>
    </center>
    <br />
    <div style="font-weight:bold;">
        <?php
        if ($login == false) {
            ?>
            <center>
                <br />
                <br />
                کاربر مهمان عزیز
                <br />
                ارسال نظر تنها برای اعضای چت روم می باشد
                <br />
                لطفا ثبت نام نمایید و دوباره وارد شوید
            </center>
            <?php
        } else {
            $message = isset($_POST['Paiam']) ? safe::string($_POST['Paiam']) : '';
            if ($message == '') {
                ?>
                <center>
                    <br />
                    <br />
                    امکان ارسال نظر بدون وارد کردن متن پیام وجود ندارد!!
                </center>
                <?php
            } else {
                $sql->where("user_id", session('id'));
                $sql->where('date>DATE_SUB(NOW(),INTERVAL 3 minute)');
                $data = $sql->getOne('chat_comment');
                if ($data['counter'] != 0) {
                    ?>
                    <center>
                        <br />
                        پیام ارسال نشد!!
                        <br />
                        امکان ارسال پیام  هر 3 دقیقه یک بار امکان پذیر میباشد
                    </center>
                    <?php
                } else {
                    $font = isset($_POST['FSize']) ? safe::number($_POST['FSize']) : '';
                    if (!in_array($font, array('15', '17', '18'))) {
                        $font = '15';
                    }
                    $color_list = array(
                        'red' => 'FF0000',
                        'blue' => '0000FF',
                        'black' => '000000',
                        'green' => '008000',
                        'orange' => 'FF6600',
                        'puple' => 'CC33FF'
                    );
                    $color = isset($_POST['FColor']) ? safe::string($_POST['FColor']) : '';
                    if ($color == '') {
                        $color = '000000';
                    } else {
                        if (isset($color_list[$color])) {
                            $color = $color_list[$color];
                        } else {
                            $color = '000000';
                        }
                    }
                    $bold = isset($_POST['bold']) ? ($_POST['bold'] == 'on' ? '1' : '0') : '0';
                    $italic = isset($_POST['italic']) ? ($_POST['italic'] == 'on' ? '1' : '0') : '0';
                    $message = str_replace(array("\r\n", "\n", "\r"), '<br/>', $message);
                    if($sql->insert('chat_comment', array(
                        'user_id' => session('id'),
                        'name' => session('name'),
                        'color' => $color,
                        'bold' => $bold,
                        'italic' => $italic,
                        'font' => $font,
                        'message' => $message
                    ))){
                        ?>
                        <center>
                            <br />
                            <br />
                            کاربر عزیز
                            <br />
                            نظر شما بعد از تایید مدیر یا ناظر به عموم افراد نشان داده خواهد شد
                        </center>
                        <?php
                    } else {
                        ?>
                        <center>
                            <br />
                            <br />
                            کاربر عزیز 
                            <br />
                            در ارسال نظر مشکلی به وجود آمده است
                        </center>
                        <?php
                    }
                }
            }
        }
        ?>
    </div>
    <div class="footer"><div>All Right Reserved <a href="http://TarhPardaz.org" target="_blank">TarhPardaz.org</a></div></div>
   
</body>
</html>