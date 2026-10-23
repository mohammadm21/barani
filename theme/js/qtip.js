var qTipTag = "a,label,input,img,textarea",
    qTipX = 0,
    qTipY = 10;
tooltip = {
    name: "qTip",
    offsetX: qTipX,
    offsetY: qTipY,
    tip: null
}, tooltip.init = function() {
    var t = "http://www.w3.org/1999/xhtml";
    if (!e) var e = "qTip";
    var o = document.getElementById(e);
    if (o || (o = document.createElementNS ? document.createElementNS(t, "div") : document.createElement("div"), o.setAttribute("id", e), document.getElementsByTagName("body").item(0).appendChild(o)), document.getElementById) {
        this.tip = document.getElementById(this.name), this.tip && (document.onmousemove = function(t) {
            tooltip.move(t)
        });
        for (var i, n, l, m = qTipTag.split(","), p = 0; p < m.length; p++)
            if (l = document.getElementsByTagName(m[p]))
                for (var u = 0; u < l.length; u++) i = l[u], n = i.getAttribute("title"), n && (i.setAttribute("tiptitle", n), i.removeAttribute("title"), i.removeAttribute("alt"), i.onmouseover = function() {
                    tooltip.show(this.getAttribute("tiptitle"))
                }, i.onmouseout = function() {
                    tooltip.hide()
                })
    }
}, tooltip.move = function(t) {
    var e = 0,
        o = 0;
    document.all ? (e = document.documentElement && document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft, o = document.documentElement && document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop, e += window.event.clientX, o += window.event.clientY) : (e = t.pageX, o = t.pageY), this.tip.style.left = e + this.offsetX + "px", this.tip.style.top = o + this.offsetY + "px"
}, tooltip.show = function(t) {
    this.tip && (this.tip.innerHTML = t, this.tip.style.display = "block")
}, tooltip.hide = function() {
    this.tip && (this.tip.innerHTML = "", this.tip.style.display = "none")
}, window.onmousemove = function() {
    tooltip.init()
};