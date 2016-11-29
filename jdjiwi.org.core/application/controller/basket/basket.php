<?php


$user = cRegister::getUser();
$user->reset();

$basket = new cmfBasket();
if(!$basket->isOrder()) {
	$basket->disable();
	return '/basket/none/';
}

list($header, $_basket, $countAll, $priceAll, $priceDiscount, $priceDelivery, $discount, $isDelivery, $delivery, $pricePay) = $res = $basket->getBasketProduct();
if(!$countAll) {
	$basket->disable();
	return '/basket/none/';
}

$this->assing('header', $header);
$this->assing('_basket', $_basket);
$this->assing('countAll', $countAll);

$this->assing('priceAll', $priceAll);
$this->assing('priceDiscount', $priceDiscount);
$this->assing('discount', $discount);
if($isDelivery)
$this->assing('priceDelivery', $priceDelivery);

cLoader::library('basket/cmfBasketView');
cmfBasketView::init($basket, 'basket');
if(!empty($_SERVER['HTTP_REFERER'])) {
    $url = str_replace(cUrl::get('/index/'), '', $_SERVER['HTTP_REFERER']);
    if(strpos($url, 'basket/')===false) {
        $this->assing2('referer', $_SERVER['HTTP_REFERER']);
        cSession::set('basketReferer', $_SERVER['HTTP_REFERER']);
    } else {
        if(cSession::is('basketReferer')) {
            $this->assing2('referer', cSession::get('basketReferer'));
        }
    }
} else {
    if(cSession::is('basketReferer')) {
        $this->assing2('referer', cSession::get('basketReferer'));
    }
}

?>