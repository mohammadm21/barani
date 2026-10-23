/* BaraniChat — نسخه موبایل تلگرامی (theme/phone) — مستقل از jQuery و تم دسکتاپ */
(function () {
    'use strict';
    var ph = window.__phone || {};
    var me = Object.assign({}, ph.me || {});
    var $ = function (id) { return document.getElementById(id); };

    function esc(s) { return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; }); }
    function qs(sel, root) { return (root || document).querySelector(sel); }
    function qsa(sel, root) { return Array.prototype.slice.call((root || document).querySelectorAll(sel)); }
    function toast(msg) { var t = $('toastBox'); if (!t) { t = document.createElement('div'); t.id = 'toastBox'; t.className = 'toast'; document.body.appendChild(t); } t.textContent = msg; t.classList.add('show'); clearTimeout(t._h); t._h = setTimeout(function () { t.classList.remove('show'); }, 2300); }
    function avatarSrc(av) { if (!av) return ''; if (av.indexOf('http') === 0) return av; if (av.indexOf('/theme/') === 0) return av; if (av.indexOf('theme/') === 0) return '/' + av; return window.__G('images/avatars/' + av); }
    function crown(ad) { return ad ? ' <span class="crown" title="مدیر">👑</span>' : ''; }
    // لینکِ نمایش زمانِ باقی‌مانده‌ی خاموشی (دریافت موعد unix، نشان‌دادن d:h:m:s)
    function silentBadge(deadline) {
        if (!deadline) return '';
        var left = deadline - (Date.now() / 1000 | 0);
        if (left <= 0) return '';
        var d = Math.floor(left / 86400), h = Math.floor((left % 86400) / 3600),
            m = Math.floor((left % 3600) / 60), s = left % 60;
        var mm = m < 10 ? '0' + m : m, ss = s < 10 ? '0' + s : s;
        var t = (d > 0 ? d + 'd ' : '') + h + ':' + mm + ':' + ss;
        return ' <span class="sil-badge" title="ساکت تا ' + t + '">🔇 ' + t + '</span>';
    }
    // قفلِ خصوصیِ بسته: اگر کاربر وضعیتِ nopm دارد کنار اسمش آیکونِ 🔒 نمایش بده
    function privateLock(sp) { return sp === 'nopm' ? ' <span class="plock" title="خصوصی بسته است">🔒</span>' : ''; }
    function avatarHtml(av, name, online) {
        var inner = av ? '<img src="' + esc(avatarSrc(av)) + '">' : esc(String(name || '?').charAt(0));
        return '<span class="avatar-wrap' + (online ? ' on' : '') + '"><span class="avatar-inner">' + inner + '</span></span>';
    }
    function peopleToName(id) { if (id === -1) return 'سیستم'; var u = userIndex[id]; return u ? u.n : ('کاربر ' + id); }
    function textOf(m) {
        var t = String(m.text || '').replace(/\(s:?\d+\)/gi, ' ').replace(/<[^>]+>/g, ' ');
        smileTokens.forEach(function (tok) { t = t.split(tok).join(' '); });
        return t;
    }
    function escStr(s, max) { s = esc(s); if (max && s.length > max) s = s.slice(0, max) + '…'; return s; }
    function stripHtml(s) { return String(s == null ? '' : s).replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').replace(/^\s+|\s+$/g, ''); }
    function isJson(s) { try { JSON.parse(String(s)); return true; } catch (e) { return false; } }
    function pinTime(t) {
        t = Number(t) || 0; if (!t) return '';
        var d = new Date(t * 1000);
        var h = '' + d.getHours(), m = '' + d.getMinutes();
        if (h.length < 2) h = '0' + h; if (m.length < 2) m = '0' + m;
        return h + ':' + m;
    }

    // ---------- AJAX ----------
    function ajax(url, opts) {
        opts = opts || {};
        var xhr = new XMLHttpRequest();
        // ارسالِ FormData باید POST باشد (قبلاً GET باز می‌شد و body/فایل ریخته می‌شد)
        var method = opts.form ? 'POST' : (opts.method || 'GET');
        xhr.open(method, url, true);
        if (opts.form) { /* multipart; browser sets content-type+boundary */ }
        else if (method === 'POST') { xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8'); }
        if (opts.onprogress && xhr.upload) { xhr.upload.onprogress = opts.onprogress; }
        xhr.onreadystatechange = function () {
            if (xhr.readyState !== 4) return;
            if (opts.success) {
                var d;
                if (opts.json === false) { d = xhr.responseText; }
                else { try { d = JSON.parse(xhr.responseText); } catch (e) { d = { status: false, raw: xhr.responseText }; } }
                opts.success(d, xhr);
            }
        };
        xhr.onerror = function () { if (opts.onerr) opts.onerr(); };
        xhr.send(opts.form ? opts.form : (opts.body || null));
    }

    // ---------- state ----------
    var state = { tab: 'chats', convs: [], rooms: [], users: [], pin: null, open: null, msgs: [], lastId: 0, activeReply: null };
    var userIndex = {};

    // ---------- read/unread (کلاینتمحور) ----------
    // حافظهی «آخرین پیامِ ورودیِ دیدهشده» روی کلاینت (localStorage) نگهداری میشود؛
    // نه روی سرور. کلید: 'pm:<peer>' و 'room:<roomId>'.
    var SEEN_KEY = 'barani_phone_seen';
    var seenMap = null;        // lazy-load از localStorage
    var badgeCache = {};       // key -> {lastIn, count} تا شمارشِ تکراریِ ناخوانده انجام نشود
    function seenKey(into, to) { return (into === 'main') ? 'room:' + to : 'pm:' + to; }
    function getSeen(key) { ensureSeen(); var v = seenMap[key]; return (typeof v === 'number') ? v : 0; }
    function setSeen(key, id) { ensureSeen(); seenMap[key] = id; try { localStorage.setItem(SEEN_KEY, JSON.stringify(seenMap)); } catch (e) {} }
    function ensureSeen() {
        if (seenMap) return;
        seenMap = {};
        try { var raw = localStorage.getItem(SEEN_KEY); if (raw) { var p = JSON.parse(raw); if (p && typeof p === 'object') seenMap = p; } } catch (e) {}
    }
    // وقتی چتِ باز این پیامهای ورودی را دریافت کرده (و auto-scroll پایین است) یعنی دیده شده.
    function markSeen(into, to, lastIn) {
        if (!lastIn) return;
        var k = seenKey(into, to);
        if (lastIn > getSeen(k)) {
            setSeen(k, lastIn);
            delete badgeCache[k];
        }
    }
    // شمارشِ ناخوانده برای یک گفتگو: اگر last_in از دیدهشده جلوتر است، تاریخچهیِ بعد از
    // دیدهشده را میگیرد و خودِ کلاینت میشمارد (سرور فقط counted نمیفرستد). کَشِ شده.
    function countUnread(into, to, lastIn, cb) {
        var k = seenKey(into, to);
        var seen = getSeen(k);
        if (!lastIn || lastIn <= seen) { cb(0); return; }
        // اگر همین آخرینِ دیدهنشده همین الان در حال شمارش است، callbacks را صف کن که همگی
        // پس از اتمام عددِ درست را بگیرند (نه صفر)، تا badge ردیف و badge تب همراستا باشند.
        var existing = badgeCache[k];
        if (existing && existing.latestLastIn === lastIn) {
            if (existing.running) { existing.cbs = existing.cbs || []; existing.cbs.push(cb); return; }
            cb(existing.count); return;
        }
        var body = 'into=' + encodeURIComponent(into) + '&to=' + encodeURIComponent(to) + '&after=' + encodeURIComponent(seen) + '&limit=50';
        badgeCache[k] = { latestLastIn: lastIn, count: 0, running: 1, cbs: [cb] };
        var finish = function (cnt) {
            var c = badgeCache[k];
            if (c && c.cbs) { c.cbs.forEach(function (f) { f(cnt); }); }
            badgeCache[k] = { latestLastIn: lastIn, count: cnt, running: 0 };
        };
        ajax(window.__url('ajax/phone.get'), { method: 'POST', body: body, success: function (d) {
            var cnt = 0;
            if (d && d.status === 'ok' && d.h) {
                d.h.forEach(function (m) { if (m.user_id !== me.id) cnt++; });
            }
            finish(cnt);
        }, onerr: function () { badgeCache[k] = { latestLastIn: lastIn, count: 0, running: 0 }; cb(0); } });
    }

    // ---------- tabs & list ----------
    function setTab(t) { state.tab = t; qsa('.tab').forEach(function (b) { b.classList.toggle('active', b.getAttribute('data-tab') === t); }); renderList(); }
    function renderList() {
        var box = $('convScroll'); var html = '';
        if (state.tab === 'chats') {
            html += '<div class="section-title">گفتگوهای خصوصی</div>';
            if (!state.convs.length) html += '<div class="urow">مکالمه‌ای ندارید</div>';
            state.convs.forEach(function (c) {
                var k = 'pm:' + c.peer;
                // اگر چت باز نیست و پیامِ تازه هست، فوراً یک badge «پیشنویس» بگذار و عدد را async بشمار
                var live = (state.open && state.open.into === 'pm' && state.open.to === c.peer);
                var hasNew = (!live && c.last_in > getSeen(k));
                html += '<div class="conv" data-open="pm:' + c.peer + '">';
                html += avatarHtml(c.avatar, c.name, c.online);
                html += '<div class="conv-mid"><div class="conv-top"><span class="conv-name">' + esc(c.name) + privateLock(c.sp) + crown(c.ad) + silentBadge(c.sil) + '</span><span class="conv-time">' + esc(c.last_time || '') + '</span></div>';
                html += '<div class="conv-text"><span>' + escStr(stripHtml(c.last_text), 60) + '</span></div></div>';
                if (hasNew) html += '<span class="unread-badge" data-badge="pm:' + c.peer + '">…</span>';
                html += '</div>';
            });
        } else if (state.tab === 'rooms') {
            html += '<div class="section-title">گروه‌ها / روم‌ها</div>';
            state.rooms.forEach(function (r) {
                var live = (state.open && state.open.into === 'main' && state.open.to === r.i);
                var hasNew = (!live && (r.lastIn || 0) > getSeen('room:' + r.i));
                html += '<div class="conv" data-open="main:' + r.i + '">';
                html += '<span class="room-badge">' + esc(String(r.t || 'روم').charAt(0)) + '</span>';
                html += '<div class="conv-mid"><div class="conv-top"><span class="conv-name">' + esc(r.t) + '</span></div><div class="conv-text"><span>' + r.o + ' نفر آنلاین</span></div></div>';
                if (hasNew) html += '<span class="unread-badge" data-badge="room:' + r.i + '">…</span>';
                html += '</div>';
            });
        } else if (state.tab === 'users') {
            html += '<div class="section-title">کاربران آنلاین (' + state.users.length + ')</div>';
            state.users.forEach(function (u) {
                if (u.i === me.id) return;
                html += '<div class="urow" data-user="' + u.i + '">';
                html += avatarHtml(u.v, u.n, u.o);
                html += '<div class="conv-mid"><div class="conv-top"><span class="conv-name">' + esc(u.n) + privateLock(u.sp) + crown(u.ad) + silentBadge(u.sil) + '</span></div><div class="conv-text"><span>' + esc(typeTitle(u.t) || 'آنلاین') + '</span></div></div>';
                // دکمهٔ مدیریت (adminu_) روی هر کاربرِ آنلاین، فقط برای مدیران
                if (me.admin && me.admin !== '0' && u.i !== me.id) html += '<button class="row-admin" data-manage="' + u.i + '" title="مدیریت">⋯</button>';
                html += '</div>';
            });
        }
        box.innerHTML = html;
        qsa('[data-open]', box).forEach(function (el) { el.addEventListener('click', function () { var p = el.getAttribute('data-open').split(':'); openChat(p[0], parseInt(p[1], 10)); }); });
        qsa('[data-user]', box).forEach(function (el) { el.addEventListener('click', function () { openChat('pm', parseInt(el.getAttribute('data-user'), 10)); }); });
        qsa('[data-manage]', box).forEach(function (el) { el.addEventListener('click', function (e) { e.stopPropagation(); openUserAdmin(parseInt(el.getAttribute('data-manage'), 10)); }); });
        fillBadges(box);
    }

    // عددِ ناخوانده را async می‌شمارد (روی کلاینت) و روی badgeِ پیش‌نویس می‌نشاند
    function fillBadges(box) {
        qsa('[data-badge]', box).forEach(function (el) {
            var key = el.getAttribute('data-badge'); var sp = key.split(':'); var into = sp[0]; var to = parseInt(sp[1], 10);
            var lastIn = (into === 'main') ? roomLastInFor(to) : findConvLastIn(to);
            countUnread(into, to, lastIn, function (n) {
                if (n > 0) { el.textContent = n > 99 ? '99+' : n; el.classList.remove('pending'); }
                else el.remove();
            });
        });
    }
    function findConvLastIn(peer) { for (var i = 0; i < state.convs.length; i++) { if (state.convs[i].peer === peer) return state.convs[i].last_in || 0; } return 0; }
    function roomLastInFor(rid) { var s = state.roomLastInMap || {}; return s[rid] || 0; }

    // نشان‌دادنِ «تعدادِ کلِ پیام‌های ناخوانده» روی خودِ هر تب (چت‌ها / گروه‌ها) و به‌روزرسانیِ پیوسته.
    // از همان شمارشِ client-side استفاده می‌کند؛ چتِ باز حذف می‌شود (دیده‌شده).
    function setTabBadge(tab, count) {
        var b = qs('.tab[data-tab="' + tab + '"] .tab-badge');
        if (!b) return;
        if (count > 0) { b.textContent = count > 99 ? '99+' : count; b.classList.remove('hidden'); }
        else b.classList.add('hidden');
    }
    function updateTabBadges() {
        var openKey = state.open ? seenKey(state.open.into, state.open.to) : null;
        // چت‌ها: روی همهٔ conv های خصوصی
        var pmPending = [], roomPending = [];
        state.convs.forEach(function (c) {
            var k = 'pm:' + c.peer;
            if (openKey === k) return;
            if ((c.last_in || 0) > getSeen(k)) pmPending.push({ into: 'pm', to: c.peer, lastIn: c.last_in || 0 });
        });
        state.rooms.forEach(function (r) {
            var k = 'room:' + r.i;
            if (openKey === k) return;
            if ((r.lastIn || 0) > getSeen(k)) roomPending.push({ into: 'main', to: r.i, lastIn: r.lastIn || 0 });
        });
        var pmCount = 0, roomCount = 0, remainPm = pmPending.length, remainRoom = roomPending.length;
        pmPending.forEach(function (p) {
            countUnread(p.into, p.to, p.lastIn, function (n) {
                if (n > 0) pmCount += n; remainPm--; if (remainPm === 0) setTabBadge('chats', pmCount);
            });
        });
        roomPending.forEach(function (p) {
            countUnread(p.into, p.to, p.lastIn, function (n) {
                if (n > 0) roomCount += n; remainRoom--; if (remainRoom === 0) setTabBadge('rooms', roomCount);
            });
        });
        // اگر هیچ گفتگویی ناخوانده نیست، عدد صفر/مخفی
        if (pmPending.length === 0) setTabBadge('chats', 0);
        if (roomPending.length === 0) setTabBadge('rooms', 0);
    }

    // ---------- open chat ----------
    function openChat(into, to) {
        var title = '', sub = '';
        if (into === 'pm') { title = peopleToName(to); var u = userIndex[to]; sub = (u ? 'آنلاین' : ''); }
        else if (into === 'main') { var r = null; state.rooms.forEach(function (x) { if (x.i === to) r = x; }); title = r ? r.t : 'عمومی'; sub = (r ? r.o + ' نفر' : ''); }
        state.open = { into: into, to: to, title: title, sub: sub };
        state.msgs = []; state.lastId = 0; state.activeReply = null; hideReplyHint();
        $('chatTitle').textContent = title; $('chatSub').textContent = sub;
        updateHdr(into, to);
        // دسکتاپ: لیست سمت چپ همیشه باز می‌ماند و چت کنارش باز می‌شود (دو ستونه).
        if (!isDesktop()) { $('convList').style.display = 'none'; } else { $('convList').style.display = 'flex'; }
        $('chatPane').classList.remove('hidden');
        $('msgInput').focus();
        loadHistory(into, to, 0, 0);
        // بازکردنِ چت = دیدنِ آخرین پیامِ ورودی (همه چیز پایینِ لیست است و auto-scroll).
        // حافظه روی کلاینت ذخیره می‌شود تا badge های دیگر این گفتگو صفر بمانند.
        if (into === 'pm') markSeen('pm', to, findConvLastIn(to));
        else if (into === 'main') markSeen('main', to, roomLastInFor(to));
        // وقتی داخل یک روم/گروه باز می‌کنیم، باید کاربر را همان‌جا منتقل کنیم وگرنه پیامِ main
        // به اتاقِ قبلیِ (درون session) می‌رود نه به این اتاق.
        if (into === 'main' && to > 0 && to !== me.room) {
            enterRoom(to);
        }
        // پول رسیوِ سریع: هر ۳ ثانیه پیام‌های جدیدِ این گفتگو را بگیر که دریافت پیام کند نباشد.
        clearInterval(pollTimerHandle);
        pollTimerHandle = setInterval(pollRefresh, 3000);
    }
    function isDesktop() { return window.innerWidth >= 720; }
    function showList() {
        clearInterval(pollTimerHandle); state.open = null;
        if (!isDesktop()) { $('chatPane').classList.add('hidden'); $('convList').style.display = ''; }
    }
    // هدرِ چتِ خصوصی: عکس پروفایل + اسمِ سطحِ کاربری کنار نام (فقط برای pm)
    function updateHdr(into, to) {
        var av = $('hdrAvatar'), ct = $('chatType');
        if (into !== 'pm') { $('hdrAvatar').classList.add('hidden'); if (ct) ct.textContent = ''; return; }
        var u = userIndex[to] || {};
        var inner = $('hdrAvatarInner');
        var avurl = u.v || '';
        inner.innerHTML = avurl ? '<img src="' + esc(avatarSrc(avurl)) + '">' : esc(String(u.n || peopleToName(to) || '?').charAt(0));
        if (ct) ct.textContent = typeTitle(u.t);
        $('hdrAvatar').classList.remove('hidden');
        $('hdrAvatar').onclick = function () { if (window.__phone.url.profile) window.open(window.__phone.url.profile + '/' + to, '_blank'); };
    }
    // نمایشِ «در حال تایپ...» در خطِ زیرِ نام؛ وقتی کسی تایپ نمی‌کند، زیرنویسِ عادی را برمی‌گرداند
    function updateTypingHdr(typing) {
        var sub = $('chatSub'); if (!sub) return;
        var base = state.open ? state.open.sub : '';
        if (typing && typing.length) {
            sub.textContent = typing.length === 1 ? typing[0] + ' در حال نوشتن...' : typing.slice(0, 2).join('، ') + ' در حال نوشتن...';
            sub.classList.add('typing');
        } else {
            sub.textContent = base || '';
            sub.classList.remove('typing');
        }
    }

    // ادغامِ پیام‌های تازه در state.msgs با حذفِ تکراری و سورتِ صعودی بر اساس id.
    // سرور ممکن است ردیف‌ها را از message + message_archive با ترتیبِ نامرتب برگرداند؛
    // پس DOM همیشه از state.msgsِ سورت‌شده ساخته می‌شود تا ترتیبِ پیام‌ها اطمینان‌پذیر باشد.
    function mergeMsgs(newList) {
        var byId = {};
        state.msgs.forEach(function (m) { byId[m.id] = m; });
        (newList || []).forEach(function (m) { if (m && m.id) byId[m.id] = m; });
        state.msgs = Object.keys(byId).map(function (k) { return byId[+k]; }).sort(function (a, b) { return a.id - b.id; });
        var max = state.lastId;
        state.msgs.forEach(function (m) { if (m.id > max) max = m.id; });
        state.lastId = max;
    }
    // افزودنِ پیام‌های تازه به انتهای پنل. اگر پاسخی شامل پیامی با id کمتر از آخرینِ رندر‌شده
    // ولی هنوز رندرنشده باشد (یک گپ/جانمایی در وسط)، کل پنل به‌شکلِ ترتیب‌دار بازسازی می‌شود
    // تا «یک عقب، یک جلو» رخ ندهد.
    function domMaxId() {
        var mx = 0, els = qsa('.msg[data-mid]', $('msgScroll'));
        for (var i = 0; i < els.length; i++) {
            var v = parseInt(els[i].getAttribute('data-mid'), 10);
            if (v > mx) mx = v;
        }
        return mx;
    }
    function appendNew() {
        var box = $('msgScroll');
        // پیام‌های موقتِ «در حال ارسال» اثبات‌شده با پیام واقعی از poll حذف می‌شوند
        qsa('.pending-sent', box).forEach(function (el) { el.remove(); });
        var renderedMax = domMaxId();
        var needsRebuild = false;
        state.msgs.forEach(function (m) {
            if (m.hidden) return;
            if (m.id <= renderedMax && !qs('[data-mid="' + m.id + '"]', box)) { needsRebuild = true; return; }
        });
        if (needsRebuild) { renderAll(); return; }
        var lastDay = null;
        var dec = qs('.day-sep:last-child', box); if (dec) lastDay = dec.textContent;
        var added = false;
        state.msgs.forEach(function (m) {
            if (m.hidden) return;
            if (qs('[data-mid="' + m.id + '"]', box)) return;
            if (m.date && m.date !== lastDay) { box.insertAdjacentHTML('beforeend', '<div class="day-sep">' + esc(m.date) + '</div>'); lastDay = m.date; }
            box.insertAdjacentHTML('beforeend', msgHtml(m));
            added = true;
        });
        if (added) { wireMsgClicks(); box.scrollTop = box.scrollHeight; }
    }
    function loadHistory(into, to, after, before) {
        var body = 'into=' + encodeURIComponent(into) + '&to=' + encodeURIComponent(to) + '&limit=50';
        if (after > 0) body += '&after=' + after;
        if (before > 0) body += '&before=' + before;
        ajax(window.__url('ajax/phone.get'), { method: 'POST', body: body, success: function (d) {
            if (!d || d.status !== 'ok' || !state.open || state.open.into !== into || state.open.to !== to) { if (!d || d.status !== 'ok') toast(d && d.e ? d.e : 'خطا در دریافت تاریخچه'); return; }
            updateTypingHdr(d.typing);
            var msgs = d.h || [];
            var fromStart = (after <= 0);
            mergeMsgs(msgs);
            if (fromStart) renderAll();
            else appendNew();
            // چتِ بازِ pm/main: آخرین پیامِ ورودیِ دیدهشده را به‌روز نگه دار (auto-scroll پایین است).
            // بر اساس بزرگترین idِ ورودیِ همین پاسخ محاسبه می‌شود (دقیق‌تر از conv list).
            if (state.open && state.open.into === into && state.open.to === to) {
                var maxIn = 0;
                msgs.forEach(function (m) { if (m.user_id !== me.id && m.id > maxIn) maxIn = m.id; });
                if (into === 'pm' && !maxIn) maxIn = findConvLastIn(to);
                if (into === 'main' && !maxIn) maxIn = roomLastInFor(to);
                if (maxIn) markSeen(into, to, maxIn);
            }
        } });
    }

    function renderAll() {
        var box = $('msgScroll'); box.innerHTML = '';
        var lastDay = null;
        state.msgs.forEach(function (m) {
            if (m.hidden) return;
            if (m.date && m.date !== lastDay) { lastDay = m.date; box.insertAdjacentHTML('beforeend', '<div class="day-sep">' + esc(m.date) + '</div>'); }
            box.insertAdjacentHTML('beforeend', msgHtml(m));
        });
        wireMsgClicks();
        box.scrollTop = box.scrollHeight;
    }
    function msgHtml(m) {
        var isOut = m.user_id === me.id;
        if (m.into === 'allowchat') return '';
        // پیامِ سیستمی فقط در «گفتگوی سیستم» (به=-1) نمایش داده می‌شود، نه در چت‌ها/روم‌ها
        if (m.isSystem && !(state.open && state.open.into === 'pm' && state.open.to === -1)) return '';
        var html = '<div class="msg ' + (isOut ? 'in' : 'out') + '" data-mid="' + m.id + '">';
        html += '<button class="msg-avatar' + (m.ol ? ' ol' : '') + '" data-u="' + m.user_id + '"' + (m.user_id === me.id ? ' data-me="1"' : '') + ' style="background-image:url(\'' + esc(avatarSrc(m.user_id === me.id ? (me.avatar || m.avatar) : m.avatar)) + '\')"></button>';
        html += '<div class="bubble">';
        if (!isOut && !m.isSystem) html += '<div class="msgsender" style="color:' + esc(m.color) + '">' + crown(m.ad) + ' ' + esc(m.username || m.user_id) + (m.ut ? ' <span class="tp-title">' + esc(typeTitle(m.ut)) + '</span>' : '') + '</div>';
        if (m.reply) html += '<div class="reply-ref"><div class="rname">' + esc(m.reply_sender || m.username || '') + '</div><div class="rtext">' + (m.reply_isimg ? '<img class="reply-img" src="' + esc(m.reply) + '" alt="">' : renderText(m.reply)) + '</div></div>';
        if (m.forward) html += '<div class="forward-ref">⟲ فوروارد از ' + esc(m.forward_sender || m.username || '') + '</div>' + (m.forward_isimg ? '<img class="msgimg" src="' + esc(m.forward) + '" alt="" onclick="window.open(this.src)">' : '<div class="msgtext">' + renderText(m.forward) + '</div>');
        html += '<div class="msgtext">' + renderText(m.text) + '</div>';
        html += '<div class="reactions">';
        if (m.reactions && m.reactions.length) {
            m.reactions.forEach(function (r) { html += '<span class="reaction-pill' + (r.mine ? ' mine' : '') + '" data-react="' + esc(r.reaction) + '" data-mid="' + m.id + '">' + esc(r.reaction) + ' ' + r.count + '</span>'; });
        }
        html += '<span class="reaction-pill reaction-add" data-reactadd="1" data-mid="' + m.id + '">❤</span>';
        html += '</div>';
        html += '<div class="msgmeta">' + (isOut ? '<span class="sent-tick ok"></span>' : '') + '<span>' + esc(m.time || '') + '</span></div>';
        html += '</div></div>';
        return html;
    }
    function renderText(t) {
        t = String(t == null ? '' : t);
        // شکلک‌ها: هم کد (s42)/(s:42) و هم نام ویژه (مثل بوس1) — با جایگزینی «بزرگترین توکن اول»
        if (smileTokens.length) {
            var re = new RegExp('(' + smileTokens.map(function (k) { return k.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); }).join('|') + ')', 'g');
            t = t.replace(re, function (tok) {
                var img = smileys[tok] || tok;
                return '<img class="smile" src="' + window.__G('images/smilies/' + img) + '" alt="' + tok + '">';
            });
        }
        // امبدِ عکس/صوت/ویدیو: اگر پیامِ فایلِ sendedimages با پسوند چندرسانه‌ای باشد،
        // به‌جای لینکِ خام، عنصرِ امبدشده نشان بده (مثل دسکتاپ ولی همان تحویلِ سرویس).
        // پیامِ فایلِ سرویس همیشه به‌صورت <a href='/theme/sendedimages/…'>لیبل</a> می‌آید.
        // (۱) کلِ تگِ aِ چندرسانه‌ای را فقط با خودِ مسیرِ فایل عوض کن؛ لبِلی که داخلش هست کنار گذاشته می‌شود
        t = t.replace(/(?:<a\b[^>]*\bhref=['"])(\/theme\/sendedimages\/[^'"\s]+?\.(?:png|jpe?g|gif|webp|mp3|m4a|ogg|wav|mp4|webm))(?=['"][^>]*>)(?:(?!<\/a>).)*?<\/a>/gi, '<span data-mediainline="$1">$1</span>');
        // لینک های عادی — فقط URLهایِ لخت؛ URL داخلِ نویسنده‌های src=/href= (که با ' یا " رو به رو است) دست نمی‌خورد
        t = t.replace(/(^|[\s>])https?:\/\/[^\s<>]+/g, function (m) {
            if (/["\x27][ ]*https?:\/\/$/.test(m)) return m; // داخلِ یک attribute است؛ دست نزن
            var lead = m.charAt(0), url = m.slice(1).trim();
            return lead + '<a class="attach-link" target="_blank" rel="noopener" href="' + url + '">' + url + '</a>';
        });
        // (۲) تبدیل span های امبد به عنصر واقعی
        t = t.replace(/<span data-mediainline="([^"]+)"[^>]*>[^<]*<\/span>/, function (m, u) {
            var ext = (u.split('.').pop() || '').toLowerCase();
            // مسیرِ افزار sendedimages یک assetِ خالص است؛ مستقیم استفاده می‌شود (بدون __url که index.php می‌سازد)
            var full = u;
            if (ext === 'mp3' || ext === 'm4a' || ext === 'ogg' || ext === 'wav') {
                return '<audio class="attach-audio" controls preload="none" src="' + esc(full) + '"></audio><br><a class="attach-media-link" href="' + esc(full) + '" target="_blank" download>⬇ دانلود</a>';
            }
            if (ext === 'mp4' || ext === 'webm') {
                return '<video class="attach-inline" controls preload="metadata" src="' + esc(full) + '"></video><br><a class="attach-media-link" href="' + esc(full) + '" target="_blank" download>⬇ دانلود</a>';
            }
            return '<img class="attach-inline" src="' + esc(full) + '" alt="' + esc(u) + '" onclick="window.open(this.src)">';
        });
        return t;
    }
    function wireMsgClicks() {
        qsa('.msg', $('msgScroll')).forEach(function (el) {
            if (el._wired) return; el._wired = true;
            el.addEventListener('click', function (e) {
                if (e.target.classList && e.target.classList.contains('msg-avatar')) { showProfile(+e.target.getAttribute('data-u')); return; }
                if (e.target.getAttribute && e.target.getAttribute('data-reactadd')) {
                    toggleReactionPicker(byId(+e.target.getAttribute('data-mid')), e.target);
                    return;
                }
                if (e.target.getAttribute && e.target.getAttribute('data-react')) {
                    toggleReaction(byId(+e.target.getAttribute('data-mid')), e.target.getAttribute('data-react'));
                    return;
                }
                openMsgMenu(+el.getAttribute('data-mid'));
            });
        });
    }
    function byId(mid) { for (var i = 0; i < state.msgs.length; i++) { if (state.msgs[i].id === mid) return state.msgs[i]; } return null; }

    // ---------- profile ----------
    function typeTitle(tid) {
        var ts = window.__phone.types || [];
        for (var i = 0; i < ts.length; i++) { if (String(ts[i].id) === String(tid)) return ts[i].title || ''; }
        return '';
    }
    function genderTitle(g) { return g === 1 ? 'آقا' : (g === 2 ? 'خانم' : 'نامشخص'); }
    function showProfile(uid) {
        var ov = $('overlay'); ov.classList.remove('hidden');
        var sh = $('sheet'); if (!sh) { sh = document.createElement('div'); sh.id = 'sheet'; sh.className = 'menu-sheet'; document.body.appendChild(sh); }
        sh.classList.remove('smile-picker');
        sh.innerHTML = '<div class="sheet-title">پروفایل</div><div class="sheet-body"><div class="prof-load">در حال بارگذاری...</div></div>';
        sh.classList.add('open');
        ov.onclick = closeSheet;
        ajax(window.__url('ajax/mobile.profile') + '?user=' + encodeURIComponent(uid), { json: true, success: function (d) {
            var body = sh.querySelector('.sheet-body');
            if (!d || !d.status || !d.p) { body.innerHTML = '<div class="sheet-empty">پروفایل در دسترس نیست</div>'; return; }
            var p = d.p;
            body.innerHTML = '<div class="prof-card">' +
                '<button class="prof-avatar-btn" id="profAvatar" style="background-image:url(\'' + esc(avatarSrc(p.av || '')) + '\');background-size:cover;background-position:center"><span class="avatar-inner"></span></button>' +
                '<div class="prof-name">' + esc(p.un || '') + '</div>' +
                '<div class="prof-row">' + esc(genderTitle(p.ug)) + ' • ' + esc(typeTitle(p.ut) || '') + '</div>' +
                '<div class="prof-row">آخرین بازدید: ' + esc(p.uv || '') + '</div>' +
                '</div>';
            var pa = body.querySelector('#profAvatar');
            if (pa) pa.addEventListener('click', function () { window.open((window.__phone.url.profile || 'profile/') + '/' + uid, '_blank'); });
        }, onerr: function () { if (sh) { var b = sh.querySelector('.sheet-body'); if (b) b.innerHTML = '<div class="sheet-empty">خطا در دریافت پروفایل</div>'; } } });
    }

    // ---------- send / reply ----------
    // نگاشت‌کردن چتِ باز به target ارسال: main => -1 ، pm => peer ، cf => id
    function sendTarget() {
        var o = state.open; if (!o) return null;
        if (o.into === 'main') return { to: '-1', where: 'main' };
        if (o.into === 'pm') return { to: String(o.to), where: 'pm' };
        return { to: String(o.to), where: 'cf' };
    }
    // تلاشِ چندباره برای ارسال؛ فقط بعد از چند شکستِ متوالی پیام قرمز می‌شود
    function retrySend(attempts, maxFail, doSend, onFinalFail) {
        doSend(
            function (ok, err) {
                if (ok) { onFinalFail(null); return; }
                if (attempts >= maxFail) { onFinalFail(err); return; }
                setTimeout(function () { retrySend(attempts + 1, maxFail, doSend, onFinalFail); }, 700);
            },
            function () { // شبکه قطع شد
                if (attempts >= maxFail) { onFinalFail('err'); return; }
                setTimeout(function () { retrySend(attempts + 1, maxFail, doSend, onFinalFail); }, 700);
            }
        );
    }
    // جایگزینیِ اسم‌های ویژه (مثل بوس1) با کد شکلک (s42) قبل از ارسال، تا همه جا درست رندر شود
    function normalizeSmileText(t) {
        t = String(t == null ? '' : t);
        var names = Object.keys(smileToCode).sort(function (a, b) { return b.length - a.length; });
        names.forEach(function (nm) { t = t.split(nm).join(smileToCode[nm]); });
        return t;
    }

    function sendMsg() {
        var inp = $('msgInput'); var text = normalizeSmileText(inp.value.replace(/\s+\n*$/, ''));
        if (!text.trim() || !state.open) return;
        var o = state.open; inp.value = ''; autoSize();
        var reply = state.activeReply;
        var st = sendTarget();
        var pend = addPendingBubble(text);
        var doSend = function (done, failNet) {
            if (reply && st) {
                ajax(window.__url('ajax/chat.reply'), { method: 'POST', json: false, body: 'to=' + encodeURIComponent(st.to) + '&uname=' + encodeURIComponent(reply.name || '') + '&umsg=' + encodeURIComponent(reply.text || '') + '&msgid=' + encodeURIComponent(reply.msgid) + '&mymsg=' + encodeURIComponent(text) + '&where=' + encodeURIComponent(o.into) + '&os=mobile', success: function () { hideReplyHint(); done(true); }, onerr: function () { failNet(); } });
            } else if (st) {
                // ارسال به یک روم: اول باید منتقل شویم (session('room') همان‌جا سِت شود) وگرنه پیام به اتاقِ قبلی می‌رود.
                var doSendNow = function () {
                    ajax(window.__url('ajax/mobile.get'), { method: 'POST', body: 'to=' + encodeURIComponent(st.to) + '&text=' + encodeURIComponent(text) + '&into=' + encodeURIComponent(o.into), success: function (d, xhr) { if (d && d.status) done(true); else done(false, d && d.e ? d.e : 'err'); }, onerr: function () { failNet(); } });
                };
                if (o.into === 'main' && o.to > 0 && o.to !== me.room) { enterRoom(o.to, doSendNow); } else { doSendNow(); }
            } else { done(true); }
        };
        retrySend(0, 3, doSend, function (err) {
            if (err === null) return;
            toast(mapSendErr(err || 'err'));
            if (pend) pend.classList.add('failed');
        });
        stopTyping(true);
        setTimeout(pollRefresh, 500);
    }
    function addPendingBubble(text) {
        var box = $('msgScroll');
        var el = document.createElement('div');
        el.className = 'msg out pending-sent';
        el.innerHTML = '<div class="bubble"><div class="msgtext">' + esc(text) + '</div></div>';
        box.appendChild(el);
        box.scrollTop = box.scrollHeight;
        return el;
    }
    function mapSendErr(e) { var M = { 'login': 'برای استفاده وارد شوید', 'cannotloadonline': 'خطا', 'yououted': 'خارج شده‌اید', 'youslient': 'ساکت شده‌اید', 'youbanned': 'اخراج شده‌اید', 'nopm': 'کاربر خصوصی را بسته', 'gpperm': 'گروه محدود شده', 'blme': 'کاربر مسدود کرده', 'speedmsg': 'سرعت زیاد', 'cfsil': 'خفه شده‌اید', 'cfdl': 'از کنفرانس حذف شده‌اید' }; return M[e] || e; }
    function pollRefresh() { if (state.open) loadHistory(state.open.into, state.open.to, state.lastId, 0); }
    // ---------- is typing... ----------
    var typingTimer = null, typingSending = false, lastTypingSent = 0;
    function typingTarget() {
        if (!state.open) return '0';
        if (state.open.into === 'pm') return String(state.open.to);
        return state.open.to > 0 ? String(state.open.to) : String(me.room || 0);
    }
    function sendTyping() {
        var now = Date.now();
        if (typingSending || !state.open || now - lastTypingSent < 2000) return;
        lastTypingSent = now;
        typingSending = true;
        ajax(window.__url('ajax/phone.typing'), { method: 'POST', body: 'to=' + encodeURIComponent(typingTarget()), success: function () { typingSending = false; }, onerr: function () { typingSending = false; } });
    }
    function noteTyping() {
        var inp = $('msgInput');
        if (inp && inp.value.trim()) sendTyping();
        clearTimeout(typingTimer);
        typingTimer = setTimeout(stopTyping, 5000); // بعد از ۵ ثانیه بی‌عمل، تایپ قطع شود
    }
    function stopTyping() { clearTimeout(typingTimer); }    function setReply(m) {
        state.activeReply = { msgid: m.id, name: (m.isSystem ? '' : (m.username || m.user_id)), text: (m.reply ? '' : textOf(m)), reply: m.reply, isimg: m.reply_isimg };
        var short = m.reply_isimg ? '[تصویر]' : escStr(textOf(m), 70);
        var h = $('replyHint');
        h.innerHTML = '<span class="rname">پاسخ به ' + esc(m.isSystem ? 'سیستم' : (m.username || m.user_id)) + ':</span><span>' + short + '</span><span class="rh-close" id="rhClose">✕</span>';
        h.classList.remove('hidden');
        $('rhClose').addEventListener('click', hideReplyHint);
        $('msgInput').focus();
    }
    function hideReplyHint() { state.activeReply = null; var h = $('replyHint'); h.classList.add('hidden'); }

    // ---------- actions ----------
    function plugin(action, data) {
        var body = 'action=' + action + '&token=' + encodeURIComponent(me.chpw || '');
        Object.keys(data).forEach(function (k) { body += '&' + k + '=' + encodeURIComponent(data[k]); });
        ajax(window.__url('ajax/plugin'), { method: 'POST', json: false, body: body, success: function (d) { if (d && d !== 'ok') toast(d); pollRefresh(); refreshList(); }, onerr: function () { toast('خطا در اتصال'); } });
    }
    function deleteMsg(m) { plugin('DelMsg', { msgid: JSON.stringify([m.id]) }); }
    function editMsg(m) { var neu = prompt('متن جدید:', textOf(m)); if (neu !== null) plugin('EidtMsg', { id: m.id, msg: neu, act: 'msg' }); }
    function pinMsg(m) { plugin('PinMsg', { msgid: m.id }); }
    function unpinMsg() { plugin('UnPinMsg', {}); }
    // انتقال به یک روم خاص: session('room') را همان‌جا می‌کند تا پیامِ main به اتاقِ درست برود.
    function enterRoom(rid, cb) {
        ajax(window.__url('ajax/chengeroom'), { method: 'POST', json: false, body: 'room_id=' + encodeURIComponent(rid) + '&token=' + encodeURIComponent(me.chpw || ''), success: function (d) { if (d === 'ok') { me.room = rid; } else if (d && d !== 'ok') { toast(d); } if (cb) cb(); }, onerr: function () { if (cb) cb(); } });
    }
    function toggleReaction(m, r, done) {
        if (r.indexOf(':') > -1) r = r.split(':')[1];
        ajax(window.__url('ajax/chat.reaction'), { method: 'POST', body: 'action=toggle&msg_id=' + encodeURIComponent(m.id) + '&reaction=' + encodeURIComponent(r), success: function (d) { if (state.open) loadHistory(state.open.into, state.open.to, 0, 0); if (done) done(); } });
    }

    // ---------- واکنش (تولتیپ تلگرامی) ----------
    var rxEmojis = (window.__phone.reactions && window.__phone.reactions.length) ? window.__phone.reactions : ['👍', '❤️', '😂', '😮', '😢', '😡', '👎'];
    var rxPop = null, rxMsgId = null, rxCloseHandler = null;
    function ensureRxPop() {
        if (rxPop) return;
        rxPop = document.createElement('div');
        rxPop.id = 'rxPop';
        rxPop.className = 'rx-pop';
        document.body.appendChild(rxPop);
        rxPop.addEventListener('click', function (e) {
            if (e.target.getAttribute && e.target.getAttribute('data-rx')) {
                var r = e.target.getAttribute('data-rx');
                var m = rxMsgId ? byId(rxMsgId) : null;
                closeRxPop();
                if (m) toggleReaction(m, r);
            }
        });
    }
    function openReactionPop(mid, anchorEl) {
        ensureRxPop(); closeRxPop();
        rxMsgId = mid;
        var html = '';
        rxEmojis.forEach(function (e) { html += '<button class="rx-opt" data-rx="' + esc(e) + '">' + esc(e) + '</button>'; });
        rxPop.innerHTML = html;
        rxPop.classList.add('open');
        var rect = anchorEl.getBoundingClientRect();
        var pw = rxPop.offsetWidth || (rxEmojis.length * 40 + 20);
        var left = rect.left + rect.width / 2 - pw / 2;
        left = Math.max(8, Math.min(window.innerWidth - pw - 8, left));
        var top = rect.top - rxPop.offsetHeight - 8;
        if (top < 4) top = rect.bottom + 8;
        rxPop.style.left = left + 'px';
        rxPop.style.top = top + 'px';
        if (rxCloseHandler) document.removeEventListener('click', rxCloseHandler, true);
        rxCloseHandler = function (ev) { if (rxPop && !rxPop.contains(ev.target)) closeRxPop(); };
        setTimeout(function () { document.addEventListener('click', rxCloseHandler, true); }, 10);
    }
    function closeRxPop() {
        if (rxCloseHandler) { document.removeEventListener('click', rxCloseHandler, true); rxCloseHandler = null; }
        if (rxPop) rxPop.classList.remove('open');
        rxMsgId = null;
    }

    // ---------- smileys ----------
    // lookup: هر «توکن» (code مثل (s42) و name مثل بوس1) -> فایل تصویر. توکن‌ها به ترتیب طول برای جایگزینی.
    var smileys = {};
    var smileList = [];
    var smileTokens = [];
    var smileToCode = {}; // اسم ویژه (مثل بوس1) -> کد (s42) تا موقع ارسال تبدیل شود
    function loadSmileys() { ajax(window.__url('ajax/mobile.smilies'), { success: function (d) { if (d && d.status && d.s) { smileList = d.s; smileys = {}; smileToCode = {}; var seen = {}; d.s.forEach(function (s) { var name = (s.n || '').trim(); var code = s.c || ''; if (code) { smileys[code] = s.i; } if (name) { if (!code || /\(\s/i.test(code) || /\(s/i.test(code)) { smileys[name] = s.i; } if (code && /\(s/i.test(code)) { smileToCode[name] = code; } } }); smileTokens = Object.keys(smileys).sort(function (a, b) { return b.length - a.length; }); } } }); }
    function openSmilePicker() {
        if (!smileList.length) { toast('شکلک‌ها در حال بارگذاری...'); return; }
        var ov = $('overlay'); ov.classList.remove('hidden');
        var sh = $('sheet'); if (!sh) { sh = document.createElement('div'); sh.id = 'sheet'; sh.className = 'menu-sheet smile-picker'; document.body.appendChild(sh); }
        sh.classList.add('smile-picker');
        var html = '<div class="sheet-title">شکلک‌ها</div><div class="smile-grid">';
        smileList.forEach(function (s, ix) {
            var code = s.c || s.n || '';
            var img = smileys[code] || s.i || '';
            html += '<button class="smile-cell" data-code="' + esc(code) + '"><img src="' + esc(window.__G('images/smilies/' + img)) + '" alt=""></button>';
        });
        html += '</div>';
        sh.innerHTML = html; sh.classList.add('open');
        qsa('.smile-cell', sh).forEach(function (el) {
            el.addEventListener('click', function () {
                var inp = $('msgInput'); inp.value += el.getAttribute('data-code'); autoSize(); inp.focus(); closeSheet();
            });
        });
        ov.onclick = closeSheet;
    }
    function toggleReactionPicker(m, anchorEl) {
        var msgEl = anchorEl || qs('.msg[data-mid="' + m.id + '"]', $('msgScroll'));
        if (!msgEl) return;
        openReactionPop(m.id, msgEl);
    }

    // ---------- file / media ----------
    // قانونِ دسکتاپ: در خصوصی/کنفرانسِ خصوصی ارسالِ فایل واقعی (upsendfile)؛
    // در رومِ عمومی ارسالِ عکس/فیلمِ لینکی + موزیکِ آپلودِ واقعی (chat.music.upload).
    var uploading = 0;
    function showUploadBar(name) {
        var b = $('uploadBar'); b.classList.remove('hidden');
        $('uploadName').textContent = name; $('uploadFill').style.width = '0%'; $('uploadPct').textContent = '0%';
    }
    function setUploadPct(pct) {
        $('uploadFill').style.width = (pct | 0) + '%'; $('uploadPct').textContent = (pct | 0) + '%';
    }
    function hideUploadBar() { $('uploadBar').classList.add('hidden'); }

    function attachFile() {
        var o = state.open; if (!o) return;
        // رومِ عمومی: منوی جدا (عکس/فیلم/موزیک) — مثل دسکتاپ
        if (o.into === 'main') {
            showSheet('ارسال به عمومی', [
                { t: '🖼 ارسال عکس (لینک)', fn: sendImageUrl },
                { t: '🎬 ارسال فیلم (لینک)', fn: sendFilmUrl },
                { t: '🎵 ارسال موزیک (آپلود واقعی)', fn: function () { $('fileInput').click(); } }
            ]);
            return;
        }
        $('fileInput').click();
    }

    // ارسالِ فایلِ خصوصی (pm/cf) به upsendfile — همان پرمیشن/سهمیه/امتیازِ دسکتاپ
    function uploadPrivate(f, done) {
        var o = state.open; if (!o) return;
        var fd = new FormData(); fd.append('myfile', f);
        uploading++; showUploadBar(f.name || 'فایل');
        ajax(window.__url('ajax/upsendfile') + '?targetid=' + encodeURIComponent(o.to) + '&into=' + encodeURIComponent(o.into), {
            form: fd, method: 'POST', json: false,
            onprogress: function (ev) { if (ev.lengthComputable) setUploadPct(Math.round(ev.loaded / ev.total * 100)); },
            success: function (d) {
                uploading--; hideUploadBar();
                toast('فایل ارسال شد'); pollRefresh(); refreshList();
                if (done) done();
            },
            onerr: function () { uploading--; hideUploadBar(); toast('خطا در آپلود'); if (done) done(); }
        });
    }

    // ارسالِ موزیکِ واقعی به رومِ عمومی — فیلد fileToUpload + XHR + نوار پیشرفت (پاسخ JSON)
    function uploadMusic(f) {
        var fd = new FormData(); fd.append('fileToUpload', f);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', window.__url('ajax/chat.music.upload'), true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        uploading++; showUploadBar(f.name || 'موزیک');
        xhr.upload.onprogress = function (ev) { if (ev.lengthComputable) setUploadPct(Math.round(ev.loaded / ev.total * 100)); };
        xhr.onload = function () {
            uploading--; hideUploadBar();
            var res; try { res = JSON.parse(xhr.responseText); } catch (e) { res = null; }
            if (res && res.status === 'ok') { toast('موزیک ارسال شد'); pollRefresh(); refreshList(); }
            else toast((res && res.message) ? res.message : 'خطا در آپلود موزیک');
        };
        xhr.onerror = function () { uploading--; hideUploadBar(); toast('خطا در ارتباط با سرور'); };
        xhr.send(fd);
    }

    // ارسالِ عکس/فیلمِ لینکی به رومِ عمومی (chat.vip send_image / chat.send film) — پاسخ خام 'ok'
    function sendMediaUrl(ep, params, kind) {
        toast('در حال ارسال ' + kind + '...');
        ajax(window.__url(ep), {
            method: 'POST', json: false, body: params,
            success: function (d) {
                if (d === 'ok') { toast(kind + ' ارسال شد'); pollRefresh(); refreshList(); }
                else toast(escStr(d, 120));
            },
            onerr: function () { toast('خطا در ارتباط با سرور'); }
        });
    }
    function sendImageUrl() {
        var url = prompt('آدرس مستقیم عکس را وارد کنید (http/https):');
        if (!url) return;
        sendMediaUrl('ajax/chat.vip', 'switch=send_image&url=' + encodeURIComponent(url.trim()), 'عکس');
    }
    function sendFilmUrl() {
        var url = prompt('آدرس مستقیم فیلم را وارد کنید (http/https):');
        if (!url) return;
        sendMediaUrl('ajax/chat.send', 'switch=film&url=' + encodeURIComponent(url.trim()), 'فیلم');
    }

    function onFile(e) {
        var f = e.target.files[0]; e.target.value = '';
        var o = state.open; if (!f || !o) return;
        // در رومِ عمومی فایل پیک شده == موزیکِ واقعی (مثل دسکتاپ که این ورودی فقط برای موزیک بود)
        if (o.into === 'main') { uploadMusic(f); return; }
        // خصوصی: پیش‌نمایش اسم/نوع + تأیید (مثل فرمِ مرورِ دسکتاپ)
        var fname = f.name || 'فایل';
        var kind = f.type ? f.type : (fname.split('.').pop() || '').toLowerCase();
        if (kind.indexOf('image') === 0 || kind === 'png' || kind === 'jpg' || kind === 'jpeg' || kind === 'gif' || kind === 'webp') kind = 'تصویر';
        else if (kind.indexOf('audio') === 0 || kind === 'mp3' || kind === 'm4a' || kind === 'ogg' || kind === 'wav') kind = 'صوت';
        else if (kind.indexOf('video') === 0 || kind === 'mp4' || kind === 'webm') kind = 'ویدیو';
        else kind = 'فایل';
        showSheet('ارسال ' + kind, [
            { t: '📎 ' + fname, disabled: 1 },
            { t: 'ارسال', fn: function () { uploadPrivate(f); } },
            { t: 'انصراف', fn: function () {} }
        ]);
    }

    // ---------- user menu / actions ----------
    function showSheet(title, items) {
        var ov = $('overlay'); ov.classList.remove('hidden');
        var sh = $('sheet'); if (!sh) { sh = document.createElement('div'); sh.id = 'sheet'; sh.className = 'menu-sheet'; document.body.appendChild(sh); }
        sh.classList.remove('smile-picker');
        var html = '<div class="sheet-title">' + esc(title) + '</div>';
        items.forEach(function (it, i) { html += '<div class="menu-item' + (it.disabled ? ' disabled' : '') + '" data-ix="' + i + '">' + it.t + '</div>'; });
        sh.innerHTML = html; sh.classList.add('open');
        qsa('.menu-item', sh).forEach(function (el) { el.addEventListener('click', function () { var it = items[+el.getAttribute('data-ix')]; if (it.disabled) return; closeSheet(); if (it.fn) it.fn(); }); });
        ov.onclick = closeSheet;
    }
    function closeSheet() { var ov = $('overlay'); ov.classList.add('hidden'); var sh = $('sheet'); if (sh) sh.classList.remove('open'); }
    function blockUser(uid, all) { ajax(window.__url('ajax/chat.block'), { method: 'POST', json: false, body: (all ? 'block_all=' : 'block_priv=') + encodeURIComponent(uid), success: function () { toast('مسدود شد'); } }); }
    function reportUser(uid, location) {
        // مثل دسکتاپ (report_text_2admins → chat.reportuser): با پارامترهای درستِ سرور →
        // پیامِ «SYSTEM [ ارسال گزارش ]» به مدیرانِ pm رسیده و در «گفتگوی سیستم» آن‌ها نمایش داده می‌شود.
        var reason = prompt('متن گزارش:', 'گزارش کاربر از نسخه موبایل');
        if (reason === null) return;
        var loc = location === 'main' ? 'main' : 'private';
        ajax(window.__url('ajax/chat.reportuser'), { method: 'POST', json: false, body: 'userid=' + encodeURIComponent(uid) + '&report=' + encodeURIComponent(reason) + '&cp=false&location=' + encodeURIComponent(loc), success: function (d) { toast('گزارش ثبت شد'); pollRefresh(); refreshList(); }, onerr: function () { toast('خطا در گزارش'); } });
    }
    function silentUser(uid) { var mins = prompt('چند دقیقه خفه شود؟ (0 = آزاد)'); if (mins === null) return; ajax(window.__url('ajax/chat.silent'), { method: 'POST', json: false, body: 'user=' + encodeURIComponent(uid) + '&time=' + encodeURIComponent(mins) + '&token=' + encodeURIComponent(me.chpw || ''), success: function (d) { d === 'ok' ? toast('خفه شد') : toast(d); } }); }
    function banUser(uid) { var reason = prompt('دلیل اخراج:'); if (reason === null) return; ajax(window.__url('ajax/bann'), { method: 'POST', json: false, body: 'user=' + encodeURIComponent(uid) + '&reason=' + encodeURIComponent(reason) + '&date=1day', success: function (d) { d === 'ok' ? toast('اخراج شد') : toast(d); } }); }
    // گزینه‌های مدیریتیِ دسکتاپ (adminu_) از طریق plugin: خفه (SilentUser) و اخراج (BennUser)
    function adminSilent(uid, minutes, msgid) {
        var mins = prompt('چند دقیقه خفه شود؟ (0 = آزاد)');
        if (mins === null) return;
        plugin('SilentUser', { user: String(uid), time: String(mins), msgid: JSON.stringify([msgid]) });
    }
    function adminBan(uid, msgid) {
        // مطابقِ دسکتاپ: مدتِ اخراج (دقیقه) + دلیل (دسکتاپ فقط دلیل می‌پرسد و زمانِ پیش‌فرض می‌گیرد؛
        // اینجا می‌توانیم مدت را هم انتخاب کنیم).
        var mins = prompt('مدتِ اخراج به دقیقه (خالی = پیش‌فرض):');
        if (mins === null) return;
        var reason = prompt('دلیل اخراج:');
        if (reason === null) return;
        if (reason.trim() === '') { toast('دلیل اخراج الزامی است'); return; }
        var data = { user: String(uid), reason: reason, msgid: JSON.stringify([msgid]) };
        if (mins && !isNaN(mins) && parseInt(mins, 10) > 0) data.time = String(parseInt(mins, 10));
        if (me.chpw) data.password = me.chpw;
        plugin('BennUser', data);
    }
    // منوی مدیریتی از لیستِ کاربرانِ آنلاین: بدونِ پیام (msgid=-1 تا هیچ پیامی حذف نشود)
    function openUserAdmin(uid) {
        var u = userIndex[uid] || {};
        var name = String(u.n || uid);
        var items = [
            { t: 'پروفایل ' + name, fn: function () { if (window.__phone.url.profile) window.open(window.__phone.url.profile + '/' + uid, '_blank'); } },
            { t: 'پاسخ‌گویی (گفتگوی خصوصی)', fn: function () { openChat('pm', uid); } },
        ];
        if (me.admin && me.admin !== '0') {
            items.push({ t: '── مدیریت ──', disabled: 1 });
            items.push({ t: 'خفه کردن', fn: function () { adminSilent(uid, '0', -1); } });
            items.push({ t: 'اخراج کاربر', fn: function () { adminBan(uid, -1); } });
        }
        items.push({ t: 'گزارش', fn: function () { reportUser(uid); } });
        showSheet('مدیریت', items);
    }
    function forwardMsg(m) {
        var target = prompt('شناسه گیرنده برای فوروارد (خالی = عمومی):');
        var body = 'text=' + encodeURIComponent(m.text || '') + '&name=' + encodeURIComponent(m.username || '');
        if (target !== null && target !== '') body += '&id=' + encodeURIComponent(target.trim());
        if (!state.open || state.open.into === 'main') body += '&room=' + encodeURIComponent(state.open ? state.open.to : me.room);
        ajax(window.__url('ajax/chat.forward'), { method: 'POST', json: false, body: body, success: function () { toast('فوروارد شد'); } });
    }

    function openMsgMenu(mid) {
        var m = byId(mid); if (!m) return;
        var mine = m.user_id === me.id, items = [];
        if (!m.isSystem && me.can_reply) items.push({ t: 'پاسخ', fn: function () { setReply(m); } });
        if (!m.isSystem && me.can_forward) items.push({ t: 'فوروارد', fn: function () { forwardMsg(m); } });
        items.push({ t: 'واکنش', fn: function () { toggleReactionPicker(m); } });
        if (me.can_pin && state.open && state.open.into === 'main') items.push({ t: 'پین کردن', fn: function () { pinMsg(m); } });
        if (mine && me.can_editmsg) items.push({ t: 'ویرایش', fn: function () { editMsg(m); } });
        if (mine && me.can_delmsg) items.push({ t: 'حذف', fn: function () { deleteMsg(m); } });
        if (m.user_id !== me.id) items.push({ t: 'بلاک', fn: function () { blockUser(m.user_id, false); } });
        if (m.user_id !== me.id) items.push({ t: 'گزارش', fn: function () { reportUser(m.user_id); } });
        // منوی مدیریتی (adminu_) برای مدیران: حذف پیامِ دیگران + خفه + اخراج
        if (me.admin && me.admin !== '0' && m.user_id !== me.id) {
            items.push({ t: '── مدیریت ──', disabled: 1 });
            if (me.can_delmsg) items.push({ t: 'حذف پیام', fn: function () { plugin('DelMsg', { msgid: JSON.stringify([m.id]) }); } });
            items.push({ t: 'خفه کردن', fn: function () { adminSilent(m.user_id, '0', m.id); } });
            items.push({ t: 'اخراج کاربر', fn: function () { adminBan(m.user_id, m.id); } });
        }
        if (!items.length) return;
        showSheet('پیام', items);
    }

    // ---------- poll list ----------
    var listTimer = null, pollTimerHandle = null, keepAliveHandle = null;
    // پینگِ آنلاین ماندن: بدونِ متن، فقط تا onlines.time تازه بماند و کاربر در لیست کاربرانِ دسکتاپ
    // حذف نشود (مشکلِ قبلی: موبایل هیچ‌وقت get نمی‌زد و به‌محض قطعِ پیامِ ارسالی آنلاین نمایش داده نمی‌شد).
    function keepOnline() {
        ajax(window.__url('ajax/mobile.get'), { method: 'POST', body: 'to=-1', success: function (d) { if (d && d.fp_capture) fpCaptureNow(); } });
    }
    // وقتی مدیر این کاربر را اخراج کرده، سرور فلگ fp_capture می‌دهد تا اثر انگشت
    // فوراً ثبت و به‌عنوان abuser ذخیره شود — پشتوانهٔ گرفتنِ برگشتی‌های هم‌دستگاهی.
    var fpCaptureSent = false;
    function fpCaptureNow() {
        if (fpCaptureSent || !window.__fillprintCapture) return;
        fpCaptureSent = true;
        window.__fillprintCapture(function () {});
    }
    function refreshList() {
        ajax(window.__url('ajax/phone.get'), { cache: false, success: function (d) {
            if (!d || d.status !== 'ok') { toast('خطا در دریافت لیست: ' + (d && d.e ? d.e : (d ? d.raw : ''))); return; }
            if (d.c) state.convs = d.c;
            if (d.r) state.rooms = d.r;
            // badge روم: room_last_in فقط برای رومِ فعالِ کاربر می‌آید؛ در roomLastInMap ثبت کن
            state.roomLastInMap = {}; state._roomLastIn = d.room_last_in || 0;
            if (d.me && d.me.room) { state.roomLastInMap[d.me.room] = state._roomLastIn; }
            if (d.o) { state.users = d.o; d.o.forEach(function (u) { userIndex[u.i] = u; }); }
            state.pin = d.pin || null;
            if (d.fp_capture) fpCaptureNow();
            // لیستِ گفتگوها همیشه به‌روز می‌شود (متنِ آخرین پیام، زمان، آنلاین، badge) حتی وقتی چتی باز است
            renderList();
            updateTabBadges();
            updatePin();
            if (d.me) { me.id = d.me.id || me.id; }
        } });
    }
    function pollList() {
        refreshList();
        listTimer = setInterval(refreshList, 6000);
        keepAliveHandle = setInterval(keepOnline, 12000);
    }
    // پیامِ ذخیره‌شده در پین ممکن است JSONِ reply/forward باشد (مثل پیامِ عادی) یا متنِ خام؛
    // همین ساختار را استخراج کن تا «شکلک/ریپلای/فوروارد» در بنر هم مثل خودِ پیام درست رندر شود.
    function parsePinPayload(t) {
        var out = { reply: null, reply_sender: '', forward: null, forward_sender: '', text: String(t == null ? '' : t) };
        var clean = out.text.replace(/<b>|<\/b>|<i>|<\/i>/g, '');
        if (isJson(clean)) {
            try {
                var jd = JSON.parse(clean);
                if (jd && typeof jd === 'object') {
                    if (jd.action === 'reply') {
                        out.reply = jd.umsg != null ? jd.umsg : '';
                        out.reply_sender = jd.uname != null ? jd.uname : '';
                        out.reply_isimg = jd.isimg ? 1 : null;
                        out.text = jd.mymsg != null ? jd.mymsg : '';
                        return out;
                    }
                    if (jd.action === 'forward') {
                        out.forward = jd.msg != null ? jd.msg : '';
                        out.forward_sender = jd.name != null ? jd.name : '';
                        out.forward_isimg = jd.isimg ? 1 : null;
                        out.text = '';
                        return out;
                    }
                }
            } catch (e) { /* متنِ ساده؛ دست نزن */ }
        }
        return out;
    }
    function updatePin() {
        var b = $('pinBanner');
        if (state.pin && state.open && state.open.into === 'main') {
            var P = parsePinPayload(state.pin.message);
            var meta = '';
            if (state.pin.name || state.pin.time) {
                var who = state.pin.name ? esc(state.pin.name) : '';
                var when = state.pin.time ? '، ' + esc(pinTime(state.pin.time)) : '';
                meta = '<div class="pin-meta">' + who + when + '</div>';
            }
            var inn = '';
            if (P.reply) inn += '<div class="reply-ref"><div class="rname">' + esc(P.reply_sender || '') + '</div><div class="rtext">' + (P.reply_isimg ? '<img class="reply-img" src="' + esc(P.reply) + '" alt="">' : renderText(P.reply)) + '</div></div>';
            if (P.forward) inn += '<div class="forward-ref">⟲ فوروارد از ' + esc(P.forward_sender || '') + '</div>' + (P.forward_isimg ? '<img class="msgimg" src="' + esc(P.forward) + '" alt="" onclick="window.open(this.src)">' : '<div class="msgtext">' + renderText(P.forward) + '</div>');
            inn += '<div class="msgtext">' + renderText(P.text) + '</div>';
            $('pinBody').innerHTML = meta + inn;
            b.classList.remove('hidden');
        } else b.classList.add('hidden');
    }

    // ---------- boot ----------
    function boot() {
        qsa('.tab').forEach(function (b) { b.addEventListener('click', function () { setTab(b.getAttribute('data-tab')); }); });
        $('btnBack').addEventListener('click', showList);
        $('btnSettings').addEventListener('click', function () { showSheet('تنظیمات', [{ t: 'نسخه کامپیوتر (دسکتاپ)', fn: function () { window.location.href = window.__phone.url.chat; } }, { t: 'راهنما', fn: function () { toast('نسخه موبایل ' + window.__phone.chat_prefix); } }, { t: 'خروج', fn: function () { window.location.href = window.__phone.url.logout; } }]); });
        $('ctMore').addEventListener('click', function () { if (!state.open) return; var items = [{ t: 'نسخه کامپیوتر', fn: function () { window.location.href = window.__phone.url.chat; } }]; if (state.open.into === 'main' && me.can_pin) items.push({ t: 'لغو پین', fn: unpinMsg }); showSheet('گفتگو', items); });
        $('cSend').addEventListener('click', sendMsg);
        $('cSmile').addEventListener('click', openSmilePicker);
        $('cAttach').addEventListener('click', attachFile);
        $('fileInput').addEventListener('change', onFile);
        var ta = $('msgInput'); ta.addEventListener('input', autoSize); ta.addEventListener('input', noteTyping);
        ta.addEventListener('keydown', function (e) { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMsg(); } });
        $('pinClose').addEventListener('click', unpinMsg);
        setTab('chats'); loadSmileys(); pollList();
    }
    function autoSize() { var ta = $('msgInput'); ta.style.height = 'auto'; ta.style.height = Math.min(110, ta.scrollHeight) + 'px'; }
    function switchDesktop() { window.location.href = window.__phone.url.chat; }

    document.addEventListener('DOMContentLoaded', boot);
})();
