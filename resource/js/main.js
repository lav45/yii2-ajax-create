/**
 * @link https://github.com/LAV45/yii2-ajax-create
 * @copyright Copyright (c) 2015 LAV45!
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

(function($){

    var Modal,
        modalBody,
        reload_container_id;

    /**
     * Fix focus element in to the modal window
     */
    $.fn.modal.Constructor.prototype.enforceFocus = function(){};

    $.ajaxCreate = function(selector) {
        Modal = $(selector);
        modalBody = Modal.find('.modal-body');

        Modal.on('beforeSubmit', 'form', beforeSubmit);
    };

    function renderModal(content, action){
        if (content.length) {
            modalBody.html(content);
            Modal.modal(action);
        }
        return content.length !== 0;
    }

    function getContainer(e)
    {
        var container = $(e).closest('.pjax-box');
        if (container === undefined) {
            container = $('.pjax-box');
        }
        return container.attr('id');
    }

    $(document).on('click', '[data-href]', function() {
        reload_container_id = getContainer(this);

        $.ajax({
            url: $(this).data('href'),
            success: function(content) {
                renderModal(content, 'show') || $.pjax.reload('#'+reload_container_id);
            },
            error: function(message) {
                renderModal(message.responseText, 'show')
            }
        });
    });

    function beforeSubmit() {
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
    }
})(jQuery);