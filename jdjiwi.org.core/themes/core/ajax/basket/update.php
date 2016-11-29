<?php



cRegister::getUser()->is();
//$r = cRegister::request();
$res = cAjax::get();


cLoader::library('basket/cmfBasket');
$basket = new cmfBasket();
if(cInput::post()->is('product')) {
	$product = cInput::post()->get('product');
    if($product and is_array($product)) {
		foreach($product as $id=>$value) {
			if($value and is_array($value)) {
			    foreach($value as $pId=>$pValue) {
			        if($pValue and is_array($pValue)) {
			            foreach($pValue as $cId=>$cValue) {
			                $basket->setProduct($id, $pId, $cId, $cValue);
			            }
			        }
		        }
	        }
		}
	}
	list($header, $_basket, $countAll, $priceAll, $priceDiscount, $priceDelivery, $discount, $isDelivery, $delivery, $pricePay) = $basket->getBasketProduct();
	$basket->save();

    if(!$basket->isOrder()) {
    	$basket->disable();
    	$res->reload();
    }

    if(cInput::post()->get('update')==='order') {
        $basket->setStep(1);
        $basket->save();
        $res->redirect(cUrl::get('/basket/delivery/'));
    }


    ob_start();
    ?>
        <b>Всего товаров на сумму</b>
        <b class="price"><i><?=$priceAll ?></i></b>
        <? if($discount) { ?>
            <b>Цена со скидкой</b>
            <b class="price"><i><?=$priceDiscount ?></i></b>
        <? } ?>
        <? if($isDelivery) { ?>
            <b>Цена с доставкой</b>
            <b class="price"><i><?=$priceDelivery ?></i></b>
        <? } ?>
        <? if($discount) { ?>
            <b>Скидка</b>
            <b class="price"><i><?=$discount ?></i></b>
        <? } ?>
    <?
    $res->html('#basketPrice', ob_get_clean())
        ->script("cmf.basket._header('{$countAll}', '{$priceAll}');");

    foreach($_basket as $k=>$v) {
        foreach($v['param'] as $pId=>$pName) if(isset($v['count'][$pId])) {
            foreach($v['count'][$pId] as $cId=>$cValue) if(isset($v['color'][$cId]) or !$cId) {
                $res->html("price{$k}_{$pId}_{$cId}", $v['view'][$pId][$cId] .'<b></b>')
                    ->html("value{$k}_{$pId}_{$cId}", $cValue)
                    ->html("comment{$k}_{$pId}_{$cId}", isset($v['comment'][$pId][$cId]) ? '('. $v['comment'][$pId][$cId] .')' : '')
                    ->script('cmf.setValue("product['. $k .']['. $pId .']['. $cId .']", "'. $cValue .'");');
            }
        }
    }
//    pre($res);
}

?>