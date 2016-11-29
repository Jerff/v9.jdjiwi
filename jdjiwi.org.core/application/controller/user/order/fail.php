<?php


$modul = cPages::param()->get(1);
$sql = cRegister::sql();
$pay = $sql->placeholder("SELECT id, data, name FROM ?t WHERE modul=? AND visible='yes'", db_payment, $modul)
			 ->fetchAssoc();
if(!$pay) {
    return 404;
}

cGlobal::set('paymentMessage', 'Платеж не прошел');
cLoader::library('payment/cmfPayment');
if($orderId = cmfPayment::fail($modul)) {
    cGlobal::set('$orderId', $orderId);
    return '/user/order/one/';
}
return 404;

?>