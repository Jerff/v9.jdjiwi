<?php

$basket = new cmfBasket();
if(cRegister::getUser()->is()) {
    if(!$basket->isStep(3) or !$basket->isOrder()) {
        cRedirect(cUrl::get('/basket/adress/'));
    }
    $basket->setStep(4);
} else {
    if(!$basket->isStep(4) or !$basket->isOrder()) {
        cRedirect(cUrl::get('/basket/subscribe/'));
    }
}
$basket->setStep(5);
$basket->save();

cRedirect(cUrl::get('/basket/confirmation/'));

?>