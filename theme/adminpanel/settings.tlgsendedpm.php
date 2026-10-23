<?php
include('header.php');
include('sidebar.php');
$tlg = json_decode($options['teloptions'], true);
?>
<style>
    .cpicker{
        display: inline-block;
        width: 25px !important;
    }
.tlg{

color:#000;
font-size:18px;
font-weight:bold;


}
</style>
<script type="text/javascript">

    function edit(){
    $.ajax({
        type: "POST",
        url: "<?php echo(url('ajax/admin.settings.tlgsendedpm')); ?>",
        data: $('#formeditsettings').serialize(),
        success: function(data){
            switch(data){
                case('ok'):$.jGrowl('ذخیره شد', { header: 'موفقیت آمیز' });break;
                default:alert(data);break;
            }
        }
    });
}
 

   function info_tlg_send_admins() {
        $("#info_tlg_send_admins").dialog('open');
    }
    $(document).ready(function () {
        $("#info_tlg_send_admins").dialog({
            autoOpen: false,
            modal: true,
            width: 500,
            buttons: [{text: "متوجه شدم", click: function () {
                        $(this).dialog("close");
                    }}]
        });
    });


</script>
<div style="display: none;" id="info_tlg_send_admins" title="راهنمای تنظیمات این سیستم">
   <p class="tlg">هر فرد علاوه بر دارا بودن آیدی کلمه ای مانند( @b4a_web20 ) یک ایدی  عد دی  هم دارد که  در اینجا ایدی بنده404418447 می باشد شما میتوانید  برای دریافت ایدی عددی  خود به ربات( @userinfobot ) مراجعه کنید </p>
</div>
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">تنظیمات</h1>
    <div class="grid_12">
        <div class="grid_12">
            <form class="box  validate" id="formeditsettings">
                <input type="hidden" name="token" value="<?php echo(session('changepw')); ?>" />
                <div class="header">
                    <h2>تنظیمات ربات تلگرام :</h2>
                </div>
                <div class="content tabbed">
<div class="row">
                        <label><strong>آیدی ربات اطلاع رسان طرح پرداز:</strong><small><br><a href="https://telegram.me/tarhpardazbot"> ایدی ربات اطلاع رسان :TarhPardazBot</a></small></label>
                        <div style="font-size:14px;" ><a style="position: relative;top: 20px;" href="https://t.me/tarhpardazbot" target="_blank" >کلیک کنید</a></div>
                        <div style=" color:#F00;font-weight:bold;" > بعد از ورود به ربات  اطلااع رسان روی  کلمه &nbsp start &nbspکلیک کنید تا گزارشات  به خصوصی شما در تلگرام ارسال شود  </a></div>
                   
