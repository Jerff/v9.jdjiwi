<?php

cLoader::library('basket/cmfHistory');


//$r = cRegister::request();
if($product = (int)cInput::post()->get('id')) {
	$basket = new cmfHistory();
	$basket->addProduct($product);
}

?>