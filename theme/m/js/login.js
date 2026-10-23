function url(e) {
    if ($url_type == "rewrite") {
        return "/" + e + $rewrite_prefix
    } else if ($url_type == "i") {
        return "?i=" + e.replace(/\//, "&")
    }
}

function theme_url(e) {
    if (e.substr(0, 6) != "theme/") {
        return "/theme/" + $theme + "/" + e
    } else {
        return "/" + e
    }
}

function login(e) {
    if (firstlogin == true) {
        $("#password").val("");
        firstlogin = false
    }
    var t = e.username.value;
    if (t == "" || t == " ") {
        alert("لطفا نام خود را وارد کنید");
        return false
    }
    $.ajax({
        type: "POST",
        url: url("ajax/login/multi"),
        data: $(e).serialize(),
        success: function(e) {
            switch (e) {
                case "ok":
                    top.location.href = url("mobile");
                    break;
                case "badname":
                    alert("این نام در لیست سیاه قرار دارد");
                    break;
                case "benn":
                    alert("شما از چت روم اخراج شده اید");
                    break;
                case "capacity":
                    alert("ظرفیت چت روم پر است");
                    break;
                case "running":
                    $("#lay_pw").slideDown(1e3);
                    $("#lay_gender").slideUp(1e3);
                    break;
                case "passwh":
                    $("#lay_pw").slideDown(1e3);
                    $("#lay_hide").slideDown(1e3);
                    $("#lay_gender").slideUp(1e3);
                    break;
                case "pass":
                    alert("کلمه عبور اشتباه است");
                    break;
                default:
                    alert(e);
                    break
            }
        }
    });
    return false
}(function(e) {
    if (typeof define === "function" && define.amd) {
        define(["jquery"], e)
    } else {
        e(jQuery)
    }
})(function(e) {
    function t(e) {
        return u.raw ? e : encodeURIComponent(e)
    }

    function n(e) {
        return u.raw ? e : decodeURIComponent(e)
    }

    function r(e) {
        return t(u.json ? JSON.stringify(e) : String(e))
    }

    function i(e) {
        if (e.indexOf('"') === 0) {
            e = e.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, "\\")
        }
        try {
            e = decodeURIComponent(e.replace(o, " "))
        } catch (t) {
            return
        }
        try {
            return u.json ? JSON.parse(e) : e
        } catch (t) {}
    }

    function s(t, n) {
        var r = u.raw ? t : i(t);
        return e.isFunction(n) ? n(r) : r
    }
    var o = /\+/g;
    var u = e.cookie = function(i, o, a) {
        if (o !== undefined && !e.isFunction(o)) {
            a = e.extend({}, u.defaults, a);
            if (typeof a.expires === "number") {
                var f = a.expires,
                    l = a.expires = new Date;
                l.setDate(l.getDate() + f)
            }
            return document.cookie = [t(i), "=", r(o), a.expires ? "; expires=" + a.expires.toUTCString() : "", a.path ? "; path=" + a.path : "", a.domain ? "; domain=" + a.domain : "", a.secure ? "; secure" : ""].join("")
        }
        var c = i ? undefined : {};
        var h = document.cookie ? document.cookie.split("; ") : [];
        for (var p = 0, d = h.length; p < d; p++) {
            var v = h[p].split("=");
            var m = n(v.shift());
            var g = v.join("=");
            if (i && i === m) {
                c = s(g, o);
                break
            }
            if (!i && (g = s(g)) !== undefined) {
                c[m] = g
            }
        }
        return c
    };
    u.defaults = {};
    e.removeCookie = function(t, n) {
        if (e.cookie(t) !== undefined) {
            e.cookie(t, "", e.extend({}, n, {
                expires: -1
            }));
            return true
        }
        return false
    }
});
var firstlogin = true,
    $url_type = "rewrite",
    $rewrite_prefix = "",
    $theme;
