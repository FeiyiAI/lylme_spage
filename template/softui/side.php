<?php
if (!defined('SOFTUI_SIDE')) { define('SOFTUI_SIDE', 1); }
global $conf;

$sf_bg       = function_exists('background') ? background() : '';
$sf_logo     = isset($conf['logo']) ? (string) $conf['logo'] : '';
$sf_title    = isset($conf['title']) ? (string) $conf['title'] : '';
$sf_sou      = function_exists('theme_sou') ? theme_sou() : array();
$sf_tags     = function_exists('theme_tags') ? theme_tags() : array();

function softui_first_char($name) {
  $name = strip_tags((string) $name);
  if ($name === '') { return '·'; }
  if (function_exists('mb_substr')) { return mb_substr($name, 0, 1, 'UTF-8'); }
  return substr($name, 0, 1);
}
?>
<?php if (!empty($sf_bg)): ?>
<div class="sf-bg" style="background-image:url('<?php echo theme_e($sf_bg); ?>');"></div>
<?php endif; ?>

<!-- 移动端顶栏 -->
<header class="sf-topbar">
  <button class="sf-round sf-burger-trigger" type="button" title="菜单" aria-label="菜单">
    <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/></svg>
  </button>
  <div class="sf-side__brand">
    <?php if (!empty($sf_logo)): ?><img src="<?php echo theme_e($sf_logo); ?>" alt="logo"><?php endif; ?>
    <span class="sf-side__name"><?php echo theme_e($sf_title); ?></span>
  </div>
  <button class="sf-round sf-mode-trigger" type="button" title="切换亮/暗模式" aria-label="切换亮暗模式">
    <svg class="sf-ico-sun" viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><circle cx="12" cy="12" r="4.2" fill="none" stroke="currentColor" stroke-width="2"/><g stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="2.5" x2="12" y2="5"/><line x1="12" y1="19" x2="12" y2="21.5"/><line x1="2.5" y1="12" x2="5" y2="12"/><line x1="19" y1="12" x2="21.5" y2="12"/><line x1="4.9" y1="4.9" x2="6.7" y2="6.7"/><line x1="17.3" y1="17.3" x2="19.1" y2="19.1"/><line x1="4.9" y1="19.1" x2="6.7" y2="17.3"/><line x1="17.3" y1="6.7" x2="19.1" y2="4.9"/></g></svg>
    <svg class="sf-ico-moon" viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path d="M20 14.5A8 8 0 1 1 9.5 4 6.3 6.3 0 0 0 20 14.5Z" fill="currentColor"/></svg>
  </button>
  <button class="sf-round sf-gear-trigger" type="button" title="主题设置" aria-label="主题设置">
    <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path d="M12 8.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm9.4 4.1c0-.4.1-.8.1-1.2s0-.8-.1-1.2l2-1.6-2-3.4-2.4 1a7.3 7.3 0 0 0-2-1.2l-.4-2.5H10.5l-.4 2.5a7.3 7.3 0 0 0-2 1.2l-2.4-1-2 3.4 2 1.6c-.1.4-.1.8-.1 1.2s0 .8.1 1.2l-2 1.6 2 3.4 2.4-1a7.3 7.3 0 0 0 2 1.2l.4 2.5h3.8l.4-2.5a7.3 7.3 0 0 0 2-1.2l2.4 1 2-3.4-2-1.6Z" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>
  </button>
</header>

