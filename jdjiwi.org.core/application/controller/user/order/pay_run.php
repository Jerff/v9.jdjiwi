<?php


$orderId = cPages::param()->get(1);
$modul = cPages::param()->get(2);
$status = cmfOrder::getStatusList(0, 1, 2);
$sql = cRegister::sql();
$order = $sql->placeholder("SELECT id, user, product, data, pay, price, isPay, registerDate, status FROM ?t WHERE id=? AND status ?@ AND `delete`='no'", db_basket, $orderId, array_keys($status))
			 ->fetchAssoc();
if(!$order) return 404;
if($order['isPay']==='yes') {
    cRedirect(cUrl::get('/user/order/one/', $orderId));
}
if($order['user']) {
    $user = cRegister::getUser();
    $user->filterIsUser();
    if($order['user']!= $user->getId()) {
        return 404;
    }
    $fio = cmfUser::generateName($user->all);

    cmfMenu::add('Личные кабинет', cUrl::get('/user/'));
    cmfMenu::add('История заказов', cUrl::get('/user/order/'));
} else {
    $fio = '';
}

cmfMenu::add('Заказ № '. $orderId, cUrl::get('/user/order/one/', $orderId));
cmfMenu::add('Оплата', cUrl::get('/user/order/pay/', $orderId));
cmfMenu::add('Оплата');

$this->assing('orderId', $orderId);
$this->assing('status', $status[$order['status']]);
$this->assing2('date', date('d.m.y', strtotime($order['registerDate'])));



list($countAll, $priceAll, $priceDiscount, $discount) = cConvert::unserialize($order['price']);
$this->assing('countAll', $countAll);
$this->assing('priceAll', $priceAll);
$this->assing('priceDiscount', $priceDiscount);
$this->assing('discount', $discount);


$pay = $sql->placeholder("SELECT id, data, name, commission FROM ?t WHERE modul=? AND visible='yes'", db_payment, $modul)
			 ->fetchAssoc();
if(!$pay) {
    cRedirect(cUrl::get('/user/order/pay/', $orderId));
}
$data = cConvert::unserialize($pay['data']);
$data['orderId'] = $orderId;
$data['fio'] = $fio;
$data['price'] = $order['pay'];
$data['commission'] = $pay['commission'];
$data['priceView'] = $priceDiscount;
$data['desc'] = "Оплата заказа №{$data['orderId']} для магазина ". cItemUrl;
$data['sendUrl'] = cUrl::get('/user/order/pay/send/', $orderId, $modul);
$data['successUrl'] = cUrl::get('/user/order/success/', $modul);
$data['failUrl'] = cUrl::get('/user/order/fail/', $modul);
$data['resulUrl'] = cUrl::get('/user/order/result/', $modul);


list(, $email) = cConvert::unserialize($order['data']);
$data['orderEmail'] = $email['E-mail'];

cLoader::library('payment/cmfPayment');
if(!cmfPayment::start($modul, $data)) {
    return 404;
}

?>