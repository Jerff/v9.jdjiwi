
//include(cmf.ajax.js);
cmf.basket = new(function() {
    var basket = this;

    basket.add = function() {
    	cmf.ajax.sendForm(cmf.ajax.url +'/basket/add/?'+ Math.random(), cmf.getId('productData'));
    };

    basket.viewProduct = function(productId) {
    	cmf.ajax.send(cmf.ajax.url +'/basket/viewProduct/?'+ Math.random(), {productId: productId});
    };

    basket.scroll = function(id) {
        iStart = $(document).scrollTop();
        iFinish = $(id).offset().top;
        jTweener.addPercent({everyFrame: function(iPercent){
                                $(document).scrollTop(Math.round(iStart+(iFinish-iStart)*iPercent));
                            },
                             transition:"easeInOutQuart",
                             time:0.4});
    };

    basket.sizeSelect = function(isPrice, el, isDiscount) {
        if(!parseInt(el.value)) return;
        if(isPrice) {
            $('#priceView').html($('option:eq('+ el.selectedIndex +')', $(el)).attr('price'));
            if(!isDiscount) {
                $('#priceOld>del>span').html($('option:eq('+ el.selectedIndex +')', $(el)).attr('priceOld'));
            }
        }
        if(parseInt($('option:eq('+ el.selectedIndex +')', $(el)).attr('dump'))) {
            $('.isOrder').hide();
            $('.isOrderButton').addClass('bay').removeClass('order');
        } else {
            $('.isOrder').show();
            $('.isOrderButton').addClass('order').removeClass('bay');
        }
    };

    basket.view = function(id, text) {
        $(id = '#basket'+ id).show().animate({opacity: 1}, 1).click(function() {
            $(this).hide()
        });
        $(id + '>div').html(text);
        setTimeout("$('"+ id +"').animate({opacity: 0}, 1000).hide();", 5000);
    };

    basket.header = function() {
        basket._header(cmf.getCookie('basketCount'), cmf.getCookie('basketPrice'));
    };

    basket._header = function(count, price) {
        if(count) {
		    switch(parseInt(count)) {
		        case 1:
		            count += ' товар';
		            break;
		        case 2: case 3: case 4:
		            count += ' товара';
		            break;
		        default:
		            count += ' товаров';
		            break;
		    }
		    $('.header-cart>span').html(count);
    	} else {
    		$('.header-cart>span').html('нет товаров');
    	}
    };


    basket.setOrder = function() {
    	cmf.setValue('update', 'order');
    	basket.update();
    };
    basket.setUpdate = function() {
    	cmf.setValue('update', 'update');
    };
    basket.updateCount = function(productId, sum) {
         var count = parseInt(parseInt(productId.value) + sum);
         if(count<1 || count>1000) return;
         productId.value = count;
    	 var left = $('a:eq(0)', $(productId).parent());
    	 if($(left).hasClass('prewOn')) {
             if(count==1) {
                 $(left).addClass('prewOff').removeClass('prewOn');
             }
    	 } else {
             if(count>1) {
                 $(left).addClass('prewOn').removeClass('prewOff');
             }
    	 }
    	 $('b', $(productId).parent()).html(count);
    	 basket.setUpdate();
    	 basket.update();
    };
    basket.update = function() {
    	return cmf.ajax.sendForm(cmf.ajax.url +'/basket/update/', cmf.getId('basketId'));
    };
    basket.del = function(id, pId, cId, redirect) {
    	if(confirm('Удалить?'))
    	return cmf.ajax.send(cmf.ajax.url +'/basket/delete/', {id: id, pId: pId, cId: cId, redirect: redirect});
    };

    basket.order = function() {
    	return cmf.ajax.send(cmf.ajax.url +'/basket/order/', {});
    };

    basket.onchangeDelivery = function(t) {
        if($(t).attr("delivery")=='true') {
            $('.isDelivery').show();
        } else {
            $('.isDelivery').hide();
        }
        
//        pre($(t).attr("contact")=='true');
        if($(t).attr("contact")=='true') {
            $('.isContact').show();
        } else {
            $('.isContact').hide();
        }
    };

});