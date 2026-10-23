// === Avatar functions ===
function show_pic_avatar(e) {
    var i = parseInt($("#span_show_picture_current").html());
    var t = parseInt($("#span_show_picture_all").html());
    if ("next" == e && i < t) {
        $("#span_show_picture_current").html(i + 1);
        $("#img_avatar_show").attr("src", $config.avatar_dir + $config.avatars[i]);
        $("#now_profile_avatar").val($config.avatars[i]);
    } else if ("prev" == e && i > 1) {
        $("#span_show_picture_current").html(i - 1);
        $("#img_avatar_show").attr("src", $config.avatar_dir + $config.avatars[i - 2]);
        $("#now_profile_avatar").val($config.avatars[i - 2]);
    }
}
function set_avatar() {
    $.ajax({ type: "POST", url: "/ajax/chat.profile.avatars", data: { action: "set_avatar", avatar: $("#now_profile_avatar").val() },
        success: function(e) { "refresh" === e ? document.location.reload() : alert(e); },
        error: function() { alert("error: check internet connection"); }
    });
}
function delete_avatar() {
    $.ajax({ type: "POST", url: "/ajax/chat.profile.avatars", data: { action: "delete_avatar", avatar: $("#now_profile_avatar").val() },
        success: function(e) { "refresh" === e ? document.location.reload() : alert(e); },
        error: function() { alert("error: check internet connection"); }
    });
}

