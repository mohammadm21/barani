function TarhPardaz_New_Js() {
    "rtl" == $("chatinhalt").style.direction && ($("postslist").style.cssFloat = "left");
    var a = $thememanager.chat.onlinelist;
    "#fff" == a["background-color"] && (a["background-color"] = "transparent");
    var b = $("postslist"),
        c = $("friends");
    if (b.style.backgroundImage = "url(" + theme_url(a["background-image"]) + ")", b.style.backgroundRepeat = a["background-repeat"], b.style.backgroundColor = a["background-color"], b.style.fontFamily = a["font-family"], b.style.fontSize = a["font-size"], b.style.boxShadow = a["box-shadow"], b.style.borderRadius = a["border-radius"], "undefined" != typeof a.width && (b.style.width = a.width), c.style.backgroundImage = "url(" + theme_url(a["background-image"]) + ")", c.style.backgroundRepeat = a["background-repeat"], c.style.backgroundColor = a["background-color"], c.style.fontFamily = a["font-family"], c.style.fontSize = a["font-size"], c.style.boxShadow = a["box-shadow"], c.style.borderRadius = a["border-radius"], "undefined" != typeof a.width && (c.style.width = a.width), 1 == $setting.socialitems && !$me.guest && (tabnewd(), window_height2(), setInterval(window_height2, 500), new Ajax.Request(url("ajax/chat.social"), {
            onSuccess: function(a) {
                $network = a.responseJSON,socialnet(), onlinefriends(), Event.observe("postslist", "click", function(a) {
                    "inflblock_" == Event.element(a).id.slice(0, 10) && Event.element(a).id != "inflblock_" + $me.id && (close_info_win(), changeUserEvent(Event.element(a).id))
                }), Event.observe("friends", "click", function(a) {
                    "inflblock_" == Event.element(a).id.slice(0, 10) && Event.element(a).id != "inflblock_" + $me.id && (close_info_win(), changeUserEvent(Event.element(a).id))
                })
            },
            method: "get"
        }), setInterval(LastProReloader, 3e4)));
    if (1 == $setting.staticreg && $me.guest) {
        $(document.body).insert('<div id="modalb"></div>');
        var d = $setup.evalJSON();
        $("modalb").insert('<div align="center" class="modals" id="staticreg"><div>عضویت فوری و آسان</div><div  style="float:right; background:none !important;  margin-right:0px;margin-top:-52px;"><input type="button" value="X" onclick="CloseM();" ></div><form id="srform"><label><span>نام کاربری : </span><input type="text" disabled="disabled" name="username" class="input" value="' + $me.username + '"></label><label><span>پسورد : </span><input type="password" class="input" name="password" placeholder="ورود پسورد الزامی است" ></label><label><span>تکرار پسورد : </span><input type="password" class="input" name="password2" placeholder="تکرار پسورد الزامی است"></label>' + (1 == d.settings.sreg_email ? '<label><span>آدرس ایمیل : </span><input type="text" class="input" placeholder="email@gmail.com" name="email"></label>' : "") + (1 == d.settings.sreg_phone ? '<label><span>شماره تماس : </span><input type="text" class="input" placeholder="09120000000" name="cellphone"></label>' : "") + '<label><small>نام و رمز خود را به خاطر بسپارید</small></label><label><input id="srsubmit" type="submit" value="عضویت" class="submit"></label></form></div>'), $("srform").onsubmit = function() {
            var a = $("srsubmit"),
                b = $("srform").getInputs("password", "password")[0].value,
                c = $("srform").getInputs("password", "password2")[0].value;
            return 0 == b.length ? (alert("پسورد نمیتواند خالی باشد"), !1) : b != c ? (alert("پسورد های وارد شده متفاوت است"), !1) : (a.setAttribute("disabled", "disabled"), new Ajax.Request(url("ajax/staticregister"), {
                onSuccess: function(a) {
                    switch (a.responseText) {
                        case "ok":
                            $("staticreg").update('<div class="okreg"> متشکريم بابت ثبت نام شما - هم اکنون رفرش نمایید تا ثبت نام شما تکمیل شود<br /><br /><a id="fresh" href="javascript:document.location.reload();">رفرش کردن صفحه </a></div>');
                            break;
                        case "password":
                            alert("لطفا کلمه عبور را وارد کنید");
                            break;
                        case "email":
                            alert("لطفا ایمیل خود را وارد کنید");
                            break;
                        case "nameexist":
                            alert("این نام کاربری قبلا توسط کاربر دیگری به ثبت رسیده است لطفا نام کاربری خود را عوض کنید");
                            break;
                        case "phone":
                            alert("لطفا تلفن همراه خود را وارد کنید");
                            break;
                        case "lastregister":
                            alert("شما قبلا عضو شده اید");
                            break;
                        case "nameexist":
                            alert("این نام کاربری قبلا توسط کاربر دیگری به ثبت رسیده است لطفا نام کاربری خود را عوض کنید");
                            break;
                        default:
                            alert(a.responseText)
                    }
                },
                onComplete: function() {
                    a.removeAttribute("disabled")
                },
                postBody: $("srform").serialize()
            }), !1)
        }
    }
}

