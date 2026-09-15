<?php
require_once __DIR__ . '/functions.php';

// ---- 逻辑区 ----
$home_title   = theme_config('home_title', '');
$accent_def   = theme_config('default_accent', 'red');
$mode_def     = theme_config('default_mode', 'auto');
$sou_list     = theme_sou();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title><?php echo theme_e($conf['title']); ?></title>
  <meta name="keywords" content="<?php echo theme_e($conf['keywords']); ?>">
  <meta name="description" content="<?php echo theme_e($conf['description']); ?>">
  <meta name="author" content="SoftUI · FeiyiAI">
  <link rel="icon" href="<?php echo theme_e($conf['logo']); ?>">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="theme-color" content="#ecf0f3">
  <?php theme_css('css/style.css'); ?>
  <script>
  (function () {
    var defAcc = <?php echo json_encode($accent_def); ?>;
    var defMode = <?php echo json_encode($mode_def); ?>;
    var storedAcc = null, storedMode = null;
    try { storedAcc = localStorage.getItem('sf_accent'); } catch (e) {}
    try { storedMode = localStorage.getItem('sf_mode'); } catch (e) {}
    var acc = storedAcc || defAcc || 'red';
    var mode = storedMode || defMode || 'auto';
    if (mode === 'auto') {
      mode = (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light';
    }
    document.documentElement.setAttribute('data-accent', acc);
    document.documentElement.setAttribute('data-mode', mode);
  })();
  </script>
</head>

<body data-mode-def="<?php echo theme_e($mode_def); ?>">
  <?php require __DIR__ . '/side.php'; ?>

  <main class="sf-main">
    <section class="sf-hero">
      <?php if (!empty($home_title)): ?>
        <h1 class="sf-hero__title"><?php echo $home_title; ?></h1>
      <?php endif; ?>

      <!-- 搜索引擎：在搜索框上方 -->
      <div class="sf-engines" id="sf-engines">
        <?php foreach ($sou_list as $i => $sou): ?>
        <label class="sf-engine<?php echo $i === 0 ? ' is-on' : ''; ?>" style="--engine-color:<?php echo theme_e($sou['color']); ?>;">
          <input type="radio" name="sou" value="<?php echo theme_e($sou['link']); ?>"
                 data-alias="<?php echo theme_e($sou['alias']); ?>" data-hint="<?php echo theme_e($sou['hint']); ?>"
                 <?php echo $i === 0 ? 'checked' : ''; ?>>
          <span class="sf-engine__ico"><?php echo $sou['icon']; ?></span>
          <span class="sf-engine__name"><?php echo theme_e($sou['name']); ?></span>
        </label>
        <?php endforeach; ?>
      </div>

      <!-- 搜索框：全宽 -->
      <form id="search-form" class="sf-search" action="#" autocomplete="off">
        <span class="sf-search__icon">
          <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><circle cx="11" cy="11" r="7" fill="none" stroke="currentColor" stroke-width="2"/><line x1="16" y1="16" x2="21" y2="21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        </span>
        <input type="text" id="search-input" placeholder="搜索一下" name="q" autocomplete="off" spellcheck="false">
        <button class="sf-search__submit" type="submit" aria-label="搜索">
          <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
      </form>

      <?php if ($conf['yan'] == 'true'): ?>
        <p class="sf-yan"><?php echo yan(); ?></p>
      <?php endif; ?>
    </section>

    <section class="sf-groups">
      <?php
      $html = array(
        'g1' => '<section class="sf-group">',
        'g2' => '<header class="sf-group__head"><span class="sf-group__icon">{group_icon}</span><h2 class="sf-group__title">{group_name}</h2></header><div class="sf-links">',
        'g3' => '</div></section>',
        'l1' => '<a class="sf-tile" href="{link_url}" target="_blank" rel="nofollow noopener" title="{link_name_text}">',
        'l2' => '<span class="sf-tile__icon">{link_icon}</span><span class="sf-tile__name">{link_name}</span>',
        'l3' => '</a>',
      );
      lists($html);
      ?>
    </section>

    <footer class="sf-footer">
      <span class="sf-copyright"><?php echo $conf['copyright']; ?></span>
      <?php theme_icp(); ?>
      <?php theme_security_filing(); ?>
    </footer>
  </main>

  <?php echo $conf['wztj']; ?>
  <script src="<?php echo $cdnpublic; ?>/assets/js/icon.js"></script>
  <script src="<?php echo $cdnpublic; ?>/assets/js/svg.js"></script>
  <?php theme_js('js/script.js'); ?>
</body>
</html>