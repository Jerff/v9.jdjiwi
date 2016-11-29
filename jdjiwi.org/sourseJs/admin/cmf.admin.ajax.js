//проверка редиректа для админской части
cmf.redirect = function(url) {
	hash = /(.*)?(\#.*)/.exec(url);
	old = /(.*)?(\#.*)/.exec(document.location.href);
	if(!hash) document.location.href = url;
	if(hash[1]!=old[1]) {
		document.location.href = url;
	}
	if(document.location.hash==hash[2]) {
        cmf.ajax.run(url);
	} else {
		document.location.hash = hash[2]
	}
}

// отправить запрос по адресу в определенную функцию с заданными данными
cmf.ajax.run = function() {
	var url = arguments[0];
	var send = arguments[1];
	if(typeof send !== 'object') {
		send = {};
	}
	send.ajaxUrl = this.reformAjaxUrl(url);
	return cmf.ajax.send(this.getUrl(), send);
}

// ajax
cmf.ajax.openUrl = function(url) {
	this.run(url);
}
cmf.ajax.startUrl = function(url) {
	this.run(url, {ajaxCommand:'start'});
}
cmf.ajax.reformAjaxUrl = function(url) {
	return url + '';
}

// меню
cmf.ajax.menuOpen = function (url) {
	if(url==document.location.href) {
        this.openUrl(url);
        return false;
	} else {
		return true;
	}
}