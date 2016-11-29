
//include(core.js);
$(document).ready(function(){
    core.init();
});
core.init = function() {
    cForm.init();

    window.alert = function(message) {
        $.pnotify({
            title: 'Alert',
            text: message
        });
    };
};