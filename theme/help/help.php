<?php check_session(); ?>
<!DOCTYPE HTML>
<html dir="rtl">
	<head>
		<title><?php echo(site_title); ?> | آموزش ها</title>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
        <link href="<?php echo(theme::url('theme/help/bootstrap.min.css')); ?>" rel="stylesheet">
		<link rel="stylesheet" href="<?php echo(theme::url('theme/help/bootstrap-rtl.min.css')); ?>">
		<link rel="stylesheet" href="<?php echo(theme::url('theme/help/help.css')); ?>">
		<link rel="stylesheet" href="<?php echo(theme::url('theme/fonts/fonts.css')); ?>">
	</head>
<body>
	<div class="navbar navbar-default" id="main_navbar">
        <div class="container">
		
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">منو</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo(url("")); ?>"><?php echo(site_title); ?></a>
          </div>
            <div class="navbar-collapse collapse" id="navbar">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="<?php echo(url("faq")); ?>" title="راهنمای امکانات">راهنمای امکانات</a></li>
                    <li class=""><a target="_blank" href="<?php echo(url("profile/")); ?>" title="پروفایل من">پروفایل من</a></li>
                    <li class=""><a target="_blank" href="<?php echo(url("contact")); ?>" title="تماس با ما">تماس با ما</a></li>
                </ul>
            </div>
        </div>
    </div>
	<div class="container">
		<div class="row first-row">
			<div class="col-lg-3">
				<div class="panel panel-primary">
					<div class="panel-heading"><i class="fa fa-user fa-2x"></i></div>
					<div class="panel-body"> <a href="#" data-toggle="modal" data-target=".bs-help-modal-1">عضویت در چت روم</a> </div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="panel panel-primary">
					<div class="panel-heading"><i class="fa fa-user-secret fa-2x"></i></div>
					<div class="panel-body"> <a href="#" data-toggle="modal" data-target=".bs-help-modal-2">ارسال پیام در خصوصی</a> </div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="panel panel-primary">
					<div class="panel-heading"><i class="fa fa-street-view fa-2x"></i></div>
					<div class="panel-body"> <a href="#" data-toggle="modal" data-target=".bs-help-modal-3">ارسال پیام در عمومی</a> </div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="panel panel-primary">
					<div class="panel-heading"><i class="fa fa-user-times fa-2x"></i></div>
					<div class="panel-body"> <a href="#" data-toggle="modal" data-target=".bs-help-modal-4">خروج از چت روم</a> </div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="hbox media">
				<div class="hbox-icon media-left alert-danger">
					<i class="fa fa-support fa-2x"></i>
				</div>
				<div class="hbox-content media-body">
					 <a href="#" data-toggle="modal" data-target=".bs-help-modal-5">چگونه دوستان خود را به لیست دوستان اضافه کنیم؟</a>
				</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="hbox media">
				<div class="hbox-icon media-left alert-danger">
					<i class="fa fa-support fa-2x"></i>
				</div>
				<div class="hbox-content media-body">
					 <a href="#" data-toggle="modal" data-target=".bs-help-modal-6">برای ارسال پیام در عمومی با مشکل امتیاز روبرو هستم</a>
				</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="hbox media">
				<div class="hbox-icon media-left alert-danger">
					<i class="fa fa-support fa-2x"></i>
				</div>
				<div class="hbox-content media-body">
					 <a href="#" data-toggle="modal" data-target=".bs-help-modal-7">چگونه مزاحمان را از پیام خصوصی مسدود سازیم</a>
				</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="hbox media">
				<div class="hbox-icon media-left alert-danger">
					<i class="fa fa-support fa-2x"></i>
				</div>
				<div class="hbox-content media-body">
					 <a href="#" data-toggle="modal" data-target=".bs-help-modal-8">برای ایجاد کنفرانس چگونه عمل کنیم؟</a>
				</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="hbox media">
				<div class="hbox-icon media-left alert-danger">
					<i class="fa fa-support fa-2x"></i>
				</div>
				<div class="hbox-content media-body">
					 <a href="#" data-toggle="modal" data-target=".bs-help-modal-9">چگونه پیام های مزاحمین را گزارش کنیم؟</a>
				</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="hbox media">
				<div class="hbox-icon media-left alert-danger">
					<i class="fa fa-support fa-2x"></i>
				</div>
				<div class="hbox-content media-body">
					 <a href="#" data-toggle="modal" data-target=".bs-help-modal-10">نام من تغییر کرده ، دلیل آن چیست؟</a>
				</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="hbox media">
				<div class="hbox-icon media-left alert-danger">
					<i class="fa fa-support fa-2x"></i>
				</div>
				<div class="hbox-content media-body">
					 <a href="#" data-toggle="modal" data-target=".bs-help-modal-11">چگونه میتوان پروفایل خود را ویرایش کرد؟</a>
				</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="hbox media">
				<div class="hbox-icon media-left alert-danger">
					<i class="fa fa-support fa-2x"></i>
				</div>
				<div class="hbox-content media-body">
					 <a href="#" data-toggle="modal" data-target=".bs-help-modal-12">چگونه میتوان از چت صوتی و تصویری استفاده کرد؟</a>
				</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="hbox media">
				<div class="hbox-icon media-left alert-danger">
					<i class="fa fa-support fa-2x"></i>
				</div>
				<div class="hbox-content media-body">
					 <a href="#" data-toggle="modal" data-target=".bs-help-modal-13">چگونه میتوان امتیاز خود را مشاهده کرد؟</a>
				</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="hbox media">
				<div class="hbox-icon media-left alert-danger">
					<i class="fa fa-support fa-2x"></i>
				</div>
				<div class="hbox-content media-body">
					 <a href="#" data-toggle="modal" data-target=".bs-help-modal-14">چگونه میتوان در عمومی لینک و عکس ارسال کرد؟</a>
				</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="hbox media">
				<div class="hbox-icon media-left alert-danger">
					<i class="fa fa-support fa-2x"></i>
				</div>
				<div class="hbox-content media-body">
					 <a href="#" data-toggle="modal" data-target=".bs-help-modal-15">چگونه میتوان در خصوصی فایل ارسال کرد؟</a>
				</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="hbox media">
				<div class="hbox-icon media-left alert-danger">
					<i class="fa fa-support fa-2x"></i>
				</div>
				<div class="hbox-content media-body">
					 <a href="#" data-toggle="modal" data-target=".bs-help-modal-16">من اخراج شده ام ، چه کنم؟</a>
				</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="hbox media">
				<div class="hbox-icon media-left alert-danger">
					<i class="fa fa-support fa-2x"></i>
				</div>
				<div class="hbox-content media-body">
					 <a href="#" data-toggle="modal" data-target=".bs-help-modal-17">چگونه رمز خود را تغییر دهم؟</a>
				</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="hbox media">
				<div class="hbox-icon media-left alert-danger">
					<i class="fa fa-support fa-2x"></i>
				</div>
				<div class="hbox-content media-body">
					 <a href="#" data-toggle="modal" data-target=".bs-help-modal-18">نکاتی در مورد خرید امتیاز و اتاق و امکانات ویژه</a>
				</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="hbox media">
				<div class="hbox-icon media-left alert-danger">
					<i class="fa fa-support fa-2x"></i>
				</div>
				<div class="hbox-content media-body">
					 <a href="#" data-toggle="modal" data-target=".bs-help-modal-19">نکاتی در مورد تنظیمات</a>
				</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="hbox media">
				<div class="hbox-icon media-left alert-danger">
					<i class="fa fa-support fa-2x"></i>
				</div>
				<div class="hbox-content media-body">
					 <a href="#" data-toggle="modal" data-target=".bs-help-modal-20">نکاتی در مورد کنفرانس</a>
				</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="hbox media">
				<div class="hbox-icon media-left alert-danger">
					<i class="fa fa-support fa-2x"></i>
				</div>
				<div class="hbox-content media-body">
					 <a href="#" data-toggle="modal" data-target=".bs-help-modal-21">چگونه امتیاز خریداری کنیم؟</a>
				</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="hbox media">
				<div class="hbox-icon media-left alert-danger">
					<i class="fa fa-support fa-2x"></i>
				</div>
				<div class="hbox-content media-body">
					 <a href="#" data-toggle="modal" data-target=".bs-help-modal-22">چگونه اتاق خریداری کنیم؟</a>
				</div>
				</div>
			</div>
		</div>
    </div>
	
	
	
	 

