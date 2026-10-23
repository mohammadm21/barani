<?php
include('header.php');
include('sidebar.php');
?>
<script type="text/javascript">
    function add() {
        var $permission = '';
        var $typepermission = $('input[name=typepermission]:checked').val();
        if ($typepermission == 'all') {
            $permission = 'all';
        } else {
            var $options = $('.dualselect .right select option');
            $permission = '';
            for (i = 0; i != $options.length; i++) {
                if ($permission != '')
                    $permission += ',';
                $permission += $($options[i]).attr('name');
            }
        }
        var $form = $('#formaddadmin');
        var data = {
            'token': '<?php echo(session('changepw')); ?>',
            'password': $('input[name=password]', $form).val(),
            'user': $('select[name=user]', $form).val(),
            'permission': $permission
        };
        $.ajax({
            type: "POST",
            url: "<?php echo(url('ajax/admin.adminpanel.add')); ?>",
            data: data,
            success: function (data) {
                switch (data) {
                    case('ok'):
                        $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'ذخیره شد'});
                        break;
                    case('user'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'این کاربر وجود ندارد'});
                        break;
                    case('emptypw'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'رمز جداگانه اختصاص داده نشده است'});
                        break;
                    case('noadminstrator'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'سطح کاربری این کاربر لازم نیست'});
                        break;
                    case('repeatuser'):
                        $.msgGrowl({type: 'warning', title: 'خطا', text: 'دسترسی این کاربر قبلا ساخته شده است'});
                        break;
                    default:
                        alert(data);
                        break;
                }
            }
        });
    }
    $(document).ready(function () {
        $('.radiobutton').click(function () {
            var $val = $('input[name=typepermission]:checked').val();
            if ($val == 'all') {
                $('#customList').slideUp(1000);
            } else {
                $('#customList').slideDown(1000);
            }

        });
        var $val = $('input[name=typepermission]:checked').val();
        if ($val == 'all') {
            $('#customList').slideUp(1000);
        } else {
            $('#customList').slideDown(1000);
        }
    });
</script>
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">افزودن دسترسی</h1>
    <div class="grid_12">
        <div class="grid_12">
            <form class="box  validate" id="formaddadmin">
                <div class="header">
                    <h2>افزودن دسترسی</h2>
                </div>
                <div class="content tabbed">
                    <div class="row">
                        <label><strong>کاربر:</strong></label>
                        <div>
                            <select name="user" class="search">
                                <?php
                                $admins = implode(',', Users::AdminsType());
                                $query = db::query("SELECT `id`, `username`, `name` FROM {prefix}users WHERE `type` IN ($admins) ORDER BY `type` DESC");
                                while ($user = mysqli_fetch_array($query)) {
                                    if (db::num('adminpanel', "`user`='{$user['id']}'") > 0)
                                        continue;
                                    ?>
                                    <option value="<?php echo($user['id']); ?>"> <?php echo($user['name'] . ' => ' . $user['username']); ?></option>
                                    <?php
                                }
                                mysqli_free_result($query);
                                ?>
                            </select>

                        </div>
                    </div>
                    <div class="row">
                        <label><strong>رمز جداگانه:</strong></label>
                        <div><input id="password" type="password" name="password" value=""></div>
                   <?php
               
                      if ( empty($_POST["password"]) )
                       {
                           echo '<div>فیلد رمز جداگانه پنل نباید خالی باشد . </div> ';
                           echo '
                               <style type="text/css" >
    
                              #password
                                    {
                                background:#FCC;
                                 }
                              </style>
                                  ';
                       }
                    
                    ?>

                    </div>
                    <div class="row">
                        <label><strong>دسترسی ها:</strong></label>
                        <div>
                            همه دسترسی ها: <input type="radio" name="typepermission" value="all"   />
                            <br />
                            سفارشی: <input type="radio" name="typepermission" value="custom" checked  />
                            <br />
                            <div id="customList">
                                <select multiple class="dualselects" data-size="small" id="permissions">
                                    <?php
                                    $permissions = explode(',', $admin['permissions']);
                                    foreach (adminpanel::$Permissions as $permission => $name) {
                                        ?>
                                        <option  name="<?php echo($permission); ?>"><?php echo($name); ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <center> <button name="btn" class="button blue flat block" onclick="add();">اضافه کردن</button></center>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>