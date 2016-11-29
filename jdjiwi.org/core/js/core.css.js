

//include(core.js);
var css = core.css = new (function() {
    var css = this;


    css.hideShow = function(id) {
        if(t.isHide(id)) {
            css.show(id);
            return true;
        } else {
            css.hide(id);
            return false;
        }
    };

    css.isHide = function(id) {
        return $(id).hasClass('cHide');
    };
    css.hide = function(id) {
        $(id).addClass('cHide');
    //        $(id).addClass('cHide').hide();
    };
    css.show = function(id) {
        $(id).removeClass('cHide');
    //        $(id).removeClass('cHide').show();
    };

});