<!-- Modals -->

<div class="modal fade bs-help-modal-1" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
برای ثبت نام در چت روم کافی است مطابق تصویر زیر بر روی دکمه سبز رنگ "برای ثبت نام کلیک کنید" بالای لاست افراد آنلاین کلیک کرده.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/1.png')); ?>"><br><br></div>سپس در داخل دو فیلد رمز خود و تکرار آن را وارد نمونده و بر روی گزینه ذخیره کلیک کنید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/2.png')); ?>"><br></div><br>سپس صفحه خود را رفرش کرده و یا از خروج را زده و مجدداً وارد شوید.<br><br><br>همچنین میتوانید مطابق عکس زیر از روی دکمه چرخ دنده در فرم ارسال این کار را انجام دهید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/3.png')); ?>"> <br><br><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/4.png')); ?>"><br></div><br>
    </div>
  </div>
</div>


<div class="modal fade bs-help-modal-2" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
برای این کار کافیست بر روی نام کاربر کلیک کرده و مطابق عکس زیر بر روی یکی از دو گزینه پیام خصوصی در پنجره اصلی (عمومی) یا در پنجره جدید کلیک کنید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/5.png')); ?>"><br></div>
    </div>
  </div>
</div>


<div class="modal fade bs-help-modal-3" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
برای ارسال پیام در عمومی ابتدا باید امتیاز لازم را کسب کرده سپس میتوانید از فرم زیر در عمومی پیام ارسال کنید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/6.png')); ?>"><br><div style="text-align: right;"><br>نکات:<br>1- توجه داشته باشید در صورتی که امتیاز شما به میزان تعیین شده رسیده اما همچنان با اخطار آن مواجه بودید صفحه خود را رفرش کرده<br>2- برای رسیدن سریع تر به میزان امتیاز لازم میتوانید از ناظرین کمک بگیرید<br></div></div>
    </div>
  </div>
