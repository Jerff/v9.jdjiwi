

cmf.ready(function() {
	$("a.fancybox").fancybox({
		'zoomSpeedIn':		300,
		'zoomSpeedOut':		300,
		'overlayShow':		false,
		"hideOnContentClick":true,
		"overlayShow":		true,
		"overlayOpacity":	0.5
	});

	$("a.openAction").fancybox({
		'zoomSpeedIn':		300,
		'zoomSpeedOut':		300,
		'overlayShow':		false,
		"hideOnContentClick":false,
		"overlayShow":		true,
		"overlayOpacity":	0.5
	});
});

cmf.preloadDocument = function() {
	cmf.admin.gallery.remove();
}