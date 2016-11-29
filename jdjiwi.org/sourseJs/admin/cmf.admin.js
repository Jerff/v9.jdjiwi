

cmf.admin = new(function() {
	var t = this;


	/* hash */
	t.isPageStart = false;
	t.isNotStart = false;

	$(function(){
		$(window).bind( 'hashchange', function(){
			cmf.admin.shangeHash();
		})
	});

	if(document.location.hash) {
		$(document).ready(function(){
			cmf.admin.shangeHash();
		});
	}

	t.shangeHash = function() {
		if(cmf.admin.isNotStart) return;
		if(!cmf.getId('mainContent')) {
			cmf.admin.isPageStart = false;
		}
		if(cmf.admin.isPageStart) {
	        cmf.ajax.openUrl(document.location.href);
		} else {
			cmf.ajax.startUrl(document.location.href);
	        cmf.admin.isPageStart = true;
		}
	}


	/* command */
	t.exit = function() {
		return cmf.ajax.run(document.location.href, {ajaxCommand:'exit'});
	}


	/* */
	t.hideShowLine = function(line, sel) {
		if(sel) {
			line.save = true;
			line.html = $(line).html();
			$(line).html('');
		} else {
			if(line.save) {
				$(line).html(line.html);
				cmf.loadDocument();
			}
		}
	}
	/* */
	t.hideShowLineNew = function(line, sel) {
		if(sel) {
			$(line).show();
		} else {
			$(line).hide();
		}
	}

	t.hideShowList = function(line, sel) {
    	$(line).each(function() {
            if(sel) {
                cmf.admin.showLine(this);
            } else {
                cmf.admin.hideLine(this);
            }
        });
	}

	t.hideLine = function(line) {
		line.save = true;
		line.html = $(line).html();
		$(line).html('');
	}

	t.showLine = function(line) {
		if(line.save) {
			$(line).html(line.html);
			cmf.loadDocument();
		}
	}

})