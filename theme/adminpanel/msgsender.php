<?php
include('header.php');
include('sidebar.php');
?>
<script type="text/javascript">
    var user = '0';
    function send() {
        var $form = $('#formsendmsg');
        var data = {
            'token': '<?php echo(session('changepw')); ?>',
            'user': user,
            'to': $('select[name=to]', $form).val(),
            'type': $('select[name=type]', $form).val(),
            'room': $('select[name=room]', $form).val(),
            'text': $('input[name=text]', $form).val(),
            'color': $('input[name=color]', $form).val()
        };
        $.ajax({
            type: "POST",
            url: "<?php echo(url('ajax/admin.msgsender')); ?>",
            data: data,
            success: function (data) {
                switch (data) {
                    case('ok'):
                        $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'ارسال شد'});
                        break;
                    case('emptytext'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'لطفا متنی را وارد کنید'});
                        break;
                    case('user'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'این کاور وجود ندارد'});
                        break;
                    default:
                        alert(data);
                        break;
                }
            }
        });
    }
    $(document).ready(function () {
        $("#user").autocomplete({
            source: "<?php echo(url('ajax/admin.users.autocomplete')); ?>",
            minLength: 2,
            select: function (event, ui) {
                if (ui.item)
                    user = ui.item.id;
            }
        });
        $('.cpicker').css('width', '25px');
    })
</script>
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">ارسال پیام گروهی</h1>
    <div class="grid_12">
        <div class="grid_12">
            <form class="box  validate" id="formsendmsg">
                <div class="header">
                    <h2>ارسال پیام گروهی</h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>فرستنده:</strong></label>
                        <div> <input type="text"  class="required" name="user" id="user" value="<?php echo($options['chat_systemname']); ?>"/></div>
                    </div>
                    <div class="row">
                        <label><strong>به:</strong></label>
                        <div><select name="to" class="required search">
                                <option value="all" selected="selected">همه کاربران آنلاین</option>
                                <?php
                                foreach ($usertype as $type) {
                                    ?>
                                    <option value="<?php echo($type['id']); ?>"><?php echo($type['title']); ?> آنلاین</option>
                                    <?php
                                }
                                ?>
                            </select></div>
                    </div>
                    <div class="row">
                        <label><strong>اتاق:</strong></label>
                        <div>
                            <select name="room" class="search">
                                <option value="0">همه اتاق ها</option>
                                <?php
                                $query = db::query("SELECT `id`, `title` FROM {prefix}rooms");
                                while ($room = mysqli_fetch_array($query)) {
                                    echo("<option value=\"$room[0]\">$room[1]</option>");
                                }
                                mysqli_free_result($query);
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>نوع پیام:</strong></label>
                        <div>
                            <select name="type" class="search">
                                <option value="pm">خصوصی - پنجره جدید</option>
                                <option value="main" selected="selected">خصوصی - پنجره اصلی</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label for="color"><strong>رنگ متن:</strong></label>
                        <div>
                            <input type="color" name="color" id="color" style="width: auto;" />
                        </div>
                    </div>
                    <div class="row">
                        <label><strong>متن پیام:</strong></label>
                        <div>
                            <input name="text" type="text"   class="required" />
                        </div>
                    </div>
                </div>
            </form>
            <center> <button class="button blue flat block" onclick="send();">ارسال</button></center>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>