</div>


<div class="modal fade bs-help-modal-4" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
برای خروج از چت روم بر روی دکمه خروج در فرم ارسال کلیک کنید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/7.png')); ?>"><br></div><br>نکات:<br>1- بهتر است پس از تصمیم بر خروج از چت روم ، قبل از بستن صفحه بر روی دکمه خروج کلیک نمایید<br>
    </div>
  </div>
</div>


<div class="modal fade bs-help-modal-5" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
برای افزودن دوستان خود به لیست . ابتدا از لیست بر روی اسم آن ها کلیک کرده سپس بر روی "افزودن به لیست دوستان" کلیک کنید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/8.png')); ?>"><br><div style="text-align: right;"><br>همچنین میتوان از صفحه ارسال پیام خصوصی بر روی آیکن شماره 11 در تصویر زیر کلیک کنید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/14.png')); ?>"><br><br><div style="text-align: right;">شما میتوانید با کلیک بر روی آیکن چرخ دنده در فرم ارسال و کلیک بر روی "لیست دوستان من" آنها را مدیریت کنید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/15.png')); ?>"><br></div></div><br></div></div></div>
    </div>
  </div>
</div>


<div class="modal fade bs-help-modal-6" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
برای ارسال پیام در عمومی باید منتظر شوید تا امتیاز شما به حد تعیین شده برسد.<br><br>ضمن اینکه شما میتوانید از ناظرین یا معاونین یا مدیران برای رسیدن به حد نصاب امتیاز کمک بگیرید.<br>
    </div>
  </div>
</div>


