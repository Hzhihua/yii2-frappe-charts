<?php
/**
 * @Author: Hzhihua
 * @Time: 17-12-12 上午11:14
 * @Email: cnzhihua@gmail.com
 */

namespace hzhihua\frappe;

use Yii;
use yii\web\View;
use yii\web\JsExpression;
use yii\base\Widget;
use yii\base\InvalidConfigException;

/**
 * 安装：
 * ```php
 * composer require hzhihua/yii2-frappe-charts
 * ```
 * 
 * 生成图形统计图，在视图模板中调用：
 * ```php
 * <? \hzhihua\frappe\Charts::widget([
 *     'view' => 'yii\web\View', // or $this, your customize view class/object, it must be extends [[yii\web\View]], default Yii::$app->getView()
 *     'instance_var_name' => 'chart', // var chart = new Chart({});
 *     'instance_var_name_keyword' => 'var', // var chart = new Chart({});
 *     'asset' => 'hzhihua\\frappe\\ChartsAsset', // asset source class
 *     'selector' => '#chart',
 *     'config' => [
 *         'height' => 250,
 *         'title' => "My Chart",
 *         'type' => 'bar', // 'axis-mixed' or 'bar', 'line', 'pie', 'percentage'
 *         'colors' => ['#7cd6fd', '#743ee2'],
 *         'format_tooltip_x' => new \yii\web\JsExpression('d => (d + \'\').toUpperCase()'), // 将生成原生js代码 d => (d + '').toUpperCase()
 *         'data' => [
 *             'labels' => ["12am-3am", "3am-6pm", "6am-9am", "9am-12am", "12pm-3pm", "3pm-6pm", "6pm-9pm", "9am-12am"
 *             ],
 *             'datasets' => [
 *                 [
 *                     'name' => "Some Data",
 *                     'axisPosition' => 'right',
 *                     'values' => [25, 40, 30, 35, 8, 52, 17, -4],
 *                 ],
 *                 [
 *                     'name' => "Another Set",
 *                     'chartType' => 'line',
 *                     'values' => [25, 50, -10, 15, 18, 32, 27, 14],
 *                 ],
 *             ],
 *         ],
 *     ],
 *     'other' => new \yii\web\JsExpression('console.log(chart)'),
 *     // @see https://frappe.github.io/charts/
 *     // @see \hzhihua\frappe\Charts for detail configuration info
 * ]);?>
 * ```
 *
 * @package hzhihua\frappe
 * @property \yii\web\View $view
 * @see rappe-charts https://github.com/frappe/charts
 * @see rappe-charts-demo https://frappe.github.io/charts/
 * @see github https://github.com/Hzhihua/rappe-charts
 */
class Charts extends Widget
{
    /**
     * js选择器
     * ```js
     * #charts,
     * div.charts
     * ```
     * @var string
     */
    public $selector = '';

    /**
     * 保存实例化对象的变量的名称
     * 
     * ```js
     *  var chart = new Chart({});
     * ```
     * @var string
     */
    public $instance_var_name = 'chart';

    /**
     * 实例化对象的变量的名称的关键字
     * 
     * ```js
     *  var chart = new Chart({});
     * ```
     * @var string
     */
    public $instance_var_name_keyword = 'var';

    /**
     * 配置参数
     * @var array
     */
    public $config = [];

    /**
     * 额外的函数调用
     * ```php
     * 'other' => new \yii\web\JsExpression('
     *     chart.show_sums(); // 显示总和
     *     chart.show_averages(); // 显示平均值
     * '),
     * ```
     *
     * ```js
     * chart.show_sums();  // 求总和 and `hide_sums()`
     * chart.show_averages();  // 求平均值 and `hide_averages()`
     * ```
     * @var string
     */
    public $other = '';

    /**
     * 静态资源类
     * @var string
     */
    public $asset = 'hzhihua\\frappe\\ChartsAsset';

    /**
     * 视图模板对象
     * @var null
     */
    public $view = null;

    /**
     * 初始化变量
     */
    public function init()
    {
        parent::init();

        $this->view = $this->getView();

        $config = $this->getConfig();
        if (!isset($config['data']))
            throw new Exception('Chart\'s data can not be empty, it must be configure in "config"');
    }

    /**
     * 入口
     */
    public function run()
    {
        parent::run();

        $js = $this->getJs();
        $this->registerAsset()->registerJs($js);
    }

    /**
     * 获取js
     * @return string
     */
    protected function getJs()
    {
        $selector = $this->selector;
        $instance_var_name = $this->instance_var_name;
        $instance_var_name_keyword = $this->instance_var_name_keyword;
        $name = "{$instance_var_name_keyword} {$instance_var_name}";
        
        $other = $this->getOther();
        $config = $this->array2json($this->getConfig()); // include data

        if ($other) {
            $js = sprintf(";%s = new Chart('%s',%s);%s;", $name, $selector, $config, $other);
        } else {
            $js = sprintf(";%s = new Chart('%s',%s);", $name, $selector, $config);
        }

        return $js;
    }

    /**
     * 注册静态资源
     * @return $this
     */
    protected function registerAsset()
    {
        $asset = $this->asset;
        $asset::register($this->view);
        return $this;
    }

    /**
     * 注册js代码
     * @param $js
     * @return $this
     */
    protected function registerJs($js)
    {
        $this->view->registerJs($js);
        return $this;
    }

    /**
     * 获取模板视图对象
     * @return object|View
     * @throws ErrorException
     */
    public function getView()
    {
        if (null !== $this->view) {
            if ($this->view instanceof View){
                return $this->view;
            } else if (is_object($this->view)) {
                throw new InvalidConfigException('object '.get_class($this->view).' must be extends yii\web\View');
            } else if (
                is_string($this->view) &&
                'yii\web\View' === ltrim($this->view, '\\')
            ) {
                return parent::getView();
            }

            return Yii::createObject($this->view);
        }
        return parent::getView();
    }

    /**
     * 获取配置参数
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * 获取配置参数other的数据
     * @return string
     */
    public function getOther()
    {
        return $this->other;
    }

    /**
     * php数组转json格式
     *
     * ```php
     * [
     *      'type' => 'bar',
     *      'colors' => ['#7cd6fd', '#743ee2'],
     *      'format_tooltip_x' => new \yii\web\JsExpression('d => (d + \'\').toUpperCase()'),
     * ]
     * ```
     *
     * Result:
     * ```json
     * {"type":"bar","colors"::["#7cd6fd","#743ee2"],"format_tooltip_x":d => (d + '').toUpperCase()}
     * ```
     * @param array $data
     * @return string
     */
    protected function array2json(array $data)
    {
        $jsExpression = [];
        $searchExpression = [];
        foreach ($data as $key => $value) {
            if ($value instanceof JsExpression) {
                $jsExpression = $value->expression;
                $searchExpression = "{\"expression\":\"{$value->expression}\"}";
            }
        }
        return str_replace($searchExpression, $jsExpression, json_encode($data));
    }

}