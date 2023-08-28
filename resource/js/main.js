/**
 * @link https://github.com/LAV45/yii2-ajax-create
 * @copyright Copyright (c) 2015 LAV45!
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

(function ($) {
    'use strict';

    let Modal,
        modalBody,
        reload_container_id,
        settings;

    $.ajaxCreate = function (options) {
        settings = $.extend(settings, options);

        Modal = $(settings.modal.container);
        modalBody = Modal.find('.modal-body');

        Modal.on('beforeSubmit', 'form', eventSubmit);
        $(document).on('click', '[data-href]', eventClick);
    };

    function renderModal(content) {
        if (content.length) {
            modalBody.html(content);
            Modal.modal('show');
        }
        return content.length !== 0;
    }

    function getContainer(e) {
        let container = $(e).closest('.pjax-box');
        if (container === undefined) {
            container = $('.pjax-box:eq(0)');
        }
        return container.attr('id');
    }

    function reloadContainer() {
        if (reload_container_id !== undefined) {
            $.pjax.reload('#' + reload_container_id, settings.pjax.options);
        }
    }

    function eventClick(e) {
        e.preventDefault();
        reload_container_id = getContainer(e.target);

        Modal.modal('hide');

        $.ajax({
            url: $(this).data('href'),
            success: function (content) {
                renderModal(content) || reloadContainer();
            },
            error: function (message) {
                renderModal(message.responseText)
            }
        });
        return false;
    }

    function eventSubmit(e) {
        e.preventDefault();
        const form = $(this);
        form.ajaxSubmit({
            success: function (errors) {
                if (errors?.length) {
                    form.yiiActiveForm('updateMessages', errors, true);
                    form.trigger('hasError');
                } else {
                    Modal.modal('hide');
                    reloadContainer();
                }
            },
            error: function (jqXHR) {
                renderModal(jqXHR.responseText);
            }
        });
        return false;
    }
})(jQuery);
