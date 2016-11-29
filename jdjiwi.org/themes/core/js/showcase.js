    var do_not_switch   = false;
    var show_video = false;
    timeout_handler = null;
    var showBirthday = false;
    var startSh = true;
    var currentShowcase;
    var countShowcase;
    var isAnimation = false;
    var animationInterval = 8000;

    function show_next_showcase ( index, interval )
    {
        if ( do_not_switch || show_video || showBirthday ) {
            timeout_handler = setTimeout ( function () { show_next_showcase ( index, interval ) }, interval );
        } else {
            if ( $("div.showcase").length == 1 ) {
                $("div.showcase").fadeIn ();
                $("div.milestone").fadeIn ();
                return;
            }
            clearTimeout ( timeout_handler );
            var next = index == $(".showcase").length - 1 ? 0 : index + 1;
            if (index == 10) {
            	var _video = $("#hidden-img").html();
                $("div#video-start").html(_video).fadeIn ();
                } else {
                    $("div#video-start").fadeOut ();
                    $("div#video-wrapper").hide ();
            }
            currentShowcase = index;
            if (!startSh) {
                $("div.showcase").fadeOut ();
            } else {
            	startSh = false;
            };
            $($("div.showcase")[index]).fadeIn ();
            $("div.milestone").fadeOut ();
            $($("div.milestone")[index]).fadeIn ();
            timeout_handler = setTimeout ( function () { show_next_showcase ( next, interval ) }, interval );
        }
    }

    $(document).ready(function(){

        $("#showcases_box").find('div.showcase').eq(0).show();
        var flagmodal = false;
        var countShowcase = -1;
        $("#contentflash .showcase").each(function() {
            countShowcase++;
        });
        if ($.browser.msie) {
            $("#contentflash").hover(
                    function () {
                        $(this).find(".showcase-btn").show();
                        do_not_switch = true;
                    },
                    function () {
                        $(this).find(".showcase-btn").hide();
                        do_not_switch = false;
                    }
                );//end of hover
        } else {
            $("#contentflash").hover(
                    function () {
                        $(this).find(".showcase-btn").fadeIn();
                        do_not_switch = true;
                    },
                    function () {
                        $(this).find(".showcase-btn").fadeOut();
                        do_not_switch = false;
                    }
                );//end of hover
        };

        $("#showcase-prev, #showcase-next").hover(
            function () {
                $(this).addClass("btnhover");
            },
            function () {
                $(this).removeClass("btnhover");
            }
        );//end of hover

        $("#showcase-prev").click( function() {
            do_not_switch = false;
            if (currentShowcase==0) {
                currentShowcase = countShowcase;
            } else {
                currentShowcase -=1;
            };
            show_next_showcase ( currentShowcase, animationInterval );
        });//end of click

        $("#showcase-next").click( function() {
            do_not_switch = false;
            if (currentShowcase==countShowcase) {
                currentShowcase = 0;

            } else {
                currentShowcase +=1;
            };
            show_next_showcase ( currentShowcase, animationInterval );
        });//end of click

        $("#showcases_box").mouseover ( function () {
            do_not_switch = true;
        });

        $("#showcases_box").mouseout ( function () {
            do_not_switch = false;
        });


        $(".showcase").each ( function () {
            var that = this;

            $("span.area", this).each ( function () { // IE7 fix
                $(this).css( "filter", "alpha(opacity=0)" );
            });

            $("span.area", this).hover ( function () {
                $("span.area", that).each ( function () {
                    $(this).stop ().animate ( {opacity: 0.4}, 350 );
                });

                $(this).stop ().animate ( {opacity: 0}, 350 );
            });

            $(this).hover ( function () {}, function () {
                $("span.area", this).each ( function () {
                    $(this).stop ().animate ( {opacity: 0}, 350 );
                });
            });
        });

        showcases_loaded = 0;
        currentShowcase = 0;

        if(isAnimation) {
            show_next_showcase ( 0, animationInterval );
        }

	});