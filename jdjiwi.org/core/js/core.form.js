

//include(core.js);
cForm = core.form = new(function() {
    var form = this;



    /* init */
    form.init = function() {
        pre('form.init');
        $('input,textarea').each(function() {
            var el = $(this);
            if(el.data('default') && !el.data('init-default')) {
                el.data('init-default', true);
                el.blur(function () {
                    if(''==el.val()) {
                        el.val(el.data('default'))
                        .addClass('formInputDefault')
                        .removeClass('formInputText');
                    }
                })
                .focus(function () {
                    if(el.data('default')==el.val()) {
                        el.val('')
                        .addClass('formInputText')
                        .removeClass('formInputDefault');
                    }
                });
                if(el.data('default')==el.val()) {
                    el.addClass('formInputDefault')
                    .removeClass('formInputText');
                }
            }
        });

        form.element.init();
    };


    /* element */
    form.element = new(function() {
        var element = this;

        // поддержка элементов
        element.notSupported = function() {
            var i = document.createElement("input");
            i.setAttribute("type", "date");
            return i.type == "text";
        };

        element.init = function() {
            if (element.notSupported('range')) {
                element.range.init();
            }
        };

        /* range */
        element.range = new(function() {
            range = this;

            range.init = function() {
                $('input[type="range"]').spinner();
            };
        });
    });


    /* view */
    form.view = new(function() {

        });



    /* error */
    form.error = new(function() {
        var error = this;

        error.alert = function(title, text) {
            core.message.error({
                title: title,
                text: text
            });
        };

        error.hide = function(id, errorId) {
            if($('#'+ errorId).length) {
                $('#'+ errorId).hide();
            } else {
                if($('#'+ id).length) {
                    $('.formElementError', $('#'+ id).parent()).remove();
                }
            }
        };


        error.show = function(id, errorId, color, message) {
            if($('#'+ errorId).length) {
                $('#'+ errorId).html(message).show();
            } else {
                if($('#'+ id).length) {
                    var parent = $('#'+ id).parent();
                    if($('.formElementError', parent).length) {
                        $('.formElementError', parent).html(message);
                    } else {
                        $('<div class="formElementError">'+ message +'</div>').prependTo(parent).show();
                    }
                }
            }
            error.color.show(id, color);
        };

        error.color = new(function() {
            var color = this;

            color.set = function(id, conf) {
                $('#'+ id).data('color-blur', conf.blur)
                .data('color-focus', conf.focus);
            };

            color.show = function(id, color) {
                var el = $('#'+ id);
                if(!el.data('color-default')) {
                    el.data('color-default', el.css('background-color'));
                    var func = function() {
                        if(el.data('color-blur')) {
                            el.data('color-blur')();
                        } else {
                            el.css('background-color', el.data('color-default'));
                        }
                    };
                    el.blur(func).focus(func);
                }
                if(el.data('color-focus')) {
                    el.data('color-focus')();
                } else {
                    el.css('background-color', color);

                }
            };
        });

    });

});