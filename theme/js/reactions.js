/**
 * Message Reactions - Integrates with dstarter.js
 * Uses real DB message IDs (msgnum_{msgid}) and existing polling for sync
 */
(function() {
    var reactionCache = {};
    var reactionEmojis = null;
    var canReact = false;
    var lastRUpdate = {};

    function loadEmojis() {
        if (typeof $setting !== "undefined" && $setting.reaction_emojis) {
            try {
                var e = typeof $setting.reaction_emojis === "string" ? JSON.parse($setting.reaction_emojis) : $setting.reaction_emojis;
                if (e && e.length) reactionEmojis = e;
            } catch(ex) {}
        }
        if (!reactionEmojis) reactionEmojis = ["👍","❤️","😂","😮","😢","😡","👎"];
        canReact = (typeof $setting !== "undefined" && $setting.can_react) ? true : false;
    }

    function getMsgId(el) {
        if (!el || !el.className) return null;
        var m = el.className.match(/msgnum_(\d+)/);
        if (m) return parseInt(m[1]);
        var p = el.querySelector ? el.querySelector("[class*='msgnum_']") : null;
        if (p && p.className) { var pm = p.className.match(/msgnum_(\d+)/); if (pm) return parseInt(pm[1]); }
        return null;
    }

    function avatarUrl(av) {
        var dir = (typeof $setting !== "undefined" && $setting.avatardir) ? $setting.avatardir : "theme/images/avatars";
        var def = (typeof $setting !== "undefined" && $setting.defaultavatar) ? $setting.defaultavatar : "photo.png";
        if (!av) av = def;
        try { return theme_url(dir + "/" + av); } catch(ex) { return "/" + dir + "/" + av; }
    }

    var activeTip = null;

    function hideTip() {
        if (activeTip) { activeTip.style.display = "none"; activeTip = null; }
    }

    function attachTooltip(bar, msgId, reactions) {
        if (!bar) return;
        var tip = document.createElement("div");
        tip.className = "r-tooltip";
        tip.style.display = "none";
        var html = "";
        var totalUsers = 0;
        // One combined list: every reaction, with the emoji shown next to each user
        for (var i = 0; i < reactions.length; i++) {
            var r = reactions[i];
            if (!r.users || !r.users.length) continue;
            for (var j = 0; j < r.users.length; j++) {
                var u = r.users[j];
                var av = avatarUrl(u.avatar);
                var nm = u.name || ("#" + u.id);
                html += '<div class="r-t-user"><em class="r-t-emoji">' + r.reaction + '</em>' +
                        '<img class="r-t-avatar" src="' + av + '" alt="" />' +
                        '<span class="r-t-name">' + nm + '</span></div>';
                totalUsers++;
            }
        }
        if (!totalUsers) return;
        tip.innerHTML = html;
        document.body.appendChild(tip);

        bar.addEventListener("mouseenter", function () {
            hideTip();
            activeTip = tip;
            tip.style.display = "block";
            var r = bar.getBoundingClientRect();
            tip.style.left = (r.left) + "px";
            tip.style.top = (r.bottom + 4) + "px";
        });
        bar.addEventListener("mouseleave", function () { hideTip(); });
    }

    function buildBar(msgId, reactions, myReaction, isSystem) {
        var bar = document.createElement("div");
        bar.className = "reactions-bar";
        bar.setAttribute("data-msg", msgId);
        if (reactions && reactions.length) {
            for (var i = 0; i < reactions.length; i++) {
                var r = reactions[i];
                var s = document.createElement("span");
                s.className = "r-item" + (r.reaction === myReaction ? " r-active" : "");
                s.innerHTML = r.reaction + " <em>" + r.count + "</em>";
                if (!isSystem) {
                    (function(em,mid){ s.onclick=function(e){e.stopPropagation();toggleReact(mid,em);}; })(r.reaction,msgId);
                }
                bar.appendChild(s);
            }
            attachTooltip(bar, msgId, reactions);
        }
        // Do not show reaction button on system messages
        if (!isSystem && canReact && reactionEmojis && reactionEmojis.length) {
            var btn = document.createElement("span");
            btn.className = "r-add";
            btn.textContent = "😊";
            btn.title = "Reaction";
            btn.onclick = function(e) { e.stopPropagation(); showPicker(e, msgId); };
            bar.appendChild(btn);
        }
        return bar;
    }

    function showPicker(e, msgId) {
        var ex = document.querySelector(".r-picker"); if (ex) ex.remove();
        var p = document.createElement("div"); p.className = "r-picker";
        for (var i = 0; i < reactionEmojis.length; i++) {
            var b = document.createElement("span"); b.textContent = reactionEmojis[i];
            (function(em,mid){ b.onclick=function(ev){ev.stopPropagation();p.remove();toggleReact(mid,em);}; })(reactionEmojis[i],msgId);
            p.appendChild(b);
        }
        var r = e.target.getBoundingClientRect();
        p.style.cssText = "position:fixed;z-index:9999;background:#fff;border:1px solid #ccc;border-radius:20px;padding:5px 10px;box-shadow:0 2px 10px rgba(0,0,0,.2);display:flex;gap:5px;left:"+r.left+"px;top:"+(r.bottom+5)+"px;";
        document.body.appendChild(p);
        setTimeout(function(){ document.addEventListener("click",function c(){if(p.parentNode)p.remove();document.removeEventListener("click",c);}); },100);
    }

    function toggleReact(msgId, emoji) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", url("ajax/chat.reaction"), true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.withCredentials = true;
        xhr.onload = function() {
            if (xhr.status === 200) {
                try { var d = JSON.parse(xhr.responseText); if (d.status === "ok") updateBar(msgId, d.reactions); } catch(ex) {}
            }
        };
        xhr.send("action=toggle&msg_id=" + msgId + "&reaction=" + encodeURIComponent(emoji));
    }

    function updateBar(msgId, rdata) {
        // Preserve known system flag if the update doesn't carry it
        var prev = reactionCache[msgId];
        if (prev && prev.isSystem && !rdata.isSystem) rdata.isSystem = true;
        reactionCache[msgId] = rdata;
        var el = findMsgEl(msgId);
        if (!el) return;
        var ex = el.querySelector(".reactions-bar");
        if (ex) ex.remove();
        if (rdata && rdata.isSystem) return;
        if (rdata && rdata.list && rdata.list.length > 0) {
            placeBar(el, buildBar(msgId, rdata.list, rdata.user_reaction, rdata.isSystem || false));
        } else if (canReact) {
            placeBar(el, buildBar(msgId, [], null, rdata.isSystem || false));
        }
    }

    function findMsgEl(msgId) {
        return document.querySelector(".msgnum_" + msgId) || document.querySelector("[class*='msgnum_" + msgId + "']");
    }

    function placeBar(container, bar) {
        // Insert reactions NEXT TO and BEFORE the dropdown (Reply/Forward area)
        var dropdown = container.querySelector('.dropdown');
        if (dropdown && dropdown.parentNode) {
            dropdown.parentNode.insertBefore(bar, dropdown);
            return;
        }
        // Fallback for public chat: find reply icon area and insert before it
        var replyLink = container.querySelector('a[onclick*="showReply"]');
        if (replyLink && replyLink.parentNode) {
            replyLink.parentNode.insertBefore(bar, replyLink);
            return;
        }
        // Fallback: before message text
        var textP = container.querySelector("p[id^='text_']");
        if (textP && textP.parentNode) {
            textP.parentNode.insertBefore(bar, textP);
            return;
        }
        // Last fallback: prepend to container
        if (container.firstChild) {
            container.insertBefore(bar, container.firstChild);
        } else {
            container.appendChild(bar);
        }
    }

    function isSystemMsgId(msgId) {
        var rd = reactionCache[msgId];
        return !!(rd && rd.isSystem);
    }

    function initMsg(el) {
        if (!el || el.querySelector(".reactions-bar")) return;
        var mid = getMsgId(el);
        if (!mid) return;
        var rd = reactionCache[mid];
        if (rd && rd.isSystem) return; // system messages: no reactions
        // Detect system message from class on the element if cache absent
        if (!rd && el.querySelector && el.querySelector("[id^='inflblock_-1'], .privat_ausg_an")) { return; }
        if (rd && rd.list && rd.list.length > 0) {
            placeBar(el, buildBar(mid, rd.list, rd.user_reaction, rd.isSystem || false));
        } else if (canReact) {
            placeBar(el, buildBar(mid, [], null, rd ? rd.isSystem : false));
        }
    }

    function processAll(root) {
        root = root || document;
        var boxes = root.querySelectorAll("[class*='msgnum_']");
        for (var i = 0; i < boxes.length; i++) initMsg(boxes[i]);
    }

    // Process reaction_updates from polling response
    function processUpdates(updates) {
        if (!updates || typeof updates !== "object") return;
        for (var mid in updates) {
            if (updates.hasOwnProperty(mid)) {
                var rdata = updates[mid];
                // preserve isSystem flag already known
                var prev = reactionCache[mid];
                if (prev && prev.isSystem && !rdata.isSystem) rdata.isSystem = true;
                reactionCache[mid] = rdata;
                var el = findMsgEl(mid);
                if (el) {
                    var ex = el.querySelector(".reactions-bar");
                    if (ex) ex.remove();
                    if (rdata.isSystem) continue;
                    if (rdata.list && rdata.list.length > 0) {
                        placeBar(el, buildBar(parseInt(mid), rdata.list, rdata.user_reaction, rdata.isSystem || false));
                    } else if (canReact) {
                        placeBar(el, buildBar(parseInt(mid), [], null, rdata.isSystem || false));
                    }
                }
            }
        }
    }

    // Hook into dstarter.js polling response handler
    function hookPolling() {
        if (typeof receiveResultJSON === "function") {
            var orig = receiveResultJSON;
            receiveResultJSON = function(data) {
                var result = orig.apply(this, arguments);
                if (data && data.reaction_updates) {
                    setTimeout(function(){ processUpdates(data.reaction_updates); }, 200);
                }
                return result;
            };
        } else if (typeof AjaxReadRequest === "function") {
            var orig2 = AjaxReadRequest;
            AjaxReadRequest = function() {
                orig2.apply(this, arguments);
            };
        }
    }

    // Hook addmessage for new messages
    function hookAddMsg() {
        if (typeof addmessage === "function") {
            var orig = addmessage;
            addmessage = function(params) {
                if (params && params.msgid && params.reactions) {
                    reactionCache[params.msgid] = { list: params.reactions, user_reaction: params.my_reaction, isSystem: params.isSystem ? 1 : 0 };
                }
                var result = orig.apply(this, arguments);
                if (params && params.msgid) {
                    setTimeout(function(mid) { var el = findMsgEl(mid); if (el) initMsg(el); }, 300, params.msgid);
                }
                return result;
            };
        } else { setTimeout(hookAddMsg, 500); }
    }

    // Intercept XMLHttpRequest to capture polling responses for reaction_updates
    function hookXHR() {
        var origOpen = XMLHttpRequest.prototype.open;
        var origSend = XMLHttpRequest.prototype.send;
        XMLHttpRequest.prototype.open = function(method, url) {
            this._url = url;
            return origOpen.apply(this, arguments);
        };
        XMLHttpRequest.prototype.send = function() {
            var self = this;
            this.addEventListener("load", function() {
                if (self._url && self._url.indexOf("ajax/get") > -1 && self.status === 200) {
                    try {
                        var resp = JSON.parse(self.responseText);
                        if (resp.reaction_updates) {
                            setTimeout(function(){ processUpdates(resp.reaction_updates); }, 300);
                        }
                    } catch(ex) {}
                }
            });
            return origSend.apply(this, arguments);
        };
    }

    // Watch for new messages via MutationObserver
    function watch() {
        var containers = document.querySelectorAll(".msgcontiner");
        for (var c = 0; c < containers.length; c++) {
            (function(ct) {
                var obs = new MutationObserver(function(muts) {
                    for (var m = 0; m < muts.length; m++) {
                        var nodes = muts[m].addedNodes;
                        for (var n = 0; n < nodes.length; n++) {
                            if (nodes[n].nodeType === 1) {
                                if (nodes[n].className && nodes[n].className.indexOf("msgnum_") > -1)
                                    setTimeout(function(el){ initMsg(el); }, 150, nodes[n]);
                                if (nodes[n].querySelectorAll) {
                                    var ch = nodes[n].querySelectorAll("[class*='msgnum_']");
                                    for (var x = 0; x < ch.length; x++)
                                        setTimeout(function(el){ initMsg(el); }, 150, ch[x]);
                                }
                            }
                        }
                    }
                });
                obs.observe(ct, { childList: true, subtree: true });
            })(containers[c]);
        }
    }

    function start() {
        loadEmojis();
        hookXHR();
        hookPolling();
        hookAddMsg();
        setTimeout(function(){ processAll(); }, 1500);
        setTimeout(watch, 1500);
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", function() { setTimeout(start, 1000); });
    } else { setTimeout(start, 1000); }
})();