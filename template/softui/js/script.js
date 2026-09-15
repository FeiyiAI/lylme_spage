/* =========================================================
   SoftUI 主题脚本（ES5，无 let/const/箭头/模板字符串）
   功能：左侧栏展开/收起、移动端抽屉、明暗切换、配色切换、
        搜索引擎选择+记忆、搜索联想（百度 JSONP）、时钟、返回顶部
   ========================================================= */
(function () {
  'use strict';
  var doc = document;
  var root = doc.documentElement;
  var body = doc.body;

  function $(sel, ctx) { return (ctx || doc).querySelector(sel); }
  function $all(sel, ctx) { return Array.prototype.slice.call((ctx || doc).querySelectorAll(sel)); }

  var mqMobile = window.matchMedia ? window.matchMedia('(max-width: 860px)') : null;
  function isMobile() { return mqMobile ? mqMobile.matches : (window.innerWidth <= 860); }

  /* ---------- 工具 ---------- */
  function tryGet(k) { try { return localStorage.getItem(k); } catch (e) { return null; } }
  function trySet(k, v) { try { localStorage.setItem(k, v); } catch (e) {} }

  /* ---------- 明暗模式 ---------- */
  function resolveMode(mode) {
    if (mode === 'auto') {
      return (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light';
    }
    return mode;
  }
  function applyMode(mode) { root.setAttribute('data-mode', resolveMode(mode)); }

  /* ---------- 配色 + 模式 标记激活 ---------- */
  function markActive() {
    var acc = root.getAttribute('data-accent') || 'red';
    var storedMode = tryGet('sf_mode') || body.getAttribute('data-mode-def') || 'auto';
    $all('.sf-swatch').forEach(function (b) {
      b.classList.toggle('is-active', b.getAttribute('data-accent') === acc);
    });
    $all('.sf-mode-btn').forEach(function (b) {
      b.classList.toggle('is-active', b.getAttribute('data-mode') === storedMode);
    });
  }

  /* ---------- 设置面板 ---------- */
  var panel = $('#sf-panel');
  function openPanel(open) { if (!panel) return; panel.classList.toggle('is-open', !!open); panel.setAttribute('aria-hidden', open ? 'false' : 'true'); }
  $all('.sf-gear-trigger').forEach(function (g) {
    g.addEventListener('click', function (ev) {
      ev.stopPropagation();
      if (!panel) return;
      panel.classList.toggle('is-open');
      panel.setAttribute('aria-hidden', panel.classList.contains('is-open') ? 'false' : 'true');
    });
  });
  doc.addEventListener('click', function (e) {
    if (!panel || !panel.classList.contains('is-open')) return;
    var t = e.target;
    if (!panel.contains(t) && !$all('.sf-gear-trigger').some(function (g) { return g === t || g.contains(t); })) {
      panel.classList.remove('is-open');
      panel.setAttribute('aria-hidden', 'true');
    }
  });

  /* ---------- 配色方案 ---------- */
  $all('.sf-swatch').forEach(function (b) {
    b.addEventListener('click', function () {
      var a = b.getAttribute('data-accent');
      root.setAttribute('data-accent', a);
      trySet('sf_accent', a);
      markActive();
    });
  });

  /* ---------- 模式按钮 ---------- */
  $all('.sf-mode-btn').forEach(function (b) {
    b.addEventListener('click', function () {
      var m = b.getAttribute('data-mode');
      trySet('sf_mode', m);
      applyMode(m);
      markActive();
    });
  });

  /* ---------- 顶部/侧栏模式按钮：轻点切换亮/暗 ---------- */
  $all('.sf-mode-trigger').forEach(function (b) {
    b.addEventListener('click', function () {
      var cur = root.getAttribute('data-mode') === 'dark' ? 'light' : 'dark';
      trySet('sf_mode', cur);
      applyMode(cur);
      markActive();
    });
  });

  /* ---------- 跟随系统变化 ---------- */
  if (window.matchMedia) {
    var mq = window.matchMedia('(prefers-color-scheme: dark)');
    var onSys = function () { var m = tryGet('sf_mode'); if (!m || m === 'auto') { applyMode('auto'); markActive(); } };
    if (mq.addEventListener) { mq.addEventListener('change', onSys); }
    else if (mq.addListener) { mq.addListener(onSys); }
  }
  markActive();

  /* ---------- 左侧栏：展开/收起 + 移动端抽屉 ---------- */
  $all('.sf-side-trigger').forEach(function (b) {
    b.addEventListener('click', function () {
      if (isMobile()) { body.classList.toggle('sf-side-open'); }
      else { body.classList.toggle('sf-collapsed'); }
    });
  });
  $all('.sf-burger-trigger').forEach(function (b) {
    b.addEventListener('click', function () { body.classList.toggle('sf-side-open'); });
  });
  // 点击 scrim 关闭抽屉
  var scrim = $('.sf-scrim');
  if (scrim) { scrim.addEventListener('click', function () { body.classList.remove('sf-side-open'); }); }
  // 跨断点切换时复位两个状态
  if (mqMobile && mqMobile.addEventListener) {
    mqMobile.addEventListener('change', function () {
      body.classList.remove('sf-side-open');
      if (!mqMobile.matches) { /* 桌面端重置 collapsed 保留用户偏好，这里不动 */ }
      else { body.classList.remove('sf-collapsed'); }
    });
  }

  /* ---------- 导航项：标记当前激活 ---------- */
  (function markNavActive() {
    var items = $all('.sf-nav__item');
    if (!items.length) return;
    var path = window.location.pathname;
    items.forEach(function (a) {
      var href = a.getAttribute('href') || '';
      var h = href.replace(/\.\.\//g, '').replace(/^\.\//, '');
      if (h && (h === path || (path.indexOf(h) === 0 && h !== '/'))) {
        a.classList.add('is-active');
      }
      a.addEventListener('click', function () {
        items.forEach(function (x) { x.classList.remove('is-active'); });
        a.classList.add('is-active');
        if (isMobile()) { body.classList.remove('sf-side-open'); }
      });
    });
  })();

  /* ---------- 搜索引擎 ---------- */
  var form = $('#search-form');
  var input = $('#search-input');
  var engines = $all('input[name="sou"]');
  var engineLabels = $all('.sf-engine');

  function currentEngine() {
    var checked = $('input[name="sou"]:checked');
    if (!checked && engines[0]) { checked = engines[0]; checked.checked = true; }
    return checked;
  }
  function syncEngineUI() {
    engineLabels.forEach(function (lab) {
      var r = lab.querySelector('input[name="sou"]');
      if (r && r.checked) { lab.classList.add('is-on'); } else { lab.classList.remove('is-on'); }
    });
    var e = currentEngine();
    if (e && input) { input.setAttribute('placeholder', e.getAttribute('data-hint') || '搜索一下'); }
  }
  function restoreEngine() {
    var saved = tryGet('theme_sou');
    if (saved) {
      engines.forEach(function (r) { if (r.value === saved) { r.checked = true; } });
    }
    syncEngineUI();
  }
  engines.forEach(function (r) {
    r.addEventListener('change', function () {
      syncEngineUI();
      trySet('theme_sou', r.value);
    });
  });
  if (form && input) {
    form.addEventListener('submit', function (ev) {
      ev.preventDefault();
      var q = input.value.trim();
      var e = currentEngine();
      if (!q || !e) return;
      window.open(e.value + encodeURIComponent(q), '_blank');
      hideSuggest();
    });
  }
  restoreEngine();

  /* ---------- 联想词（百度 JSONP，安全降级） ---------- */
  var sugBox = null, reqId = 0, sugTimer = null, cursor = -1, items = [];
  function ensureBox() {
    if (sugBox) return sugBox;
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
      if (myId !== reqId) return;
      var arr = (data && data.s) ? data.s : [];
      items = arr.slice(0, 10);
      renderSuggest(items);
    };
    script.onerror = function () { /* 静默降级 */ };
    doc.body.appendChild(script);
    setTimeout(function () { if (script.parentNode) { script.parentNode.removeChild(script); } }, 1600);
  }
  if (input) {
    input.addEventListener('input', function () {
      var kw = input.value.trim();
      if (sugTimer) clearTimeout(sugTimer);
      if (kw.length < 1) { hideSuggest(); return; }
      sugTimer = setTimeout(function () { fetchSuggest(kw); }, 250);
    });
    input.addEventListener('keydown', function (ev) {
      if (!sugBox || sugBox.style.display === 'none') return;
      var els = $all('.sf-suggest__item', sugBox);
      if (ev.keyCode === 38) {
        ev.preventDefault();
        cursor = (cursor <= 0) ? els.length - 1 : cursor - 1;
        setCursor(els);
      } else if (ev.keyCode === 40) {
        ev.preventDefault();
        cursor = (cursor >= els.length - 1) ? 0 : cursor + 1;
        setCursor(els);
      } else if (ev.keyCode === 13 && cursor >= 0) {
        ev.preventDefault();
        input.value = els[cursor].textContent;
        submitCurrent();
      } else if (ev.keyCode === 27) {
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
    if (!clock) return;
    var d = new Date();
    var wd = ['日','一','二','三','四','五','六'][d.getDay()];
    var tEl = $('.sf-clock__time', clock);
    var dEl = $('.sf-clock__date', clock);
    if (tEl) tEl.textContent = pad(d.getHours()) + ':' + pad(d.getMinutes()) + ':' + pad(d.getSeconds());
    if (dEl) dEl.textContent = d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()) + ' 周' + wd;
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