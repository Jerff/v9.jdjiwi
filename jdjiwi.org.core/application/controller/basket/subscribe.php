<?php


$user = cRegister::getUser();
$user->reset();
$this->assing2('userUrl', cUrl::get('/user/enter/'));
$this->assing2('isUser',    $user->is());


$basket = new cmfBasket();
if(!$basket->isStep(3) or !$basket->isOrder()) {
    cRedirect(cUrl::get('/basket/adress/'));
}
if($user->is()) {
    cRedirect(cUrl::get('/basket/pay/'));
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


cLoader::library('basket/cmfBasketSubscribe');
$order = new cmfBasketSubscribe();
$order->loadData();
$this->assing('order',	$order);
$this->assing('formSubscribe',	$order->form()->get());


cLoader::library('basket/cmfBasketView');
cmfBasketView::init($basket, 'subscribe');
if(cSession::is('basketReferer')) {
    $this->assing2('referer', cSession::get('basketReferer'));
}


?>