<?php
/**
 * SoftUI 主题自定义配置表单
 * 调用：theme_config('参数名称', '默认值')
 * 类型：text/textarea/select/checkbox/radio/color，开关建议用 radio 替代 switch
 */

$theme_config = array(
    array(
        'type' => 'textarea',
        'name' => 'home_title',
        'title' => '首页标题',
        'description' => '主区域大标题，<code>支持HTML</code>，留空不显示',
        'value' => $GLOBALS['conf']['home-title'],
    ),
    array(
        'type' => 'radio',
        'name' => 'default_scheme',
        'title' => '默认配色方案',
        'description' => '首次访问（未手动切换过）时使用的配色，用户可在页面右下角实时切换',
        'value' => 'mint',
        'enum' => array(
            'mint'     => '雨过天青（青瓷绿）',
            'lavender' => '碧空淡紫（薰衣草）',
            'peach'    => '灼灼其华（蜜桃粉）',
            'sky'      => '碧空如洗（晴空蓝）',
            'graphite' => '质感灰（石墨）',
        ),
    ),
    array(
        'type' => 'radio',
        'name' => 'default_mode',
        'title' => '默认明暗模式',
        'description' => 'auto 表示跟随系统配色',
        'value' => 'auto',
        'enum' => array(
            'auto'  => '跟随系统',
            'light' => '亮色',
            'dark'  => '暗色',
        ),
    ),
    array(
        'type' => 'select',
        'name' => 'lytoday',
        'title' => '今日热榜',
        'description' => 'LyToday-JS 插件显示位置',
        'value' => 0,
        'enum' => array(
            0 => '关闭',
            1 => '搜索栏下方',
            2 => '底部',
        ),
    ),
    array(
        'type' => 'textarea',
        'name' => 'lytodaycode',
        'title' => '今日热榜代码',
        'description' => 'LyToday-JS 插件自定义代码，若不了解请勿修改',
        'value' => '<div id="lytoday"></div><script src="https://lytoday.lylme.com/"></script>',
    ),
    array(
        'type' => 'text',
        'name' => 'gonganbei',
        'title' => '公安备案号',
        'description' => '公安备案号，留空不显示',
        'value' => '',
        'placeholder' => '京公安网备xxxxxxxxxx号',
    ),
);
