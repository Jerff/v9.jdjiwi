
//include(jquery-1.5.1.min.js);
//include(function.js);
var cmf = new(function() {
	var t = this;

	/* point */
	t.getId = function(id) {
		return document.getElementById(id);
	};
    t.getValue = function(id) {
        return t.getId(id) ? t.getId(id).value : false;
    };
    t.setValue = function(id, value) {
        if(t.getId(id)) t.getId(id).value = value;
    };
    t.isChecked = function(id) {
        if(t.getId(id)) return t.getId(id).checked;
        else return false;
    };


    /* cookie */
    t.getCookie = function(name) {
        var cookie = " "+ document.cookie;
        var search = " "+ name +"=";
        var setStr = null;
        var offset = 0;
        var end = 0;
        if (cookie.length > 0) {
            offset = cookie.indexOf(search);
            if (offset != -1) {
                offset += search.length;
                end = cookie.indexOf(";", offset);
                if (end == -1) {
                    end = cookie.length;
                }
                setStr = unescape(cookie.substring(offset, end));
            }
        }
        return setStr;
    };


    /* изменение документа при загрузке */
    t.readyList = new Array();
    t.ready = function(func) {
        t.readyList.push(func);
    };
    t.preloadDocument = function() {
    };
    t.loadDocument = function() {
        var fn, i = 0;
        while ( (fn = t.readyList[ i++ ]) ) {
            fn.call(window);
        }
    };


    /* изменение адреса */
    t.redirect = function(url) {
        document.location.href = url;
    };
    t.reload = function(url) {
        document.location.reload();
    };


    /* image */
    t.image = new(function() {
        var image = this;
        image.list = new Array();
        image.i = 0;

        image.add = function(url) {
            image.list[image.i] = new Image();
            image.list[image.i++].src = url;
        }

    });

});


/* сборник функций текущей странциы */
cmf.pages = new(function() {
});