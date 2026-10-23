<!DOCTYPE html>
<html>
    <head>
        <title>بازیابی رمز عبور در <?php echo $options['chat_site_title']; ?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
        <link type="text/css" rel="stylesheet" href="<?php echo url("theme/fonts/fonts.css"); ?>">
        <link type="text/css" rel="stylesheet" href="<?php echo(theme::url('css/register.css', $options['chat_register_theme'])); ?>">
        <script src="<?php echo url("theme/js/jquery.js"); ?>"></script>
        <script>
            $(document).ready(function () {
                $('#resetpw').submit(function (e) {
                    e.preventDefault();
                    var $b = $('button', this);
                    $b.attr('disabled', true);
                    $.ajax({
                        type: 'post',
                        url: '<?php echo url('ajax/resetpw'); ?>',
                        data: $(this).serialize(),
                        dataType: 'json',
                        success: function (data) {
                            if (data.status) {
                                $('.resetpwcontiner').slideUp(500, function () {
                                    $('.confrimcontiner').slideDown(500);
                                });

                            } else {
                                switch (data.error) {
                                    case('limitime'):
                                    case('limitime-user'):
                                        alert('محدودیت زمانی بین دو درخواست بازیابی رمز عبور 5 ساعت برای هر کاربر است.');
                                        break;
                                    case('proxyblock'):
                                        alert('استفاده از فیلتر شکن مجاز نیست');
                                        break;
                                    case('nowaytomsg'):
                                        alert('شما اطلاعاتی برای بازیابی رمز عبور در اختیار ما قرار ندادید');
                                        break;
                                    case('cellphone'):
                                        $('#halfcell').show();
                                        $('#halfmail').hide();
                                        break;
                                    case('email'):
                                        $('#halfmail').show();
                                        $('#halfcell').hide();
                                        break;
                                    case('invalidemail'):
                                        alert('ایمیل وارد شده صحیح نیست');
                                        break;
                                    case('wrongemail'):
                                        alert('ایمیل وارد شده با ایمیل کاربر مطابقت ندارد');
                                        break;
                                    case('invalidphonenumber'):
                                        alert('شماره تلفن همراه کاربر صحیح نیست');
                                        break;
                                    case('404user'):
                                        alert('این کاربر ثبت نام نکرده است');
                                        break;
                                    case('registerinfomations'):
                                        alert('این کاربر شماره تلفن همراه خود را در اختیار ما قرار نداده است');
                                        break;
                                    case('cannotsms'):
                                        alert('ما در حال حاضر توان ارسال پیامک را نداریم.لطفا بعدا امتحان کنید');
                                        break;
                                    case('cannotmail'):
                                        alert('ما در حال حاضر توان ارسال ایمیل را نداریم.لطفا بعدا امتحان کنید');
                                        break;
                                }

                            }

                        },
                        error: function () {

                        },
                        complete: function () {
                            $b.attr('disabled', false);
                        }

                    });

                });
                $('#confrim').submit(function (e) {
                    e.preventDefault();
                    var $b = $('button', this);
                    $b.attr('disabled', true);
                    $.ajax({
                        type: 'post',
                        url: '<?php echo url('ajax/resetpw.confrim'); ?>',
                        data: $(this).serialize(),
                        dataType: 'json',
                        success: function (data) {
                            if (data.status) {
                                window.location.href = '<?php echo url(); ?>';

                            } else {
                                switch (data.error) {
                                    case('noresetpwcookie'):
                                    case('noresetpw'):
                                        alert('ما جزئیات درخواست بازیابی کلمه عبور شمارا پیدا نمیکنیم.لطفا دوباره امتحان کنید');
                                        break;
                                    case('changeip'):
                                        alert('آی پی شما در حین ارسال این درخواست تغییر کرده.لطفا دوباره از نو تلاش کنید');
                                        break;
                                    case('404user'):
                                        alert('ما این کاربر را پیدا نمیکنیم');
                                        break;
                                    case('registerinfomations'):
                                        alert('ما اطلاعات عضویت این کاربر را پیدا نمیکنیم');
                                        break;
                                    case('wrongcode'):
                                        alert('کد وارد شده صحیح نمیباشد');
                                        break;
                                    case('cannotsms'):
                                        alert('ارسال کلمه عبور جدید به دلیل عدم توان ارسال پیامک امکان پذیر نیست.لطفا بعدا امتحان کنید');
                                        break;
                                    case('cannotmail'):
                                        alert('ما در حال حاضر توان ارسال ایمیل را نداریم.لطفا بعدا امتحان کنید');
                                        break;
                                }

                            }

                        },
                        error: function () {

                        },
                        complete: function () {
                            $b.attr('disabled', false);
                        }

                    });
                });
            });


        </script>
    </head>
    <body>
        <div class="content resetpwcontiner"<?php
        if (isset($resetpw) and $resetpw) {
            echo(' style="display:none"');
        }
        ?>>
            <h1>بازیابی کلمه عبور</h1>
            <form id="resetpw">
                <div class="row">
                    <div class="half"><input name="username" value="" placeholder="نام کاربری" class="form-control" type="text"></div>
                    <div class="half" id="halfcell" style="display:none"><input name="cellphone" value="" placeholder="تلفن همراه" class="form-control" type="text"></div>
                    <div class="half" id="halfmail" style="display:none"><input name="email" value="" placeholder="ایمیل" class="form-control" type="email"></div>

                </div>
                <div class="row">ما ابتدا از طریق ارسال پیامی هویت شمارا تائید و در مرحله دوم کلمه عبور را برایتان ارسال میکنیم.</div>
                <div class="row">
                    <button type="submit" class="btn">بازیابی</button>
                </div>
            </form>
        </div>
        <div class="content confrimcontiner"<?php
        if (!isset($resetpw) or ! $resetpw) {
            echo(' style="display:none"');
        }
        ?>>
            <h1>تایید عضویت</h1>
            <form id="confrim">
                <div class="row"><input name="code" value="" placeholder="کد فعال سازی" class="form-control" type="text"></div>
                <div class="row">ما برای شما پیامی ارسال کردیم تا مطمئن شویم شما خودتان هستید!با وارد کردن کدی که در آن ذکر کردیم کلمه عبور جدید برای شما ارسال خواهد شد.</div>
                <div class="row">
                    <button type="submit" class="btn">فعال سازی</button>
                </div>
            </form>
        </div>
		
    </body>
</html>
