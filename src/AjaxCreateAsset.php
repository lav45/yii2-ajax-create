<?php
/**
 * @link https://github.com/LAV45/yii2-ajax-create
 * @copyright Copyright (c) 2015 LAV45!
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace lav45\widget;

use yii\web\AssetBundle;

/**
 * Class AjaxCreateAsset
 * @package lav45\widget
 */
class AjaxCreateAsset extends AssetBundle
{
    public $sourcePath = '@vendor/lav45/yii2-ajax-create/resource';

    public $js = [
        'js/main.js'
    ];

    public $depends = [
        'yii\widgets\PjaxAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
        'lav45\widget\AjaxFormAsset',
    ];
}