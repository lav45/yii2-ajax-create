<?php
/**
 * @link https://github.com/LAV45/yii2-ajax-create
 * @copyright Copyright (c) 2015 LAV45!
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace lav45\widget;

use yii\web\AssetBundle;

/**
 * Class AjaxFormAsset
 * @package lav45\widget
 */
class AjaxFormAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery-form/dist';

    public $js = [
        'jquery.form.min.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}