// === Post functions ===
function editpost(e) {
    $(".bs-editpost-lg #editpostid").val(e);
    $.ajax({ type: "POST", url: "/ajax/chat.profile.editpost", data: { action: "get", pid: e }, datatype: "json",
        success: function(e) {
            if ("success" == e.type) { $(".bs-editpost-lg #editposttext").val(e.text); $(".bs-editpost-lg").modal("show"); }
            else if ("error" == e.type) { swal({ title: "خطا", text: "پست یافت نشد.", type: "warning", showCancelButton: false, confirmButtonColor: "#DD6B55", confirmButtonText: "OK", closeOnConfirm: true }); }
        }
    });
}
function postlike(e, i) {
    $.ajax({ type: "POST", url: "/ajax/chat.profile.postlike", data: { action: i, pid: e, trust: "false" }, datatype: "json",
        success: function(t) {
            if ("error" == t.type) {
                if ("me" == t.text) { swal("شما نمیتوانید مطالب خود را لایک یا دیسلایک کنید", "", "error"); return false; }
                swal({ title: "آیا شما مطمئن هستید؟", text: t.text, type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "بله پاک کن !", closeOnConfirm: false },
                    function() {
                        $.ajax({ type: "POST", url: "/ajax/chat.profile.postlike", data: { action: i, pid: e, trust: "true" }, datatype: "json",
                            success: function(t) {
                                if ("success" == t.type) {
                                    if ("like" == i) { swal(t.text, "", t.type); var s = $("#plikeid-" + e).text(); $("#plikeid-" + e).html(parseInt(s) - 1); }
                                    else { swal(t.text, "", t.type); var o = $("#pdislikeid-" + e).text(); o = o.replace("-", ""); $("#pdislikeid-" + e).html("-" + (parseInt(o) - 1)); }
                                } else { swal(t.text, "", t.type); }
                            }
                        });
                    }
                );
            } else if ("success" == t.type) {
                if ("like" == i) { var s = $("#plikeid-" + e).text(); $("#plikeid-" + e).html(parseInt(s) + 1); }
                else { var o = $("#pdislikeid-" + e).text(); o = o.replace("-", ""); $("#pdislikeid-" + e).html("-" + (parseInt(o) + 1)); }
            } else if ("error" == t.type) { swal(t.text, "", t.type); }
        }
    });
}
function UserLike(e, i, t) {
    $.ajax({ type: "POST", url: "/ajax/chat.profile.userlike", data: { action: i, pid: e, trust: "false", location: t }, datatype: "json",
        success: function(s) {
            if ("me" == s.text) { swal("شما نمیتوانید پروفایل خود را لایک یا دیسلایک کنید", "", "error"); return false; }
            if ("errors" == s.type) {
                swal({ title: "آیا شما مطمئن هستید؟", text: s.text, type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "بله پاک کن !", closeOnConfirm: false },
                    function() {
                        $.ajax({ type: "POST", url: "/ajax/chat.profile.userlike", data: { action: i, pid: e, trust: "true", location: t }, datatype: "json",
                            success: function(r) {
                                if ("success" == r.type) {
                                    if ("like" == i) {
                                        swal(r.text, "", r.type);
                                        var a = $("#plikeidav-" + e).text(), o = $("#plikeidco-" + e).text(), l = $("#plikeidinf-" + e).text(), m = $("#plikeidmu-" + e).text();
                                        $("#plikeidav-" + e).html(parseInt(a) + 1); $("#plikeidco-" + e).html(parseInt(o) + 1);
                                        $("#plikeidinf-" + e).html(parseInt(l) + 1); $("#plikeidmu-" + e).html(parseInt(m) + 1);
                                    } else {
                                        var r2 = $("#pdislikeidav-" + e).text(), c = $("#pdislikeidco-" + e).text(), n = $("#pdislikeidinf-" + e).text(), g = $("#pdislikeidmu-" + e).text();
                                        r2 = r2.replace("-", ""); c = c.replace("-", ""); n = n.replace("-", ""); g = g.replace("-", "");
                                        $("#pdislikeidav-" + e).html("-" + (parseInt(r2) + 1)); $("#pdislikeidco-" + e).html("-" + (parseInt(c) + 1));
                                        $("#pdislikeidinf-" + e).html("-" + (parseInt(n) + 1)); $("#pdislikeidmu-" + e).html("-" + (parseInt(g) + 1));
                                    }
                                } else { swal(r.text, "", r.type); }
                            }
                        });
                    }
                );
            } else if ("success" == s.type) {
                if ("like" == i) { var t2 = $("#ValueLikeUser").text(); $("#ValueLikeUser").html(parseInt(t2) + 1); }
                else { var s2 = $("#ValueDisLikeUser").text(); s2 = s2.replace("-", ""); $("#ValueDisLikeUser").html("-" + (parseInt(s2) + 1)); }
            } else if ("error" == s.type) { swal(s.text, "", s.type); }
        }
    });
}
var _profilePointLock = false;
function ProfilePoint(e) {
    if (_profilePointLock) return;
    if ($config.profile_id == $config.myid) return false;
    _profilePointLock = true;
    setTimeout(function() { _profilePointLock = false; }, 2000);
    $.ajax({ type: "POST", url: "/ajax/chat.profile.like", data: { action: e, id: $config.profile_id, trust: "false" }, datatype: "json",
        success: function(i) {
            if ("errors" == i.type) {
                swal({ title: "آیا شما مطمئن هستید؟", text: i.text, type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "بله پاک کن !", closeOnConfirm: false },
                    function() {
                        $.ajax({ type: "POST", url: "/ajax/chat.profile.like", data: { action: e, id: $config.profile_id, trust: "true" }, datatype: "json",
                            success: function(i) {
                                _profilePointLock = false;
                                if ("success" == i.type) { swal(i.text, "", i.type); } else { swal(i.text, "", i.type); }
                            }
                        });
                    }
                );
            } else if ("success" == i.type) {
                if ("like" == e) {
                    var t = $("#ValueLikeUser").text();
                    $("#ValueLikeUser").html(parseInt(t) + 1);
                } else {
                    var s = $("#ValueDisLikeUser").text().replace("-", "");
                    $("#ValueDisLikeUser").html(parseInt(s) + 1);
                }
            } else if ("error" == i.type) { swal(i.text, "", i.type); _profilePointLock = false; }
        }
    });
}