</div> 
            <div class="row">
                        <label><strong>چت آیدی:</strong><small><a href="javascript:info_tlg_send_admins();">[توضیحات]</a></small></label>
                      
                        <div> <input type="text" style="width:140px; text-align: center;"  name="tlg_send_admins" value="<?php echo($options['tlg_send_admins']) ?>"/><font style="color:#000;font-weight:bold;"> توجه: برای پیدا کردن چت ایدی  از ربات  (&nbsp  https://t.me/userinfobot &nbsp)استفاده کنید</font> </div>
						
                    </div>
               		  <div class="row">
                        <label><strong>گزارش ورود به پنل مدیریت:</strong></label>
					
						<div>
						فعال باشد:
							 <input type="checkbox" value="1" name="sendloginlog" <?php if ($tlg['sendloginlog'] == 'Sure') echo('checked'); ?>  />بلي
						</div>
                        </div> 
<div class="row">
                        <label><strong>گزارش ارسال پیام گروهی:</strong><small></small></label>
					
						<div>
						فعال باشد:
							 <input type="checkbox" value="1" name="sendmsgsenderlog" <?php if ($tlg['sendmsgsenderlog'] == 'Sure') echo('checked'); ?>  />بلی
						</div>
                        </div> 
<div class="row">
                        <label><strong>گزارش مدیریت تحریم ایپی:</strong><small>بستن ایپی</small></label>
					
						<div>
						فعال باشد:
							 <input type="checkbox" value="1" name="sendipbanneraddlog" <?php if ($tlg['sendipbanneraddlog'] == 'Sure') echo('checked'); ?>  />بلی
						</div>
                        </div>  
<div class="row">
                        <label><strong>گزارش مدیریت قالب داخل:</strong><small>ویرایش</small></label>
					
						<div>
						فعال باشد:
							 <input type="checkbox" value="1" name="sendthemechatlog" <?php if ($tlg['sendthemechatlog'] == 'Sure') echo('checked'); ?>  />بلی
						</div>
                        </div> 
<div class="row">
                        <label><strong>  گزارش مدیریت محدودیت ها:</strong></label>
					
						<div>
						فعال باشد:
							 <input type="checkbox" value="1" name="sendLimitationslog" <?php if ($tlg['sendLimitationslog'] == 'Sure') echo('checked'); ?>  />بلی
						</div>
                        </div>   
<div class="row">
                        <label><strong>  گزارش مدیریت تنظیمات:</strong></label>
					
						<div>
						فعال باشد:
							 <input type="checkbox" value="1" name="sendsettinglog" <?php if ($tlg['sendsettinglog'] == 'Sure') echo('checked'); ?>  />بلی
						</div>
                        </div> 
<div class="row">
                        <label><strong>  گزارش مدیریت اقدامات ویژه:</strong></label>
					
						<div>
						فعال باشد:
							 <input type="checkbox" value="1" name="sendspecialactionslog" <?php if ($tlg['sendspecialactionslog'] == 'Sure') echo('checked'); ?>  />بلی
						</div>
                        </div> 
  <div class="row">
                        <label><strong>گزارش مدیریت کد جاوا:</strong><small><br>ساخت ، ویرایش ، حذف درجه   </a></small></label>
					
						<div>
						فعال باشد:
							 <input type="checkbox" value="1" name="sendjavacodelog" <?php if ($tlg['sendjavacodelog'] == 'Sure') echo('checked'); ?>  />بلی
						</div>
                        </div>  
  <div class="row">
                        <label><strong>گزارش مدیریت اتاق ها:</strong><small><br> ساخت ، ویرایش ، حذف اتاق   </a></small></label>
					
						<div>
						فعال باشد:
							 <input type="checkbox" value="1" name="sendroomlog" <?php if ($tlg['sendroomlog'] == 'Sure') echo('checked'); ?>  />بلی
						</div>
                        </div> 
<div class="row">
                        <label><strong>گزارش مدیریت کاربران:</strong><small><br>ساخت ، ویرایش ، حذف</a></small></label>
					
						<div>
						فعال باشد:
							 <input type="checkbox" value="1" name="senduserlog" <?php if ($tlg['senduserlog'] == 'Sure') echo('checked'); ?>  />بلی
						</div>
                        </div> 
<div class="row">
                        <label><strong>گزارش مدیریت سطوح کاربری:</strong><small><br>ساخت ، ویرایش ، حذف</a></small></label>
					
						<div>
						فعال باشد:
							 <input type="checkbox" value="1" name="sendusertypeslog" <?php if ($tlg['sendusertypeslog'] == 'Sure') echo('checked'); ?>  />بلی
						</div>
                        </div> 
<div class="row">
                        <label><strong>گزارش مدیریت تبلیغات:</strong><small><br>افزودن ، ویرایش ، حذف تبلیغ</a></small></label>
					
						<div>
						فعال باشد:
							 <input type="checkbox" value="1" name="sendadslog" <?php if ($tlg['sendadslog'] == 'Sure') echo('checked'); ?>  />بلی
						</div>
                        </div> 
   <div class="row">
                        <label><strong>گزارش مدیریت هزینه تبلیغات:</strong><small><br> ویرایش  </a></small></label>
					
						<div>
						فعال باشد:
							 <input type="checkbox" value="1" name="sendadsbuylog" <?php if ($tlg['sendadsbuylog'] == 'Sure') echo('checked'); ?>  />بلی
						</div>
                        </div> 
      <div class="row">
                        <label><strong>گزارش مدیریت لینک دونی:</strong><small><br>فزودن ، ویرایش ، حذف لینک   </a></small></label>
					
						<div>
						فعال باشد:
							 <input type="checkbox" value="1" name="sendlinkdonilog" <?php if ($tlg['sendlinkdonilog'] == 'Sure') echo('checked'); ?>  />بلی
						</div>
                        </div> 
  
     <div class="row">
                        <label><strong>گزارش مدیریت دسترسی:</strong><small><br>  افزودن ، ویرایش ، حذف مدیریت دسترسی   </a></small></label>
					
						<div>
						افزودن:
							 <input type="checkbox" value="1" name="sendadminpaneladslog" <?php if ($tlg['sendadminpaneladslog'] == 'Sure') echo('checked'); ?>  />فعال باشد
                                                       <br>	   ویرایش : 
							 <input type="checkbox" value="1" name="sendadminpaneleditlog" <?php if ($tlg['sendadminpaneleditlog'] == 'Sure') echo('checked'); ?>  />فعال باشد
						 <br>  حذف : 
							 <input type="checkbox" value="1" name="senadeldminpanellog" <?php if ($tlg['senadeldminpanellog'] == 'Sure') echo('checked'); ?>  />فعال باشد
						 <br>
						</div>
                        </div> 
 
  <div class="row">
                        <label><strong>گزارش مدیریت پیام ها:</strong><small><br>  دریافت ، حذف   </a></small></label>
					
						<div>
						دریافت  پیام:
						 <input type="checkbox" value="1" name="sendletterslog" <?php if ($tlg['sendletterslog'] == 'Sure') echo('checked'); ?>  />فعال باشد
					<br>	
	حذف  پیام:
							 <input type="checkbox" value="1" name="senddelletterslog" <?php if ($tlg['senddelletterslog'] == 'Sure') echo('checked'); ?>  />فعال باشد
						<br>	

                                       </div>

<br>	
                        </div> 
              

              </div>
            </form>
            <center><medium style="color:red">پس از ویرایش ممکن است به طور طبیعی حدودا یک دقیقه اعمال تغییرات طول بکشد</medium><br> <button class="button blue flat block" onclick="edit();">ویرایش</button></center>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>