<div class="modal fade bs-help-modal-7" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
برای مسدود سازی مزاحمان ابتدا بر روی نام انها در لیست کلیک کرد ، سپس بر روی گزینه "مسدود/آزاد سازی پیام ها" کلیک کنید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/11.png')); ?>"><br><div style="text-align: right;"><br>همچنین شما میتوانید از صفحه پیام خصوصی بر روی آیکن 4 تصویر زیر کلیک کرده و خصوصی شخص مورد نظر را مسدود کنید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/14.png')); ?>"><br><div style="text-align: right;"><br>ضمناً برای بستن کامل خصوصی خود میتوانید بر روی آیکن چرخ دنده در فرم ارسال کلیک و تیک آیتم "مشاهده پیام خصوصی" را بردارید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/10.png')); ?>"><br><br><div style="text-align: right;">نکات:<br>1- دوستان شما میتوانند در صورت بسته بودن پیام های خصوصی به شما پیام دهند.<br><br>شما میتوانید با کلیک بر روی آیکن چرخ دنده در فرم ارسال و کلیک بر روی "لیست مسدود شده ها" آنها را مدیریت کنید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/15.png')); ?>"><br></div></div></div></div></div></div></div>
    </div>
  </div>
</div>


<div class="modal fade bs-help-modal-8" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
برای ایجاد کنفرانس میبایست با کلیک بر روی نام کاربر در لیست یا صفحه پیام های عمومی بر گزینه "دعوت به اتاق کنفرانس" کلیک کرد.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/12.png')); ?>"><br><div style="text-align: right;"><br>همچنین میتوان در صفحه پیام خصوصی با کلیک بر روی آیکن شماره 10 تصویر زیر ، کاربر را به کنفرانس دعوت کرد.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/14.png')); ?>"><br><div style="text-align: right;"><br>توجه داشته باشید که فقط ایجاد کننده کنفرانس میتواند شخصی را به آن دعوت کند.<br></div></div></div></div>
    </div>
  </div>
</div>


<div class="modal fade bs-help-modal-9" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
برای این کار بر روی آیکون قرمز رنگ سنم چپ آواتار کاربران کلیک کنید و دکمه OK را انتخاب کنید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/16.png')); ?>"><br></div>
    </div>
  </div>
</div>


<div class="modal fade bs-help-modal-10" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
دلیل این امر این بوده که نام شما دارای لغات غیر مجاز بوده و توسط ناظرین ما به نام مناسب یا خودکار تغییر یافته است.<br>
    </div>
  </div>
</div>


<div class="modal fade bs-help-modal-11" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
برای ویرایش پروفایل خود ابتدا با کلیک بر روی آواتار خود و یا با کلیک بر روزی علامت مشخص شده در عکس زیر در کنار نام خود در لیست وارد پروفایل خود شوید .<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/17.png')); ?>"><br><div style="text-align: right;"><br>سپس بر روی دکمه ویرایش کلیک کنید.<br><br><div style="text-align: center;"><img class="img-responsive" style="width: 583px; height: 355px;" src="<?php echo(theme::url('theme/help/images/48.png')); ?>"><br><div style="text-align: right;"><br>با کلیک بر روی ویرایش وارد صفحه ای مانند زیر میشوید که در این صفحه میتوانید اطلاعات خود را وارد کنید.<br><br><div style="text-align: center;"><img class="img-responsive" style="width: 635px; height: 470px;" src="<?php echo(theme::url('theme/help/images/49.png')); ?>"><br></div><br>توجه داشته باشید که در این صفحه برای عوض کردن آواتار خود باید ابتدا بر روی آن کلیک کنید و سپس در کادر جدید تصویر مورد نظر خود را انتخاب کنید و آپلود نمایید.<br></div></div></div></div>

    </div>
  </div>
</div>


