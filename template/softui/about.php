<?php require_once __DIR__ . '/functions.php'; ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>关于 - <?php echo theme_e($conf['title']); ?></title>
  <link rel="icon" href="<?php echo theme_e($conf['logo']); ?>">
  <meta name="theme-color" content="#ecf0f3">
  <?php theme_css('css/style.css'); ?>
  <?php require __DIR__ . '/init.php'; ?>
</head>
<body data-mode-def="<?php echo theme_e(theme_config('default_mode', 'auto')); ?>">
  <?php require __DIR__ . '/side.php'; ?>

  <main class="sf-main">
    <div class="sf-page__wrap">
      <h1 class="sf-page__title">关于本站</h1>
      <p class="sf-page__sub"><?php echo theme_e($conf['title']); ?></p>

      <div class="sf-page__card sf-prose">
        <?php echo isset($conf['about_content']) ? (string) $conf['about_content'] : ''; ?>
      </div>

      <p style="text-align:center; margin-top:24px; color:var(--sf-body); font-size:13px;">
        <a class="sf-btn sf-btn--ghost" href="/">返回首页</a>
      </p>
    </div>
  </main>

  <?php echo $conf['wztj']; ?>
  <script src="<?php echo $cdnpublic; ?>/assets/js/icon.js"></script>
  <script src="<?php echo $cdnpublic; ?>/assets/js/svg.js"></script>
  <?php theme_js('js/script.js'); ?>
</body>
</html>