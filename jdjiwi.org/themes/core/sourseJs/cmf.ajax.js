

//include(cmf-0.1.js);
cmf.ajax = new (function() {
	var t = this;


	var url = '';
	t.setUrl = function(n) {
		t.url = n;
	};
	t.getUrl = function() {
		return t.url;
	};


	/* ajax */
	t.sendForm = function(url, form) {
		return t.send(url, {form: form});
	};
	t.sendCache = function() {
		return t.send(arguments[0], arguments[1], true);
	};
	t.send = function() {
	    return t.drive.start(arguments[0], arguments[1], arguments[2]);
	};


	/* показать лог */
	t.log = function(text) {
		if(text) {
			if($.browser.msie && $.browser.version=='6.0') {
			} else {
				$('#idAjaxLog').show();
				$('#idAjaxLog').html(text);
			}
		}
	};


	/* выполнение ajax команды */
	t.command = function(url, header) {
		$('#idAjaxCommand2').html('<iframe src="'+ url +'" width="100%" height="1000px" scrolling="yes" frameborder="0"></iframe>');
		$('#idAjaxCommand1').show();
		if(header) alert('"'+ header + '" Запущено!');
	};


	/* показать, скрыть лоадер */
	t.show = function() {
		$("#isAjaxLoad").show();
	};
	t.hide = function() {
		$("#isAjaxLoad").hide();
	};

});