<?php
include('header.php');
include('sidebar.php');
?>
<script type="text/javascript">
    var $delID = '';
    function changepage(elem) {
        window.location.href = '<?php
if ($el == 'el3') {
    echo(url('admin/users/\'+elem.value+\''));
}
if ($el == 'el4') {
    echo(url("admin/users/$el3/'+elem.value+'"));
}
if ($el == 'el5') {
    echo(url("admin/users/list/$el4/'+elem.value+'"));
}
?>';
    }
    var $search_type = '';
    function search_username() {
        $('#name_leyer').slideUp(1000);
        $('#gender_leyer').slideUp(1000);
        $('#username_leyer').slideDown(1000);
        $('#search2').slideDown(1000);
        $search_type = 'username';
    }
    function search_name() {
        $('#name_leyer').slideDown(1000);
        $('#gender_leyer').slideUp(1000);
        $('#username_leyer').slideUp(1000);
        $('#search2').slideDown(1000);
        $search_type = 'name';
    }
    function search_gender() {
        $('#name_leyer').slideUp(1000);
        $('#gender_leyer').slideDown(1000);
        $('#username_leyer').slideUp(1000);
        $('#search2').slideDown(1000);
        $search_type = 'gender';
    }
    function search_ip() {
        $('#name_leyer').slideUp(1000);
        $('#gender_leyer').slideUp(1000);
        $('#ip_leyer').slideDown(1000);
        $('#username_leyer').slideUp(1000);
        $('#search2').slideDown(1000);
        $search_type = 'ip';
    }
    function opensearch() {
        $("#dialog_search").dialog("open");
        return false;

    }
    function search_form(form) {
        form.search_type.value = $search_type;

    }
    $(document).ready(function () {
        $("#dialog_search").dialog({
            autoOpen: false,
            modal: true,
            width: 400,
            open: function () {
                $(this).parent().css('overflow', 'visible');
                $$.utils.forms.resize()
            }
        });
        $('#search_btn').click(opensearch);
        $("#dialog_delete").dialog({
            autoOpen: false,
            modal: true,
            width: 400,
            open: function () {
                $(this).parent().css('overflow', 'visible');
                $$.utils.forms.resize()
            }
        }).find('button.submit').click(function () {
            var $el = $(this).parents('.ui-dialog-content');
            $.ajax({
                type: "POST",
                url: "<?php echo(url('ajax/admin.users.delete')); ?>",
                data: {'user': $delID, token: "<?php echo(session('changepw')); ?>"},
                success: function (data) {
                    switch (data) {
                        case('ok'):
                            $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'حذف شد'});
                            setTimeout("window.location.href = '<?php echo(url('ref')); ?>';", 1500);
                            break;
                        case('user'):
                            $.msgGrowl({type: 'warning', title: 'خطا', text: 'کاربر وجود ندارد'});
                            break;
                        default:
                            alert(data);
                            break;
                    }
                }
            });
            $el.dialog('close');
        }).end().find('button.cancel').click(function () {
            var $el = $(this).parents('.ui-dialog-content');
            $el.dialog('close');
            ;
        });
        $('#useradd_btn').click(function () {
            var $linewuser = $('#shortcuts_creatuser');
            $linewuser.addClass('active');
            $('.small', $linewuser).fadeIn(1000);
        });
    });
    function delUser(id, username) {
        $delID = id;
        $dialog = $('#dialog_delete');
        var $html = $('.classp', $dialog).html();
        $html = $html.replace(/\{username\}/gi, username).replace(/\{userid\}/gi, id);
        $('.classp', $dialog).html($html);
        $dialog.dialog("open").dialog("option", "title", "حذف  " + username);
    }
</script>
<div style="display: none;" id="dialog_delete" title="">
    <p class="classp">آیا از حذف "{username}" با شماره کاربری "{userid}" کمال اطمینان را دارید؟</p>
    <p style="font-weight: 800;text-align: center;text-decoration: blink;color:red">این فرایند غیرقابل برگشت میباشد و تمامی اطلاعات کاربر از جمله پروفایل ، پیام ها حذف میشوند</p>

    <div class="actions">
        <div class="left"><button class="grey cancel">انصراف</button></div>
        <div class="right"><button class="submit">حذف</button></div>
    </div>
