var update = '<br />Для корректной работы необходимо обновить вашу версию браузера';
if($.browser.safari) {
	version = '525';
	browser = $.browser.version.replace(/([0-9]+).*/, "$1");
	//document.write(version + ' '+ (version>browser)  + ' '+ browser);
	if(version>browser) {
		document.write(update);
	}
} else if($.browser.opera) {
	version = 9;
	subVersion = '64';
	browser = $.browser.version.replace(/([0-9]+)\.([0-9]+)/, "$1");
	subBrowser = $.browser.version.replace(/([0-9]+)\.([0-9]+)/, "$2");
	//document.write(version+subVersion + ' '+ (version>browser && subVersion>subBrowser)  + ' '+ browser+subBrowser);
	if(version>browser || (version==browser && subVersion>subBrowser)) {
		document.write(update);
	}
} else if($.browser.mozilla) {
	version = 1;
	subVersion = '7';
	browser = $.browser.version.replace(/([0-9]+)\.([0-9]+).*/, "$1");
	subBrowser = $.browser.version.replace(/([0-9]+)\.([0-9]+).*/, "$2");
	//document.write(version+subVersion + ' '+ (version>browser && subVersion>subBrowser)  + ' '+ browser+subBrowser);
	if(version>browser || (version==browser && subVersion>subBrowser)) {
		document.write(update);
	}
} else if($.browser.msie){
    version = 8;
	browser = $.browser.version.replace(/([0-9]+).*/, "$1");
	//document.write(version + ' '+ (version>browser)  + ' '+ browser);
	if(version>browser) {
		document.write(update);
	}
}