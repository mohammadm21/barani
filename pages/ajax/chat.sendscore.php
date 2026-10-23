<?php
if (!session('id')) {
    header::Go(url('login'));
    return;
} else {
    ?>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <link type="text/css" rel="stylesheet" href="<?php echo url("theme/fonts/fonts.css"); ?>">
        <?php if (!chatroom::checkPermission('sendscore')) { ?>
            <title>غیر فعال شده است!</title>
        <?php } else { ?>
            <title>انتقال امتیاز</title>
        <?php } ?>
        <script type="text/javascript">
            function send(elem) {
                elem.innerHTML = 'درحال انتقال امتیاز';
            }
        </script>
        <?php if (isset($_COOKIE["sendscore"]) == 1) { ?>
        <center><font color="red"><b>شما مجازید در هر 1 ساعت یک بار انتقال امتیاز کنید</b></font></center>
    <?php } else { ?>
        <style>
            *{font-family:Byekan;text-align:center;direction: rtl;}
            button[type="text"],form select,form button[type="password"]{font-family:Byekan;vertical-align:middle;color:#007236;background: #fcfdfe;border-radius:3px;border:#b8c1cb solid 1px;color:#000;width:175px;direction:rtl;padding:8px 10px}
            button[type="button"],button[type="button"],button[type="submit"]{font-family:Byekan;background: #007236;border:#007236 solid 1px;color:#fff;border-radius:5px;font-weight:700;text-shadow:#0e67b7 0 1px;cursor:pointer;padding:5px 10px}
            .noscore{font-family:Byekan;background: #007236;border:#007236 solid 1px;color:#fff;border-radius:5px;font-weight:700;text-shadow:#0e67b7 0 1px;cursor:pointer;padding:5px 10px}
            .sendscore{font-family:Byekan;background: #007236;border:#007236 solid 1px;color:#fff;border-radius:5px;font-weight:700;text-shadow:#0e67b7 0 1px;cursor:pointer;padding:5px 10px}
            input[type="button"],input[type="submit"]{font-family:Byekan;padding:10px}
            table{font-family:Byekan;border: 1px #000 solid}
            td{font-family:Byekan;border: 1px #000 solid}
            tr{font-family:Byekan;border: 1px #000 solid}
        </style>
        </head>
        <body>
            <?php
            if (!chatroom::checkPermission('sendscore')) {
                ?>
                <b><h3 style="text-align: center;text-shadow: 0 0 10px red;padding-top:20px;">این قابلیت برای گروه کاربری شما غیر فعال شده است!</h3></b>
                <?php
                if (session('score') < 0) {
                    die('<h4 style="text-align: center;text-shadow: 0 0 10px blue;padding-top:20px;">امتیاز شما برای انجام این عملیات کافی نیست!</h4><br /><br /><center><a style="text-decoration:none;" href="' . url('ajax/chat.vip') . '"><p style="color: red;font-weight: bold;">بازشگت به صفحه نخست</p><center>');
                }
                ?>
                <?php
            } else {
                ?>
                <?php
                $sql->where("id", session('id'));
                $mescore = $sql->getOne('onlines');
                $score = safe::number($_POST['score']);
                $to = safe::number($_POST['to']);
                $sender = session('name');
                if (isset($_POST['sendscore'])) {
                    if ($score < $mescore['score']) {
                        if ($score != '') {
                            if ($to != '') {
                                $sql->where("id",session('id'))->update('users', array(
                                    'score' => $sql->dec($score),
                                    'onlinetime' => $sql->dec($score * 60)
                                ));
                                $sql->where("id",session('id'))->update('onlines', array(
                                    'score' => $sql->dec($score)
                                ));
                                $sql->where("id",$to)->update('users', array(
                                    'score' => $sql->inc($score),
                                    'onlinetime' => $sql->inc($score * 60)
                                ));
                                $sql->where("id",$to)->update('onlines', array(
                                    'score' => $sql->dec($score)
                                ));
                                $sql->where("id", $to);
                                $name = $sql->getOne('users');
                                chatroom::addlog('5', " به کاربر " . Users::get('name', $sender) . "  " . $name['name'] . "امتیاز انتقال داد");
                                $num = $sql->getValue('onlines', 'count(*)');
                                $allow = json_decode($options['useroptions'], true);
                                for ($i = 0; $i <= $num; $i++) {
				    $type = $sql->getOne('onlines', array('type'));
				    $id = $sql->getOne('onlines', array('id'));
                                    if (in_array($type['type'], $allow['send_user2private_admins'])) {
                                        $date = 'UNIX_TIMESTAMP()'; //microtime(true)
                                        $note = "<table cellpadding=\"0\" border=\"1\" align=\"center\"><center><tr><td align=\"center\"><b>شما یک گزارش انتقال امتیاز دریافت کرده اید</b> " . "<br></tr><td>
<tr><td align=\"center\"><b> فرستنده : " . session('name') . " <br></b></tr></td>
<tr><td align=\"center\"><b> دریافت کننده ی امتیاز : " . Users::get('username', $to) . " <br></b></tr></td>
<tr><td align=\"center\"><b> مقدار امتیاز : " . $score . " <br></b></tr></td>
<tr><td align=\"center\"><b> توضیحات : امتیاز با موفقیت انتقال داده شد . در صورت وجود مشکل برخورد کنید</b></tr></td>";
                                        $sql->insert('message', array(
                                            'user' => -1,
                                            'username' => $options['chat_systemname'],
                                            'color' => '000000',
                                            'room' => -1,
                                            'text' => $note,
                                            'date' => time(),
                                            'to' => $id['id'],
                                            'into' => 'pm',
                                            'type' => 0
                                        ));
                                    }
                                }

                                setcookie("sendscore", "1", time() + 3600);
                                echo "<center><b><font size=\"3px\" color=\"#009900\">ارسال امتیاز با موفقیت انجام شد<br />از شما مقدار امتیاز " . $score . " کسر شد و به کاربر خاطی ارسال شد</font></b></center>  ";
                            } else {
                                echo "<center><b><font size=\"3px\" color=\"red\">لطفا گیرنده ی امتیاز را انتخاب کنید</font></b></center>";
                            }
                        } else {
                            echo "<center><b><font size=\"3px\" color=\"red\">لطفا مقدار امتیاز را وارد کنید</font></b></center>";
                        }
                    } else {
                        echo "<center><b><font size=\"3px\" color=\"red\">امتیازی انتقالی بیشتر از امتیاز هم اکنون شما میباشد .</font></b></center>";
                    }
                }
                ?>
                <br>
                <form id="formsendscore" method="post" action="">
                    <label>مقداری امتیاز:</label>
                    <select name="score">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="250">250</option>
                        <option value="500">500</option>
                        <option value="1000">1000</option>
                        <option value="2500">2500</option>
                        <option value="5000">5000</option>
                        <option value="10000">10000</option>
                        <option value="25000">25000</option>
                        <option value="50000">50000</option>
                    </select>
                    <hr />
                    <label>دریافت کننده ی امتیاز:</label>
                    <select name="to">
                        <?php
                        $sql->where("id", session('id'), '!=');
                        foreach($sql->get('onlines') as $user){
                            ?>
                            <option value="<?php echo($user['id']); ?>"> <?php echo($user['name']); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <hr>
                    <?php if (($mescore['score'] - $options['scoretosendscore']) < 0) { ?>
                        <input disabled class="noscore" value="امتیاز شما کافی نیست">
                        <?php
                    } else {
                        ?>
                        <button name="sendscore" id="sendscore" class="sendscore" onclick="send(this);">انتقال</button>
                    <?php } ?>
                </form>
            </body>

            <?php
        }
    }
}
?>