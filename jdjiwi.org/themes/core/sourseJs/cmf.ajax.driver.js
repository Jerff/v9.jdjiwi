

//include(cmf.ajax.js);
cmf.ajax.drive = new (function() {
	var drive = this;
	drive.arg = new Array();


	drive.start = function(url, send, cache) {
		if(typeof send != 'object') {
			send = {};
		}
		send.jsAjaxId = String((new Date()).getTime()) + Math.random()*(new Date()).getTime();
		//setTimeout("cmf.ajax.drive.reset('"+ send.jsAjaxId +"');", 15000);
		drive.arg[send.jsAjaxId] = {url: url, send: send, cache: cache};
		return drive.send(url, send, cache);
	};
	drive.reset = function(id) {
		if(drive.arg[id]) {
            drive.send(drive.arg[id].url, drive.arg[id].send, drive.arg[id].cache);
			setTimeout("cmf.ajax.drive.reset('"+ id +"');", 15000);
		}
	};
	drive.end = function(req) {
		if(req.responseJS && req.responseJS.jsAjaxId) {
            var jsAjaxId = req.responseJS.jsAjaxId;
            var arg = new Array();
            for(var id in drive.arg)
            	if(jsAjaxId!=id) {
                	arg[id] = drive.arg[id];
            	}
            drive.arg = arg;
		}
	};

	drive.statusIsSend = false;
	drive.send = function(url, send, cache) {
	    if(drive.statusIsSend) return false;
	    drive.statusIsSend = true;
        cmf.ajax.show();
	    var req = new JsHttpRequest();
	    if(cache) req.caching = true;
	    req.onreadystatechange = function() {
            if (req.readyState == 4) {
				drive.end(req);
				cmf.ajax.log(req.responseText);

				if(req.responseJS && req.responseJS.loadHTML) {
					$(req.responseJS.loadHTML).each(function() {
						$(this.id).html(this.content);
					});
				}
				if(req.responseJS && req.responseJS.js) $.globalEval(req.responseJS.js);
	            cmf.ajax.hide();
	            cmf.loadDocument();
                drive.statusIsSend = false;
	        }
	    };
	    req.open(null, url, true);
	    req.send(send);
	   	return false;
	};

});