// === Post management ===
function ProfileDeletePost(e) {
    $.ajax({ type: "POST", url: "/ajax/chat.profile.deletepost", data: { pid: e }, datatype: "json",
        success: function(i) { if ("success" == i.type) { $("#panelid-" + e).toggle(2000); } }
    });
}
function ProfilePostManager(e, i) {
    $.ajax({ type: "POST", url: "/ajax/chat.profile.postmanager", data: { id: $config.profile_id, pid: e, action: i }, datatype: "json",
        success: function(t) {
            if ("success" == t.type) {
                var newAction = ("1" == i) ? "0" : "1";
                var newLabel = ("1" == i) ? '<center><font color="green">فعال کردن نظرات</font></center>' : '<center><font color="red">غیر فعال کردن نظرات</font></center>';
                $("#postmanager-" + e).attr("onclick", 'ProfilePostManager("' + e + '", "' + newAction + '")');
                $("#postmanager-" + e).html(newLabel);
            } else { swal(t.text, "", t.type); }
        }
    });
}
function PostReport(e) {
    $.ajax({ type: "POST", url: "/ajax/chat.profile.postreport", data: { pid: e, proid: $config.profile_id }, datatype: "json",
        success: function(e) { swal(e.text, "", e.type); }
    });
}

// === Comments ===
function ShowCommentsProfile(e, i) {
    var t = i.replace("-", "");
    if ("cover" != t && "info" != t && "avatar" != t && "music" != t && "" != t) { ShowW8("showw8-" + e); lasttime = t; i = ""; }
    else { lasttime = 0; ShowW8("append-comments-" + i + e); }
    $.ajax({ type: "POST", url: "/ajax/chat.profile.getcomment", data: { pid: e, action: i.replace("-", ""), time: lasttime },
        success: function(t) {
            if (0 == lasttime) {
                $("#append-comments-" + i + e).html(t);
                var s = (lasttime = $("#append-comments-" + i + e).children().last().attr("id")).split("-");
                var o = "ShowCommentsProfile('" + e + "','" + s[1] + "')";
                $("#reloadtime-" + e).attr("onclick", o);
            } else {
                $("#showw8-" + i + e).html("");
                while ($("#append-comments-" + i + e + " #cmid-now").length) { $("#append-comments-" + i + e + " #cmid-now").remove(); }
                var s = (lasttime = $("#append-comments-" + i + e).children().last().attr("id")).split("-");
                var o = "ShowCommentsProfile('" + e + "','" + s[1] + "')";
                $("#reloadtime-" + e).attr("onclick", o);
            }
        }
    });
}
function ShowReplies(e, i) {
    ShowW8("show-" + i);
    $.ajax({ type: "POST", url: "/ajax/chat.profile.getreplies", data: { pid: e, cid: i },
        success: function(e) { $("#split-" + i).html("<br><hr>"); $("#show-" + i).html(e); }
    });
}
function SendCommentUser(e, i) {
    ShowW8("showw8-" + i + e);
    var t = $("#postid-" + i + e + " textarea").val();
    if (/^\s*$/.test(t)) { $("#showw8-" + i + e).html(""); return false; }
    var s = replaceSmileis(t, false);
    $.ajax({ type: "POST", url: "/ajax/chat.profile.sendcomment", data: { comment: s, pid: e, action: i.replace("-", "") },
        success: function(t) {
            if ("success" == t.type) {
                $("#showw8-" + i + e).html("");
                var o = '<div id="cmid-now" class="row"><div class="col-lg-12"><div class="media"><div class="pull-right"><a class="pull-left" href="/profile/' + $config.myid + '"><img class="media-object" src="' + $config.myavatar + '"></a></div><div class="media-body"><h5 class="media-heading"><a href="/profile/' + $config.myid + '">' + $config.myname + '</a> <small>لحظاتی پیش</small></h5><p>' + s + '</p></div></div></div></div>';
                $("#append-comments-" + i + e).append(o);
                $("#postid-" + i + e + " textarea").val("");
            } else { $("#showw8-" + i + e).html(""); swal(t.text, "", t.type); }
        }
    });
}
function SendReplyComment(e, i) {
    var t = $("#postid-" + e + " textarea").val();
    if (/^\s*$/.test(t)) return false;
    $.ajax({ type: "POST", url: "/ajax/chat.profile.replycomment", data: { comment: t, pid: e, cid: i },
        success: function(i) { alert(i); $("#postid-" + e + " textarea").val(""); }
    });
}
function DeleteComment(e, i, t, s, o) {
    $.ajax({ type: "POST", url: "/ajax/chat.profile.deletecomment", data: { pid: e, uid: t, cid: i, time: s, action: o }, datatype: "json",
        success: function(e) {
            if ("success" == e.type) { $("#" + i).toggle(2000); }
            else if ("error" == e.type) { swal(e.text, "", "error"); }
            else { alert(e); }
        }
    });
}
function DeleteCommentReply(e, i, t, s, o) {
    $.ajax({ type: "POST", url: "/ajax/chat.profile.deletereplies", data: { pid: e, uid: s, cid: i, ccid: t, time: o }, datatype: "json",
        success: function(t) {
            if ("success" == t.type) { $("#panelid-" + e + " #replyid-" + i).toggle(2000); }
            else if ("error" == t.type) { swal(t.text, "", "error"); }
            else { alert(t); }
        }
    });
}

