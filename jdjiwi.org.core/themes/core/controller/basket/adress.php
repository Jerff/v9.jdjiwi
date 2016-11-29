<?php


$user = cRegister::getUser();
$user->reset();
$this->assing2('userUrl', cUrl::get('/user/enter/'));
$this->assing2('isUser',    $user->is());


$basket = new cmfBasket();
if(!$basket->isStep(2) or !$basket->isOrder()) {
    cRedirect(cUrl::get('/basket/delivery/'));
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


cLoader::library('basket/cmfBasketAdress');
$order = new cmfBasketAdress();
$order->loadData();
$this->assing('order',	$order);
$this->assing('form',			$order->form()->get(1));
$this->assing('formAdress',		$order->form()->get(3));
$this->assing('formContact',	$order->form()->get(4));
$this->assing('formNotice',		$order->form()->get(5));

$this->assing2('userInfo',		cSettings::get('user', 'basket'));
$this->assing2('deliveryInfo',	cSettings::get('delivery', 'basket'));
cLoader::library('basket/cmfBasketView');
cmfBasketView::init($basket, 'adress');
if(cSession::is('basketReferer')) {
    $this->assing2('referer', cSession::get('basketReferer'));
}


?>