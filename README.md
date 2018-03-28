# Yii2 frappe-charts widget
[frappe-charts](https://github.com/frappe/charts) [frappe-charts-demo](https://frappe.github.io/charts/)
## 预览图
![](https://raw.githubusercontent.com/frappe/charts/master/.github/example.gif)

## 安装
```cmd
composer require hzhihua/yii2-frappe-charts
```

## 使用
在模板中添加html标签
```html
<div id="chart"></div>
```
调用 Charts widget
```php

<? \hzhihua\frappe\Charts::widget([
    //'view' => 'yii\web\View', // or $this, your customize view class/object, it must be extends [[yii\web\View]], default Yii::$app->getView()
    //'instance_var_name' => 'chart', // var chart = new Chart({});
    //'instance_var_name_keyword' => 'var', // var chart = new Chart({});
    //'asset' => 'hzhihua\\frappe\\ChartsAsset', // asset source class
    
    'selector' => '#chart',
    'config' => [
        'height' => 250,
        'title' => "My Chart",
        'type' => 'bar', // 'axis-mixed' or 'bar', 'line', 'pie', 'percentage'
        'colors' => ['#7cd6fd', '#743ee2'],
        'format_tooltip_x' => new \yii\web\JsExpression('d => (d + \'\').toUpperCase()'), // 将生成原生js代码 d => (d + '').toUpperCase()
        'data' => [
            'labels' => ["12am-3am", "3am-6pm", "6am-9am", "9am-12am", "12pm-3pm", "3pm-6pm", "6pm-9pm", "9am-12am"
            ],
            'datasets' => [
                [
                    'name' => "Some Data",
                    'axisPosition' => 'right',
                    'values' => [25, 40, 30, 35, 8, 52, 17, -4],
                ],
                [
                    'name' => "Another Set",
                    'chartType' => 'line',
                    'values' => [25, 50, -10, 15, 18, 32, 27, 14],
                ],
            ],
        ],
    ],
    'other' => new \yii\web\JsExpression('console.log(chart)'),
    // @see https://frappe.github.io/charts/
    // @see \hzhihua\frappe\Charts for detail configuration info
]);?>
```