// === Profile management ===
function ProfileReset(e) {
    if ("021" !== prompt("لطفا پسورد مدیریتی را وارد نمایید:")) { alert("پسورد اشتباه است. دسترسی مقدور نیست."); return; }
    if ("all" != e && !confirm("آیا شما مطمئن هستید؟\n با این کار کل مطالب ارسالی کاربر از پروفایل -اطلاعات خصوصی و... پاک خواهد شد و قابل برگشت نیستند!")) return;
    $.ajax({ type: "POST", url: "/ajax/mods.profilereset", data: { uid: $config.profile_id, action: e, token: $config.token },
        success: function(i) {
            if ("disable" == i || "enable" == i) {
                var isDisable = ("disable" == e);
                $("#profileresetmanager").attr("onclick", 'ProfileReset("' + (isDisable ? "enable" : "disable") + '")');
                $("#profileresetmanager").html(isDisable ? '<font color="green">فعال کردن پروفایل</font>' : '<font color="red">غیر فعال کردن پروفایل</font>');
            } else { alert(i); }
        }
    });
}
function BlackList(e) {
    $.ajax({ type: "POST", url: "/ajax/chat.profile.blacklist", data: { pid: $config.profile_id, action: e }, datatype: "json",
        success: function(i) {
            if ("success" == i.type) {
                swal(i.text, "", i.type);
                if ("add" == e) { $("#blacklistmanager").attr("onclick", "BlackList('del')"); $("#blacklistmanager").html('<font color="green">برقراری ارتباط</font>'); }
                else { $("#blacklistmanager").attr("onclick", "BlackList('add')"); $("#blacklistmanager").html('<font color="red">قطع ارتباط</font>'); }
            } else { swal(i.text, "", i.type); }
        }
    });
}
function SendReques2friend(e) {
    if (!confirm("آیا شما مطمئن هستید؟")) return;
    $.ajax({ type: "POST", url: "/ajax/" + ("add" == e ? "chat.profile.blacklistmanager" : "chat.showmyfriends"),
        data: { id: $config.profile_id, action: e, location: "profile" },
        success: function(e) { alert(e); }
    });
}

// === Offline messages ===
function OfflineMessage() {
    $(".offlinemsg .modal-body").load("/ajax/chat.profile.offlinemsg", { userid: $config.profile_id },
        function(e) { $(".offlinemsg h4.modal-title").html("پیام های آفلاین"); $(".offlinemsg").modal("show"); }
    );
}
function DeleteOfflineMessage() {
    $.ajax({ type: "POST", url: "/ajax/chat.profile.offlinemsg", data: { userid: $config.profile_id, del: "true" }, datatype: "json",
        success: function(e) {
            if (typeof e === "object") { swal(e.text, "", e.type); }
            else { swal("پیام‌ها حذف شد", "", "success"); }
        }
    });
}

