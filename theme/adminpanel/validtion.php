<?php include('header.php'); include('sidebar.php'); ?>
<script>
var $submitok= false;
$(document).ready(function(){
    $("#step1_dialog").dialog({
        autoOpen:true,
        modal:true,
        width:500,
        buttons:[{text:"ادامه میدهم",click:function(){$(this).dialog("close");}}],
        close: function( ) {
            $("#step2_dialog").dialog("open");
            
        }
    });
    $("#success_dialog").dialog({
        autoOpen:false,
        modal:true,
        width:500,
        buttons:[{text:"متوجه شدم",click:function(){$(this).dialog("close");}}]
    });
    $("#resend_dialog").dialog({
        autoOpen:false,
        modal:true,
        width:500,
        buttons:[
            {text:"ادامه میدهم",click:function(){
                $('#resend').hide();
                $(this).html('<p>کمی صبر کنید...</p>').dialog({'buttons':[]});
                $.ajax({
                    url:'<?php echo(url('ajax/admin.dchat.validtion.resend')); ?>',
                    success: function(data){
                        switch(data){
                            case('ok'):$.msgGrowl({type: 'success', title: 'ارسال شد', text: 'ما پیامکی حاوی کد فعال سازی برایتان ارسال کردیم'});$("#resend_dialog").dialog("close");break;
                            case('cannot'):$.msgGrowl({type: 'success', title: 'ارسال نشد', text: 'ما اکنون توان ارسال پیامک را نداریم'});$("#resend_dialog").dialog("close");break;
                            case('time'):$.msgGrowl({type: 'success', title: 'ارسال نشد', text: 'ما فقط 2 بار پیامک را برای شما ارسال میکنیم'});$("#resend_dialog").dialog("close");break;
                            default:$.msgGrowl({type: 'success', title: 'پاسخ سرور'});$("#resend_dialog").dialog("close");break;
                        }
                    },
                    error:function(){
                        $.msgGrowl({type: 'success', title: 'ارسال نشد', text: 'ما اکنون توان ارسال پیامک را نداریم'});
                        $("#resend_dialog").dialog("close");
                    }
                })
            }},{text:"نیازی نیست!",click:function(){
                $("#resend_dialog").dialog("close");
                }
            }
        ]
    });
    $("#step2_dialog").dialog({
        autoOpen:false,
        modal:true,
        width:500,
        buttons:[{text:"بزن بریم!",click:function(){$(this).dialog("close");}}]
    });
    $('#resend').click(function(){
        $("#resend_dialog").dialog("open");
        
    });
    $('#wiz').wizard();
    $('.finish').click(function(){
        if($submitok){
            $.ajax({
                type:'post',
	           url:'<?php echo(url('ajax/admin.dchat.validtion')); ?>',
               data:$('form#wiz').serialize(),
               success:function(data){
                    switch(data){
                        case('ok'):$("#success_dialog").dialog('open'); break;
                        case('firstname'):$.msgGrowl({type: 'success', title: 'خطا', text: 'نام کوچک خود را وارد کنید'});break;
                        case('lastname'):$.msgGrowl({type: 'success', title: 'خطا', text: 'نام خانوادگی خود را وارد کنید'});break;
                        case('email'):$.msgGrowl({type: 'success', title: 'خطا', text: 'ایمیل معتبری وارد کنید'});break;
                        case('password'):$.msgGrowl({type: 'success', title: 'خطا', text: 'کلمه عبور مستحکمی وارد کنید'});break;
                        case('city'):$.msgGrowl({type: 'success', title: 'خطا', text: 'شهرتان وارد نشده'});break;
                        case('address'):$.msgGrowl({type: 'success', title: 'خطا', text: 'ادرستان وارد نشده'});break;
                        case('zip'):$.msgGrowl({type: 'success', title: 'خطا', text: 'کدپستی را لطفا وارد کنید'});break;
                        case('phone'):$.msgGrowl({type: 'success', title: 'خطا', text: 'تلفن ثابت شما هنوز وارد نشده'});break;
                        case('activecode'):$.msgGrowl({type: 'success', title: 'خطا', text: 'کد فعال سازی وارد شده معتبر نیست'});break;
                        default:$.msgGrowl({type: 'success', title: 'پاسخ سرور'});break;
                    }
               }   
	       });
            return false;            
                    
        }
            
    })    
});
</script>
<div style="display: none;" id="step1_dialog" title="فعال سازی پنل مدیریت">
    <p>عذرمیخواهیم بابت این واقعه اما ما مجبور هستیم تا بدانیم مدیر این چت روم کیست؟!</p>
    <p>البته این فقط برای شما نیست و تمامی مشتریان خود را از این طریق شناسایی میکنیم.ما قوانین کاری خود را تغییر دادیم و طبق این قوانین لازم است بدانیم کسانی که با ما کار میکنند چه مشخصاتی دارند!</p>
    <p>به همین دلیل پنل مدیریت چترومتان را غیر فعال کرده ایم تا قبل از هر کار دیگه ای از شما اطلاعاتی که به آن نیاز داریم دریافت کنیم و ما قبل از این به دلیل شناسایی شما از شریک ها و دوستانتان که به پنل دسترسی دارند یک پیامک به تلفن همراهتان ارسال کردیم و کدی را ذکر کرده ایم تا به وسیله آن مطمئنمان کنید که شما خودتان هستید!</p>
    <p>اصلا جای نگرانی نیست،فقط لازم است این کادر را ببندید و در پشت این پنجره کد فعال سازی را وارد کنید.بعد از آن ما اطلاعات ساده ای از شما دریافت میکنیم و در آخر همه این هارا برایمان با یک کلیک ارسال کنید</p>
    <p>قول میدهیم کل این کار ها 5 دقیقه از وقتتان هم نگیرد!</p>
