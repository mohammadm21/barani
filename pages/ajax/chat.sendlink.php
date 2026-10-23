<html>
    <head>
        <?php if (!chatroom::checkPermission('sendlink')) { ?>
            <title>غیر فعال شده است!</title>
        <?php } else { ?>
            <title>ارسال لینک در خصوصی</title>
        <?php } ?>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link type="text/css" rel="stylesheet" href="<?php echo url("theme/fonts/fonts.css"); ?>">
        <script type="text/javascript">
            function send(elem) {
                elem.innerHTML = 'درحال ارسال لینک';
            }
        </script>
        <style>
            *{font-family:Byekan;text-align:center;direction: rtl;}
            button[type="text"],form select,form button[type="password"]{font-family:Byekan;vertical-align:middle;color:#007236;background: #fcfdfe;border-radius:3px;border:#b8c1cb solid 1px;color:#000;width:175px;direction:rtl;padding:8px 10px}
            button[type="button"],button[type="button"],button[type="submit"]{font-family:Byekan;background: #007236;border:#007236 solid 1px;color:#fff;border-radius:5px;font-weight:700;text-shadow:#0e67b7 0 1px;cursor:pointer;padding:5px 10px}
            .noscore{font-family:Byekan;background: #007236;border:#007236 solid 1px;color:#fff;border-radius:5px;font-weight:700;text-shadow:#0e67b7 0 1px;cursor:pointer;padding:5px 10px}
            .sendlink{font-family:Byekan;background: #007236;border:#007236 solid 1px;color:#fff;border-radius:5px;font-weight:700;text-shadow:#0e67b7 0 1px;cursor:pointer;padding:5px 10px}
            input[type="button"],input[type="submit"]{padding:10px}
            table{font-family:Byekan;border: 1px #000 solid}
            td{font-family:Byekan;border: 1px #000 solid}
            tr{font-family:Byekan;border: 1px #000 solid}
            .div_text_mess {font-family:Byekan;background: none repeat scroll 0 0 #ffffff;border: 1px solid #adadad;direction: rtl;list-style-type: none;margin: 5px 0;padding: 8px;direction: rtl;}
        </style>
        <?php
        if (!chatroom::checkPermission('sendlink')) {
            ?>
        <h2 style="text-align: center;text-shadow: 0 0 10px red;padding-top:20px;">این قابلیت برای گروه کاربری شما غیر فعال شده است!</h2>
        <?php
    } else {
        ?>
        <?php
        $id = ".session('id').";
        $link = safe::url($_POST['link']);
        $title = safe::string($_POST['title']);
        $to = safe::number($_POST['to']);
        $linkurl = "<center><a href=\"http://{$link}\" target=\"_blank\"> لینک ارسال شده :{$title}</a></center>";
        $room = '-1';
        if (isset($_POST['sendlink'])) {
            if ($title != '') {
                if ($link != '') {
                    if ($to != '') {
                        $sql->insert('message', array(
                            'user' => session('id'),
                            'username' => session('name'),
                            'color' => '000000',
                            'room' => $room,
                            'text' => $linkurl,
                            'date' => time(),
                            'to' => $to,
                            'into' => 'pm',
                            'type' => session('admin')
                        ));
                        echo "<center><b><font size=\"3px\" color=\"#009900\">لینک با موفقیت به کاربر ارسال شد</font></b></center>  ";
                    } else {
                        echo "<center><b><font size=\"3px\" color=\"red\">لطفا گیرنده ی لینک را انتخاب کنید</font></b></center>";
                    }
                } else {
                    echo "<center><b><font size=\"3px\" color=\"red\">لطفا ادرس لینک را وارد کنید</font></b></center>";
                }
            } else {
                echo "<center><b><font size=\"3px\" color=\"red\">لطفا متن لینک را وارد کنید</font></b></center>";
            }
        }
        ?>
        <br>
        <form id="formsendlink" method="post" action="">
            <label>متن لینک:</label>
            <input type="text" name="title" placeholder="متن لینک ...">
            <hr />
            <label>آدرس لینک</label>
            <input type="text" name="link" placeholder="ادرس لینک ...">&nbsp;&nbsp;<b style="color: red;font-size: 13px;">بدون http://</b>
            <hr />
            <label>کاربر مورد نظر:</label>
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
            <hr />
            <button name="sendlink" id="sendlink" class="sendlink" onclick="send(this);">ارسال</button>
        </form>
    </html>
<?php } ?>