// === Followers ===
function UserFollowers(e) {
    if ("show" == e) {
        $(".followers .modal-body .friends").load("/ajax/chat.profile.followers", { action: e, pid: $config.profile_id },
            function(e) { $(".followers h4.modal-title").html("دنبال کنندگان"); $(".followers").modal("show"); }
        );
    } else {
        $("#userfollowmanager").html('<i class="fa fa-link"></i><span><img style="position:relative;" src="' + $config.ajax_loader + '">');
        $.ajax({ type: "POST", url: "/ajax/chat.profile.followers", data: { action: e, pid: $config.profile_id }, datatype: "json",
            success: function(i) { swal(i.text, "", i.type); }
        });
    }
}

// === Show lists ===
function ShowLDMain(e) {
    var i = ("liked" == e) ? "کاربرانی که پسندیدند" : "کاربرانی که نپسندیدند";
    $(".bs-profile-ldmain-lg .modal-body .friends").load("/ajax/chat.profile.showlikes", { action: e, id: $config.profile_id },
        function(e) { $(".bs-profile-ldmain-lg h4.modal-title").html(i); $(".bs-profile-ldmain-lg").modal("show"); }
    );
}
function ShowLDUser(e, i, t) {
    var s = ("liked" == i) ? "کاربرانی که پسندیدند" : "کاربرانی که نپسندیدند";
    $(".ldposts .modal-body .friends").load("/ajax/chat.profile.showlikes", { action: i, id: e, loc: t },
        function(e) { $(".ldposts h4.modal-title").html(s); $(".ldposts").modal("show"); }
    );
}

// === Smiley ===
function replaceSmileis(e, i) {
    for (var t, s = [], o = [], a = 1; a <= 119; a++) {
        s.push("s" + a + "-"); o.push('<img src=/theme/microblog/images/smiley/' + a + '.gif>');
    }
    for (var a = 0; a < s.length; a++) {
        if (i) { t = RegExp(o[a], "g"); e = e.replace(t, s[a]); }
        else { t = RegExp(s[a], "g"); e = e.replace(t, o[a]); }
    }
    return e;
}
function SmileArea(e) {
    ShowW8("smilearea-loader-" + e);
    if ("none" == $("#smilearea-" + e).css("display")) {
        $.ajax({ type: "GET", url: "/ajax/chat.profile.loadsmiley",
            success: function(i) {
                $("#smilearea-loader-" + e).html("");
                $("#smilearea-" + e).html(i.replace(/\[id\]/g, e));
                $("#smilearea-" + e).css("display", "block");
            }
        });
    } else {
        $("#smilearea-loader-" + e).html("");
        $("#smilearea-" + e).css("display", "none");
    }
}
function SmileInner(e, i) {
    var t = $(i).attr("rel");
    var s = $("#postid-" + e + " textarea").val();
    $("#postid-" + e + " textarea").val(s + t).focus();
}
function SmileInShout(e) {
    var i = $(e).attr("rel");
    var t = $("#showshout input").val();
    $("#showshout input").val(t + i).focus();
}
function ShowSmileShout() {
    $("#showsmileshout").html("");
    if ("none" == $("#showsmileshout").css("display")) {
        ShowW8("showsmileshout-loader");
        _Ajax(function(e) { $("#showsmileshout").html(e); $("#showsmileshout-loader").html(""); $("#showsmileshout").show(); }, "loadsmiley", {}, false);
    } else { $("#showsmileshout").hide(); }
}

// === Shout / Single edit ===
function SingleEdit(e) {
    if ("shout" == e) {
        var i = $("#showshout input").val();
        _Ajax(function(e) {
            if ("success" == e.type) { $("#showshout, #showsmileshout").hide(); $("#hiddenshout").show(); $("#hiddenshout span").html(replaceSmileis(i, false)); }
            else if ("error" == e.type) { swal(e.text, "", e.type); }
        }, "singleedit", { content: i, action: e }, true);
    }
}

// === Reply helpers ===
function UserReplyComment(e, i, t) {
    $("#reply-" + t).show();
    $("#comments-form-" + t + " button[type=submit]").attr("onclick", 'SendReplyComment("' + t + '", "' + i + '");return false;');
    $("#reply-" + t).html('پاسخ به نظر <b>' + e + '</b> ، <a class="cursor" onclick="hideReply(' + t + ');return false;">لغو پاسخ</a>');
    $("#postid-" + t + " textarea").focus();
}
function hideReply(e) {
    $("#comments-form-" + e + " button[type=submit]").attr("onclick", 'SendCommentUser("' + e + '", "");return false;');
    $("#reply-" + e).hide();
}