</div>
<div style="display: none;" id="step2_dialog" title="تذکر ها">
    <p>حریم خصوصی شما را درک خواهیم کرد پس بدانید تمامی اطلاعات شما بصورت حفاظت شده در مکان امنی قرار میدهیم که امنیت آن را تامین کنیم</p>
    <p>از شما هم خواهش میکنیم اطلاعات را به زبان شیرین فارسی بنویسید و صداقت را رعایت کنید.ما بلافاصله بعد از دریافت این ها پنل شمارا فعال میکنیم ولی اگر اپراتورهایمان تشخیص دهند که کاسه ای زیر نیم کاسه است قول نمیدهیم که همکاری خود را با شما ادامه دهیم</p>
    <p>لازم است که قبل از بروز مشکل هشدارهایمان را بدهیم.تلفن همراه و ایمیل شما تنها راهی است که ما شما را میتوانیم به درستی تشخیص دهیم.هر اتفاقی بیوفتد ما از این دو وسیله برای برقراری ارتباط با شما استفاده میکنیم پس سعی کنید تلفن همراه خود را همیشه در دسترس قرار دهید و ایمیلی را به ما معرفی کنید که همیشه به آن دسترسی داشته باشید.</p>
    <p>و بالاخره بدانید که دی چت خانه تکانی را شروع کرده و در سومین قدم خود سایت خود را راه اندازی کرده.با کلیک بر روی <a href="">اینجا</a> اگر دوست دارید اطلاعیه ای در این زمینه را مطالعه فرمائید.</p>
</div>
<div style="display: none;" id="resend_dialog" title="ارسال مجدد کد فعال سازی">
    <p>ما شماره تلفن همراه تمامی مشتریان خود را داریم و برای آنان پیامک ارسال کرده ایم اما اگر شما این پیام را دریافت نکرده اید میتوانید دلیلش آن باشد که تلفنتان در دسترس شبکه نبوده یا حتی ممکن است دریافت پیامک را از سامانه ی ما مسدود کرده اید.</p>
    <p>ولی ما به شما اجازه میدهیم فقط 2 مرتبه دیگه درخواست کنید که این کد برای شما ارسال شود و اگر بعد از این تلاش ها باز هم چیزی دریافت نکردید چاره آن است که با تیم دی چت از طریق ایمیل یا تماس تلفنی ارتباط برقرار کنید و مسئله را با ما درمیان بگذارید.</p>
