

//include(cmf-0.1.js);
user = cmf.user = new (function() {
	var t = this;


    t.registerChahgeType = function(el) {
        if(el.value==2) {
            style.hide('#userType1');
            style.show('#userType2');
        } else {
            style.hide('#userType2');
            style.show('#userType1');
        }
	};

	t.header = function(el) {
        var name = cmf.getCookie('sessionUserName');
	    if(!name) return;

        var email = cmf.getCookie('sessionUserEmail');
	    name = decodeURIComponent(escape(name));
        email = decodeURIComponent(escape(email));

        if(typeof $zopim != "undefined") {
            $zopim(function() {
                $zopim.livechat.setName(name);
                $zopim.livechat.setEmail(email);
            });
        }

	    $('#userHeader1').remove();
	    //$('#userHeader2 .user-a').html('Здравствуйте, '+ name);
	    $('#userHeader2').show();
	};

	t.onchangeCountry = function(regionId, value) {
        cmf.ajax.send(cmf.ajax.url +'/user/changeCountry/', {regionId: regionId, value: value});
	};

    t.onchangeSubscribe = function(checked) {
    	if(checked) {
    	    $('#subscribe').show();
    	} else {
    	    $('#subscribe').hide();
    	}
    };

	t.register = new(function() {
        var register = this;
	});

});
