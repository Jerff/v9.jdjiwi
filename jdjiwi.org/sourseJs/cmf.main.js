
//include(cmf.ajax.js);
cmf.ajax.runController = function() {
    return cmf.ajax.drive.start(cmf.ajax.getUrl() + arguments[0], arguments[1], arguments[2]);
};
cmf.main = new(function() {
    var t = this;


    /* form */
    t.form = new(function() {
        var form = this;

        form.login = function($is) {
            /*if($is) {
                $(this).addClass('i-text-error').removeClass('i-text');
            } else {
                $(this).addClass('i-text').removeClass('i-text-error');
            }*/
        };
    });


    /* catalog */
    t.search = new(function() {
        var search = this;

        search.start = function() {
            var name = cmf.getId('searchName').value;
            if(!name) return false;
            if(name=='Поиск...') return false;
            return true;
        };
    });

    /* catalog */
    t.searchArticul = new(function() {
        var search = this;
        cmf.ready(function(){
            search.init = cmf.getValue('searchArticul');
        });

        search.start = function() {
            var name = cmf.getValue('searchArticul');
            if(search.init) {
                if(search.init==name) return false;
                if(name=='Артикул...') {
                    cmf.setValue('searchArticul', '');
                }
                return true;
            } else {
                if(!name) return false;
                if(name=='Артикул...') return false;
                return true;
            }
        };
    });

    /* gallery */
    t.gallery = new(function() {
        var gallery = this;
        var list = new Array();
        var item = -1;

        gallery.init = function(config) {
            var l = list[++item] = {};
            l.time = config.time;
            l.rand = config.rand;
            l.items = $(config.select).find('ul > li');
            if(l.items.length<2) return;
            l.items.each(function() {
                if(!$(this).hasClass('active')) {
                    //$(this).hide();
                }
            });
            l.liCount = l.items.index(l.items.filter(':last'));
            l.active = l.items.index(l.items.filter('.active:eq(0)'));
            if (l.active == -1) l.active = 0;
            l.temp = l.active;
            l.items.css({opacity: 0}).eq(l.active).css({opacity: 1});
            if(l.liCount) {
                setInterval("cmf.main.gallery.call("+ item +", false);", config.interval*1000);
            }
        };

        gallery.call = function(item, is) {
            var l = list[item];
            if(l.rand) {
                var id = l.active;
                while(id==l.active) {
                    id = Math.floor(Math.random() * (l.items.length));
                }
                l.active = id;
            } else {
                l.active++;
            }

            if (l.active > (l.liCount)) l.active = 0;
            l.old = l.temp;
            l.items.eq(l.temp).animate({opacity: 0} , l.time, undefined,
                                        function() {
                                            l.items.eq(l.old).removeClass('active');
                                        });
            l.items.eq(l.active).addClass('active');
            l.items.eq(l.active).animate({opacity: 1}, l.time, undefined,
                                        function() {
                                            l.items.eq(l.active).addClass('active');
                                        });
            l.temp = l.active;
        };
    });


    /* catalog */
    t.showcase = new(function() {
        var showcase = this;

        showcase.run = function(time) {
            showcase.main = $('.banner-1>a:first').clone();
            $('.banner-1>a:first').remove();

            setInterval(function() {
                var item = $('.banner-1>a:first');
                var mainId = showcase.main.attr('id').replace('small', '');
                var newId = item.attr('id').replace('small', '');

                showcase.main.appendTo(".banner-1");
                showcase.main = item.clone();
                $('.banner-1>a:first').animate({width: 0}, 1000, undefined, function() {
                    $('.banner-1>a:first').remove();
                });
                $('.banner-1>a:eq(4)').css('width', 0).animate({width: '182px'}, 1000);

                $('#main'+ mainId).animate({opacity: 0} , 1000, undefined, function() {
                    $('#main'+ mainId).removeClass('active')
                });
                $('#main'+ newId).css('opacity', 0).addClass('active').animate({opacity: 1}, 1000);

            }, time*1000);
        };
    });


    /* catalog */
    t.catalogMenu = new(function() {
        var menu = this;
        menu.color = function() {
            menu.i = false;
            $('#catalogMenu>ul.item-menu>li:visible').each(function() {
                menu.clas(this);
                if($(this).hasClass('active1')) {
                    menu.child($(this).attr('parent'));
                }
            });
        };

        menu.child = function(parent) {
            $('ul[child='+ parent +']>li', $('#catalogMenu')).each(function() {
                menu.clas(this);
                if($(this).hasClass('active1')) {
                    menu.child($(this).attr('parent'));
                }
            });
        };

        menu.clas = function(t) {
            if(menu.i) {
                $(t).addClass('c2').removeClass('c1');
            } else {
                $(t).addClass('c1').removeClass('c2');
            }
            menu.i = !menu.i;
        };
    });




    /* product */
    t.product = new(function() {
        var product = this;

        /* preview */
        product.preview = new(function() {
            var preview = this;

            preview.item = false;
            preview.time = 1000;
            preview.init = function() {
                $('#productMain>ul>li').each(function() {
                    if(!preview.item) {
                    	preview.item = this.id.replace('main', '');
                    	$(this).css({opacity: 1});
                    } else {
                    	$(this).css({opacity: 0});
                    }
                });
            };

            preview.getItem = function(id) {
                if(preview.item) {
                    return preview.item;
                } else {
                    return $('#productMain>ul>li.active').attr('id').substr(4);
                }
            };
            preview.shange = function(id) {
                preview.item = preview.getItem();
                if(id==preview.item) return;
                var item = $('#main'+ preview.item +', #big'+ preview.item);
                var active = $('#main'+ id +', #big'+ id);

                preview.item = id;
                var height = $('#big'+ id +' a img').height();
                var heightNew = $('#big'+ id +' a img').height();
                if(heightNew>height) {
                    height = heightNew;
                }
                $('#productBig').height(height);
                item.animate({opacity: 0} , preview.time, undefined,
                                            function() {
                                                item.removeClass('active')
                                            });
                active.animate({opacity: 1}, preview.time, undefined,
                                            function() {
                                                active.addClass('active');
                                            });
            };

            preview.select = function(id) {
                if(style.hideShow('#main')) {
                    $('#mainImage').hide();
                } else {
                    $('#mainImage').show();
                    $('#productBig').height($('#big'+ id +' a img').height())
                }
            };

            preview.message = function(id) {
                $('#productBig>ul>li>a').mouseover(function() {
                    $('.img-popup-small').html('Нажмите, чтобы вернуться к описанию').show();
                }).mouseout(function() {
                    $('.img-popup-small').hide()
                }).mousemove(function(e){
                    $('.img-popup-small').css({'left':e.pageX+15,'top':e.pageY});
                });

                $('#productMain>ul>li>a').mouseover(function() {
                    $('.img-popup-small').html('Нажмите для увеличения').show();
                }).mouseout(function() {
                    $('.img-popup-small').hide()
                }).mousemove(function(e){
                    $('.img-popup-small').css({'left':e.pageX+15,'top':e.pageY});
                });
            };
        });

        /* gallery */
        product.gallery = function() {
            for(i=1; i<4;i++) {
                var coll=0;
                $('#car'+ i).find('td').each(function(){
                    coll++;
                });
                if(coll>5) {
                    $('#car'+ i).jscroll({axis : {y:false,x:true}});
                    $('#car'+ i).width(70*(coll));
                }
            }

            var coll=0;
            $('#car4').find('td').each(function(){
                coll++;
            });
            if(coll>5) {
                $('#car4').jscroll({axis : {y:false,x:true}});
                $('#car4').width(64*(coll+1));
            }
        };

        product.colorSelect = function(value) {
            var color = '.colorView'+ value;
            var id = cmf.getId("color["+ value +"]");
            if(id.checked) {
                $(color).addClass('colorbox').removeClass('colorbox2');
            } else {
                $(color).addClass('colorbox2').removeClass('colorbox');
                $(color).parent().siblings('a').each(function() {
                    $('div.colorItem', $(this)).addClass('colorbox').removeClass('colorbox2');
                    $('div input', $(this)).attr('checked', false);
                });
            }
            id.checked = !id.checked;
            if(id.checked) {
                $('#productImageList>a').each(function() {
                    if($(this).attr('color') && $(this).attr('color').indexOf('['+ value +']')==-1) {
                        style.hide(this);
                        $('#2'+ $(this).attr('id')).hide();
                    } else {
                        style.show(this);
                        $('#2'+ $(this).attr('id')).show();
                    }
                });
                var active = product.preview.getItem();
                if(style.isHide('#small'+ active)) {
                    $('#productImageList>a:visible:first, #productImageList2>a:visible:first').click();
                }

            } else {
                style.show('#productImageList>a');
                style.show('#productImageList2>a');
            }
        };


        product.sizeSelect = function(price) {
            if(parseInt(price)) {
                $('#priceProduct').html(price +' р.');
            }
        };
    });

    /* catalog */
    t.search = new(function() {
        var search = this;

        search.start = function() {
            var name = cmf.getId('searchName').value;
            if(!name) return false;
            if(name=='Поиск...') return false;
            return true;
        };
    });


    /* imageLoad */
    t.imageLoad = new(function() {
        var imageLoad = this;
        imageLoad.list = new Array();
        imageLoad.id = 0;

        imageLoad.add = function(img) {

            imageLoad.list[imageLoad.id] = new Image();
            imageLoad.list[imageLoad.id].src = img;
            imageLoad.id++;
        };
    });

    /* imageLoad */
    t.sizeList = new(function() {
        var sizeList = this;

        sizeList.select = function(t, id) {
            $('.sizeHeader').removeClass('sizeHeaderSelect');
            $(t).addClass('sizeHeaderSelect');
            $('#sizeListContent').html($('#sizeListContent'+ id).html());
        };
    });

});