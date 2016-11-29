$(document).ready(function(){
	$('#tabs').each(function(){
		var $tabs = $(this);
		var indexActiveTab = 0;
		$('#tabs .tabs-head li').click(function(){
			indexActiveTab = $tabs.find('.tabs-head li').index(this);
			$(this).addClass('active').siblings().removeClass('active');
			$tabs.find('#tabs-'+(indexActiveTab+1)).show().siblings().hide();
			return false;
		});
		$('#tabs .tabs-head li').eq(0).click();
	});
	$('input[title]').example(function(){
	    return $(this).attr('title');
	});
	$('.consultant').click(function(){
		$(this).toggleClass('consultant-hide');
	});
	$("a.user-a-register, a.user-a-login, a.terms-of-use, .call-back a").fancybox({
		'cache' : false,
        'width': 406,
		'height': 450
	});

    $("a.user-a-register, a.user-a-login, a.terms-of-use, .call-back a").each(function() {
        $(this).attr('href', $(this).attr('href') +'?fancybox')
               .attr('target', '');
    });

    //$("#websites2,#websites4,#websites5").msDropDown();
    $(".websites>select").msDropDown({mainCSS:'dd2'});
    $("#websites4").msDropDown({mainCSS:'dd2'});

    $('#colorList>a>div').corner("6px");
    $('.articul>span').corner("11px");
})


