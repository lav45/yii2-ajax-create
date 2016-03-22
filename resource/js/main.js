/**
 * @link https://github.com/LAV45/yii2-ajax-create
 * @copyright Copyright (c) 2015 LAV45!
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

(function ($) {

    var Modal,
        modalBody,
        reload_container_id;

    /**
     * Fix focus element in to the modal window
     */
    $.fn.modal.Constructor.prototype.enforceFocus = function () {};

    $.ajaxCreate = function (selector) {
        Modal = $(selector);
        modalBody = Modal.find('.modal-body');

        Modal.on('beforeSubmit', 'form', eventSubmit);
        $(document).on('click', '[data-href]', eventClick);
    };

    function renderModal(content, action) {
        if (content.length) {
            modalBody.html(content);
            Modal.modal(action);
        }
        return content.length !== 0;
    }

    function getContainer(e) {
        var container = $(e).closest('.pjax-box');
        if (container === undefined) {
            container = $('.pjax-box');
        }
        return container.attr('id');
    }

    function eventClick(e) {
        var container_id = getContainer(e.target);
        if (container_id !== undefined) {
            reload_container_id = container_id;
        }

        Modal.modal('hide');

        $.ajax({
            url: $(this).data('href'),
            success: function (content) {
                renderModal(content, 'show') || $.pjax.reload('#' + reload_container_id);
            },
            error: function (message) {
                renderModal(message.responseText, 'show')
            }
        });
    }

    function eventSubmit() {
        var form = $(this);
        form.ajaxSubmit({
            success: function (errors) {
                if (errors.length == 0) {
                    Modal.modal('hide');
                    $.pjax.reload('#' + reload_container_id);
                } else {
                    form.yiiActiveForm('updateMessages', errors, true)
                }
            },
            error: function (jqXHR) {
                renderModal(jqXHR.responseText, 'show');
            }
        });
        return false;
    }
})(jQuery);