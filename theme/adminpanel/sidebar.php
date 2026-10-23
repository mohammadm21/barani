<?php
if (session('admin') != '0') {
    ?>
    <aside>
        <div class="top">
            <nav>
                <ul class="collapsible accordion">
                    <?php
                    $el2 = giu('el2');
                    $el3 = giu('el3');
                    ?>
       
                    <li <?php
                    if ($el2 == 'index' or ! $el2) {
                        echo('class="current"');
                    }
                    ?> >
                        <a href="<?php echo(url('admin')); ?>"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/dashboard.png')); ?>" alt="" height="16" width="16">پیشخوان</a>
                    </li>

                    <?php if (adminpanel::check_Permission('bool', 'statos') == true) { ?>
                        <li <?php
                        if ($el2 == 'statos') {
                            echo('class="current"');
                        }
                        ?>>
                            <a href="../../../admin/statos"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/statos.png')); ?>" alt="" style="height:16px;width:16px;">افزودن وضعیت<span class="badge"><?php echo(db::num('statos')); ?></span></a>
                        </li>
                    <?php } if (adminpanel::check_Permission('bool', 'news') == true) { ?>
                        <li <?php
                        if ($el2 == 'news') {
                            echo('class="current"');
                        }
                        ?>>
                            <a href="../../../admin/news"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/bookmarks.png')); ?>" alt="" height=16 width=16>قسمت اخبار<span class="badge"><?php echo(db::num('news')); ?></span></a>
                        </li>
                    <?php } if (adminpanel::check_Permission('bool', 'users') == true) { ?>

                        <li <?php
                        if ($el2 == 'users') {
                            echo('class="current"');
                        }
                        ?>>
                            <a href="javascript:void(0);"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/user.png')); ?>" alt="" height="16" width="16">کاربران<span class="badge"><?php echo(db::num('users')); ?></span></a>
                            <ul>
                                <?php
                                foreach ($usertype as $val) {
                                    ?>
                                    <li><a href="../../../admin/users/list/<?php echo $val['id']; ?>"><img src="<?php echo(theme::url($val['icon'])); ?>"><?php echo($val['title']); ?></a></li>
                                    <?php
                                }
                                ?><li <?php
                                if ($el3 == 'bann') {
                                    echo('class="current"');
                                }
                                ?>><a href="../../../admin/users/bann"><span class="icon icon-picture"></span>کاربران غیرفعال</a></li>
                                <li <?php
                                if ($el3 == 'man') {
                                    echo('class="current"');
                                }
                                ?>><a href="../../../admin/users/man"><span class="icon icon-picture"></span>کاربران مذکر</a></li>
                                <li <?php
                                if ($el3 == 'woman') {
                                    echo('class="current"');
                                }
                                ?>><a href="../../../admin/users/woman"><span class="icon icon-picture"></span>کاربران مونث</a></li>
                            </ul>
                        </li>
                         <?php }if (adminpanel::check_Permission('bool', 'report')) { ?>
                <li <?php if ($el2 == 'report') echo('class="current"'); ?>>
                    <a href="../../../admin/report"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/application-form.png')); ?>" alt="" height=16 width=16>کاربران ریپورت شده<span class="badge"><?php echo(db::num('report_users')); ?></span></a>
                </li>
                    <?php } if (adminpanel::check_Permission('bool', 'usertypes') == true) { ?>
                        <li <?php
                        if ($el2 == 'usertypes') {
                            echo('class="current"');
                        }
                        ?>>
                            <a href="../../../admin/usertypes"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/user-share.png')); ?>" alt="" height=16 width=16>سطوح کاربری<span class="badge"><?php echo(db::num('usertypes')); ?></span></a>
                        </li>
                    <?php } if (adminpanel::check_Permission('bool', 'rooms') == true) { ?>
                        <li <?php
                        if ($el2 == 'rooms') {
                            echo('class="current"');
                        }
                        ?>>
                            <a href="../../../admin/rooms"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/application-form.png')); ?>" alt="" height=16 width=16>اتاق ها<span class="badge"><?php echo(db::num('rooms')); ?></span></a>
                        </li>
                    <?php } if (adminpanel::check_Permission('bool', 'smilies') == true) { ?>
                        <li <?php
                        if ($el2 == 'smilies') {
                            echo('class="current"');
                        }
                        ?>>
                            <a href="../../../admin/smilies"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/chart.png')); ?>" alt="" height=16 width=16>شکلک ها<span class="badge"><?php echo(db::num('smilies')); ?></span></a>
                        </li>
<?php }if (adminpanel::check_Permission('bool', 'avatars')) { ?>
               <li <?php if ($el2 == 'avatars') echo('class="current"'); ?>>
                    <a href="../../../admin/avatars"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/animal-monkey.png')); ?>" alt="" height=16 width=16>آواتار های پیشفرض<span class="badge"><?php echo(db::num('avatars')); ?></span></a>
                </li>
              <?php }if (adminpanel::check_Permission('bool', 'smilies')) { ?>
                <li <?php if ($el2 == 'usersmilies') echo('class="current"'); ?>>
                    <a href="../../../admin/usersmilies"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/chart.png')); ?>" alt="" height=16 width=16>شکلک های ارسال شده<span class="badge"><?php echo(db::num('user_smile')); ?></span></a>
                </li> 
                 <?php } if (adminpanel::check_Permission('bool', 'filters')) { ?>
                        <li <?php if ($el2 == 'badwords')  echo('class="current"'); ?>>
                             <a href="javascript:void(0);"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/table.png')); ?>" alt="" height=16 width=16>کلمات فیلتر شده</a>
                            <ul>
                         <?php  if (adminpanel::check_Permission('bool', 'badwords')) {
							include('inc/badwords.php');
							if($badwords){
								$num = 0;
								foreach($badwords as $badword){
									$num++;
								}
							}else{
								$num = '0';
							}
							
							?>
                                <li>
                                    <a href="../../../admin/badwords">کلمات زشت <span class="badge"><?php echo($num); ?></span></a>
                                </li>
                                <?php } if (adminpanel::check_Permission('bool', 'badnames')) {
							      include('inc/badnames.php');
							       if($badnames){
								      $nums = 0;
								    foreach($badnames as $badname){
									  $nums++;
								    }
							       }else{
								      $nums = '0';
							       }
                                 ?>
                                <li>
                                    <a href="../../../admin/badnames">نام های کاربری زشت <span class="badge"><?php echo($nums); ?></span></a>
                                </li>
                                <li> <?php } if (adminpanel::check_Permission('bool', 'badwordsettings')) { ?>
                                    <a href="../../../admin/badwords/setting">تنظیمات کلمات فیلتر شده </a>
                                </li> <?php } ?>
                            </ul>
                        </li>
  <?php } if (adminpanel::check_Permission('bool', 'analyzer') == true) { ?>
                        <li <?php
                        if ($el2 == 'stats' or $el2 == 'analyzer') {
                            echo('class="current"');
                        }
                        ?>>
                            <a href="javascript:void(0);"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/chart.png')); ?>" alt="" height=16 width=16>آمارها</a>
                            <ul><?php  if (adminpanel::check_Permission('bool', 'statss') == true) { ?>
                                <li>
                                    <a href="../../../admin/stats">آمار کلی</a>
                                </li><?php } if (adminpanel::check_Permission('bool', 'statslist') == true) { ?>
                                <li>
                                    <a href="../../../admin/stats/list">ریز آمار</a>
                                </li><?php } if (adminpanel::check_Permission('bool', 'statsanalyzer') == true) { ?>
                                <li>
                                    <a href="../../../admin/analyzer">امار تحلیلی</a>
                                </li>
      <?php } ?>
                            </ul>
                        </li>
                    <?php } if (adminpanel::check_Permission('bool', 'logs') == true) { ?>
                        


                               <li <?php if($el2 == 'logs')echo('class="current"'); ?>>
											<a href="javascript:void(0);"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/notebook-medium.png')); ?>" alt="" height=16 width=16>رصد خانه</a>
										      <ul><?php  if (adminpanel::check_Permission('bool', 'avatar') == true) { ?>
                                                                                               
												<li>
													<a href="/admin/logs/avatar">لیست آواتار ها</a>
												</li><?php } if (adminpanel::check_Permission('bool', 'cover') == true) { ?>
												<li>
													<a href="/admin/logs/cover">لیست جلد ها</a>
												</li><?php } if (adminpanel::check_Permission('bool', 'profile') == true) { ?>
<li>
													<a href="/admin/logs/profile"> لیست مطالب ها</a>
												</li><?php } if (adminpanel::check_Permission('bool', 'logon') == true) { ?>
                                                                                                 
                                                                                                 
												<li>
													<a href="/admin/logs#logs-1">فعالیت های داخل چت روم</a>
												</li>                                                                                             
                                                <?php } if (adminpanel::check_Permission('bool', 'fillprint') == true) { ?>
	<li>
		<a href="<?php echo(url('admin/fillprint')); ?>">اثر انگشت مشکوک</a>
	</li>
<?php } ?>
                                            </ul>
                    <?php } if (adminpanel::check_Permission('bool', 'letters') == true) { ?>
                        <li <?php
                        if ($el2 == 'letters') {
                            echo('class="current"');
                        }
                        ?>>
                            <a href="../../../admin/letters"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/mails-stack.png')); ?>" alt="" height=16 width=16>نامه ها<span class="badge"><?php echo(db::num('letters', "`to`='" . session('id') . "' and `status`='0'")); ?></span></a>
                        </li>
                    <?php } if (adminpanel::check_Permission('bool', 'adminpanel') == true) { ?>
                        <li <?php
                        if ($el2 == 'adminpanel') {
                            echo('class="current"');
                        }
                        ?>>
                            <a href="../../../admin/adminpanel"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/lock-warning.png')); ?>" alt="" height=16 width=16>مدیریت دسترسی ها<span class="badge"><?php echo(db::num('adminpanel')); ?></span></a>
                        </li>
                    <?php } if (adminpanel::check_Permission('bool', 'rollcall') == true) { ?>
                        <li <?php
                        if ($el2 == 'rollcall') {
                            echo('class="current"');
                        }
                        ?>>
                            <a href="../../../admin/rollcall"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/folder-open-table.png')); ?>" alt="" height=16 width=16>حضور و غیاب</a>
                        </li>
                    <?php } if (adminpanel::check_Permission('bool', 'visualuser') == true) { ?>
                        <li <?php
                        if ($el2 == 'visualuser') {
                            echo('class="current"');
                        }
                        ?>>
                            <a href="../../../admin/visualuser"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/user--plus.png')); ?>" alt="" height=16 width=16>کاربران مجازی<span class="badge"><?php echo(db::num('onlines', "`time`='0'")); ?></span></a>
                        </li>
                    <?php } if (adminpanel::check_Permission('bool', 'msgsender') == true) { ?>
                        <li <?php
                        if ($el2 == 'msgsender') {
                            echo('class="current"');
                        }
                        ?>>
                            <a href="../../../admin/msgsender"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/mail-medium-open.png')); ?>" alt="" height=16 width=16>ارسال پیام</a>
                        </li>
                    <?php } if (adminpanel::check_Permission('bool', 'msgreader') == true) { ?>
                        <li <?php
                        if ($el2 == 'msgreader') {
                            echo('class="current"');
                        }
                        ?>>
                            <a href="../../../admin/msgreader"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/eye.png')); ?>" alt="" height=16 width=16>خصوصی خوان</a>
                        </li>
                    <?php } if (adminpanel::check_Permission('bool', 'ipbanner') == true) { ?>
                        <li <?php
                        if ($el2 == 'ipbanner') {
                            echo('class="current"');
                        }
                        ?>>
                            <a href="../../../admin/ipbanner"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/network-ip.png')); ?>" alt="" height=16 width=16>تحریم آی پی<span class="badge"><?php echo(db::num('banns')); ?></span></a>
                        </li>
                    <?php } if (adminpanel::check_Permission('bool', 'silent') == true) { ?>
                        <li <?php
                        if ($el2 == 'silent') {
                            echo('class="current"');
                        }
                        ?>>
                            <a href="../../../admin/silent"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/speaker-volume-control-mute.png')); ?>" alt="" height=16 width=16>خفه کن<span class="badge"><?php echo(db::num('silents')); ?></span></a>
                        </li>
                    <?php } if (adminpanel::check_Permission('bool', 'player') == true) { ?>
                        <li <?php
                        if ($el2 == 'player') {
                            echo('class="current"');
                        }
                        ?>>
                            <a href="../../../admin/player"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/blue-document-music.png')); ?>" alt="" height=16 width=16>پخش کننده موسیقی<span class="badge"><?php echo(db::num('player')); ?></span></a>
                        </li>
                    <?php } if (adminpanel::check_Permission('bool', 'links') == true) { ?>
                        <li <?php
                        if ($el2 == 'links') {
                            echo('class="current"');
                        }
                        ?>>
                            <a href="../../../admin/links"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/tag--pencil.png')); ?>" alt="" height=16 width=16>لینکدونی<span class="badge"><?php echo(db::num('links')); ?></span></a>
                        </li>
                    <?php } if (adminpanel::check_Permission('bool', 'pays') == true) { ?>
                        <li <?php
                        if ($el2 == 'pays') {
                            echo('class="current"');
                        }
                        ?>>
                            <a href="../../../admin/pays"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/point.png')); ?>" alt="" height=16 width=16>پرداخت ها</a>
                        </li>
                    <?php } if (adminpanel::check_Permission('bool', 'sendedimages') == true) { ?>
                        <li <?php
                        if ($el2 == 'sendedimages') {
                            echo('class="current"');
                        }
                        ?>>
                            <a href="../../../admin/sendedimages"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/arrow-transition-090.png')); ?>" alt="" height=16 width=16>فایل های ارسالی<span class="badge"><?php echo(db::num('sendedimages')); ?></span></a>
                        </li>
                    <?php } if (adminpanel::check_Permission('bool', 'database') == true) { ?>
                        <li <?php
                        if ($el2 == 'database') {
                            echo('class="current"');
                        }
                        ?>>
                            <a href="../../../admin/database"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/database-property.png')); ?>" alt="" height=16 width=16>پایگاه داده</a>
                        </li>

                    <?php } if (adminpanel::check_Permission('bool', 'logintheme') == true) { ?>
                        <li <?php
                        if ($el2 == 'logintheme') {
                            echo('class="current"');
                        }
                        ?>>
                            <a href="javascript:void(0);"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/television-image.png')); ?>" alt="" height=16 width=16>مدیریت قالب بیرونی</a>
                            <ul><?php if (adminpanel::check_Permission('bool', 'themeselect') == true) { ?>
                                <li>

                                </li><?php } if (adminpanel::check_Permission('bool', 'themelogins') == true) { ?>

                                <li>
                                    <a href="../../../admin/theme/logintheme">انتخاب قالب</a>
                                </li>
                               <?php } ?>
                            </ul>

                             <li <?php if($el2 == 'question')echo('class="current"'); ?>>
                            <a href="../../../admin/question"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/soal.png')); ?>" alt="" height=16 width=16>طرح سوال</a>
                        </li>

                        </li>
						
                    <?php } if (adminpanel::check_Permission('bool', 'theme') == true) { ?>
                        <li <?php
                        if ($el2 == 'theme') {
                            echo('class="current"');
                        }
                        ?>>
                            <a href="javascript:void(0);"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/television-image.png')); ?>" alt="" height=16 width=16>مدیریت قالب</a>
                            <ul>
                                <li><?php if (adminpanel::check_Permission('bool', 'themechat') == true) { ?>
                                    <a href="../../../admin/theme/chat">قالب داخل چت روم</a>
                                </li> <?php } if (adminpanel::check_Permission('bool', 'headers') == true) { ?>
                                <li>
                                    <a href="../../../admin/theme/headers">سربرگ ها</a>
                                </li><?php } if (adminpanel::check_Permission('bool', 'codejava') == true) { ?>
                                <li>
                                    <a href="../../../admin/theme/code">کد های جاوا و فلش</a>
                                </li><?php } if (adminpanel::check_Permission('bool', 'privatstyle') == true) { ?>
                                <li>
                                    <a href="../../../admin/theme/winstyle">استایل پنجره ها</a>
                                </li><?php } if (adminpanel::check_Permission('bool', ' fontsin') == true) { ?>
                                <li>
                                    <a href="../../../admin/theme/fonts">فونت ها</a>
                                </li>
                           <?php } ?>
                            </ul>
                        </li>
						<?php } ?>
         
                    <li <?php
                    if ($el2 == 'icons') {
                        echo('class="current"');
                    }
                    ?>>
                        <a href="javascript:void(0);"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/windows.png')); ?>" alt="" style="height:16px;width:16px;">مدیریت ایکون ها</a>
                        <ul><?php if (adminpanel::check_Permission('bool', 'iconlist') == true) { ?>
                                <li>
                                    <a href="../../../admin/icons/list">ایکون های لیست انلاین</a>
                                </li>
                            <?php } if (adminpanel::check_Permission('bool', 'iconomoomi') == true) { ?>
                                <li>
                                    <a href="../../../admin/icons/omoomi">ایکون های داخلی و عمومی</a>
                                </li>
                            <?php } if (adminpanel::check_Permission('bool', 'iconmororgar') == true) { ?>
                                <li>
                                    <a href="../../../admin/icons/mororgar">ایکون مرورگر و سیستم کاربر</a>
                                </li>
                            <?php } if (adminpanel::check_Permission('bool', 'theme') == true) { ?>
                                <li>
                                    <a href="../../../admin/icons/form-icons">ایکون های کادر ارسال</a>
                                </li>
                            <?php } if (adminpanel::check_Permission('bool', 'iconstatus') == true) { ?>
                                <li>
                                    <a href="../../../admin/icons/status"><small>ایکون های وضعیت کاربر در لیست</small></a>
                                </li>
                            <?php } if (adminpanel::check_Permission('bool', 'iconstatus') == true) { ?>
                                <li>
                                    <a href="../../../admin/icons/statuslarg"><small>ایکون های وضعیت کاربر در عمومی</small></a>
                                </li>
                            <?php } if (adminpanel::check_Permission('bool', 'iconprivate') == true) { ?>
                                <li>
                                    <a href="../../../admin/icons/private">ایکون های چت خصوصی</a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>

                    <?php if (adminpanel::check_Permission('bool', 'ads') == true) { ?>
                        <li <?php
                        if ($el2 == 'ads') {
                            echo('class="current"');
                        }
                        ?>>
											<a href="javascript:void(0);"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/image-select.png')); ?>" alt="" height=16 width=16>تبلیغات<span class="badge"><?php echo(db::num('ads')); ?></span></a>
					      										      <ul> <?php if (adminpanel::check_Permission('bool', 'adss') == true) { ?>
                                                <li>
													<a href="../../../admin/ads">مدیریت تبلیغات</a>
												</li><?php } if (adminpanel::check_Permission('bool', 'adsbuy') == true) { ?>
                                                <li>
													<a href="../../../admin/settings/adsbuy">قیمت تبلیغات</a>
							 <?php } ?>					</li>
                                            </ul>
                                        </li>
                    <?php } if (adminpanel::check_Permission('bool', 'specialactions') == true) { ?>
                        <li <?php
                        if ($el2 == 'specialactions') {
                            echo('class="current"');
                        }
                        ?>>
                            <a href="../../../admin/specialactions"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/burn--minus.png')); ?>" alt="" height=16 width=16>اقدمات ویژه</a>
                        </li>

                    <?php } ?>
                 
                    <li <?php
                    if ($el2 == 'restriction') {
                        echo('class="current"');
                    }
                    ?>>
                        <a href="javascript:void(0);"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/traffic-cone--pencil.png')); ?>" alt="" height=16 width=16>محدودیت ها</a>
                        <ul>
                            <?php if (adminpanel::check_Permission('bool', 'settingssocial') == true) { ?>
                                <li>
                                    <a href="../../../admin/restriction/social">شبکه اجتماعی</a>
                                </li>
                            <?php } if (adminpanel::check_Permission('bool', 'settingsvip') == true) { ?>
                                <li>
                                    <a href="../../../admin/restriction/vip">محدودیت امکانات ویژه</a>
                                </li>
                            <?php } if (adminpanel::check_Permission('bool', 'useroptionsettings') == true) { ?>
                                <li>
                                    <a href="../../../admin/restriction/useroptions">محدودیت امکانات</a>
                                </li>
                            <?php } if (adminpanel::check_Permission('bool', 'settingsprofile') == true) { ?>
                                <li>
                                    <a href="../../../admin/restriction/profile">محدودیت پروفایل</a>
                                </li>
                            <?php } if (adminpanel::check_Permission('bool', 'settingsmod') == true) { ?>
                                <li>
                                    <a href="../../../admin/restriction/mod">محدودیت امکانات مدیریت</a>
                                </li>
                            <?php } if (adminpanel::check_Permission('bool', 'settingsprivate') == true) { ?>
                                <li>
                                    <a href="../../../admin/restriction/private">محدودیت های چت خصوصی</a>
                                </li>
                            <?php } if (adminpanel::check_Permission('bool', 'loginsettings') == true) { ?>
                                <li>
                                    <a href="../../../admin/restriction/login">محدودیت ورود</a>
                                </li>
                            <?php } if (adminpanel::check_Permission('bool', 'rclicksettings') == true) { ?>
                                <li>
                                    <a href="../../../admin/restriction/rightclick">کلیک راست</a>
                                </li>
                            <?php } ?>
                        </ul>
                    <li>


                    <li <?php
                    if ($el2 == 'settings') {
                        echo('class="current"');
                    }
                    ?>>
                        <a href="javascript:void(0);"><img src="<?php echo(theme::url('img/icons/packs/fugue/16x16/auction-hammer--pencil.png')); ?>" alt="" height=16 width=16>تنظیمات</a>
                        <ul>
        <?php  if (adminpanel::check_Permission('bool', 'generalsettings') == true) { ?>
                                 <li>      
                                    <a href="../../../admin/settings/general">تنظیمات اصلی</a>
                                
                            

                                </li>   
                            <?php } if (adminpanel::check_Permission('bool', 'defualtssettings') == true) { ?>
                                <li>
                                    <a href="../../../admin/settings/defualts">پیشفرض ها</a>
                                </li>
                               
                                       <?php } if (adminpanel::check_Permission('bool', 'tlgsendedpm') == true) { ?>
                                <li>
                                    <a href="../../../admin/settings/tlgsendedpm">تنظیمات ربات تلگرام</a>
                                </li>   

                             <?php } if (adminpanel::check_Permission('bool', 'msgsettings') == true) { ?>
                                <li>
                                    <a href="../../../admin/settings/msg">پیام ها</a>
                                </li>
                            <?php } if (adminpanel::check_Permission('bool', 'settingsseo') == true) { ?>
                                <li>
                                    <a href="../../../admin/settings/seo">تنظیمات سئو</a>
                                </li>
                            <?php } if (adminpanel::check_Permission('bool', 'settingsmobilesite') == true) { ?>
                                <li>
                                    <a href="../../../admin/settings/mobilesite">نسخه موبایل</a>
                                </li>
                               <?php } if (adminpanel::check_Permission('bool', 'cmdsettings') == true) { ?>
                                <li>
                                    <a href="<?php echo(url('admin/settings/cmd')); ?>">تنظیمات دستورات</a>
                                </li>
                            <?php } if (adminpanel::check_Permission('bool', 'settingsfillprint') == true) { ?>
                                <li>
                                    <a href="<?php echo(url('admin/settings/fillprint')); ?>">اثر انگشت دستگاه</a>
                                </li>
                            <?php } if (adminpanel::check_Permission('bool', 'textsetting') == true) { ?>
                                <li>
                                    <a href="../../../admin/settings/texts">تنظیمات متون</a>
                                </li>
                             <?php if (adminpanel::check_Permission('bool', 'wlcchat') == true) { ?>
                                                <li>
				    <a href="../../../admin/settings/pmwlc">پنجره خوش آمد گویی داخلی</a>
				</li><?php } if (adminpanel::check_Permission('bool', 'wlclogin') == true) { ?>
                                                <li>
			            <a href="../../../admin/settings/wlclogin">پنجره خوش آمد گویی بیرونی</a>
							 <?php } ?>	
				</li>
                            <?php } ?>
                        </ul>
                    </li>
            </nav>

        </div>
        <div class="bottom sticky">
								<div class="divider">
								</div>
								<div class="progress">
									<div class="bar" data-title="فضای هاست" data-value="<?php echo(host::get('spaceUsing')); ?>" data-max="<?php echo(host::get('space')); ?>" data-format="0,0 MB">
									</div>
									<div class="bar" style="direction: rtl;" data-title="ظرفیت چتروم" data-value="<?php echo(db::num('onlines', "`time`!='0'")); ?>" data-max="<?php echo(chatroom::theCapacity()); ?>" data-format="00 Online">
									</div>
									<div class="bar" data-title="انقضا چت روم" data-value="<?php echo(host::get('expirationUsing')); ?>" data-max="<?php echo(host::get('expiration')); ?>" data-format="0 Day">
									</div>
								</div>
								<div class="divider">
								</div>
            <div class="buttons">
                <a target="_blank" href="http://www.baran0.ir" class="button grey block">ناحیه کاربری</a>
              
            </div>
        </div>
    </aside>
    <?php
} else {
    include("pages/notfound.php");
}
?>

