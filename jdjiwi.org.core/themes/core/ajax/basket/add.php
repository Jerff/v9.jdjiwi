<?php



cRegister::getUser()->is();
//$r = cRegister::request();
$res = cAjax::get();


$productId = (int)cInput::post()->get('productId');
if(!$productId) exit;

$paramId = (int)cInput::post()->get('param');
if(cInput::post()->is('param')) {
    if(!$paramId) {
        cAjax::get()->script("cmf.basket.view($productId, 'Выберите размер');");
        exit;
    }
}

$color = array_keys((array)cInput::post()->get('color'));
cLoader::library('basket/cmfBasket');
$basket = new cmfBasket();
if($color) {
    foreach($color as $colorId) {
        $basket->addProduct($productId, $paramId, $colorId);
    }
} else {
    $basket->addProduct($productId, $paramId, 0);
}
$basket->initPrice();
$basket->save();
cAjax::get()->script("cmf.main.order.buy();");

?>