
//include(jquery-1.8.3.min.js);
//include(function.js);
var core = new(function() {
    var core = this;

    /* отладка */
    core.debug = new(function() {
        var debug = this;

        debug.pre = function() {
            if(typeof(console)!=='undefined' && typeof(console.log)!=='undefined') {
                console.log(arguments);
            }
        };
    });


    /* loader */
    core.loader = new(function() {
        var loader = this;

        // ajax загрузка при отправке формы
        loader.form = new(function(){
            var form = this;

            form.show = function() {
                if(typeof(form.item)==='undefined') {

                }
            };

            form.hide = function() {
                if(typeof(form.item)!=='undefined') {

                }
            };
        });


        loader.show = function() {
            if(typeof(loader.item)==='undefined') {
                loader.item = $('<div class="core-loading"><div></div></div>').appendTo('body');
                loader.noclick = $('<div class="core-noclick"><div></div></div>').appendTo('body');
            }
        };

        loader.hide = function() {
            if(typeof(loader.item)!=='undefined') {
                loader.item.remove();
                loader.noclick.remove();
                loader.item = undefined;
            }
        };

        loader.overlay = new(function() {
            var overlay = this;

            overlay.show = function() {
                if(typeof(overlay.item)==='undefined') {
                    overlay.item = $('<div class="core-overlay"></div>').appendTo('body');
                }
            };

            overlay.hide = function() {
                if(typeof(overlay.item)!=='undefined') {
                    overlay.item.remove();
                    overlay.item = undefined;
                }
            };
        });
    });


    /* loader */
    core.message = new(function() {
        var message = this;

        /* сообщение об ошибки */
        message.error = function(message) {
            $.pnotify({
                title: message.title,
                text: message.text,
                type: 'error'
            });
        };

        /* сообщение об ошибки */
        message.success = function(message) {
            $.pnotify({
                title: message.title,
                text: message.text,
                type: 'success'
            });
        };
    });


    // ready
    core.ready = function(func) {
        $(document).ready(func);
    };

    /* изменение адреса */
    core.redirect = function(url) {
        document.location.href = url;
    };
    core.reload = function(url) {
        document.location.reload();
    };

});

var pre = core.debug.pre;