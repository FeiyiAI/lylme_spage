<?php require_once __DIR__ . '/functions.php'; ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title><?php echo theme_e(strip_tags((string) $url_name)); ?> - <?php echo theme_e($conf['title']); ?></title>
  <meta name="keywords" content="<?php echo theme_e((string) $url_keywords); ?>">
  <meta name="description" content="<?php echo theme_e((string) $url_description); ?>">
  <link rel="icon" href="<?php echo theme_e($conf['logo']); ?>">
  <meta name="theme-color" content="#ecf0f3">
  <?php theme_css('css/style.css'); ?>
  <?php require __DIR__ . '/init.php'; ?>
</head>
<body data-mode-def="<?php echo theme_e(theme_config('default_mode', 'auto')); ?>">
  <?php require __DIR__ . '/side.php'; ?>

  <main class="sf-main">
    <div class="sf-page__wrap">
      <h1 class="sf-page__title"><?php echo theme_e(strip_tags((string) $url_name)); ?></h1>
      <p class="sf-page__sub">所属分组：<?php echo theme_e(strip_tags((string) $group_name)); ?></p>

      <div class="sf-page__card">
        <div class="sf-detail">
          <div class="sf-detail__icon"><?php echo $url_icon; ?></div>
          <div>
            <div class="sf-detail__name"><?php echo theme_e(strip_tags((string) $url_name)); ?></div>
            <div class="sf-detail__group"><?php echo theme_e(strip_tags((string) $group_name)); ?></div>
          </div>
        </div>

        <div class="sf-detail__meta">
          <b>链接地址：</b><a class="sf-detail__url" href="<?php echo theme_e($url_herf); ?>" target="_blank" rel="nofollow noopener"><?php echo theme_e($url_herf); ?></a>
        </div>

        <?php if (!empty($url_title)): ?>
        <div class="sf-detail__meta"><b>网站标题：</b><?php echo theme_e($url_title); ?></div>
        <?php endif; ?>

        <?php if (!empty($url_description)): ?>
        <p class="sf-detail__desc"><?php echo theme_e($url_description); ?></p>
        <?php endif; ?>

        <?php
        $sf_kw_arr = array();
        if (!empty($url_keywords)) {
          $sf_kw_arr = preg_split('/[\s,，、]+/u', (string) $url_keywords, -1, PREG_SPLIT_NO_EMPTY);
        }
        ?>
        <?php if (!empty($sf_kw_arr)): ?>
        <div class="sf-detail__kw">
          <?php foreach ($sf_kw_arr as $kw):
            $kw = trim(strip_tags((string) $kw));
            if ($kw === '' || $kw === '无') continue; ?>
            <span><?php echo theme_e($kw); ?></span>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="sf-detail__actions">
          <a class="sf-btn" href="<?php echo theme_e($url_herf); ?>" target="_blank" rel="nofollow noopener">访问网站</a>
          <a class="sf-btn sf-btn--ghost" href="/">返回首页</a>
        </div>
      </div>
    </div>
  </main>

  <?php echo $conf['wztj']; ?>
  <script src="<?php echo $cdnpublic; ?>/assets/js/icon.js"></script>
  <script src="<?php echo $cdnpublic; ?>/assets/js/svg.js"></script>
  <?php theme_js('js/script.js'); ?>
</body>
</html>