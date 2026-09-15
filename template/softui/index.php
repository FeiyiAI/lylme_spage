<?php
require_once __DIR__ . '/functions.php';

// ---- 逻辑区：条件渲染集中处理 ----
$home_title   = theme_config('home_title');
$lytoday      = theme_config('lytoday', 0);
$lytodaycode  = theme_config('lytodaycode');
$scheme_def   = theme_config('default_scheme', 'mint');
$mode_def     = theme_config('default_mode', 'auto');
$background_url = background();

$sou_list = theme_sou();
$tag_list = theme_tags();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title><?php echo theme_e($conf['title']); ?></title>
    <meta name="keywords" content="<?php echo theme_e($conf['keywords']); ?>">
    <meta name="description" content="<?php echo theme_e($conf['description']); ?>">
    <meta name="author" content="SoftUI">
    <link rel="icon" href="<?php echo theme_e($conf['logo']); ?>">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#e3ece8">
    <?php theme_css('css/style.css'); ?>
    <script>
    (function () {
        var defScheme = '<?php echo theme_e($scheme_def); ?>';
        var defMode = '<?php echo theme_e($mode_def); ?>';
        var scheme = localStorage.getItem('sf_scheme') || defScheme;
        var mode = localStorage.getItem('sf_mode') || defMode;
        if (mode === 'auto') {
            mode = (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light';
        }
        var r = document.documentElement;
        r.setAttribute('data-scheme', scheme);
        r.setAttribute('data-mode', mode);
    })();
    </script>
    <script src="<?php echo $cdnpublic; ?>/assets/js/jquery.min.js" type="application/javascript"></script>
</head>

<body data-mode-def="<?php echo theme_e($mode_def); ?>">

    <?php if (!empty($background_url)): ?>
    <div class="sf-bg" style="background-image:url('<?php echo theme_e($background_url); ?>');"></div>
    <?php endif; ?>

    <!-- 顶部控制栏 -->
    <header class="sf-topbar">
        <a class="sf-brand" href="/" title="<?php echo theme_e($conf['title']); ?>">
            <img class="sf-brand__logo" src="<?php echo theme_e($conf['logo']); ?>" alt="logo">
            <span class="sf-brand__name"><?php echo theme_e($conf['title']); ?></span>
        </a>
        <div class="sf-clock" id="sf-clock">
            <span class="sf-clock__time">--:--</span>
            <span class="sf-clock__date">----</span>
        </div>
        <div class="sf-topbar__actions">
            <button class="sf-round" id="sf-mode-toggle" type="button" title="切换亮/暗模式" aria-label="切换亮暗模式">
                <svg class="sf-ico-sun" viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><circle cx="12" cy="12" r="4.2" fill="none" stroke="currentColor" stroke-width="2"/><g stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="2.5" x2="12" y2="5"/><line x1="12" y1="19" x2="12" y2="21.5"/><line x1="2.5" y1="12" x2="5" y2="12"/><line x1="19" y1="12" x2="21.5" y2="12"/><line x1="4.9" y1="4.9" x2="6.7" y2="6.7"/><line x1="17.3" y1="17.3" x2="19.1" y2="19.1"/><line x1="4.9" y1="19.1" x2="6.7" y2="17.3"/><line x1="17.3" y1="6.7" x2="19.1" y2="4.9"/></g></svg>
                <svg class="sf-ico-moon" viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path d="M20 14.5A8 8 0 1 1 9.5 4 6.3 6.3 0 0 0 20 14.5Z" fill="currentColor"/></svg>
            </button>
            <button class="sf-round" id="sf-gear" type="button" title="主题设置" aria-label="主题设置">
                <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path d="M12 8.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm9.4 4.1c0-.4.1-.8.1-1.2s0-.8-.1-1.2l2-1.6-2-3.4-2.4 1a7.3 7.3 0 0 0-2-1.2l-.4-2.5H10.5l-.4 2.5a7.3 7.3 0 0 0-2 1.2l-2.4-1-2 3.4 2 1.6c-.1.4-.1.8-.1 1.2s0 .8.1 1.2l-2 1.6 2 3.4 2.4-1a7.3 7.3 0 0 0 2 1.2l.4 2.5h3.8l.4-2.5a7.3 7.3 0 0 0 2-1.2l2.4 1 2-3.4-2-1.6Z" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>
            </button>
        </div>
    </header>

    <!-- 英雄区 + 搜索 -->
    <main class="sf-hero">
        <?php if (!empty($home_title)): ?>
            <h1 class="sf-hero__title"><?php echo $home_title; ?></h1>
        <?php endif; ?>

        <form id="search-form" class="sf-search" action="#" autocomplete="off">
            <span class="sf-search__icon">
                <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><circle cx="11" cy="11" r="7" fill="none" stroke="currentColor" stroke-width="2"/><line x1="16" y1="16" x2="21" y2="21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            </span>
            <input type="text" id="search-input" placeholder="搜索一下" name="q" autocomplete="off" spellcheck="false">
            <button class="sf-search__submit" type="submit" aria-label="搜索">
                <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><circle cx="11" cy="11" r="7" fill="none" stroke="currentColor" stroke-width="2"/><line x1="16" y1="16" x2="21" y2="21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            </button>
        </form>

        <div class="sf-engines" id="sf-engines">
            <?php foreach ($sou_list as $i => $sou): ?>
            <label class="sf-engine" style="--engine-color:<?php echo theme_e($sou['color']); ?>;">
                <input type="radio" name="sou" value="<?php echo theme_e($sou['link']); ?>"
                       data-alias="<?php echo theme_e($sou['alias']); ?>" data-hint="<?php echo theme_e($sou['hint']); ?>"
                       <?php echo $i === 0 ? 'checked' : ''; ?>>
                <span class="sf-engine__ico"><?php echo $sou['icon']; ?></span>
                <span class="sf-engine__name"><?php echo theme_e($sou['name']); ?></span>
            </label>
            <?php endforeach; ?>
        </div>

        <?php if ($lytoday == 1): echo $lytodaycode; endif; ?>

        <!-- 导航菜单（标签） -->
        <?php if (!empty($tag_list)): ?>
        <nav class="sf-tags" aria-label="导航菜单">
            <?php foreach ($tag_list as $tag): ?>
            <a class="sf-tag" href="<?php echo theme_e($tag['link']); ?>" <?php echo $tag['blank'] ? 'target="_blank"' : ''; ?>>
                <?php echo theme_e($tag['name']); ?>
            </a>
            <?php endforeach; ?>
        </nav>
        <?php endif; ?>

        <?php if ($conf['yan'] == 'true'): ?>
            <p class="sf-yan"><?php echo yan(); ?></p>
        <?php endif; ?>
    </main>

    <!-- 链接分组 -->
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

    <?php if ($lytoday == 2): echo $lytodaycode; endif; ?>

    <footer class="sf-footer">
        <span class="sf-copyright"><?php echo $conf['copyright']; ?></span>
        <?php theme_icp(); ?>
        <?php theme_security_filing(); ?>
    </footer>

    <!-- 主题设置面板 -->
    <div class="sf-panel" id="sf-panel" aria-hidden="true">
        <div class="sf-panel__head">主题设置</div>
        <div class="sf-panel__section">
            <div class="sf-panel__label">配色方案</div>
            <div class="sf-swatches">
                <button class="sf-swatch" data-scheme="mint"     style="--sw:#2f9e8f" title="雨过天青"></button>
                <button class="sf-swatch" data-scheme="lavender" style="--sw:#6c63ff" title="碧空淡紫"></button>
                <button class="sf-swatch" data-scheme="peach"    style="--sw:#e2685f" title="灼灼其华"></button>
                <button class="sf-swatch" data-scheme="sky"      style="--sw:#2f7fed" title="碧空如洗"></button>
                <button class="sf-swatch" data-scheme="graphite" style="--sw:#5b6470" title="质感灰"></button>
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

    <?php echo $conf['wztj']; ?>
    <script src="<?php echo $cdnpublic; ?>/assets/js/icon.js"></script>
    <script src="<?php echo $cdnpublic; ?>/assets/js/svg.js"></script>
    <?php theme_js('js/script.js'); ?>
</body>
</html>
