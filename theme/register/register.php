<!DOCTYPE html>
<html>
    <head>
        <title>عضویت در <?php echo $options['chat_site_title']; ?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
        <link type="text/css" rel="stylesheet" href="<?php echo url("theme/fonts/fonts.css"); ?>">
        <link type="text/css" rel="stylesheet" href="<?php echo(theme::url('css/register.css', $options['chat_register_theme'])); ?>">
        <script src="<?php echo url("theme/js/jquery.js"); ?>"></script>
        <script>
            $(document).ready(function () {
                $('input[name=activation]').click(function () {
                    $val = $(this).val();
                    if ($val == 'email') {
                        $('#registermsg-0, #registerbox-1').text('ایمیلی');
                    } else if ($val == 'sms') {
                        $('#registermsg-0').text('شماره تلفن همراهی');
                        $('#registermsg-1').text('پیامکی');
                    }
                });
                $('#register').submit(function (e) {
                    e.preventDefault();
                    var $b = $('button', this);
                    $b.attr('disabled', true);
                    $.ajax({
                        type: 'post',
                        url: '<?php echo url('ajax/register'); ?>',
                        data: $(this).serialize(),
                        dataType: 'json',
                        success: function (data) {
                            if (data.status) {
                                $('.registercontiner').slideUp(500, function () {
                                    $('.confrimcontiner').slideDown(500);
                                });
                            } else {
                                switch (data.error) {
                                    case('testcockie'):
                                        alert('لطفا صفحه خود را رفرش کنید');
                                        break;
                                    case('proxyblock'):
                                        alert('استفاده از فیلتر شکن مجاز نیست');
                                        break;
                                    case('username'):
                                        alert('لطفا نام کاربری وارد کنید');
                                        break;
                                    case('badname'):
                                        alert('این نام کاربری مجاز نیست');
                                        break;
                                    case('runningname'):
                                        alert('این نام کاربری متعلق به کاربر دیگری است');
                                        break;
                                    case('password'):
                                        alert('لطفا کلمه عبور را وارد کنید');
                                        break;
                                    case('repeatpassword'):
                                        alert('تکرار کلمه عبور صحیح نمیباشد');
                                        break;
                                    case('firstname'):
                                        alert('لطفا نام کوچک خود را وارد کنید');
                                        break;
                                    case('lastname'):
                                        alert('لطفا نام خانوادگی خود را وارد کنید');
                                        break;
                                    case('cellphone'):
                                        alert('لطفا تلفن همراه خود را وارد کنید');
                                        break;
                                    case('recellphone'):
                                        alert('این تلفن همراه متعلق به کاربر دیگری است');
                                        break;
                                    case('state'):
                                        alert('لطفا استان خود را وارد کنید');
                                        break;
                                    case('city'):
                                        alert('لطفاشهر خود را وارد کنید');
                                        break;
                                    case('gender'):
                                        alert('جنسیت خود را مشخص کنید');
                                        break;
                                    case('cannotsms'):
                                        alert('در حال حاضر عضویت به علت عدم توان ارسال پیامک غیرفعال است');
                                        break;
                                    case('cannotmail'):
                                        alert('درحال حاضر ما توان ارسال ایمیل نداریم.لطفا از روش پیامک استفاده کنید');
                                        break;
                                    case('every24'):
                                        alert('ثبت نام هر 24 ساعت فقط یک بار امکان پذیر است');
                                        break;
                                    case('email'):
                                        alert('لطفا ایمیل خود را وارد کنید');
                                        break;
                                    case('remail'):
                                        alert('این ایمیل قبلا ثبت شده');
                                        break;
                                    case('cannotmail'):
                                        alert('درحال حاضر ما توان ارسال ایمیل نداریم.لطفا از روش پیامک استفاده کنید');
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
                        url: '<?php echo url('ajax/register.confrim'); ?>',
                        data: $(this).serialize(),
                        dataType: 'json',
                        success: function (data) {
                            if (data.status) {
                                window.location.href = '<?php echo url('chat'); ?>';
                            } else {
                                switch (data.error) {
                                    case('invalid'):
                                        alert('کد معتبر نیست');
                                        break;
                                    case('code'):
                                        alert('لطفا کد فعال سازی را وارد کنید');
                                        break;
                                    case('timeout'):
                                        alert('زمان تائید عضویت بیش از حد طول کشیدولطفا دوباره امتحان کنید');
                                        break;
                                    case('register'):
                                        alert('لطفا ابتدا عضو بشوید');
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
        <div class="content registercontiner"<?php
        if (isset($register) and $register) {
            echo(' style="display:none"');
        }
        ?>>
            <h1>عضویت در چتروم</h1>
            <form id="register">
                <div class="row"><input name="username" value="" placeholder="نام کاربری" class="form-control" type="text"></div>
                <div class="row">
                    <div class="half"><input name="firstname" value="" placeholder="نام کوچک" class="form-control" type="text"></div>
                    <div class="half"><input name="lastname" value="" placeholder="نام خانوادگی" class="form-control" type="text"></div>
                </div>
                <div class="row">
                    <div class="half"><input name="pwd" value="" placeholder="کلمه عبور" class="form-control" type="password"></div>
                    <div class="half"><input name="repwd" value="" placeholder="تکرار کلمه عبور" class="form-control" type="password"></div>
                </div>
                <div class="row">
                    <div class="half"><input name="cellphone" value="" placeholder="تلفن همراه" class="form-control" type="text"></div>
                    <div class="half"><input name="email" value="" placeholder="ایمیل" class="form-control" type="text"></div>
                </div>
                <div class="row">
                    <div class="half"><input name="state" value="" placeholder="استان محل زندگی" class="form-control" type="text"></div>
                    <div class="half"><input name="city" value="" placeholder="شهر محل زندگی" class="form-control" type="text"></div>
                </div>
                <div class="row">
                    <div class="half">جنسیت:</div>
                    <div class="half">
                        <label for="gender-1">مذکر</label><input type="radio" name="gender" value="1" id="gender-1" />
                        <label for="gender-2">مونث</label><input type="radio" name="gender" value="2" id="gender-2" />
                    </div>
                </div>
                <div class="row">
                    <div class="half">شیوه فعال سازی:</div>
                    <div class="half"><label>ایمیل<input name="activation" id="activation_mail" value="email" checked="checked" type="radio"></label>
                        <label>پیامک<input id="activation_sms" name="activation" value="sms" type="radio"></label>
                    </div>
                </div>
                <div class="row">شما با کلیک بر روی گزینه ثبت نام تمامی قوانین جمهوری اسلامی ایران و جرائم رایانه ای را پذیرفته اید وما برای تائید اطلاعاتتان،به 
                    <span id="registermsg-0">ایمیلی</span> که در فرم بالا وارد نمودید پیامی ارسال میکنیم که در مرحله بعدی از آن استفاده میکنیم در طرف مقابل صاحبان این پایگاه گفتگو به حقوق حریم خصوصی پایبندند و اطلاعات شمارا محفوظ نگه میدارند و بدون حکم قضایی آن هارا در دسترسی شخص ثالثی قرار نخواهند داد.
                </div>
                <div class="row">
                    <button type="submit" class="btn">ثبت نام</button>
                </div>
            </form>
        </div>
        <div class="content confrimcontiner"<?php
        if (!isset($register) or ! $register) {
            echo(' style="display:none"');
        }
        ?>>
            <h1>تایید عضویت</h1>
            <form id="confrim">
                <div class="row"><input name="code" value="" placeholder="کد فعال سازی" class="form-control" type="text"></div>
                <div class="row">ما برای شما <span id="registerbox-1">ایمیلی</span> ارسال کردیم و کدی برای فعال سازی نام کاربریتان در آن ذکر نمودیم.لطفا آن کد را در اینجا وارد کنید.درصورتی که پیامی دریافت نکردید 5 دقیقه صبر کنید و بعد از 5 دقیقه شما مجاز به ثبت نام دوباره خواهید شد.</div>
                <div class="row">
                    <button type="submit" class="btn">فعال سازی</button>
                </div>
            </form>
        </div>
		
    </body>
</html>
