<!DOCTYPE html>
<?php
if (!session('id')) {
    exit('login');
}
$admin = session('admin');
if ($admin != '0') {
    if ($admin == '1') {
        if (isset($_POST['del'])) {
            $del = safe::number($_POST['del']);
            if (!empty($del) && $del != '') {
                $sql->where("id", $del)->delete('chat_comment');
            }
        }
    }
    if (isset($_POST['acc'])) {
        $acc = safe::number($_POST['acc']);
        if (!empty($acc) && $acc != '') {
            $sql->where("id", $acc)->update('chat_comment', array(
                'active' => 1
            ));
        }
    }
}
$id = session('id');
if ($admin != '0') {
    $sql->orderBy('id', 'DESC');
    $rows = $sql->get('chat_comment',null, array('id', 'user_id', 'name', 'color', 'bold', 'italic', 'font', 'message', 'date', 'active'));
} else {
    $sql->where("active", 1);
    $sql->orderBy('id', 'DESC');
    $rows = $sql->get('chat_comment',null, array('id', 'user_id', 'name', 'color', 'bold', 'italic', 'font', 'message', 'date', 'active'));

}
?>

﻿<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <style type="text/css">
            td {
                border:groove 0.3px #7de0ed;
                -moz-border-radius-topleft: 14px;
                -moz-border-radius-topright:14px;
                -moz-border-radius-bottomleft:14px;
                -moz-border-radius-bottomright:14px;
                -webkit-border-top-left-radius:14px;
                -webkit-border-top-right-radius:14px;
                -webkit-border-bottom-left-radius:14px;
                -webkit-border-bottom-right-radius:14px;
                border-top-left-radius:14px;
                border-top-right-radius:14px;
                border-bottom-left-radius:14px;
                border-bottom-right-radius:14px;
                background-color: rgba(220, 220, 236, 0.9);
                background: rgba(220, 220, 236, 0.9);
                color: rgba(220, 220, 236, 0.9);
                transition:All 0.5s ease;
                -webkit-transition:All 0.5s ease;
                -moz-transition:All 0.5s ease;
                -o-transition:All 0.5s ease;
            }
            td:hover{
                border:groove 0.3px #7de0ed;
                -moz-border-radius-topleft: 14px;
                -moz-border-radius-topright:14px;
                -moz-border-radius-bottomleft:14px;
                -moz-border-radius-bottomright:14px;
                -webkit-border-top-left-radius:14px;
                -webkit-border-top-right-radius:14px;
                -webkit-border-bottom-left-radius:14px;
                -webkit-border-bottom-right-radius:14px;
                border-top-left-radius:14px;
                border-top-right-radius:14px;
                border-bottom-left-radius:14px;
                border-bottom-right-radius:14px;
                background-color: rgba(199, 199, 235, 0.9);
                background: rgba(199, 199, 235, 0.9);
                color: rgba(199, 199, 235, 0.9);
                transition:All 0.5s ease;
                -webkit-transition:All 0.5s ease;
                -moz-transition:All 0.5s ease;
                -o-transition:All 0.5s ease;
            }
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
    <head>
    <body background="/theme/comments/img/bg.jpg" dir="rtl" style="font-family:'Yekan';">
        <a href="/ajax/chat.comments" class="link">ارسال نظر</a><br />
        <?php
        if (mysqli_num_rows($query) == 0) {
            ?>
            <div style="font-weight: bold;"><center><br/><br/>کاربر عزیز<br /> هیچ نظری وجود ندارد</center></div>
            <?php
        } else {
            ?>
        <center>
            <table cellspacing="10" width="100%" align='right'>
                <?php
                while ($row = mysqli_fetch_array($query)) {
                    ?>
                    <tr>
                        <td>
                            <div style='color:Black;font-size:13px;'>
                                &nbsp;&nbsp;&nbsp;
                                <span dir='rtl'>نام کاربر : <?php echo $row['name'] . ($admin != '0' ? "- کد کاربر : {$row['user_id']}" : ''); ?></span>
                            </div>
                            <div style='margin-left:15px;float:left;'>
                                <?php
                                if ($admin != '0') {
                                    if ($admin == '1') {
                                        ?>
                                        <form method="POST" name="d<?php echo $row['id']; ?>" id="d<?php echo $row['id']; ?>">
                                            <input type="hidden" name="del" value="<?php echo $row['id']; ?>" />
                                            <img src="/theme/comments/img/delet.png" title="پاک کردن نظر" height="20px" alt="پاک کردن نظر" width="20px" onclick="document.getElementById('d<?php echo $row['id']; ?>').submit();"/>
                                        </form>
                                        <?php
                                    }
                                    if ($row['active'] == '0') {
                                        ?>
                                        <form method="POST" name="a<?php echo $row['id']; ?>" id="a<?php echo $row['id']; ?>">
                                            <input type="hidden" name="acc" value="<?php echo $row['id']; ?>"/>
                                            <img src="/theme/comments/img/accept.png" title="تایید نظر" height="20px" width="20px" alt="تایید نظر" onclick="document.getElementById('a<?php echo $row['id']; ?>').submit();"/>
                                        </form>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                            <hr />
                            <div style="width:240px;word-wrap: break-word;font-size:<?php echo($row['font']) ?>px;font-weight:<?php echo($row['bold'] == '1' ? 'bold' : 'normal'); ?>;font-style:<?php echo($row['italic'] == '1' ? 'bold' : 'normal'); ?>;color:#<?php echo($row['color']); ?>"><?php echo $row['message']; ?></div>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <div class="footer"><div>All Right Reserved <a href="http://TarhPardaz.org" target="_blank">TarhPardaz.org</a></div></div>
        </center>
        <?php
    }
    ?>
</body>
</html>