// === Utilities ===
function ShowW8(e) { $("#" + e).html('<center><img src="' + $config.ajax_loader + '"></center>'); }
function GetLastPost() {
    ShowW8("show-ajax-w8");
    $.ajax({ type: "POST", url: "/ajax/chat.profile.getlastpost", data: { id: $config.profile_id },
        success: function(e) { $("#show-ajax-w8").html(""); $("#getajaxposts").after(e); }
    });
}
function ProfileCountPlayedMusic(e) {
    $.ajax({ type: "POST", url: "/ajax/chat.profile.upmusic", data: { action: "count", id: $config.profile_id },
        success: function(i) { window.open(e); }
    });
}
function _Ajax(e, i, t, s) {
    if (s) {
        $.ajax({ type: "POST", url: "/ajax/chat.profile." + i, data: t, datatype: "json", success: function(i) { e(i); } });
    } else {
        $.ajax({ type: "POST", url: "/ajax/chat.profile." + i, data: t, success: function(i) { e(i); } });
    }
}

// === Image resize + click to open ===
$(".panel-default .panel-body img").each(function() {
    var e = $(this).width(), i = $(this).height();
    if (e > 500) $(this).width(500);
    if (i > 500) $(this).height(500);
});
$(".panel-default .panel-body img").css("cursor", "pointer").on("click", function() {
    var e = $(this).attr("src");
    if (e) window.open(e, "_blank");
});

// === Help section ===
$("#helpsec").click(function() { $("#helpinfo").slideDown(1000); $("#helpsec").hide(1000); $("#closehelpsec").show(1000); });
$("#closehelpsec").click(function() { $("#helpinfo").slideUp(1000); $("#closehelpsec").hide(1000); $("#helpsec").show(1000); });

// === SCEditor init ===
$(function() {
    $("textarea#postval").sceditor({
        plugins: "bbcode", width: "98%", style: $config.style_sceeditor, locale: "fa", rtl: true, height: "100px",
        toolbarExclude: "source,print,table,subscript,superscript,quote,font",
        emoticonsRoot: "/theme/microblog/editor/themes/", resizeWidth: false
    });
});

// === Tooltips ===
$(function() { $(".sceditor-button").tooltip(); });
$(function() { $('[data-toggle="tooltip"]').tooltip(); });

// === Affix sidebar ===
$(function() {
    var e, i = 0;
    $(".info").affix({ offset: { top: 295 } });
    var t = $(".container").position().left > 0 ? $(".container").position().left : $(".container").offset().left;
    e = $(window).width();
    $(window).resize(function() {
        $(".info.affix").width($(".info").width());
        $(".info.affix").css("left", t + $(".container").width() - $(".info.affix").width() + 13);
        t = $(".container").position().left > 0 ? $(".container").position().left : $(".container").offset().left;
    });
    $(".info").on("affixed.bs.affix", function() { $(".info.affix").width(Math.floor(23 * $(".content").width() / 100) - 15); i = $(".info.affix").position().left; });
    $(".info").on("affix.bs.affix", function() { $(".info.affix").width(Math.floor(23 * $(".content").width() / 100) - 15); });
    $(window).scroll(function() {
        if (i > 0 && 0 != jQuery(this).scrollLeft()) { $(".info.affix").css("left", i - jQuery(this).scrollLeft()); }
        if (0 != jQuery(this).scrollTop()) {
            $(".info.affix").css("left", t + $(".container").width() - $(".info.affix").width() + 28 - jQuery(this).scrollLeft());
            $(".info.affix").width(Math.floor(23 * $(".content").width() / 100) - 15);
        }
    });
    if ($(".info").hasClass("affix")) { $(".info.affix").width(Math.floor(23 * $(".content").width() / 100) - 15); }
});

