

//include(cmf-0.1.js);
var style = cmf.style = new (function() {
	var t = this;


	t.hideShow = function(id) {
		if(t.isHide(id)) {
			t.show(id);
			return true;
		} else {
			t.hide(id);
			return false;
		}
	};

	t.isHide = function(id) {
		return $(id).hasClass('cmfHide');
	};
	t.hide = function(id) {
		$(id).addClass('cmfHide').hide();
	};
	t.show = function(id) {
		$(id).removeClass('cmfHide').show();
	};

	t.checkboxLabel = function(el) {
        $('input.styled', $(el).parent()).click();
	};

});
