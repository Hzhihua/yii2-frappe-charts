<?php
/**
 * @Author: Hzhihua
 * @Time: 17-12-12 下午12:08
 * @Email: cnzhihua@gmail.com
 */
use hzhihua\frappe\Charts;
?>

<div id="chart"></div>

<?= Charts::widget([
    'config' => [
        'parent' => '#chart',
        'height' => 250,
        'title' => "My Chart",
        'type' => 'line',
        'colors' => ['#7cd6fd', '#743ee2'],
        'format_tooltip_x' => '<%d => (d + \'\').toUpperCase()%>', // 将生成原生js代码 d => (d + '').toUpperCase()
    ],
    'data' => [
        'labels' => ["12am-3am", "3am-6pm", "6am-9am", "9am-12am", "12pm-3pm", "3pm-6pm", "6pm-9pm", "9am-12am"
        ],
        'datasets' => [
            [
                'title' => "Some Data",
                'values' => [25, 40, 30, 35, 8, 52, 17, -4],
            ],
            [
                'title' => "Another Set",
                'values' => [25, 50, -10, 15, 18, 32, 27, 14],
            ],
        ],
    ],
    'other' => <<<'JS'
chart.show_sums(); // 显示总和
chart.show_averages(); // 显示平均值
JS
]);?>
