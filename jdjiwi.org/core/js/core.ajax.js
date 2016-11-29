

//include(core.js);
core.ajax = new (function() {
    var ajax = this;

    ajax.settings = {};

    ajax.init = function(settings) {
        if(settings.hash !== undefined) {
            ajax.settings.hash = settings.hash;
        }
    };



    /* loader */
    ajax.loader = new(function() {
        var loader = this;

        loader.show = function() {
            core.loader.show();
            core.loader.overlay.show();
        };

        loader.hide = function() {
            core.loader.hide();
            core.loader.overlay.hide();
        };
    });



    /* ajax */
    ajax.sendForm = function(url, form) {
        return ajax.send(url, $(form).serialize());
    };
    ajax.send = function(url, data, func) {
        ajax.loader.show();
        data.isAjax = true;
        $.post(url, data, function(data) {
            if(data) {
//                try {
                    if(typeof(data.debug)!=='undefined' && data.debug.is) {
                        ajax.log.show(data.debug.log);
                    }
                    if(typeof(data.error)!=='undefined') {
                        core.message.error(data.error);
                    } else if(typeof(data.ok)!=='undefined') {
                        if(typeof(data.result)!=='undefined') {
                            if(typeof(data.result.html)!=='undefined') {
                                $(data.result.html).each(function() {
                                    $(this.selected).html(this.content);
                                });
                            }
                            if(typeof(data.result.script)!=='undefined') {
                                $.globalEval(data.result.script);
                            }
                            core.init();
                        }
                    }
                    if(typeof(func)==='function') {
                        func(data);
                    }
//                } catch(e) {
//                    core.message.error(core.config.error.script);
//                };
            }
            ajax.loader.hide();
        }, 'json').error(function() {
            core.message.error(core.config.error.connect);
            ajax.loader.hide();
        });
        return false;
    };



    /* показать лог */
    ajax.log = new(function() {
        var log = this;

        log.show = function(message) {
            if(!log.item) {
                if($('#coreDebugLog').length) {
                    log.item = $('<div id="coreAjaxLog"></div>').insertBefore('#coreDebugLog');
                } else {
                    log.item = $('<div id="coreAjaxLog"></div>').appendTo('body');
                }
            }
            log.item.html(message);
        };

    });


//    /* выполнение ajax команды */
//    ajax.command = function(url, header) {
//
//        $('#idAjaxCommand2').html('<iframe src="'+ url +'" width="100%" height="1000px" scrolling="yes" frameborder="0"></iframe>');
//        $('#idAjaxCommand1').show();
//        if(header) alert('"'+ header + '" Запущено!');
//    };

});