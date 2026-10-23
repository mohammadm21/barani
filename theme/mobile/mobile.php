<!--<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $options['chat_site_title']; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <?php theme::get_header('mobile'); ?>
<script type="text/javascript">loadonlines();</script>
    </head>
    <body class=""> 
        <div class="alertnotif"></div>
        <div class="navtop">
            <div id="title">
                <div class="continer">
                    <a class="logo" onclick="showmainpage()" ><i class="fa fa-home "><br><span id="onlines" style="font-size:12px;">آنلاین : <?=db::num('onlines',"`hide`='0'");?></span> </i></a>
                    <ul class="tools">
                        <li class="menubtn"><i class="fa fa-tasks "></i></li>
                        <li class="notificationli"><a><i class="fa fa-bell"></i></a>

                            <ul class="notifications">
                                <li><span class="dropdown-menu-title"></span></li>
                                <li>
                                    <div class="drop-down-wrapper ps-container">
                                        <ul></ul>
                                        <div style="left: 0px; bottom: 3px; width: 270px; display: none;" class="ps-scrollbar-x-rail">
                                            <div style="left: 0px; width: 0px;" class="ps-scrollbar-x">
                                            </div>
                                        </div>
                                        <div style="top: 0px; height: 250px; display: inherit;" class="ps-scrollbar-y-rail">
                                            <div style="top: 0px; height: 142px;" class="ps-scrollbar-y"></div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        <li class="mosi" ><a href="<?php echo url('chat'); ?>">نسخه کامپیوتر</a></li>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="settingsd">
            <div class="settingcontiner">
                <div class="settingtitle">تنظیمات</div>
            </div>
            <div class="settingbtn"><i class="fa fa-wrench"></i></div><br>
            <div id="li_setting">
                <ul>
                    <?php if ((trim(session('type')) == "") || (trim(session('type')) == 1)) { ?>
                        <li>تغییر فونت: <input type='text' id="custom" /></li>
                        <li onclick="user_register();"><a href="javascript:void(0);">عضویت</a></li>
                    <?php } ?>
                    <?php if ($usertype[session('type')]['guest'] == '0') { ?>
                        <li>تغییر فونت: <input type='text' id="custom" /></li>
                        <li onclick="user_changepw();"><a href="javascript:void(0);">تغییر رمز</a></li>
                        <li onclick="user_changestatus();"><a href="javascript:void(0);">تغییر وضعیت</a></li>
                        <li onclick="create_conference();"><a href="javascript:void(0);">ساخت اتاق کنفرانس</a></li>
                        <li><input id="status_private" onclick="changeSetting('status_private');" type="checkbox" checked=""><a href="javascript:void(0);"> مشاهده پیام های خصوصی</a></li>
                        <li><input id="avatar_setting" onclick="changeSetting('avatar_setting');" type="checkbox" checked=""><a href="javascript:void(0);"> مشاهده آواتارها</a></li>
                        <li><input id="show_conference" onclick="changeSetting('show_conference');" type="checkbox" checked=""><a href="javascript:void(0);"> نمایش درخواست کنفرانس</a></li>
                        <li><input id="showfriend_req" onclick="changeSetting('showfriend_req');" type="checkbox" checked=""><a href="javascript:void(0);"> مشاهده درخواست دوستی کاربران</a></li>
                    <?php } ?>
                    <?php
                    $admin = session('admin');
                    if ($admin != '0') {
                        ?><li><a target="_blank" href="<?php echo(url("history")); ?>">تاریخچه</a></li>
                        <li><a target="_blank" href="<?php echo(url("admin")); ?>">پنل مدیریت</a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <div class="profileerror">
            <p class="icon"><i class="fa fa-lock"></i></p>
            <p class="errormsg">پروفایل این کاربر قفل شده است</p>
            <p class="backbtn"><i class="fa fa-rotate-left"></i></p>
        </div>
        <div class="continer profile">
            <div class="tabbable">
                <ul id="myTab4" class="nav nav-tabs tab-padding tab-space-3 tab-blue">
                    <li class="active">
                        <a>مشاهده</a>
                    </li>
                    <li>
                        <a class="microblog_url" target="_blank">میکروبلاگ</a>
                    </li>
                    <li>

                        <a data-toggle="tab" href="<?php echo(url()); ?>/mobile">برگشت به صفحه اصلی</a> 
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="showprof" class="active">
                        <div class="row">
                            <div class="center">
                                <h4 class="user_name"></h4>
                                <img src="" class="img-polaroid avatar" height="180" width="150">
                                <div class="socialnetworks">
                                    <p>
                                        <a class="btn btn-facebook btn-sm btn-squared"><i class="fa fa-facebook"></i></a>
                                        <a class="btn btn-google-plus btn-sm btn-squared"><i class="fa fa-google-plus"></i></a>
                                        <a class="btn btn-linkedin btn-sm btn-squared"><i class="fa fa-youtube"></i></a>
                                        <a class="btn btn-twitter btn-sm btn-squared"><i class="fa fa-twitter"></i></a>
                                    </p>
                                </div>
                            </div>
                            <table class="table table-condensed table-hover profilecontact">
                                <thead><tr><th colspan="3">اطلاعات تماس</th></tr></thead>
                                <tbody>
                                    <tr class="web"><td>وب:</td><td><a></a></td></tr>
                                    <tr class="mail"><td>ایمیل:</td><td><a></a></td></tr>
                                    <tr clsas="tel"><td>تلفن:</td><td><a></a></td></tr>
                                </tbody>
                            </table>
                            <table class="table table-condensed table-hover baseprofile">
                                <thead><tr><th colspan="3"></th></tr></thead>
                                <tbody>
                                    <tr><td>درجه کاربری:</td><td class="type"></td></tr>
                                    <tr><td>آخرین فعالیت:</td><td class="lastview"></td></tr>
                                    <tr><td>جنسیت:</td><td class="gender"></td></tr>
                                </tbody>
                            </table>
                            <table class="table table-condensed table-hover additionalprofile">
                                <thead><tr><th colspan="3">اطلاعات اضافی</th></tr></thead>
                                <tbody>
                                    <tr class="firstname"><td>نام:</td><td></td></tr>
                                    <tr class="lastname"><td>نام خانوادگی:</td><td></td></tr>
                                    <tr class="country"><td>کشور:</td><td></td></tr>
                                    <tr class="city"><td>شهر:</td><td></td></tr>
                                    <tr class="birthday"><td>تاریخ تولد:</td><td></td></tr>
                                    <tr class="desc"><td>توضیحات:</td><td></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="avatarlarg">
            <div class="cover"><i class="fa fa-rotate-left"></i></div>
            <img src="" />
        </div>
        <div class="modal-back"></div>
        <div class="modal roompwdmodal">
            <div class="modal-header">
                <h4 class="modal-title">ورود به اتاق</h4>
                <button type="button" class="close">×</button>
            </div>
            <div class="modal-body">
                <form>
                    <input name="room" value="" type="hidden">
                    <div class="row">
                        <label for="roompw">لطفا رمز اتاق را وارد کنید:</label>
                        <input id="roompw" type="password" name="roompw" required />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-blue submit">ورود</button>
                <button type="button" class="btn btn-light-grey cancel">انصراف</button>
            </div>
        </div>
        <div class="modal blockuser">
            <div class="modal-header">
                <h4 class="modal-title">مسدود سازی کاربر</h4>
                <button type="button" class="close">×</button>
            </div>
            <div class="modal-body">
                <form>
                    <input name="user" value="" type="hidden">
                    <p>مسدود سازی پیام های:</p>
                    <div class="row">
                        <label for="privmsgradio">خصوصی</label>
                        <input id="privmsgradio" type="radio" name="type" value="private" />
                        <label for="publimsgradio">همه</label>
                        <input id="publimsgradio" type="radio" name="type" value="all" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-blue submit">مسدود</button>
                <button type="button" class="btn btn-light-grey cancel">انصراف</button>
            </div>
        </div>
        <div class="modal user_register">
            <div class="modal-header">
                <h4 class="modal-title">عضویت در چت روم</h4>
                <button type="button" class="close">×</button>
            </div>
            <div class="modal-body">
                <form>
				<input type="hidden" name="token" value="<?=token2();?>" />
				<input type="hidden" name="<?=rrobot();?>" value="<?=mrobot();?>" />
				<input type="hidden" name="<?=trobot();?>" value="<?=rtoken();?>" />
				<input type="hidden" name="<?=token2();?>" value="<?=gtoken();?>" />
                    <div class="row">
                        <p><label>نام کاربری</label></p>
                        <p><input id="register_username" type="text" name="username" value="<?php echo session('name'); ?>" /></p>
                        <p><label>رمز</label></p>
                        <p><input id="register_password" type="password" name="register_password" value="" /></p>
                        <p><label>تکرار رمز</label></p>
                        <p><input id="rep_register_password" type="password" name="rep_register_password" value="" /></p>
                        <input type="hidden" id="register_user_id" name="userid" value="<?php echo session('id'); ?>" />
                    </div>
                    <p>لطفا بعد از عضو شدن از چت روم خارج شوید مجدد وارد شوید</p>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-blue submit">عضویت</button>
                <button type="button" class="btn btn-light-grey cancel">انصراف</button>
            </div>
        </div>
        <div class="modal user_changepw">
            <div class="modal-header">
                <h4 class="modal-title">تغییر رمز</h4>
                <button type="button" class="close">×</button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <label>پسورد جدید</label>
                        <input id="new_register_password" type="password" name="new_register_password" value="" />
                        <input type="hidden" id="register_user_id" name="userid" value="<?php echo session('id'); ?>" />
				<input type="hidden" name="token" value="<?=token2();?>" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-blue submit">تغییر بده</button>
                <button type="button" class="btn btn-light-grey cancel">انصراف</button>
            </div>
        </div>
        <div class="user_changestatus">
            <div class="status_title">وضعیت ها</div>
            <div class="status">
            </div>
        </div>
        <div class="modal bannuser">
            <div class="modal-header">
                <h4 class="modal-title">اخراج کاربر</h4>
                <button type="button" class="close">×</button>
            </div>
            <div class="modal-body">
                <form>
                    <input name="user" id="id_bann_user" value="" type="hidden">
				<input type="hidden" name="token" value="<?=token2();?>" />
                    <p>دلیل اخراج :</p>
                    <input class="row" type="text" id="reason" placeholder="دلیل اخراج">
                    <p>مدت زمان اخراج:</p>
                    <div class="row">
                        <select id="blacklist_time" size="1">
                            <option value="exit">لحظه اي</option>
                            <option value="1min">يک دقيقه</option>
                            <option value="10min">ده دقيقه</option>
                            <option value="30min">سي دقيقه</option>
                            <option value="1hour">يک ساعت</option>
                            <option value="12hour">دوازده ساعت</option>
                            <option value="1day">يک روز</option>
                            <option value="3day">3 روز</option>
                            <option value="1week">1 هفته</option>
                            <option value="2week">2 هفته</option>
                            <option value="1month">يک ماه</option>
                            <option value="6month">شش ماه</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-blue submit">اخراج</button>
                <button type="button" class="btn btn-light-grey cancel">انصراف</button>
            </div>
        </div>
        <div class="menu">
            <ul>
                <li><a data-do="gopublichat">صفحه عمومی</a></li>
                <li><a data-do="gomainpage" style="cursor: pointer;">لیست آنلاین ها و اتاق ها</a></li>
                <li><a href="<?php echo url('chat'); ?>">نسخه کامپیوتر</a></li>
                <li><a href="/profile" target="_blank">پروفایل من</a></li>
                <li><a href="<?php echo url('game'); ?>">بازی آنلاین</a></li>
                <li><a href="<?php echo url('contact'); ?>">تماس با ما</a></li>
                <li><a href="<?php echo url('logoutm'); ?>">خروج</a></li>
            </ul>
        </div>
        <div class="continer mainpage"><div class="rooms"></div></div>
        <div class="continer publichat messaging" id="publichat">
            <div class="msgcontiner" id="msgcontiners"></div>
            <div class="sendmsg fixed">
                <div class="smilebox">
                    <ul></ul>
                </div>
                <?php if ($usertype[session('type')]['guest'] == '1') { ?>
				<div class="replybox"></div>
                    <form class="sendformmsg">
                        <div class="formrow">
                            <input name="msg" class="" value="" placeholder="برای ارسال پیام در عمومی باید عضو شوید" type="text">
                        </div>	
                        <a href="" class="backbtn"><i class="fa fa-rotate-left"></i></a>
                    </form>
                <?php } if ($usertype[session('type')]['guest'] == '0') { ?>
				<div class="replybox"></div>
                    <form class="sendformmsg">
                        <div class="formrow">
                            <input name="msg" class="msginput" value="" placeholder="متن پیام..." type="text">
                            <ul class="msgtools"></ul>
                            <div class="s"><a href="" class="btnsendmsg"><i class="fa fa-check"></i></a><a class="smilebtn"><i class="fa fa-smile-o"></i></a>
                            </div>	<a href="" class="backbtn"><i class="fa fa-rotate-left"></i></a>
                        </div>
                    </form>
                <?php } ?>
            </div>
        </div>
       
        <script>
            $("#custom").spectrum({showPaletteOnly:!0,togglePaletteOnly:!0,togglePaletteMoreText:"بیشتر",togglePaletteLessText:"بستن",cancelText:"کنسل",chooseText:"انتخاب",color:"#ff00ff",change:function(e){var c=e.toHexString();c=c.replace("#",""),$.ajax({url:"<?php url('ajax/upcolor') ?>",type:"POST",data:{color:c},success:function(f){$(".msginput").css("color",e.toHexString()),$me.color=c}}),console.log(c)},palette:[["#000","#444","#666","#999","#ccc","#eee","#f3f3f3","#fff"],["#f00","#f90","#ff0","#0f0","#0ff","#00f","#90f","#f0f"],["#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc"],["#ea9999","#f9cb9c","#ffe599","#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd"],["#e06666","#f6b26b","#ffd966","#93c47d","#76a5af","#6fa8dc","#8e7cc3","#c27ba0"],["#c00","#e69138","#f1c232","#6aa84f","#45818e","#3d85c6","#674ea7","#a64d79"],["#900","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47"],["#600","#783f04","#7f6000","#274e13","#0c343d","#073763","#20124d","#4c1130"]]});
        </script>
    </body>
</html>-->