</div>
<div style="display: none;" id="success_dialog" title="فعال سازی پنل مدیریت">
    <p>تمام شد!به همین سادگی...!ما هم اکنون برای شما در سایتمان اکانتی ساخته ایم و امکانات را برای مدیریت بهتر چت رومتان قرار داده ایم.</p>
    <p>توجه کنید که اکانت شما در سایت ما و هر آنجه به آن مربوط میشود کاملا مربوط به صاحب اصلی چتروم است و مسئولیت هر کاری در آن با شخص شماست!</p>
    <p>ما از قبل اینکه این کار هارا انجام دهیم مشغول اماده سازی قدم های بعدیمان شده ایم و تلاش خود را برای هرچه بهتر شدن از قبل هرگز کاهش نمیدهیم.</p>
</div>
<section id="content" class="container_12 clearfix" data-sort=true>
    <div class="grid_12">
    	<form class="box wizard manual validate" id="wiz" >
    		<div class="header">
    			<h2>
    				<img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/ui-tab--arrow.png')); ?>" class="icon">
    				فعال سازی پنل مدیریت
    			</h2>
    		</div>
    		<div class="content">
    			<ul class="steps">
    				<li>
    					<a class="current" href="#wiz_valid">فعال سازی</a>
    				</li>
    				<li>
    					<a href="#wiz_singup">دریافت اطلاعات</a>
    				</li>
    			</ul>
    			<fieldset id="wiz_valid">
    				<div class="row">
    					<label for="w1_activecode">
    						<strong>
    							کد فعال سازی:
    						</strong>
    						<small>
    							برای مدیراصلی جتروم ارسال شده
    						</small>
    					</label>
    					<div>
    						<input type="text" class="required" pattern="^\d{5}$" name="activecode" id="w1_activecode" />
    					</div>
    				</div>
    			</fieldset>
    			<fieldset id="wiz_singup">
    				<div class="row">
    					<label for="w1_email">
    						<strong>ایمیل شما:</strong>
    					</label>
    					<div>
    						<input type="text" class="required" email="true" name="email" id=w1_email />
    					</div>
    				</div>
    				<div class="row">
    					<label>
    						<strong>کلمه عبور</strong>
                            <small>مستحکم باشد</small>
    					</label>
    					<div>
    						<input type="password" class="required" name="password" id=w1_userpassword />
    					</div>
    				</div>
                    <div class="row">
    					<label>
    						<strong>تکرار کلمه عبور</strong>
                            <small>مستحکم باشد</small>
    					</label>
    					<div>
    						<input equalTo='#w1_userpassword' type="password" class="required" name="password2" />
    					</div>
    				</div>
                    <div class="row">
                        <p class="_50">
                        <label>
    						<strong>نام</strong>
    					</label>
    						<input type="text" class="required" name="firstname" />
    					</p>
                        <p class="_50">
                            <label>
    						  <strong>نام خانوادگی</strong>
    					   </label>
    						<input type="text" class="required" name="lastname" />
    					</p>
    				</div>
                    
                    <div class="row">
                        <label>
    						<strong>شهر</strong>
    					</label>
   						<div>
                          <input type="text" class="required" name="city" />
                        </div>
    				</div>
                    <div class="row">
                        <label>
    						<strong>آدرس</strong>
    					</label>
   						<div>
                          <input type="text" class="required" name="address" />
                        </div>
    				</div>
                    <div class="row">
                        <label>
    						<strong>کد پستی</strong>
    					</label>
   						<div>
                          <input type="text" class="required"  pattern="^\d{1,20}$" name="zip" />
                        </div>
    				</div>
                    <div class="row">
                        <label>
    						<strong>تلفن ثابت</strong>
                            <small>همراه با پیش شماره شهر</small>
    					</label>
   						<div>
                          <input type="text" class="required"  pattern="^\d{1,20}$"  name="phone" />
                        </div>
    				</div>
    			</fieldset>
    		</div>
    		<div class="actions">
    			<div class="left">
    				<a href="#" class="button grey"><span><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/arrow-180.png')); ?>" width=16 height=16></span>بازگشت</a>
    			</div>
    			<div class="right">
    				<a href="#" class="button grey"><span><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/arrow.png')); ?>" width=16 height=16></span>ادامه</a>
    				<a href="#" class="button finish"><span><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/arrow.png')); ?>" width=16 height=16></span>اتمام</a>
    			</div>
    		</div>
    	</form>
    </div>
    <p style="text-align: center;">
        <button href="javascript:void(0);" id="resend" class="button grey block"><span class="icon icon-plus"></span>ارسال مجدد کد فعال سازی</button>
    </p>
</section>
<?php include('footer.php'); ?>