<!-- 侧栏 -->
<aside class="sf-side" aria-label="导航">
  <div class="sf-side__brand">
    <button class="sf-collapse sf-round sf-side-trigger" type="button" title="收起/展开侧栏" aria-label="收起或展开侧栏">
      <svg class="sf-ico-collapse" viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path d="M15 6l-6 6 6 6" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
      <svg class="sf-ico-expand"  viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </button>
    <?php if (!empty($sf_logo)): ?><img src="<?php echo theme_e($sf_logo); ?>" alt="logo"><?php endif; ?>
    <span class="sf-side__name"><?php echo theme_e($sf_title); ?></span>
  </div>

  <nav class="sf-nav" aria-label="导航菜单">
    <?php foreach ($sf_tags as $tg): ?>
    <a class="sf-nav__item" href="<?php echo theme_e($tg['link']); ?>"<?php echo !empty($tg['blank']) ? ' target="_blank" rel="noopener"' : ''; ?>>
      <span class="sf-nav__glyph"><?php echo theme_e(softui_first_char($tg['name'])); ?></span>
      <span class="sf-nav__label"><?php echo theme_e(strip_tags((string) $tg['name'])); ?></span>
    </a>
    <?php endforeach; ?>
  </nav>

  <div class="sf-side__foot">
    <div class="sf-side__clock" id="sf-clock">
      <div class="sf-clock__time">--:--:--</div>
      <div class="sf-clock__date">----</div>
    </div>
    <div class="sf-side__tools">
      <button class="sf-round sf-mode-trigger" type="button" title="切换亮/暗模式" aria-label="切换亮暗模式">
        <svg class="sf-ico-sun" viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><circle cx="12" cy="12" r="4.2" fill="none" stroke="currentColor" stroke-width="2"/><g stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="2.5" x2="12" y2="5"/><line x1="12" y1="19" x2="12" y2="21.5"/><line x1="2.5" y1="12" x2="5" y2="12"/><line x1="19" y1="12" x2="21.5" y2="12"/><line x1="4.9" y1="4.9" x2="6.7" y2="6.7"/><line x1="17.3" y1="17.3" x2="19.1" y2="19.1"/><line x1="4.9" y1="19.1" x2="6.7" y2="17.3"/><line x1="17.3" y1="6.7" x2="19.1" y2="4.9"/></g></svg>
        <svg class="sf-ico-moon" viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path d="M20 14.5A8 8 0 1 1 9.5 4 6.3 6.3 0 0 0 20 14.5Z" fill="currentColor"/></svg>
      </button>
      <button class="sf-round sf-gear-trigger" type="button" title="主题设置" aria-label="主题设置">
        <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path d="M12 8.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm9.4 4.1c0-.4.1-.8.1-1.2s0-.8-.1-1.2l2-1.6-2-3.4-2.4 1a7.3 7.3 0 0 0-2-1.2l-.4-2.5H10.5l-.4 2.5a7.3 7.3 0 0 0-2 1.2l-2.4-1-2 3.4 2 1.6c-.1.4-.1.8-.1 1.2s0 .8.1 1.2l-2 1.6 2 3.4 2.4-1a7.3 7.3 0 0 0 2 1.2l.4 2.5h3.8l.4-2.5a7.3 7.3 0 0 0 2-1.2l2.4 1 2-3.4-2-1.6Z" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>
      </button>
    </div>
  </div>
</aside>

<div class="sf-scrim" aria-hidden="true"></div>

<!-- 主题设置面板 -->
<div class="sf-panel" id="sf-panel" aria-hidden="true">
  <div class="sf-panel__head">主题设置</div>
  <div class="sf-panel__section">
    <div class="sf-panel__label">配色方案</div>
    <div class="sf-swatches">
      <button class="sf-swatch" data-accent="red"    style="--sw:#ff014f" title="灼灼其华"></button>
      <button class="sf-swatch" data-accent="teal"   style="--sw:#2a9d9a" title="雨过天青"></button>
      <button class="sf-swatch" data-accent="indigo" style="--sw:#5b6cff" title="碧空如洗"></button>
      <button class="sf-swatch" data-accent="clay"   style="--sw:#d97742" title="落日熔金"></button>
    </div>
  </div>
  <div class="sf-panel__section">
    <div class="sf-panel__label">明暗模式</div>
    <div class="sf-modes">
      <button class="sf-mode-btn" data-mode="auto">跟随系统</button>
      <button class="sf-mode-btn" data-mode="light">亮色</button>
      <button class="sf-mode-btn" data-mode="dark">暗色</button>
    </div>
  </div>
</div>

<!-- 返回顶部 -->
<button class="sf-top" id="sf-top" type="button" title="返回顶部" aria-label="返回顶部">
  <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path d="M12 19V6M5 12l7-7 7 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
</button>