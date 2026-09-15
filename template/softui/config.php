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
        'value' => isset($GLOBALS['conf']['home-title']) ? (string) $GLOBALS['conf']['home-title'] : '',
    ),
    array(
        'type' => 'radio',
        'name' => 'default_accent',
        'title' => '默认配色方案',
        'description' => '首次访问（未手动切换过）时使用的配色，用户可在页面右下角齿轮实时切换',
        'value' => 'red',
        'enum' => array(
            'red'    => '灼灼其华（朱砂红）',
            'teal'   => '雨过天青（青松绿）',
            'indigo' => '碧空如洗（晴空蓝）',
            'clay'   => '落日熔金（暮色橙）',
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