<?php



//$r = cRegister::request();

cLoader::library('user/cmfUserOrderView');
$orderView = new cmfUserOrderView(cInput::get()->get('orderId'));
$orderView->run1();

?>