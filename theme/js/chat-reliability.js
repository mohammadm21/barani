// =====================================================
// Chat Reliability Layer v3 - Simple & Safe
// - Error recovery for receiveResultJSON
// - Stuck polling detection
// - Connection monitoring
// - Exponential backoff on retries
// - Safe patching even if target functions load later
// - Accurate stale-connection timing
// - Optional teardown via window.ChatReliability.destroy()
// =====================================================

(function() {
    'use strict';

    var _lastMessageTime = Date.now();
    var _retryCount = 0;
    var _connectionState = 'online';
    var _patched = false;
    var _patchPollId = null;
    var _staleCheckId = null;
    var _pendingReconnectTimeout = null;

    var MAX_RETRY_DELAY = 30000;   // cap backoff at 30s
    var BASE_RETRY_DELAY = 1000;   // start at 1s
    var STALE_THRESHOLD = 60000;   // consider stale after 60s of silence
    var STUCK_POLL_THRESHOLD = 15000;
    var STALE_CHECK_INTERVAL = 5000; // check every 5s instead of 30s for accuracy

    function resetRetry() {
        _retryCount = 0;
        _lastMessageTime = Date.now();
        if (_pendingReconnectTimeout) {
            clearTimeout(_pendingReconnectTimeout);
            _pendingReconnectTimeout = null;
        }
        if (_connectionState !== 'online') {
            _connectionState = 'online';
            updateConnectionIndicator('online');
        }
    }

    function updateConnectionIndicator(state) {
        var el = document.getElementById('connection-indicator');
        if (!el) {
            el = document.createElement('div');
            el.id = 'connection-indicator';
            el.style.cssText = 'position:fixed;top:5px;right:5px;padding:5px 10px;border-radius:4px;font-size:11px;z-index:99999;display:none;font-family:tahoma;';
            document.body.appendChild(el);
        }
        if (state === 'online') { el.style.display = 'none'; }
        else if (state === 'reconnecting') { el.style.display = 'block'; el.style.backgroundColor = '#ff9800'; el.style.color = '#fff'; el.textContent = 'در حال اتصال مجدد...'; }
        else if (state === 'offline') { el.style.display = 'block'; el.style.backgroundColor = '#f44336'; el.style.color = '#fff'; el.textContent = 'اتصال قطع شد'; }
    }

    // Exponential backoff with jitter, capped at MAX_RETRY_DELAY
    function getBackoffDelay() {
        var exp = Math.min(MAX_RETRY_DELAY, BASE_RETRY_DELAY * Math.pow(2, _retryCount));
        var jitter = exp * 0.2 * Math.random();
        return Math.round(exp + jitter);
    }

    function scheduleReconnect(fn) {
        if (_pendingReconnectTimeout) return; // already scheduled, don't stack requests
        var delay = getBackoffDelay();
        console.warn('[ChatReliability] Scheduling reconnect attempt #' + (_retryCount + 1) + ' in ' + delay + 'ms');
        _pendingReconnectTimeout = setTimeout(function() {
            _pendingReconnectTimeout = null;
            _retryCount++;
            fn();
        }, delay);
    }

    // === Safe patching: wait for target functions to exist before wrapping ===
    // Prevents silent no-op patching if this script loads before the chat's own script.
    function applyPatches() {
        if (_patched) return true;
        if (typeof window.receiveResultJSON !== 'function' || typeof window.AjaxReadRequest !== 'function') {
            return false; // not ready yet
        }

        var _origReceive = window.receiveResultJSON;
        window.receiveResultJSON = function(e) {
            try {
                if (!e || !e.responseText) return;
                resetRetry();
                if (_origReceive) _origReceive(e);
            } catch (err) {
                console.warn('[ChatReliability] receiveResultJSON error caught:', err.message);
                // Don't fully resetRetry here - a caught error still means something
                // went wrong, so let backoff logic decide whether to reconnect.
                _lastMessageTime = Date.now();
            }
        };

        var _origAjaxRead = window.AjaxReadRequest;
        window.AjaxReadRequest = function(forced) {
            if (!forced && typeof $get_message_req !== 'undefined' && $get_message_req) {
                if (Date.now() - _lastMessageTime > STUCK_POLL_THRESHOLD) {
                    console.warn('[ChatReliability] Poll stuck 15s, force reset');
                    $get_message_req = false;
                } else {
                    return;
                }
            }
            _lastMessageTime = Date.now();
            if (_origAjaxRead) _origAjaxRead(forced);
        };

        _patched = true;
        console.log('[ChatReliability] Patches applied');
        return true;
    }

    // Poll for the target functions until they exist (handles load-order issues),
    // then apply patches. Gives up after ~20s to avoid polling forever.
    function waitForTargetsAndPatch() {
        if (applyPatches()) return;
        var attempts = 0;
        var maxAttempts = 40; // 40 * 500ms = 20s
        _patchPollId = setInterval(function() {
            attempts++;
            if (applyPatches() || attempts >= maxAttempts) {
                clearInterval(_patchPollId);
                _patchPollId = null;
                if (!_patched) {
                    console.warn('[ChatReliability] Gave up waiting for receiveResultJSON/AjaxReadRequest - patches not applied');
                }
            }
        }, 500);
    }

    // === Connection monitoring ===
    var _onlineHandler = function() {
        console.log('[ChatReliability] Connection restored');
        _connectionState = 'online';
        resetRetry();
        if (typeof window.AjaxReadRequest === 'function') window.AjaxReadRequest(true);
    };
    var _offlineHandler = function() {
        console.log('[ChatReliability] Connection lost');
        _connectionState = 'offline';
        updateConnectionIndicator('offline');
    };
    if (window.addEventListener) {
        window.addEventListener('online', _onlineHandler);
        window.addEventListener('offline', _offlineHandler);
    }

    // === Stale connection detection (checked frequently, acts only after threshold) ===
    _staleCheckId = setInterval(function() {
        if (_connectionState === 'offline') return; // browser already knows we're offline
        var silentFor = Date.now() - _lastMessageTime;
        if (silentFor > STALE_THRESHOLD) {
            if (_connectionState !== 'reconnecting') {
                _connectionState = 'reconnecting';
                updateConnectionIndicator('reconnecting');
            }
            console.warn('[ChatReliability] No messages for ' + silentFor + 'ms, attempting reconnect');
            scheduleReconnect(function() {
                if (typeof $get_message_req !== 'undefined') $get_message_req = false;
                if (typeof window.AjaxReadRequest === 'function') window.AjaxReadRequest(true);
            });
        }
    }, STALE_CHECK_INTERVAL);

    // === Public API for diagnostics / manual teardown ===
    window.ChatReliability = {
        getState: function() {
            return {
                connectionState: _connectionState,
                retryCount: _retryCount,
                lastMessageAgoMs: Date.now() - _lastMessageTime,
                patched: _patched
            };
        },
        destroy: function() {
            if (_staleCheckId) clearInterval(_staleCheckId);
            if (_patchPollId) clearInterval(_patchPollId);
            if (_pendingReconnectTimeout) clearTimeout(_pendingReconnectTimeout);
            if (window.removeEventListener) {
                window.removeEventListener('online', _onlineHandler);
                window.removeEventListener('offline', _offlineHandler);
            }
            console.log('[ChatReliability] Destroyed');
        }
    };

    // === Init ===
    document.addEventListener('DOMContentLoaded', function() {
        waitForTargetsAndPatch();
        console.log('[ChatReliability] Initialized');
    });

    // In case DOMContentLoaded already fired before this script ran
    if (document.readyState === 'interactive' || document.readyState === 'complete') {
        waitForTargetsAndPatch();
    }

})();