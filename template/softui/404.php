<?php require_once __DIR__ . '/functions.php'; ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>页面不存在 - <?php echo theme_e($conf['title']); ?></title>
  <link rel="icon" href="<?php echo theme_e($conf['logo']); ?>">
  <meta name="theme-color" content="#ecf0f3">
  <?php theme_css('css/style.css'); ?>
  <?php require __DIR__ . '/init.php'; ?>
</head>
<body data-mode-def="<?php echo theme_e(theme_config('default_mode', 'auto')); ?>">
  <?php require __DIR__ . '/side.php'; ?>

  <main class="sf-main">
    <div class="sf-page__wrap">
      <div class="sf-page__card sf-404">
        <div class="sf-404__code">404</div>
        <p class="sf-404__msg">抱歉，您访问的页面不存在或链接已失效</p>
        <a class="sf-btn" href="/">返回首页</a>
      </div>
    </div>
  </main>

  <?php echo $conf['wztj']; ?>
  <script src="<?php echo $cdnpublic; ?>/assets/js/icon.js"></script>
  <script src="<?php echo $cdnpublic; ?>/assets/js/svg.js"></script>
  <?php theme_js('js/script.js'); ?>
</body>
</html>