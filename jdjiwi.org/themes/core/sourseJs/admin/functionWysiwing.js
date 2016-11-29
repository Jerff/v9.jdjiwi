function cmfWysiwingGetData(key) {
	var get = new String(window.location);
	var x = get.indexOf('?');
	if(x!=-1) {
		var l = get.length;
		get = get.substr(x+1, l-x);

		l = get.split('&');
		for(i in l) {
			get = l[i].split('=');
			if(key==get[0]) return  get[1];
		}
	}
	return false;
}

function cmfWysiwingGetParam() {
	var path = cmfWysiwingGetData('path');
	var number = cmfWysiwingGetData('number');
	return '&path='+ path +'&number='+ number;
}

function cmfWysiwingGetParamCkfinder() {
	var path = cmfWysiwingGetData('path');
	var number = cmfWysiwingGetData('number');
	return '-path-'+ path +'-number-'+ number;
}