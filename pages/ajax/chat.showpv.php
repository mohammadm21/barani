<?php
check_session();
echo('<meta charset="UTF-8">');
if (chatroom::checkPermission('showpv')) {
    $sql->where("to", session('id'));
    $sql->where("user", -1,'!=');
    $rows = $sql->get('message');
    if (empty($rows)) {
        exit('<font color="red"><b><center>لیست پیام های دریافتی شما خالی است</center></b></font>');
    }
    ?>
    <p style="color:red;font-weight:bold;text-align:center;">لیست پیام های دریافتی</p>
    <table dir="rtl" width="90%" border="10" align="center">
        <tr>
            <td align="center"><font size="2" face="Byekan">ردیف</font></td>
            <td align="center"><font size="2" face="Byekan">متن</font></td>
            <td align="center"><font size="2" face="Byekan">درجه</font></td>
            <td align="center"><font size="2" face="Byekan">فرستنده</font></td>
            <td align="center"><font size="2" face="Byekan">پروفایل</font></td>
        </tr>
        <?php
        foreach($rows as $row){
            ?>
            <tr>
                <td align="center"><font size="2" face="Byekan"><?= $row['id']; ?></font></td>
                <td align="center"><font size="2" face="Byekan"><?= $row['text']; ?></font></td>
                <td align="center"><font size="2" face="Byekan"><img src="<?= url($usertype[Users::get("type", $row['user'])]['icon']) ?>"></td>
                <td align="center"><font size="2" face="Byekan"><?= Users::get("name", $row['user']) ?></font></td>
                <td align="center"><a href="<?= url("profile/" . $row['user']) ?>" target="_blank"><img src="<?= theme::url('theme/images/chat/chat.pr.png') ?>"></a></td>
            </tr>
            <?php
        }
        ?>
    </table>
<?php } else { ?>
    <h4 style="text-align: center;text-shadow: 0 0 6px #333;font-family: Byekan;direction: rtl;color: red">این قابلیت برای گروه کاربری شما غیرفعال شده است!</h4>
<?php } ?>
