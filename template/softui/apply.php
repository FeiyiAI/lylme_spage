<?php
require_once __DIR__ . '/functions.php';
global $conf, $site, $DB;
$apply_status = isset($conf['apply']) ? (int) $conf['apply'] : 0;
$apply_gg     = isset($conf['apply_gg']) ? (string) $conf['apply_gg'] : '';
$apply_groups = $site->getGroups();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>申请收录 - <?php echo theme_e($conf['title']); ?></title>
  <link rel="icon" href="<?php echo theme_e($conf['logo']); ?>">
  <meta name="theme-color" content="#ecf0f3">
  <?php theme_css('css/style.css'); ?>
  <?php require __DIR__ . '/init.php'; ?>
  <style>
    /* 申请收录页专属：加载层（覆盖全局），保持原 apply.js 兼容 */
    #loading {
      position: fixed; left: 0; top: 0;
      width: 100vw; height: 100vh;
      z-index: 999;
      display: none;
      align-items: center; justify-content: center;
      background: light-dark(rgba(236,240,243,.78), rgba(26,28,31,.78));
      backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);
      color: var(--sf-body); font-size: 14px;
    }
    #loading > img { height: 18px; width: 18px; margin-right: 6px; }
  </style>
</head>
<body data-mode-def="<?php echo theme_e(theme_config('default_mode', 'auto')); ?>">
  <?php require __DIR__ . '/side.php'; ?>

  <div id="loading"><img src="../assets/admin/loading.gif" alt="">&nbsp;正在获取…</div>

  <main class="sf-main">
    <div class="sf-page__wrap">
      <h1 class="sf-page__title">申请收录</h1>
      <p class="sf-page__sub">提交您的网站信息，经审核后展示在本站</p>

      <?php if ($apply_status == 2): ?>
      <div class="sf-page__card">
        <h2 class="sf-page__title" style="font-size:20px;">网站已关闭收录</h2>
        <div class="sf-prose"><?php echo $apply_gg; ?></div>
        <p style="margin-top:18px;"><a class="sf-btn sf-btn--ghost" href="/">返回首页</a></p>
      </div>
      <?php else: ?>
      <div class="sf-page__card">
        <?php if (!empty($apply_gg)): ?>
        <div class="sf-notice"><?php echo $apply_gg; ?></div>
        <?php endif; ?>

        <form class="sf-form" id="apply-form" onsubmit="return false;">
          <div class="sf-field">
            <label>* 网站地址</label>
            <div class="sf-row">
              <input class="sf-input" type="text" name="url" id="apply-url" placeholder="完整链接或域名" required>
              <button type="button" class="sf-btn sf-btn--ghost" onclick="get_url()">自动获取</button>
            </div>
          </div>

          <div class="sf-field">
            <label>* 选择分组</label>
            <select class="sf-select" name="group_id" required>
              <option value="">请选择</option>
              <?php while ($g = $DB->fetch($apply_groups)): ?>
              <option value="<?php echo (int) $g['group_id']; ?>"><?php echo theme_e($g['group_name']); ?></option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="sf-field">
            <label>* 网站名称</label>
            <input class="sf-input" type="text" name="name" id="title" placeholder="网站名称" required>
          </div>

          <div class="sf-field">
            <label>网站图标（URL）</label>
            <div class="sf-row">
              <input class="sf-input" type="text" name="icon" id="icon" placeholder="填写图标的 URL 地址">
              <button type="button" class="sf-btn sf-btn--ghost" onclick="$('#file').click();">选择</button>
              <input type="file" id="file" onchange="uploadimg()" accept="image/png,image/jpeg,image/gif,image/x-icon" style="display:none;">
            </div>
            <img id="review" class="sf-review" src="" alt="" style="display:none;">
          </div>

          <div class="sf-field">
            <label>* 验证码</label>
            <div class="sf-row">
              <input class="sf-input sf-input--code" type="text" name="authcode" placeholder="验证码" required>
              <img id="captcha_img" class="sf-captcha" src="../include/validatecode.php" title="点击刷新" onclick="recode()">
            </div>
          </div>

          <button type="button" class="sf-btn" onclick="submit()">提交申请</button>
          <p style="text-align:center; margin:0;"><a class="sf-btn sf-btn--ghost" href="/">返回首页</a></p>
        </form>
      </div>
      <?php endif; ?>
    </div>
  </main>

  <?php echo $conf['wztj']; ?>
  <script src="../assets/js/jquery.min.js"></script>
  <script src="../assets/js/layer.js"></script>
  <script src="../assets/js/sweetalert.min.js"></script>
  <script src="./apply.js"></script>
  <script src="<?php echo $cdnpublic; ?>/assets/js/icon.js"></script>
  <script src="<?php echo $cdnpublic; ?>/assets/js/svg.js"></script>
  <?php theme_js('js/script.js'); ?>
</body>
</html>