<div class="modal fade bs-help-modal-12" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
برای برقراری ارتباط صوتی و تصویری در چت از طریق چت خصوصی بر روی آیکن 6 مشخص شده در عکس کلیک کنید و سپس موارد مورد نیاز را تنظیم کنید.<br><br>
<div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/14.png')); ?>"><br><div style="text-align: right;"><br>در مواردی مانند کنفرانس با کلیک بر روی همین دکمه و تنظیم میکروفن و دوربین میتوانید با اعضای کنفرانس ارتباط صوتی و تصویری برقرار سازید.<br></div></div>
<div style="text-align: right;">نکات مهم :<br>
۱: مرورگر شما باید آپدیت باشد اگر آپدیت نیست از لینک های زیر میتوانید آخرین نسخه ارائه شده مرورگرها رو دانلود کنید<br>
* موزیلا فایرفاکس<img width="40" height="40" src="http://png.findicons.com/files/icons/783/mozilla_pack/128/firefox.png">: <a target="_blank" href="http://dl2.soft98.ir/soft/m/Mozilla.Firefox.37.0.2.EN.exe">لینک دانلود نسخه انگلیسی</a>&nbsp;&nbsp;<a target="_blank" href="http://dl2.soft98.ir/soft/m/Mozilla.Firefox.37.0.2.FA.exe">لینک دانلود نسخه فارسی</a><br>
* گوگل کروم<img width="40" height="40" src="http://png.findicons.com/files/icons/2344/faenza/48/google_chrome.png">: <a target="_blank" href="http://dl2.soft98.ir/soft/g/Google.Chrome.42.0.2311.135.x86.exe">لینک دانلود گوگل کروم</a><br>
* اپرا<img width="40" height="40" src="http://png.findicons.com/files/icons/1008/quiet/128/opera.png">: <a target="_blank" href="http://dl2.soft98.ir/soft/o/Opera.29.0.1795.47.exe">لینک دانلود اپرا</a><br>
۲: آخرین نسخه فلاش پلیر حتما رو مرورگرتون نصب باشد<br>
*فلاش پلیر<img width="40" height="40" src="http://png-2.findicons.com/files/icons/1757/isabi/128/flash_player.png">: <a target="_blank" href="http://dl2.soft98.ir/soft/a/Adobe.Flash.Player.17.0.0.169.firefox.exe">لینک دانلود فلاش پلیر</a><br>
<font color="red"><b>*تذکر: هنگام نصب فلاش پلیر حتما مرورگرتون باید بسته باشد</b></font><br><br>
۳: <b>اگر از موبایل استفاده میکنید مرورگرتان باید جزو ۳ مرورگر که در بالا ذکر شده است باشد !</b></font><br><hr>
<b>-آموزش استفاده کلی در مرورگر ها:</b><br>
۱: موزیلا فایرفاکس<img width="40" height="40" src="http://png.findicons.com/files/icons/783/mozilla_pack/128/firefox.png"><br>
<font color="red"><b>*تذکر: درصورتی که در مرحله 2 بر روی گزینه "وبکم و میکروفن ندارم" انتخاب کنید مرحله 4 برای شما نمایش داده نمیشود</b></font>
<img style="width: 583px; height: 355px;" class="img-responsive" src="<?php echo(theme::url('theme/help/images/webfirefox.png')); ?>"><br><hr>
۳: گوگل کروم<img width="40" height="40" src="http://png.findicons.com/files/icons/2344/faenza/48/google_chrome.png"><br>
<font color="red"><b>*تذکر: درصورتی که در مرحله 2 بر روی گزینه "وبکم و میکروفن ندارم" انتخاب کنید مرحله 4 برای شما نمایش داده نمیشود</b></font>
<img style="width: 583px; height: 355px;" class="img-responsive" src="<?php echo(theme::url('theme/help/images/webchrome.png')); ?>"><br><hr>
۳: اپرا<img width="40" height="40" src="http://png.findicons.com/files/icons/1008/quiet/128/opera.png"><br>
<font color="red"><b>*تذکر: درصورتی که در مرحله 2 بر روی گزینه "وبکم و میکروفن ندارم" انتخاب کنید مرحله 4 برای شما نمایش داده نمیشود</b></font>
<img style="width: 583px; height: 355px;" class="img-responsive" src="<?php echo(theme::url('theme/help/images/webopera.png')); ?>"><hr>
</div>
	</div>
  </div>
</div>


<div class="modal fade bs-help-modal-13" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
برای مشاهده امتیاز خود میتوانید بر روی دکمه مثبت سبز رنگ در فرم ارسال کلیک کرده.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/20.png')); ?>"><br></div><br>سپس بر روی آیتم اطلاعات من کلیک کنید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/19.png')); ?>"><br></div><br>در این بخش شما میتوانید امتیار بانک و امتیاز کاربری و ... خود را مشاهده کنید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/18.png')); ?>"><br></div>
    </div>
  </div>
</div>


