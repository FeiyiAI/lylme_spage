/* =========================================================
   SoftUI 主题脚本（ES5，无 let/const/箭头/模板字符串）
   功能：明暗切换、配色切换、搜索引擎、时钟、返回顶部、搜索联想
   ========================================================= */
(function () {
    'use strict';
    var doc = document;
    var root = doc.documentElement;

    /* ---------- 工具 ---------- */
    function $(sel, ctx) { return (ctx || doc).querySelector(sel); }
    function $all(sel, ctx) { return Array.prototype.slice.call((ctx || doc).querySelectorAll(sel)); }

    /* ---------- 明暗 / 配色 ---------- */
    var gear = $('#sf-gear');
    var panel = $('#sf-panel');
    var modeToggle = $('#sf-mode-toggle');

    function resolveMode(mode) {
        if (mode === 'auto') {
            return (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light';
        }
        return mode;
    }

    function applyMode(mode) {
        root.setAttribute('data-mode', resolveMode(mode));
    }

    function markActive() {
        var scheme = root.getAttribute('data-scheme');
        var storedMode = localStorage.getItem('sf_mode');
        var mode = storedMode || root.getAttribute('data-mode-def') || 'auto';
        $all('.sf-swatch').forEach(function (b) {
            b.classList.toggle('is-active', b.getAttribute('data-scheme') === scheme);
        });
        $all('.sf-mode-btn').forEach(function (b) {
            b.classList.toggle('is-active', b.getAttribute('data-mode') === mode);
        });
    }

    if (gear && panel) {
        gear.addEventListener('click', function () { panel.classList.toggle('is-open'); });
        doc.addEventListener('click', function (e) {
            if (panel.classList.contains('is-open') && !panel.contains(e.target) && e.target !== gear && !gear.contains(e.target)) {
                panel.classList.remove('is-open');
            }
        });
    }

    $all('.sf-swatch').forEach(function (b) {
        b.addEventListener('click', function () {
            var s = b.getAttribute('data-scheme');
            root.setAttribute('data-scheme', s);
            try { localStorage.setItem('sf_scheme', s); } catch (err) {}
            markActive();
        });
    });

    $all('.sf-mode-btn').forEach(function (b) {
        b.addEventListener('click', function () {
            var m = b.getAttribute('data-mode');
            try { localStorage.setItem('sf_mode', m); } catch (err) {}
            applyMode(m);
            markActive();
        });
    });

    if (modeToggle) {
        modeToggle.addEventListener('click', function () {
            var cur = root.getAttribute('data-mode') === 'dark' ? 'light' : 'dark';
            try { localStorage.setItem('sf_mode', cur); } catch (err) {}
            applyMode(cur);
            markActive();
        });
    }

    // 跟随系统变化时同步（仅当用户选了 auto 时）
    if (window.matchMedia) {
        var mq = window.matchMedia('(prefers-color-scheme: dark)');
        var onSysChange = function () {
            var m = localStorage.getItem('sf_mode');
            if (!m || m === 'auto') { applyMode('auto'); markActive(); }
        };
        if (mq.addEventListener) { mq.addEventListener('change', onSysChange); }
        else if (mq.addListener) { mq.addListener(onSysChange); }
    }

    markActive();

    /* ---------- 搜索引擎 ---------- */
    var form = $('#search-form');
    var input = $('#search-input');
    var engines = $all('input[name="sou"]');

    function currentEngine() {
        var checked = $('input[name="sou"]:checked');
        if (!checked) { checked = engines[0]; }
        return checked;
    }

    // 记忆引擎选择
    function restoreEngine() {
        var saved;
        try { saved = localStorage.getItem('theme_sou'); } catch (err) { saved = null; }
        if (saved) {
            engines.forEach(function (r) {
                if (r.value === saved) { r.checked = true; }
            });
        }
        var e = currentEngine();
        if (e && input) { input.setAttribute('placeholder', e.getAttribute('data-hint') || '搜索一下'); }
    }

    engines.forEach(function (r) {
        r.addEventListener('change', function () {
            if (input) { input.setAttribute('placeholder', r.getAttribute('data-hint') || '搜索一下'); }
            try { localStorage.setItem('theme_sou', r.value); } catch (err) {}
        });
    });

    if (form && input) {
        form.addEventListener('submit', function (ev) {
            ev.preventDefault();
            var q = input.value.trim();
            var e = currentEngine();
            if (!q || !e) { return; }
            var url = e.value + encodeURIComponent(q);
            window.open(url, '_blank');
            hideSuggest();
        });
    }

    restoreEngine();

    /* ---------- 联想词（百度 JSONP，安全降级） ---------- */
    var sugBox = null, reqId = 0, sugTimer = null, cursor = -1, items = [];
    function ensureBox() {
        if (sugBox) { return sugBox; }
        sugBox = doc.createElement('div');
        sugBox.className = 'sf-suggest';
        sugBox.style.display = 'none';
        if (form && form.parentNode) { form.parentNode.insertBefore(sugBox, form.nextSibling); }
        return sugBox;
    }
    function renderSuggest(list) {
        var box = ensureBox();
        if (!list || !list.length) { box.style.display = 'none'; box.innerHTML = ''; return; }
        var html = '';
        for (var i = 0; i < list.length; i++) {
            html += '<div class="sf-suggest__item" data-idx="' + i + '">' + list[i] + '</div>';
        }
        box.innerHTML = html;
        box.style.display = 'block';
        $all('.sf-suggest__item', box).forEach(function (el) {
            el.addEventListener('mousedown', function (ev) {
                ev.preventDefault();
                input.value = el.textContent;
                submitCurrent();
            });
        });
    }
    function submitCurrent() {
        var q = input.value.trim();
        var e = currentEngine();
        if (q && e) { window.open(e.value + encodeURIComponent(q), '_blank'); }
        hideSuggest();
    }
    function hideSuggest() {
        if (sugBox) { sugBox.style.display = 'none'; sugBox.innerHTML = ''; }
        cursor = -1; items = [];
    }
    function fetchSuggest(kw) {
        reqId++;
        var myId = reqId;
        var cbName = '_sf_sug_' + myId;
        var script = doc.createElement('script');
        script.src = 'https://suggestion.baidu.com/su?wd=' + encodeURIComponent(kw) + '&cb=' + cbName;
        window[cbName] = function (data) {
            delete window[cbName];
            if (myId !== reqId) { return; }
            var arr = (data && data.s) ? data.s : [];
            items = arr.slice(0, 10);
            renderSuggest(items);
        };
        script.onerror = function () { /* 静默降级 */ };
        doc.body.appendChild(script);
        // 仅移除 script 节点，绝不删除回调（避免旧响应触发 ReferenceError）
        setTimeout(function () {
            if (script.parentNode) { script.parentNode.removeChild(script); }
        }, 1600);
    }
    if (input) {
        input.addEventListener('input', function () {
            var kw = input.value.trim();
            if (sugTimer) { clearTimeout(sugTimer); }
            if (kw.length < 1) { hideSuggest(); return; }
            sugTimer = setTimeout(function () { fetchSuggest(kw); }, 250);
        });
        input.addEventListener('keydown', function (ev) {
            if (!sugBox || sugBox.style.display === 'none') { return; }
            var els = $all('.sf-suggest__item', sugBox);
            if (ev.keyCode === 38) { // up
                ev.preventDefault();
                cursor = (cursor <= 0) ? els.length - 1 : cursor - 1;
                setCursor(els);
            } else if (ev.keyCode === 40) { // down
                ev.preventDefault();
                cursor = (cursor >= els.length - 1) ? 0 : cursor + 1;
                setCursor(els);
            } else if (ev.keyCode === 13 && cursor >= 0) { // enter
                ev.preventDefault();
                input.value = els[cursor].textContent;
                submitCurrent();
            } else if (ev.keyCode === 27) { // esc
                hideSuggest();
            }
        });
        input.addEventListener('blur', function () { setTimeout(hideSuggest, 150); });
    }
    function setCursor(els) {
        for (var i = 0; i < els.length; i++) {
            els[i].classList.toggle('is-cur', i === cursor);
        }
        if (els[cursor]) { input.value = els[cursor].textContent; }
    }

    /* ---------- 时钟 ---------- */
    var clock = $('#sf-clock');
    function pad(n) { return n < 10 ? '0' + n : '' + n; }
    function tick() {
        if (!clock) { return; }
        var d = new Date();
        var wd = ['日', '一', '二', '三', '四', '五', '六'][d.getDay()];
        var tEl = $('.sf-clock__time', clock);
        var dEl = $('.sf-clock__date', clock);
        if (tEl) { tEl.textContent = pad(d.getHours()) + ':' + pad(d.getMinutes()) + ':' + pad(d.getSeconds()); }
        if (dEl) { dEl.textContent = d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()) + ' 周' + wd; }
    }
    tick();
    setInterval(tick, 1000);

    /* ---------- 返回顶部 ---------- */
    var top = $('#sf-top');
    if (top) {
        window.addEventListener('scroll', function () {
            if (window.pageYOffset > 300) { top.classList.add('is-show'); }
            else { top.classList.remove('is-show'); }
        });
        top.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
})();
