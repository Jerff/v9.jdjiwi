

//include(core.ajax.js);
core.ajax.controller = new (function() {
    var controller = this;

    controller.scroll = function(controller, scroll) {
        if($('#'+ scroll).length) {
            $.scrollTo($('#'+ scroll));
        } else {
            $.scrollTo($('#'+ controller));
        }
    };

    controller.error = function(title, text) {
        core.message.error({
            title: title,
            text: text
        });
    };

    controller.success = function(title, text) {
        core.message.success({
            title: title,
            text: text
        });
    };

});
