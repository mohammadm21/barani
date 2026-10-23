<html dir="rtl">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="Content-Language" content="fa">
        <meta name="robots" content="index, nofollow"/>
    </head>
    <body>
        <div align="center">
            <table border="0" width="288" background="<?php echo(url('theme/images/chat/bodd.jpg')); ?>" height="241">
                <tr>
                    <td>
                        <div class="menu">
                            <div class="text-menu">
                                <p align="center">
                                    <font size="2" face="Byekan" color="#FFFFFF">درجه بندی کاربران</font>
                            </div>
                        </div>
                        <div class="body">
                            <div align="center">
                                <MARQUEE id=rsh_link onmouseover=this.stop() onmouseout=this.start() trueSpeed scrollAmount=2 direction=up height=150 align="middle" width="220" style="font-family: Byekan; font-size: 10pt; background-color:#000000"><center>

                                        <table border="0" width="186" id="table4">
                                            <?php
                                            foreach ($usertype as $type) {
                                                if ($type['guest'] == 1) {
                                                    continue;
                                                }
                                                ?>
                                                <tr>
                                                    <td width="78"><font size="2" face="Byekan"><img border="0" src="<?php echo(theme::url($type['icon'])); ?>" width="43" height="16"></font></td>
                                                    <td width="98" align="center"><font size="2" color="#FFFFFF"><?php
                                                        if ($type['admin'] == '1' and $type['score'] == '0') {
                                                            echo('مدیریت');
                                                        } elseif ($type['score'] == '0') {
                                                            echo('ثبت نام');
                                                        } else {
                                                            echo("{$type['score']} امتیاز");
                                                        }
                                                        ?></font>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </table>
                                    </center>
                                </MARQUEE>
                            </div>
                            <br>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>