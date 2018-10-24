<?php
/**
 * @link https://github.com/LAV45/yii2-ajax-create
 * @copyright Copyright (c) 2015 LAV45!
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace lav45\widget;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\Pjax;
use yii\bootstrap4\Modal;

/**
 * Class AjaxCreate
 * @package lav45\widget
 * @author Alexey Loban <lav451@gmail.com>
 *
 * AjaxCreate::begin();
 *
 * echo Html::button('<span class="glyphicon glyphicon-plus"></span>', [
 *      'data-href' => Url::toRoute(['create']),
 *      'class' => 'btn btn-success',
 * ])
 *
 * AjaxCreate::end();
 *
 * @property Modal $modal
 */
class AjaxCreate extends Widget
{
    /**
     * @see Pjax
     * @var array
     */
    public $optionsPjax = [
        'options' => []
    ];
    /**
     * @see Modal
     * @var array
     */
    public $optionsModal = [
        'closeButton' => false
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
        $this->registerAsset();
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

    protected function registerAsset()
    {
        AjaxCreateAsset::register($this->getView());
    }

    public function registerScript()
    {
        $options = Json::htmlEncode([
            'pjax' => [
                'options' => ArrayHelper::getValue($this->optionsPjax, 'clientOptions', [])
            ],
            'modal' => [
                'container' => '#' . $this->modal->id
            ],
        ]);

        $this->getView()->registerJs("$.ajaxCreate({$options});");
    }

    public function getModal()
    {
        if (null !== self::$_modal) {
            return self::$_modal;
        }
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

        return self::$_modal;
    }
}
