<?php
/**
 * @link https://github.com/LAV45/yii2-target-behavior
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
 * @property $modal Modal
 */
class AjaxCreate extends Widget
{
    /**
     * @var array
     */
    public $optionsPjax = [
        'options' => []
    ];
    /**
     * @var array
     */
    public $optionsModal = [
        'closeButton' => false
    ];
    /**
     * @var Modal
     */
    private static $_modal;

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
        $this->getView()->registerJs(<<<JS
            (function($){
                var Modal = $('#{$this->modal->id}');
                var modalBody = Modal.find('.modal-body');
                var reload_container_id;

                $.fn.modal.Constructor.prototype.enforceFocus = function(){};

                function renderModal(content, action){
                    if (content.length) {
                        modalBody.html(content);
                        Modal.modal(action);
                    }
                    return content.length !== 0;
                }

                $(document).on('click', '[data-href]', function() {
                    reload_container_id = $(this).closest('.pjax-box').attr('id');

                    $.ajax({
                        url: $(this).data('href'),
                        success: function(content) {
                            renderModal(content, 'show') && $.pjax.reload('#'+reload_container_id);
                        },
                        error: function(message) {
                            renderModal(message.responseText, 'show')
                        }
                    });
                });

                Modal.on('beforeSubmit', 'form', function() {
                    var form = $(this),
                        data = form.data('yiiActiveForm');

                    $.ajax({
                        url: form.attr('action'),
                        type: form.attr('method'),
                        data: form.serialize(),
                        dataType: data.settings.ajaxDataType,
                        success: function() {
                            Modal.modal('hide');
                            $.pjax.reload('#'+reload_container_id);
                        },
                        error: function(message) {
                            renderModal(message.responseText, 'show');
                        }
                    });
                    return false;
                });
            })(jQuery);
JS
        );
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