function tabnewd() {
    $("changemod").style.display = "block", "postslist" == $setting.sideitems ? ($("postslist").style.display = "block", $("onlinelist").style.display = "none", $("spostslist").setAttribute("class", "selected"), $("sonlinelist").setAttribute("class", "")) : ($("onlinelist").style.display = "block", $("postslist").style.display = "none", $("spostslist").setAttribute("class", ""), $("sonlinelist").setAttribute("class", "selected")), $("schatinhalt").setAttribute("class", "selected"), "social" == $setting.mainitems ? ($("social").style.display = "block", $("chatinhalt").style.display = "none", $("snewposts").setAttribute("class", "selected"), $("schatinhalt").setAttribute("class", ""), $("message").disabled = "disabled", $("message").placeholder = "غیر فعال") : ($("chatinhalt").style.display = "block", $("social").style.display = "none", $("snewposts").setAttribute("class", ""), $("schatinhalt").setAttribute("class", "selected"), $("message").disabled = ""), $("sonlinelist").onclick = function() {
        return 0 == $setting.allowonlinelist ? void alert("شما به لیست کاربران آنلاین دسترسی ندارید. برای استفاده فعالیت کرده و امتیاز و ارتقای درجه بگیرید.") : void("block" == $("postslist").style.display && ($("postslist").style.display = "none", $("onlinelist").style.display = "block", $("spostslist").setAttribute("class", ""), $("sonlinelist").setAttribute("class", "selected")))
    }, $("spostslist").onclick = function() {
        "block" == $("onlinelist").style.display && ($("onlinelist").style.display = "none", $("postslist").style.display = "block", $("spostslist").setAttribute("class", "selected"), $("sonlinelist").setAttribute("class", ""))
    }, $("schatinhalt").onclick = function() {
        "block" == $("social").style.display && ($("social").style.display = "none", $("chatinhalt").style.display = "block", $("snewposts").setAttribute("class", ""), $("schatinhalt").setAttribute("class", "selected"), $("message").disabled = "", $("message").placeholder = "متن ارسالی...")
    }, $("snewposts").onclick = function() {
        "block" == $("chatinhalt").style.display && ($("chatinhalt").style.display = "none", $("social").style.display = "block", $("snewposts").setAttribute("class", "selected"), $("schatinhalt").setAttribute("class", ""), $("message").disabled = "disabled", $("message").placeholder = "غیر فعال")
    };
    var a = 0;
    $("reloadsocial").onclick = function() {
        return 1 == a ? (alert("در حال پردازش"), !1) : (a = 1, alert("درخواست در حال اجرا"), void new Ajax.Request(url("ajax/chat.social"), {
            onSuccess: function(b) {
                $network = b.responseJSON, socialnet(), a = 0
            },
            method: "get"
        }))
    }
}
    var old = 0;

function socialnet() {
    var mosi = 0;
    $("postslist").update(""), $("postslist").insert("<div>آخرین تغییرات پروفایل شما و دوستانتان</div>");
    var lc = $network.lkcm;
    if (lc > 0) {
        mosi++;
        $("postslist").insert('<div id="post_div_1"><div style="float:left;"><div style="float: right; text-align: left;"><span class="useri" onclick="listlikecm()">مشاهده</span><br></div></div><div class="details">شما تعداد ' + lc + ' لایک یا نظر در پروفایل خود دارید</div><div style="clear:both"></div></div>');
    }
    var xpe = $network.xpending;
    if (xpe > 0) {
        mosi++;
        $("postslist").insert('<div id="post_div_1"><div style="float:left;"><div style="float: right; text-align: left;"><span class="useri" onclick="showpending()">مشاهده</span><br></div></div><div class="details">شما ' + xpe + ' درخواست دوستی دارید</div><div style="clear:both"></div></div>');
    }
    var off = $network.offlinemsg;
    if (off > 0) {
        mosi++;
        $("postslist").insert('<div id="post_div_2"><div style="float:left;"><div style="float: right; text-align: left;"><span class="useri" onclick="offMsg()">مشاهده</span><br></div></div><div class="details">شما  ' + off + ' پیام افلاین دارید</div><div style="clear:both"></div></div>');
    }
    var a = $network.lastposts;
    for (var b = 0; b != a.length; b++) {
        mosi++;
        var c = getType(a[b].type),
            d = "on" == a[b].online ? theme_url("theme/images/chat/online.png") : theme_url("theme/images/chat/offline_pro.png");
        "آنلاین" == a[b].online ? "green" : "gray", $("postslist").insert('<div id="post_div_' + a[b].id + '"><div style="float:left;"> <a href="/profile/' + a[b].id + '" target="_blank"><img src="' + a[b].avatar + '" title="برای مشاهده پروفایل کلیک کنید" class="avatar"></a><div style="float: right; text-align: left;"><span class="useri" id="inflblock_' + a[b].id + '">' + a[b].name + '</span><br><span><img src="' + d + '"></span></div></div><div class="details">' + a[b].time + '<div style="color:' + c.color + '"><img src="' + c.icon + '"></div></div><div style="clear:both"></div></div>')
       }
    $("spostslist").innerHTML = 'بخش اجتماعی <span>' + mosi + '</span>';
}

function window_height2() {
    if (window.innerHeight) $("postslist") ? $("postslist").style.height = eval(window.innerHeight - 150) + "px" : "", $("social") ? $("social").style.height = eval(window.innerHeight - 150) + "px" : "";
    else {
        if (!document.documentElement || !document.documentElement.clientWidth) return 0;
        try {
            $("postslist") && ($("postslist").style.height = eval(document.documentElement.clientHeight - 150) + "px"), $("social") && ($("social").style.height = eval(document.documentElement.clientHeight - 150) + "px")
        } catch (a) {
            return 0
        }
    }
}



function CloseM() {
    document.getElementById("modalb").style.display = "none"
}
var $network = {},
    $fnetwork = {};
ready(function() {
    TarhPardaz_New_Js()
});
