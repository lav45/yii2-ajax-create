<?php
/**
 * @link https://github.com/LAV45/yii2-ajax-create
 * @copyright Copyright (c) 2015 LAV45!
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace lav45\widget;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;

/**
 * Class AjaxCreate
 * @package lav45\widget
 * @author Alexey Loban <lav451@gmail.com>
 *
 * AjaxCreate::begin();
 *
 * echo $form->field($model, 'category_id')->dropDownList([ ... ]);
 *
 * echo Html::button('<span class="glyphicon glyphicon-plus"></span>', [
 *     'class' => 'btn btn-success',
 *     'data-href' => Url::toRoute(['category/create']),
 * ])
 *
 * AjaxCreate::end();
 *
 * @property Modal $modal
 */
class AjaxCreate extends Widget
{
    /**
     * @var array
     */
    public $optionsPjax = [
        'linkSelector' => false,
        'formSelector' => false,
    ];
    /**
     * @var array
     */
    public $optionsModal = [
        'header' => '',
    ];
    /**
     * @var Modal
     */
    protected static $_modal;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->registerScript();

        Html::addCssClass($this->optionsPjax['options'], 'pjax-box');
        Pjax::begin($this->optionsPjax);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        Pjax::end();
    }

    public function registerScript()
    {
        AjaxCreateAsset::register($this->getView());
        $this->getView()->registerJs("$.ajaxCreate('#{$this->modal->id}');");
    }

    public function getModal()
    {
        if (self::$_modal === null) {
            ob_start();
            ob_implicit_flush(false);

            $this->optionsModal['class'] = Modal::className();
            self::$_modal = Yii::createObject($this->optionsModal);
            $out = self::$_modal->run();
            $out = ob_get_clean() . $out;
            $view = $this->getView();

            $view->on($view::EVENT_END_BODY, function () use ($out) {
                echo $out;
            });
        }
        return self::$_modal;
    }
}