<div class="modal fade bs-help-modal-14" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
برای این کار از طریق دکمه مثبت سبز رنگ در فرم ارسال ، بخش امکانات ویژه را باز کرده.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/20.png')); ?>"><br><div style="text-align: right;"><br>سپس در پنجره کوچک باز شده بر روی آیتم ارسال عکس و ارسال لینک کلیک کنید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/13.png')); ?>"><br></div><br>توجه داشته باشید که در صورت نداشتن امتیاز کافی به خرید آن اقدام کنید.<br></div></div>
    </div>
  </div>
</div>


<div class="modal fade bs-help-modal-15" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
برای ارسال فایل در خصوصی ابتدا بر روی دکمه شماره 9 در تصویر زیر کلیک کنید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/14.png')); ?>"><br></div><br>سپس در پنجره جدید فایل خود را انتخاب کرده و آپلود نمایید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/50.png')); ?>"><br></div><br>توجه داشته باشید که این امکان در پتجره کنفرانس نیز با همان آیکن فراهم است.<br>
    </div>
  </div>
</div>


<div class="modal fade bs-help-modal-16" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
در صورت اخراج از سایت میتوانید از طریف فرم تماس با ما با یکی از مدیران ارتباط بر قرار کرده و درخواست آزاد سازی نام خود را مطرح کنید.<br>
    </div>
  </div>
</div>


<div class="modal fade bs-help-modal-17" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
برای تغییر رمز خود ابتدا بر روی آیکن چرخ دنده در فرم ارسال کلیک کرده.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/25.png')); ?>"><br></div><br>سپس بر روی گزینه تغییر رمز کلیک کنید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/24.png')); ?>"><br></div><br>سپس رمز جدید خود را انتخاب و ذخیره کنید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/23.png')); ?>"><br></div>
    </div>
  </div>
</div>


<div class="modal fade bs-help-modal-18" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
امکانات ویژه شامل بخشی از چت روم است که با خرید امتیاز بانک میتوانید به امکانات بیشتری مانند ارسال لینک و عکس در عمومی خرید درجه و خرید اتاق دست یابید.<br><br>برای ورود به این بخش بر روی مثبت سبز رنگ در فرم ارسال کلیک کنید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/20.png')); ?>"><br></div><br>در این بخش شما با گزینه خرید امتیاز روبرو میشوید . شما میتوانید با انتخاب آن و انتخاب مبلغ و تایید خرید و ادامه خرید از بانک به امتیاز بانک دست پیدا کنید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/21.png')); ?>"><br><br><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/28.png')); ?>"><br></div><br>در صورت تمایل به خرید اتاق شما میتوانید بر روی آن کلیک کرده و با وارد کردن نام اتاق و سپس دسترسی مدیریت و سپس توضیحات خوش آمد گویی اتاق و در آخر رمز اتاق ، اتاق خود را بسازید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/26.png')); ?>"><br></div><br>همچنین شما میتوانید درجه مورد نظر خود را خریداری کنید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/27.png')); ?>"><br></div>
    </div>
  </div>
</div>


<div class="modal fade bs-help-modal-19" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
بخش تنظیمات شامل امکاناتی است که در بخش مربوط به دکمه چرخ دنده در فرم ارسال تعبیه گردیده است.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/25.png')); ?>"><br></div><br>در این بخش امکاناتی شامل تصاویر زیر دیده میشود. <br><div style="text-align: center;"><br><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/22.png')); ?>"><br><br><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/30.png')); ?>"><br><br><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/29.png')); ?>"><br></div><br><br>
    </div>
  </div>
</div>


