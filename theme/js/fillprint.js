/**
 * fillprint.js
 * جمع‌آوری سیگنال‌های مرورگر/سیستم برای ثبت اثر انگشت هر کاربر واردشده
 * و بررسی شباهت با کاربران مشکوک/اخراج‌شده.
 *
 * داده‌ها پس از بارگذاری چت، به صورت خودکار به pages/ajax/fillprint.collect.php
 * ارسال می‌شود. اگر ویژگی در پنل غیرفعال باشد، درخواست ارسال نمی‌شود.
 */
(function () {
  var ENABLED = (typeof $setting !== 'undefined' && $setting.fillprint_enabled === 'true');

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

  function getAudioFingerprint() {
    return new Promise(function (resolve) {
      try {
        var AudioContext = window.OfflineAudioContext || window.webkitOfflineAudioContext;
        if (!AudioContext) return resolve('unsupported');
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
          resolve(hashString(sum.toString()));
        };
        setTimeout(function () { resolve('timeout'); }, 1000);
      } catch (e) {
        resolve('error');
      }
    });
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

  // -----------------------------------------------------------------------
  // تشخیصِ مرورگرِ واقعی (فراتر از User-Agent که به‌راحتی جعل/تنظیم می‌شود)
  // -----------------------------------------------------------------------

  // Brave رسمی یک API دارد: navigator.brave.isBrave() — قابل‌اعتماد است.
  function detectBraveAsync() {
    try {
      if (navigator.brave && typeof navigator.brave.isBrave === 'function') {
        return navigator.brave.isBrave().then(function (res) {
          return !!res;
        }).catch(function () { return false; });
      }
    } catch (e) { /* ignore */ }
    return Promise.resolve(false);
  }

  // Tor Browser (و فایرفاکسِ با privacy.resistFingerprinting) API رسمی ندارند؛
  // ترکیبی از چند نشانه‌ی هم‌زمان را بررسی می‌کنیم. هر نشانه به‌تنهایی ضعیف است،
  // ولی مجموعشان نشانه‌ی قوی‌تری می‌دهد.
  function detectFingerprintResistSignals() {
    var signals = [];
    try {
      var tz = (Intl.DateTimeFormat().resolvedOptions().timeZone) || '';
      if (tz === 'UTC' || tz === 'Etc/UTC') signals.push('utc_tz');
    } catch (e) { /* ignore */ }
    try {
      var onlyEnUs = navigator.language === 'en-US' &&
        (!navigator.languages || (navigator.languages.length === 1 && navigator.languages[0] === 'en-US'));
      if (onlyEnUs) signals.push('en_us_only_lang');
    } catch (e) { /* ignore */ }
    try {
      // از نسخه‌ی ۹ به بعد، Tor Browser عرضِ/ارتفاعِ پنجره را به مضربِ ۲۰۰×۱۰۰
      // گرد می‌کند (letterboxing) تا اندازه‌ی صفحه قابل‌ردیابی نباشد.
      if (window.innerWidth > 0 && window.innerWidth % 200 === 0 && window.innerHeight % 100 === 0) {
        signals.push('letterboxed_window');
      }
    } catch (e) { /* ignore */ }
    try {
      // مرورگرهای واقعاً کرومیومی (Chrome, Brave, Edge, Opera) یک شیءِ
      // window.chrome دارند؛ اگر UA ادعای Chrome دارد ولی این شیء نیست،
      // به احتمال زیاد UA جعل شده (مثلاً فایرفاکس/Tor خودش را Chrome معرفی کرده).
      if (typeof window.chrome === 'undefined' && /Chrome\//.test(navigator.userAgent)) {
        signals.push('chrome_ua_no_chrome_obj');
      }
    } catch (e) { /* ignore */ }
    try {
      if (navigator.webdriver) signals.push('webdriver');
    } catch (e) { /* ignore */ }
    return signals;
  }

  // اگر مرورگر از Client Hints (User-Agent Data) پشتیبانی کند، برندهای واقعیِ
  // موتور را می‌دهد. فقط مرورگرهای کرومیومی این را دارند؛ فایرفاکس/سافاری/Tor ندارند
  // (که خودش هم یک نشانه‌ی جانبی است).
  function detectUaDataBrands() {
    try {
      if (navigator.userAgentData && Array.isArray(navigator.userAgentData.brands)) {
        return navigator.userAgentData.brands.map(function (b) { return b.brand; }).join(',');
      }
    } catch (e) { /* ignore */ }
    return '';
  }

  function computeBrowserHint(isBrave) {
    var signals = detectFingerprintResistSignals();
    var uaBrands = detectUaDataBrands();
    var label = 'chrome_or_chromium'; // پیش‌فرض: چیزی مشکوک ندیدیم

    if (isBrave) {
      label = 'brave';
    } else if (signals.indexOf('chrome_ua_no_chrome_obj') !== -1) {
      // UA می‌گوید Chrome ولی شیءِ کرومیومی نیست — جعلِ UA محرز است.
      var strong = ['utc_tz', 'en_us_only_lang', 'letterboxed_window']
        .filter(function (s) { return signals.indexOf(s) !== -1; }).length;
      label = (strong >= 2) ? 'tor_or_hardened_firefox_likely' : 'spoofed_ua_not_chromium';
    }
    if (signals.indexOf('webdriver') !== -1) {
      label += '+webdriver';
    }
    return {
      label: label,
      signals: signals.join(','),
      uaBrands: uaBrands
    };
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

  // توکنِ دستگاه: هشِ ترکیبیِ چند سیگنالِ سخت‌عوض (GPU، canvas، پلتفرم، رزولوشن).
  // عمداً از کوکی خوانده نمی‌شود: پاک‌کردنِ کوکی/دیتای مرورگر نباید دستگاه را
  // ناشناس کند. منبعِ حقیقت همان سیگنال‌های خودِ سیستم است، نه حافظه‌ی پایدار.
  // (توکن فقط یک برچسبِ کمکی برای سرور است؛ امتیازِ similarity خودِ سیگنال‌ها
  //  معیارِ تعیین‌کننده‌ی هم‌دستگاهی است و به کوکی نیازی ندارد.)
  function makeToken() {
    try {
      var _c = collect();
      return hashString(_c.canvasFp + '|' + _c.webglRenderer + '|' + _c.platform + '|' + _c.screenWidth + 'x' + _c.screenHeight);
    } catch (e) {
      return 't' + Math.floor(Math.random() * 1e9).toString(36);
    }
  }
  var TOKEN = makeToken();

  var AJAX_URL = (typeof url === 'function') ? url('ajax/fillprint.collect') : 'ajax/fillprint.collect';

  function send() {
    var payload = collect();
    payload.fpToken = TOKEN;
    payload.timezone = payload.timezone || '';
    Promise.all([getAudioFingerprint(), detectBraveAsync()]).then(function (results) {
      payload.audioFp = results[0];
      var hint = computeBrowserHint(results[1]);
      payload.browserHint = hint.label;
      payload.browserSignals = hint.signals + (hint.uaBrands ? '|brands:' + hint.uaBrands : '');
      try {
        fetch(AJAX_URL, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          credentials: 'same-origin',
          body: JSON.stringify(payload)
        }).catch(function (err) {
          // به‌جای بلعیدنِ خطا، در کنسول ثبت کن تا تشخیصِ نبودِ ثبت اثر انگشت آسان باشد
          if (window.console && console.error) console.error('[fillprint-send]', err);
        });
      } catch (e) {
        if (window.console && console.error) console.error('[fillprint-send]', e);
      }
    });
  }

  function run() {
    if (!ENABLED) return;
    // تأخیرِ خیلی کوتاه، فقط برای اطمینان از آماده بودنِ session/کوکی؛ قبلاً ۸۰۰
    // میلی‌ثانیه بود که بی‌دلیل ریسکِ «بن شدن قبل از اولین ثبتِ اثر انگشت» را
    // بالا می‌برد (اگر مدیر خیلی سریع کاربر را اخراج می‌کرد). حالا حداقلِ ممکن است.
    setTimeout(send, 50);

    // ارسالِ دوره‌ای (heartbeat): تا وقتی کاربر در صفحه باز است، هر چند دقیقه یک‌بار
    // دوباره اثر انگشت را می‌فرستد. این‌طور رکوردِ فینگرپرینتِ این uid همیشه تازه و
    // در دسترس است، حتی برای جلسه‌های طولانی؛ Users::bann() که آخرین ردیفِ همین
    // session_ref را برمی‌دارد، دیگر هیچ‌وقت با نبودِ رکورد یا رکوردِ خیلی قدیمی
    // مواجه نمی‌شود.
    setInterval(function () {
      if (document.visibilityState === 'hidden') return;
      send();
    }, 3 * 60 * 1000); // هر ۳ دقیقه
  }

  document.addEventListener('DOMContentLoaded', run);
  if (document.readyState === 'complete') run();
})();