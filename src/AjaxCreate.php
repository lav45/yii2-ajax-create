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
use yii\helpers\Json;
use yii\widgets\Pjax;

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
 */
class AjaxCreate extends Widget
{
    /** @var array */
    public $optionsPjax = [
        'options' => []
    ];
    /**@var array */
    private $optionsModal = [
        'class' => '\yii\bootstrap5\Modal',
        'closeButton' => false
    ];

    protected static $modal;

    public function setOptionsModal(array $options)
    {
        $this->optionsModal = array_merge($this->optionsModal, $options);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->registerScript();
        $this->registerAsset();

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
                'options' => isset($this->optionsPjax['clientOptions']) ? $this->optionsPjax['clientOptions'] : []
            ],
            'modal' => [
                'container' => '#' . $this->getModal()->id
            ],
        ]);

        $this->getView()->registerJs("$.ajaxCreate({$options});");
    }

    public function getModal()
    {
        if (null !== self::$modal) {
            return self::$modal;
        }
        ob_start();
        ob_implicit_flush(false);

        self::$modal = Yii::createObject($this->optionsModal);
        $out = self::$modal->run();
        $out = ob_get_clean() . $out;
        $view = $this->getView();

        $view->on($view::EVENT_END_BODY, function () use ($out) {
            echo $out;
        });

        return self::$modal;
    }
}
