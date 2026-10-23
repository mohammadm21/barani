/**
 * phone fillprint.js
 * Lightweight device-fingerprint collector for the /phone (mobile) theme.
 * Duplicates the signal set of theme/js/fillprint.js so mobile users are
 * fingerprinted too — closing the gap where mobile users were invisible to
 * the abuser-matching system. Uses the phone theme's own globals
 * (window.__url, window.__phone) instead of $setting / url().
 *
 * Two triggers:
 *   1. Once after boot, if fillprint_enabled === 'true'.
 *   2. On demand when the server returns fp_capture:1 (admin just banned this
 *      user and wants a guaranteed abuser fingerprint row).
 */
(function () {
  var ENABLED = (window.__phone && window.__phone.fillprint_enabled === 'true');
  var SEND_URL = window.__url('ajax/fillprint.collect');
  var sentStatic = false;

  function hashString(str) {
    var hash = 0;
    for (var i = 0; i < str.length; i++) {
      hash = (hash << 5) - hash + str.charCodeAt(i);
      hash |= 0;
    }
    return hash.toString(36);
  }

  function getCanvasFingerprint() {
    try {
      var canvas = document.createElement('canvas');
      var ctx = canvas.getContext('2d');
      canvas.width = 220;
      canvas.height = 30;
      ctx.textBaseline = 'top';
      ctx.font = "14px 'Arial'";
      ctx.fillStyle = '#f60';
      ctx.fillRect(0, 0, 100, 20);
      ctx.fillStyle = '#069';
      ctx.fillText('fingerprint-check-🔒', 2, 15);
      ctx.fillStyle = 'rgba(102,204,0,0.7)';
      ctx.fillText('fingerprint-check-🔒', 4, 17);
      return hashString(canvas.toDataURL());
    } catch (e) {
      return 'unsupported';
    }
  }

  function getWebGLInfo() {
    try {
      var canvas = document.createElement('canvas');
      var gl = canvas.getContext('webgl') || canvas.getContext('experimental-webgl');
      if (!gl) return { vendor: 'none', renderer: 'none' };
      var dbgInfo = gl.getExtension('WEBGL_debug_renderer_info');
      if (!dbgInfo) return { vendor: 'blocked', renderer: 'blocked' };
      return {
        vendor: gl.getParameter(dbgInfo.UNMASKED_VENDOR_WEBGL),
        renderer: gl.getParameter(dbgInfo.UNMASKED_RENDERER_WEBGL)
      };
    } catch (e) {
      return { vendor: 'error', renderer: 'error' };
    }
  }

  function getAudioFingerprint(onDone) {
    try {
      var AudioContext = window.OfflineAudioContext || window.webkitOfflineAudioContext;
      if (!AudioContext) return onDone('unsupported');
      var ctx = new AudioContext(1, 44100, 44100);
      var oscillator = ctx.createOscillator();
      oscillator.type = 'triangle';
      oscillator.frequency.setValueAtTime(10000, ctx.currentTime);
      var compressor = ctx.createDynamicsCompressor();
      oscillator.connect(compressor);
      compressor.connect(ctx.destination);
      oscillator.start(0);
      ctx.startRendering();
      ctx.oncomplete = function (e) {
        var sum = 0;
        for (var i = 4500; i < 5000; i++) {
          sum += Math.abs(e.renderedBuffer.getChannelData(0)[i] || 0);
        }
        onDone(hashString(sum.toString()));
      };
      setTimeout(function () { onDone('timeout'); }, 1000);
    } catch (e) {
      onDone('error');
    }
  }

  function detectFonts() {
    var testFonts = [
      'Arial', 'Verdana', 'Times New Roman', 'Courier New', 'Georgia',
      'Tahoma', 'Comic Sans MS', 'Impact', 'B Nazanin', 'IRANSans',
      'Segoe UI', 'Calibri', 'Helvetica'
    ];
    var baseFonts = ['monospace', 'sans-serif', 'serif'];
    var testString = 'mmmmmmmmmmlli';
    var testSize = '72px';
    var span = document.createElement('span');
    span.style.position = 'absolute';
    span.style.left = '-9999px';
    span.style.fontSize = testSize;
    span.style.fontFamily = 'monospace';
    document.body.appendChild(span);
    var baseWidths = {};
    baseFonts.forEach(function (bf) {
      span.style.fontFamily = bf;
      baseWidths[bf] = span.offsetWidth;
    });
    var detected = [];
    testFonts.forEach(function (font) {
      var isDetected = false;
      for (var i = 0; i < baseFonts.length; i++) {
        span.style.fontFamily = "'" + font + "'," + baseFonts[i];
        if (span.offsetWidth !== baseWidths[baseFonts[i]]) {
          isDetected = true;
          break;
        }
      }
      if (isDetected) detected.push(font);
    });
    document.body.removeChild(span);
    return detected;
  }

  function collect() {
    var data = {};
    data.userAgent = navigator.userAgent;
    data.language = navigator.language;
    data.languages = (navigator.languages || []).join(',');
    data.platform = navigator.platform || '';
    data.hardwareConcurrency = navigator.hardwareConcurrency || 0;
    data.deviceMemory = navigator.deviceMemory || 0;
    data.maxTouchPoints = navigator.maxTouchPoints || 0;
    data.timezone = (Intl.DateTimeFormat().resolvedOptions().timeZone) || '';
    data.timezoneOffset = new Date().getTimezoneOffset();
    data.screenWidth = screen.width;
    data.screenHeight = screen.height;
    data.screenColorDepth = screen.colorDepth;
    data.pixelRatio = window.devicePixelRatio || 1;
    data.canvasFp = getCanvasFingerprint();
    var webgl = getWebGLInfo();
    data.webglVendor = webgl.vendor;
    data.webglRenderer = webgl.renderer;
    data.fonts = detectFonts().join(',');
    data.cookiesEnabled = navigator.cookieEnabled;
    data.doNotTrack = navigator.doNotTrack || 'unspecified';
    data.plugins = Array.from(navigator.plugins || []).map(function (p) { return p.name; }).join(',');
    return data;
  }

  // توکنِ دستگاه: هشِ سیگنال‌های سخت‌عوض (GPU، canvas، پلتفرم، رزولوشن).
  // عمداً از کوکی خوانده نمی‌شود تا پاک‌کردنِ کوکی/دیتای مرورگر دستگاه را ناشناس
  // نکند. معیارِ واقعیِ هم‌دستگاهی روی سرور، امتیازِ similarityِ خودِ سیگنال‌هاست.
  function makeToken() {
    try {
      var _c = collect();
      return hashString(_c.canvasFp + '|' + _c.webglRenderer + '|' + _c.platform + '|' + _c.screenWidth + 'x' + _c.screenHeight);
    } catch (e) {
      return 't' + Math.floor(Math.random() * 1e9).toString(36);
    }
  }
  var TOKEN = makeToken();

  // Sends the device fingerprint. If capture is true, the server stores it as a
  // confirmed abuser (admin instructed a ban-time capture). Errors are surfaced
  // to the console instead of being silently swallowed.
  function send(capture, cb) {
    var payload = collect();
    payload.fpCapture = capture ? true : false;
    payload.fpToken = TOKEN;
    getAudioFingerprint(function (audioFp) {
      payload.audioFp = audioFp;
      try {
        fetch(SEND_URL, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          credentials: 'same-origin',
          body: JSON.stringify(payload)
        }).then(function (r) { return r.text(); }).then(function () { if (cb) cb(); }).catch(function (err) {
          if (window.console && console.error) console.error('[fillprint-send]', err);
          if (cb) cb();
        });
      } catch (err) {
        if (window.console && console.error) console.error('[fillprint-send]', err);
        if (cb) cb();
      }
    });
  }

  // Exposed so phone.js can trigger a capture when the server asks for one.
  window.__fillprintCapture = function (cb) { send(true, cb); };

  function runStatic() {
    if (!ENABLED || sentStatic) return;
    sentStatic = true;
    setTimeout(function () { send(false); }, 800);
  }

  if (document.readyState === 'complete') { runStatic(); }
  else { document.addEventListener('DOMContentLoaded', runStatic); }
  setTimeout(runStatic, 1500);
})();
