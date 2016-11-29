
$(document).ready(function (){
    cmf.main.order = new(function() {
        order = this;
        order.BILL_ICON_OFFSET_TOP=10;
        order.GROW_RATE=1.5;
        order.FLY_DURATION=0.4;
        order.BUBBLE_DURATION=0.25;
        order.SCROLL_DURATION=0.4;
        order.DELAY_BETWEEN_ANIS=0.15;

        order.buy = function (){
            order.jPage=$(document),
                order.jBillLink=$("#basketOrder span"),
                order.jBillIcon=order.jBillLink.find("img.icon"),
                order.jFly=$('<img id="fly" />');
            order.iMemorizedScroll;
            order.aAniQueue=[];

            order.jPhoto=$("#productMain").find("li.active img");
            order.oPhotoRect={width:order.jPhoto.attr("width"),
                              height:order.jPhoto.attr("height"),
                              left:null,
                              top:null},
                order.oBillRect={width:order.jBillIcon.attr("width"),
                                 height:order.jBillIcon.attr("height"),
                                 left:parseInt(order.jBillIcon.css("left")),
                                 top:parseInt(order.jBillIcon.css("top"))};
            order.jFly=$('<img id="fly" />').attr("src", order.jPhoto.attr("src"));
            if(order.jPhoto.size()){
                if(order.isDestinationPointOutOfView()){
                    order.rememberScroll();
                    order.animate(order.scrollIn);
                    order.animate(order.fly);
                    order.animate(order.bubble);
                    order.animate(order.scrollOut);
                } else{
                    order.animate(order.fly);
                    order.animate(order.bubble);
                }
            }
        };
        order.scrollIn = function(fOnComplete){
            order.scroll(order.getScroll(), 0, fOnComplete);
        };
        order.fly = function(fOnComplete){
            order.prepareToFly();
            order.doFly(fOnComplete);
        };
        order.prepareToFly = function(){
            $.extend(order.oPhotoRect, order.jPhoto.offset());
            order.jFly.css(order.oPhotoRect);
            $(document.body).append(order.jFly);
        };
        order.doFly = function(fOnComplete){
            function flyPhase(oCssProps,fOnComplete){
                jTweener.addTween(order.jFly, $.extend(oCssProps, {
                    time:order.FLY_DURATION/2,transition:"easeOutSine",onComplete:fOnComplete
                }));
            }
            var oFinalPoint=order.getDestinationPoint(),
                oGrow=order.centerScale(order.oPhotoRect),
                oFall={width:0,height:0,left:oFinalPoint.left,top:oFinalPoint.top};
            flyPhase(oGrow,function(){
                flyPhase(oFall,function(){order.cleanAfterFly();fOnComplete();});
            });
        };
        order.bubble = function(fOnComplete){
            function bubblePhase(oCssProps,fOnComplete){
                jTweener.addTween(order.jBillIcon,$.extend(oCssProps,{time:order.BUBBLE_DURATION/2,transition:"easeOutSine",onComplete:fOnComplete}));
            }
            order.jBillIcon.css(order.oBillRect);
            bubblePhase(order.centerScale(order.oBillRect),function(){bubblePhase(order.oBillRect,fOnComplete)});
        };
        order.scrollOut = function(){
            order.scroll(order.getScroll(),order.iMemorizedScroll);
        };
        order.scroll = function(iStart,iFinish,fOnComplete){
            jTweener.addPercent({everyFrame:function(iPercent){
                                     order.setScroll(Math.round(iStart+(iFinish-iStart)*iPercent));
                                }, onComplete:fOnComplete,
                                   transition:"easeInOutQuart",
                                   time:order.SCROLL_DURATION});
        };
        order.animate = function(fAni){
            order.aAniQueue.push(fAni);
            function onPrevAniComplete(){
                var fNextAni=order.aAniQueue[1];
                if(fNextAni&&typeof fNextAni==="function"){
                    setTimeout(function(){
                        fNextAni(onPrevAniComplete);
                        order.aAniQueue.shift();
                    },order.DELAY_BETWEEN_ANIS*1000)}
                else{order.aAniQueue.shift();}
            }
            if(order.aAniQueue.length===1){fAni(onPrevAniComplete);}
        };
        order.cleanAfterFly = function(){
            order.jFly.remove();
        };
        order.isDestinationPointOutOfView = function(){
            return(order.getDestinationPoint().top<order.getScroll());
        };
        order.getScroll = function(){
            return order.jPage.scrollTop();
        };
        order.setScroll = function(iScroll){
            order.jPage.scrollTop(iScroll);
        };
        order.rememberScroll = function(){
            order.iMemorizedScroll=order.getScroll();
        };
        order.getDestinationPoint = function(){
            var oBillLinkOffset=order.jBillLink.offset();
            return {left:oBillLinkOffset.left+order.jBillLink.width()/2, top:oBillLinkOffset.top+order.BILL_ICON_OFFSET_TOP}
        };
        order.centerScale = function(oRect){
            var w=oRect.width,h=oRect.height,l=oRect.left,t=oRect.top;
            return{width:w*order.GROW_RATE,height:h*order.GROW_RATE,left:l-(w*(order.GROW_RATE-1)/2),top:t-(h*(order.GROW_RATE-1)/2)}
        };
    });
});