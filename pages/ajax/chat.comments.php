<!DOCTYPE html>
<?php
$login = true;
if (!session('id')) {
    $login = false;
}
?>
﻿<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>سيستم نظرات</title>
        <script type="text/javascript">
            function sfont() {
                var x = document.getElementById("ssfont").selectedIndex;
                var y = document.getElementById("ssfont").options;
                if (y[x].index == "0") {
                    document.getElementById("mesg").style.fontSize = "15px";
                } else if (y[x].index == "1") {
                    document.getElementById("mesg").style.fontSize = "17px";
                } else if (y[x].index == "2") {
                    document.getElementById("mesg").style.fontSize = "18px";
                } else if (y[x].index == "3") {
                    document.getElementById("mesg").style.fontSize = "19px";
                } else if (y[x].index == "4") {
                    document.getElementById("mesg").style.fontSize = "20px";
                }
            }
            function cfont() {
                var x = document.getElementById("ccfont").selectedIndex;
                var y = document.getElementById("ccfont").options;
                if (y[x].index == "0") {
                    document.getElementById("mesg").style.color = "red";
                    document.getElementById("ccfont").style.color = "red";
                } else if (y[x].index == "1") {
                    document.getElementById("mesg").style.color = "blue";
                    document.getElementById("ccfont").style.color = "blue";
                } else if (y[x].index == "2") {
                    document.getElementById("mesg").style.color = "black";
                    document.getElementById("ccfont").style.color = "black";
                } else if (y[x].index == "3") {
                    document.getElementById("mesg").style.color = "green";
                    document.getElementById("ccfont").style.color = "green";
                } else if (y[x].index == "4") {
                    document.getElementById("mesg").style.color = "#FF6600";
                    document.getElementById("ccfont").style.color = "#FF6600";
                } else if (y[x].index == "5") {
                    document.getElementById("mesg").style.color = "#CC33FF";
                    document.getElementById("ccfont").style.color = "#CC33FF";
                }
            }
            function boldText(checkBox, target) {
                if (checkBox.checked) {
                    document.getElementById(target).style.fontWeight = "bold";
                } else {
                    document.getElementById(target).style.fontWeight = "normal";
                }
            }
            function italicText(checkBox, target) {
                if (checkBox.checked) {
                    document.getElementById(target).style.fontStyle = "italic";
                } else {
                    document.getElementById(target).style.fontStyle = "normal";
                }
            }
        </script>
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
    <body dir="rtl" background="/theme/comments/img/bg.jpg">
        <?php
        if ($login == true) {
            ?>
            <div style="font-weight: bold;">نام کاربري شما: <?php echo session('name'); ?></div>
            <form action="/ajax/chat.comments.add" method="post">
                <textarea id="mesg" name="Paiam" style="width: 230px; height: 250px;font-family:'Yekan';resize:none;"></textarea>
                <br />
                <table>
                    <tr>
                        <td>
                            <b>اندازه قلم : </b>
                            <select id="ssfont" name="FSize" onclick="sfont()">
                                <option selected="selected">15</option>
                                <option>17</option>
                                <option>18</option>
                            </select>
                        </td>
                        <td>
                            <b>رنگ قلم : </b>
                            <select id="ccfont" name="FColor" onchange="cfont()">
                                <option value="red" style="color:red;font-weight:bold;">قرمز</option>
                                <option value="blue" style="color:blue;font-weight:bold;">آبي</option>
                                <option value="black" selected="selected" style="color:black;font-weight:bold;">سياه</option>
                                <option value="green" style="color:green;font-weight:bold;">سبز</option>
                                <option value="orange" style="color:#FF6600;font-weight:bold;">نارنجي</option>
                                <option value="puple" style="color:#CC33FF;font-weight:bold;">بنفش</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>ضخیم : </b>
                            <input id="bold" name="bold" type="checkbox" value="1" onclick="boldText(this, 'mesg')"/>
                        </td>
                        <td>
                            <b><i>کج : </i></b>
                            <input id="italic" name="italic" type="checkbox" value="1" onclick="italicText(this, 'mesg')"/>
                        </td>
                    </tr>
                </table>
                <input type="submit" value="ارسال نظر" style='cursor:pointer;' class='link'/>
                <input type="button" value="نمایش نظرات" style="cursor:pointer;" class="link" onclick="window.location = '/ajax/chat.comments.show';" />
            </form>
            <?php
        } else {
            ?>
            <br />
            <div style="font-weight:bold;font-family:Yekan">
                <center>
                    <br />
                    <span style="color: brown;">لطفا وارد شوید</span>
                </center>
            </div>
            <?php
        }
        ?>
        <div class="footer"><div>All Right Reserved <a href="http://www.sttarh.ir" target="_blank">STTARH.IR</a></div></div>
    </body>
</html>