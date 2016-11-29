

cmf.ajax.drive = new (function() {
	var drive = this;
	drive.isRun = false;
	drive.arg = false;

	drive.get = function() {
		return drive.isRun;
	}
	drive.start = function(url, send, cache) {
		drive.isRun = true;
		drive.arg = {url: url, send: send, cache: cache};
		//setTimeout("cmf.ajax.drive.reset();", 15000);
		return drive.send(url, send, cache);
	}
	drive.reset = function() {
		if(drive.get()) {
			drive.send(drive.arg.url, drive.arg.send, drive.arg.cache);
		}
	}
	drive.end = function() {
		drive.isRun = false;
	}

	drive.send = function(url, send, cache) {
	    cmf.ajax.show();
	    var req = new JsHttpRequest();
	    if(cache) req.caching = true;
	    req.onreadystatechange = function() {
	        if (req.readyState == 4) {
				if(!drive.get()) return;
				drive.end();

				cmf.ajax.log(req.responseText);

	            cmf.preloadDocument();
				if(req.responseJS && req.responseJS.loadHTML) {
					$(req.responseJS.loadHTML).each(function() {
						$(this.id).html(this.content);
					});
				}
				if(req.responseJS && req.responseJS.js) $.globalEval(req.responseJS.js);
	            cmf.ajax.hide();
	            cmf.loadDocument();
	        }
	    }
	    req.open(null, url, true);
	    req.send(send);
		return false;
	}
});