<div class="modal fade bs-help-modal-20" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
برای ورود به کنفرانس یا باید خود ایجاد کننده آن باشید و یا به آن دعوت شده باشید. در صورت دعوت تصاویری مانند زیر را مشاهده میکنید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/31.png')); ?>"><br><br><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/32.png')); ?>"><br></div><br>صفحه کنفرانس که به شرح زیر شکل زیر است شامل دکمه های به ترتیب از چپ به راست : 1-ارسال 2-شکلک ها 3- پاک کن 4-چت صوتی و تصویری 5-ارسال فایل 6-اطلاعات کنفرانس خواهد بود.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/33.png')); ?>"><br></div><br>در بخش چت صوتی و تصویری ، نمایی مانند زیر را مشاهده میکنید که پس از تنظیم میتوانید از آن استفاده کنید.<br><br><div style="text-align: center;"><img class="img-responsive" style="width: 601px; height: 356px;" src="<?php echo(theme::url('theme/help/images/35.png')); ?>"><br></div><br>در بخش اطلاعات تصویری مانند زیر را مشاهده میکنید که شامل اطلاعات ایجاد کننده کنفرانس و کاربران حاضر در کنفرانس و البته گزینه خروج از کنفرانس است.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/34.png')); ?>"><br></div><br>در صورتی که شما ایجاد کننده کنفرانس باشید ، میتوانید کاربران حاضر در کنفرانس را مدیریت کنید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/47.png')); ?>"><br></div>    </div>
  </div>
</div>


<div class="modal fade bs-help-modal-21" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
برای خرید امتیاز ابتدا بر روی علامت مثبت سبز رنگ در فرم ارسال کلیک کنید. سپس گزینه خرید امتیاز را انتخاب کنید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/40.png')); ?>"><br><br><div style="text-align: right;">سپس صفحه ای مطابق زیر را مشاهده میکنید . در این صفحه مبلغ مورد نظر خود را وارد کنید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/36.png')); ?>"><br></div><br>سپس با کلیک بر روی گزینه ارسال به صفحه بعد که تایید خرید است منتقل میشوید .<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/37.png')); ?>"><br></div><br>در این مرحله شما با کلیک بر روی گزینه پرداخت به صفحه پرداخت اینترنتی درگاه بانکی منتقل خواهید شد.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/38.png')); ?>"><br></div></div></div><br>با وارد کردن اطلاعات بانکی خود و کلیک بر روی دکمه پرداخت به صفحه تایید منتقل شده و امتیاز به بانک شما افزوده خواهد شد.<br><br>نکته : درگاه بانکی مرتبط به شبکه الکترونیکی شاپرک بوده و صحت آن را میتوانید بررسی کنید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/39.png')); ?>"><br></div>
    </div>
  </div>
</div>


<div class="modal fade bs-help-modal-22" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
برای ساخت اتاق ابتدا بر روی مثبت سبز رنگ در فرم ارسال کلیک کرده سپس آیتم خرید اتاق را انتخاب کنید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/46.png')); ?>"><br></div><br>سپس با پیغامی مطابق زیر روبرو میشوید<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/41.png')); ?>"><br></div><br>در این بخش شما باید نام اتاق خود را انتخاب کنید و سپس بر روی اوکی کلیک کنید.<br><br>در مرحه بعدی با پیغامی مانند تصویر زیر روبرو میشوید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/42.png')); ?>"><br></div><br>در صورت وارد کردن عدد 1 و کلیک بر روی اوکی شما میتوانید کاربران داخل اتاق را مدیریت کنید . اما در صورتی که عدد 0 را وارد کنید این امکان برای شما فراهم نخواهد شد.<br><br>پس از این مرحله با پیغامی مانند زیر روبرو میشوید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/43.png')); ?>"><br></div><br>در این بخش باید توضیحات اتاق خود را وارد کنید . توضیحات وارد شده در این بخش در بالای اتاق شما به عنوان پیام خوش آمد گویی قرار خواهد گرفت.<br><br>پس از این مرحله به مرحله انتخاب رمز خواهید رسید.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/44.png')); ?>"><br></div><br>در صورتی که تمایل به رمز دار بودن اتاق خود دارید میتوانید در این مرحله برای اتاق خود رمزی را انتخاب کنید.<br><br>در مرحه آخر در صورتی که موجودی بانک شما کافی باشد اتاق شما ساخته خواهد شد و در غیر این صورت با پیغام زیر روبرو خواهید شد.<br><br><div style="text-align: center;"><img class="img-responsive" src="<?php echo(theme::url('theme/help/images/45.png')); ?>"><br></div><br>
    </div>
  </div>
</div>
	
	
	<!-- JavaScript -->
	<script src="<?php echo theme::url('theme/help/jquery.js');?>"></script>
	<script src="<?php echo theme::url('theme/microblog/js/bootstrap.min.js');?>"></script>
	
</body>
</html>