</div>
<div style="display: none;" id="dialog_search" title="جستجو کاربران">
    <form action="<?php echo(url('admin/users/search')); ?>" class="full" method="post">
        <div class="row">
            <label for="username"><strong>نام کاربری</strong></label>
            <div><input  type="text" name="username" id="username_search" /></div>
        </div>
        <div class="row">
            <label for="name"><strong>نام نمایشی</strong></label>
            <div><input  type="text" name="name" id="name_search" /></div>
        </div>
        <div class="row">
            <label for="gender"><strong>جنسیت</strong></label>
            <div><select style="padding-bottom: 10px" name="gender" id="gender_search" data-placeholder="یکی را انتخاب کنید">
                    <option value="0">فرقی نمیکند / نامعلوم</option>
                    <option value="1">مذکز</option>
                    <option value="2">مونث</option>
                </select></div>
        </div>
        <div class="row">
            <label for="name"><strong>آی پی</strong></label>
            <div><input  type="text" name="ip" id="ip_search" /></div>
        </div>
    </form>
    <div class="actions">
        <div class="right">
            <button class="grey cancel" onclick="$('#dialog_search').dialog('close');">انصراف</button>
        </div>
        <div class="left">
            <input type="submit" class="submit" value="بگرد" onclick="$('#dialog_search form')[0].submit();" />
        </div>
    </div>
</div>
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">کاربران</h1>

    <div class="grid_12">
        <p style="text-align: center;">
            <button id="search_btn" class="button grey block">جستجو</button>
            <button id="useradd_btn" class="button blue block">ساخت کاربر</button>
        </p>
        <div class="box">
            <div class="header">
                <h2>
                    <img class="icon" src="<?php echo(theme::url('img/icons/packs/fugue/16x16/users.png')); ?>">
                    کاربران
                </h2>

            </div>
            <div class="content">
                <p >
                    صفحه:<select onchange="changepage(this);" class="search">
                        <?php
                        $num = db::num('users', $if);
                        $totalpages = ceil($num / $msgshostory);
                        $x = 0;
                        while ($x < $totalpages) {
                            $nextpage = $x + 1;
                            $x++;
                            $num -= $msgshostory;
                            if ($nextpage == $page) {
                                $selected = " selected=\"selected\"";
                            } else {
                                $selected = "";
                            }
                            echo("<option$selected>$nextpage</option>");
                        }
                        if ($x == 0) {
                            echo("<option>1</option>");
                        }
                        ?>
                    </select>
                </p>
                <div class="tabletools">
                </div>
                <table class="styled" id="daynmic_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>نام کاربری</th>
                            <th>نام</th>
                            <th>جنسیت</th>
                            <th>سطح کاربری</th>
                            <th>آی پی</th>
                            <th>امتیاز</th>
                            <th>امتیاز بانکی</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = db::query("SELECT * FROM {prefix}users WHERE $if LIMIT $startpage,$msgshostory");
                        while ($row = mysqli_fetch_array($query)) {
                            switch ($row['gender']) {
                                case('1'):$gender = 'مرد';
                                    break;
                                case('2'):$gender = 'زن';
                                    break;
                                default:$gender = 'نا مشخص';
                                    break;
                            }
                            ?>
                            <tr id="user-<?php echo($row['id']); ?>">
                                <td><?php echo($row['id']); ?></td>
                                <td>
                                    <a href="../../../../admin/users/edit/<?php echo($row[0]); ?>"><?php echo($row['username']); ?></a>
                                    <br />
                        <center><a href="../../../../admin/users/edit/<?php echo($row[0]); ?>"><span style="font-size: 10px;">[ویرایش]</span></a>  <a href="javascript:delUser('<?php echo($row[0]); ?>','<?php echo($row['username']); ?>');"><span style="font-size: 10px;">[حذف]</span></a></center>
                        </td>
                        <td><?php echo($row['name']); ?></td>
                        <td><?php echo($gender); ?></td>
                        <td><a href="../../../../admin/users/list/<?php echo($row['type']); ?>"><img src="<?php echo(theme::url($usertype[$row['type']]['icon'])); ?>"></a></td>
                        <td><?php echo($row['ip']); ?></td>
                        <td><?php echo($row['score']); ?></td>
                        <td><?php echo($row['bank']); ?></td>
                        </tr>
                        <?php
                    }
                    mysqli_free_result($query);
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>