// === Main ready block ===
$(document).ready(function() {
    // Infinite scroll
    var scrollPage = 1, scrollLoading = false, scrollTotal = parseInt($config.total_groups) || 0;
    $(window).off("scroll.infiniteScroll").on("scroll.infiniteScroll", function() {
        if (!scrollLoading && scrollPage <= scrollTotal) {
            var scrollPos = $(window).scrollTop() + $(window).height();
            var docHeight = $(document).height();
            if (scrollPos >= docHeight - 500) {
                scrollLoading = true;
                ShowW8("show-ajax-w8");
                $.ajax({
                    type: "POST", url: $config.scroll_loding_url,
                    data: { group_no: scrollPage, id: $config.profile_id },
                    success: function(data) {
                        if ($.trim(data) != "") { $("#results").append(data); scrollPage++; }
                        $("#show-ajax-w8").html(""); scrollLoading = false;
                    },
                    error: function() { $("#show-ajax-w8").html(""); scrollLoading = false; }
                });
            }
        }
    });

    // Cover click opens modal (but not when clicking like/dislike)
    $(".h-cover").off("click.coverModal").on("click.coverModal", function(e) {
        if ($(e.target).closest("a").length === 0 && $(e.target).closest("#rating").length === 0 && $(e.target).closest("#likes").length === 0) { $(".bs-cover-lg").modal("show"); }
    });

    // Autocomplete
    $("#autocomplete").autocomplete({
        source: "/ajax/chat.profile.search", minLength: 2,
        select: function(e, i) { if (i.item) location.href = "/profile/" + i.item.id; },
        search: function() { $("#loading-user").show(); $(".track3").parent().remove(); $(".ui-autocomplete").css("padding-right", "0"); },
        response: function() { $("#loading-user").hide(); $(".ui-autocomplete").enscroll({ showOnHover: false, verticalTrackClass: "track3", verticalHandleClass: "handle3", zIndex: 1050 }); }
    });

    // Post form
    $("#postform").submit(function(e) {
        e.preventDefault();
        var i = $("#postval").val();
        if (/^\s*$/.test(i)) return false;
        $("#sendpostbtn").attr("disabled", true);
        $.ajax({ type: "POST", url: "/ajax/chat.profile.post", data: { post: i }, datatype: "json",
            success: function(e) {
                if ("success" == e.type) { $(".sceditor-container iframe").contents().find("body").text(""); GetLastPost(); }
                else { swal(e.text, "", e.type); }
                setTimeout(function() { $("#sendpostbtn").removeAttr("disabled"); }, 2000);
            }
        });
    });

    // Message form
    $("#messageform").submit(function(e) {
        e.preventDefault();
        var i = $("#messageval").val();
        $.ajax({ type: "POST", url: "/ajax/chat.profile.message", data: { post: i, id: $config.profile_id }, datatype: "json",
            success: function(e) { swal(e.text, "", e.type); }
        });
        return false;
    });

    // Friends list
    $("#myfriends .more").click(function() {
        $(".bs-more .modal-body .friends").load("/ajax/chat.profile.friendslist", { userid: $config.profile_id },
            function(e) { $(".bs-more h4.modal-title").html("لیست کامل دوستان"); $(".bs-more").modal("show"); }
        );
    });

    // User info
    $("#userinfo .more").click(function() { $(".userinfo-more h4.modal-title").html("توضیحات"); $(".userinfo-more").modal("show"); });

    // Music comments
    $("#mymusic .more-right").click(function() { $(".comments-mymusic h4.modal-title").html("آهنگ مورد علاقه"); $(".comments-mymusic").modal("show"); });

    // Edit post form
    $("#editpost").submit(function(e) {
        e.preventDefault();
        var i = $(".bs-editpost-lg #editposttext").val();
        var t = $(".bs-editpost-lg #editpostid").val();
        $.ajax({ type: "POST", url: "/ajax/chat.profile.editpost", data: { post: i, action: "update", pid: t }, datatype: "json",
            success: function(e) {
                $("#editpostid").val(""); $("#editposttext").val("");
                $("#panelid-" + t + " .panel-body span").html(e.html);
                $(".bs-editpost-lg").modal("toggle");
                swal(e.text, "", e.type);
            }
        });
    });
});
