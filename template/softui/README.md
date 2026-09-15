# SoftUI 主题

> 新拟物风格（Neumorphism）导航主题 · 自定义配色 · 亮/暗模式自适应

- **主题名称**：SoftUI
- **主题介绍**：新拟物风格，自定义配色，亮暗模式自适应
- **主题作者**：FeiyiAI
- **主题链接**：https://www.liuqinmging.com/works/softui
- **适用版本**：六零导航页 lylme_spage >= 1.2.5
- **演示地址**：https://www.liuqinmging.com/works/softui

## 视觉设定

参考 https://next.liuqingming.com 的「柔和 / 明亮 / 质感 / 暗黑 / 跟随系统」多主题思路，
以**新拟物（Soft UI / Neumorphism）**为唯一设计语言：

- 元素与背景同色，依靠「左上高光 + 右下暗影」的双层柔和阴影营造立体凸起；
  按下（`:active` / 选中）时翻转为内凹阴影，形成可触摸的按压反馈。
- 视觉来源为纯 CSS 变量 + 同色系阴影叠加，不依赖任何外部字体或图片 CDN，可离线运行。

## 配色方案（后台可设默认，页面可实时切换）

| 方案 key | 名称 | 灵感 |
|----------|------|------|
| `mint` | 雨过天青 | 青瓷绿 |
| `lavender` | 碧空淡紫 | 薰衣草 |
| `peach` | 灼灼其华 | 蜜桃粉 |
| `sky` | 碧空如洗 | 晴空蓝 |
| `graphite` | 质感灰 | 石墨 |

页面右下角齿轮 → 「配色方案」实时切换，选择记忆在 `localStorage`。

## 明暗模式

- 提供 **跟随系统 / 亮色 / 暗色** 三态，右下角太阳/月亮按钮可快速切换亮暗；
- 两种模式均保持新拟物光影一致性（暗色下阴影加深、高光转弱）；
- 选择记忆在 `localStorage`，后台「默认明暗模式」控制首次访问（未手动切换过）的状态。

## 布局原型

英雄居中式：顶部新拟物控制栏（品牌 + 时钟 + 模式/设置按钮）→ 中部搜索胶囊（内凹）+
引擎平铺选择 + 导航标签 → 下方分组以凸起面板承载自适应链接瓦片（凸起→按下内凹）。
移动端 768px 断点：时钟隐藏、引擎横向滚动、瓦片列宽收窄、设置面板铺满。

## 配置项（后台 → 主题设置）

- `home_title` 首页标题（支持 HTML）
- `default_scheme` 默认配色方案
- `default_mode` 默认明暗模式（auto/light/dark）
- `lytoday` / `lytodaycode` 今日热榜
- `gonganbei` 公安备案号

## 已实现的官方契约

- `theme.ini`（六字段齐全，版本三段式）
- `functions.php`：`theme_version / theme_css / theme_js / theme_e / theme_tags / theme_sou / theme_icp / theme_security_filing`
- `lists()` 接口（g1/g2/g3/l1/l2/l3 占位符）
- 搜索 DOM 锚点：`#search-form` / `#search-input` / `name="sou"`（`data-alias`/`data-hint`，首个 `checked`）
- 保留 `$conf['wztj']` 统计代码与 `icon.js` / `svg.js` 图标雪碧图引入
- 图标尺寸已对承载 `svg` 与 `img` 同时约束

## 自检清单

- [x] `index.php` / `theme.ini` 存在，目录名 `softui` 与 `theme_name` 对应
- [x] PHP 兼容 5.4+（统一 `array()`，无 `??`/箭头/`[]`）
- [x] 文本输出经 `theme_e()`（`{link_name}` / `sou_icon` 除外）
- [x] JS 为 ES5（无 `let/const/箭头/模板字符串`）
- [x] 768px 断点下排版正常、不溢出
- [x] 未复用 dev-theme 禁止清单中的 class 名；CSS 变量为自创 `sf-` 体系
