<?php


$user = cRegister::getUser();
$user->reset();
$this->assing2('userUrl', cUrl::get('/user/enter/'));
$this->assing2('isUser',    $user->is());


$basket = new cmfBasket();
if(!$basket->isStep(1) or !$basket->isOrder()) {
    cRedirect(cUrl::get('/basket/'));
}

list($header, $_basket, $countAll, $priceAll, $priceDiscount, $priceDelivery, $discount, $isDelivery, $delivery, $pricePay) = $res = $basket->getBasketProduct();
if(!$countAll) {
	cRedirect(cUrl::get('/basket/'));
}

$this->assing('priceAll', $priceAll);
$this->assing('priceDiscount', $priceDiscount);
$this->assing('discount', $discount);
if($isDelivery)
$this->assing('priceDelivery', $priceDelivery);


cLoader::library('basket/cmfBasketDelivery');
$order = new cmfBasketDelivery();
$order->loadData();
$this->assing('order',	$order);
$this->assing('formFilter',		$order->form()->get());


$this->assing2('userInfo',		cSettings::get('user', 'basket'));
$this->assing2('deliveryInfo',	cSettings::get('delivery', 'basket'));

cLoader::library('basket/cmfBasketView');
cmfBasketView::init($basket, 'delivery');
if(cSession::is('basketReferer')) {
    $this->assing2('referer', cSession::get('basketReferer'));
}


?>