<?php
// تم موبایل تلگرامی — ورودی پویا؛ توسط pages/phone.php پس از احراز هویت include می‌شود.
$chat_setup = json_decode(theme::setup_chat(), true);
$ph_json = array(
    'me' => array(
        'id' => (int) $me['id'],
        'name' => isset($me['name']) ? $me['name'] : session('name'),
        'type' => $me['type'],
        'gender' => $me['gender'],
        'score' => isset($me['score']) ? $me['score'] : 0,
        'bank' => isset($me['bank']) ? $me['bank'] : 0,
        'room' => (int) session('room'),
        'admin' => session('admin'),
        'chpw' => session('changepw'),
        'guest' => isset($usertype[session('type')]['guest']) ? $usertype[session('type')]['guest'] : '0',
        'avatar' => isset($me['avatar']) ? $me['avatar'] : '',
        'can_pin' => isset($chat_setup['me']['pinmsg']) ? $chat_setup['me']['pinmsg'] : false,
        'can_delmsg' => isset($chat_setup['me']['delmsg']) ? $chat_setup['me']['delmsg'] : false,
        'can_editmsg' => isset($chat_setup['me']['editmsg']) ? $chat_setup['me']['editmsg'] : false,
        'can_forward' => isset($chat_setup['me']['forward']) ? $chat_setup['me']['forward'] : false,
        'can_reply' => isset($chat_setup['me']['reply']) ? $chat_setup['me']['reply'] : false,
    ),
    'url' => array(
        'chat' => url('chat'),
        'phone' => url('phone'),
        'logout' => url('logout'),
        'profile' => url('profile'),
        'setup' => '',
        'theme' => theme::url(''),
    ),
    'setting' => isset($chat_setup['settings']) ? $chat_setup['settings'] : array(),
    'types' => isset($chat_setup['types']) ? $chat_setup['types'] : array(),
    'fillprint_enabled' => isset($options['fillprint_enabled']) ? $options['fillprint_enabled'] : 'false',
    'reactions' => isset($options['reaction_emojis']) ? json_decode($options['reaction_emojis'], true) : array('👍', '❤️', '😂', '😮', '😢', '😡', '👎'),
    'chat_theme' => $options['chat_theme'],
    'chat_prefix' => $options['chat_prefix'],
    'url_type' => $options['url_type'],
    'rewrite_prefix' => $options['rewtite_suffix'],
);
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#17212b">
    <title><?php echo htmlspecialchars($options['chat_site_title']); ?></title>
    <link rel="stylesheet" href="<?php echo theme::url('css/phone.css'); ?>?ver=1.2.0">
</head>
<body class="phone-app">
<script type="text/javascript">
    window.__phone = <?php echo json_encode($ph_json, JSON_UNESCAPED_UNICODE); ?>;
</script>

<div id="app" class="app">
    <!-- ستون لیست گفتگوها -->
    <div id="convList" class="conv-list">
        <header class="conv-header">
            <h1 class="app-title"><?php echo htmlspecialchars($options['chat_site_title']); ?></h1>
            <button class="hdr-btn" id="btnSettings" title="تنظیمات">•••</button>
        </header>
        <div class="tabs">
            <button class="tab active" data-tab="chats">چت‌ها <span class="tab-badge hidden"></span></button>
            <button class="tab" data-tab="rooms">گروه‌ها <span class="tab-badge hidden"></span></button>
            <button class="tab" data-tab="users">کاربران</button>
        </div>
        <div class="conv-scroll" id="convScroll"></div>
    </div>

    <!-- ستون چت -->
    <div id="chatPane" class="chat-pane hidden">
        <header class="chat-header">
            <button class="back-btn" id="btnBack" title="بازگشت">→</button>
            <button class="hdr-avatar-wrap" id="hdrAvatar" title="پروفایل">
                <span class="avatar-inner" id="hdrAvatarInner"></span>
            </button>
            <div class="chat-title-wrap">
                <div class="chat-title" id="chatTitle"></div>
                <div class="chat-sub" id="chatSub"></div>
                <div class="chat-type" id="chatType"></div>
            </div>
            <button class="hdr-btn" id="ctMore" title="بیشتر">⁝</button>
        </header>
        <div class="pin-banner hidden" id="pinBanner">
            <span class="pin-x" id="pinClose">✕</span>
            <div class="pin-title">پیام سنجاق‌شده</div>
            <div class="pin-body" id="pinBody"></div>
        </div>
        <div class="msg-scroll" id="msgScroll"></div>
        <div class="reply-hint hidden" id="replyHint"></div>
        <div class="upload-bar hidden" id="uploadBar">
            <div class="upload-name" id="uploadName"></div>
            <div class="upload-track"><div class="upload-fill" id="uploadFill"></div></div>
            <span class="upload-pct" id="uploadPct">0%</span>
        </div>
        <div class="composer">
            <button class="comp-btn" id="cSmile" title="شکلک">☺</button>
            <button class="comp-btn" id="cAttach" title="ارسال فایل">📎</button>
            <input type="file" id="fileInput" style="display:none">
            <textarea id="msgInput" rows="1" placeholder="پیام..." ></textarea>
            <button class="send-btn" id="cSend" title="ارسال">➤</button>
        </div>
    </div>

    <div class="overlay hidden" id="overlay"></div>
</div>

<script type="text/javascript">
    // bootstrap درستِ globals دلخواه کلاینت (مستقل از تم دسکتاپ)
    window.__T = function(p){ return window.__phone.theme + p; };
    // مسیر asset های سراسری (آواتار/شکلک در پوشه global theme زندگی می‌کنند، نه تم phone)
    window.__G = function(p){ return (p.indexOf('theme/') === 0 ? '/' : '/theme/') + p; };
    // ساختاردهیِ آدرس endpoint مطابق عملگر url() در دسکتاپ (مستقل از jQuery)
    (function(){
        var ph = window.__phone;
        window.__url = function(p){
            if (p.indexOf('theme/') === 0) return '/' + p;
            var ut = ph.url_type;
            var sfx = ph.rewrite_prefix || '';
            if (ut === 'rewrite' || ut === 'rewrite-i') return '/' + p + sfx;
            if (ut === 'i') return 'index.php?i=' + p.replace(/\//g, '&');
            return 'index.php?' + p; // get
        };
    })();
</script>
<script src="<?php echo theme::url('js/phone.js'); ?>?ver=1.1.0"></script>
<script src="<?php echo theme::url('js/fillprint.js'); ?>?ver=1.0.0"></script>
</body>
</html>
