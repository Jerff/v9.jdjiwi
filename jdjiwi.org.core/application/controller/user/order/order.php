<?php


$orderId = cGlobal::get('$orderId') ? cGlobal::get('$orderId') : cPages::param()->get(1);
$status = cmfOrder::getStatusList(0, 1, 2);
$order = cRegister::sql()->placeholder("SELECT id, EMS, deliveryDesc, user, product, data, isPay, isDelivery, registerDate, status, price FROM ?t WHERE id=? AND status ?@ AND `delete`='no'", db_basket, $orderId, array_keys($status))
								->fetchAssoc();
if(!$order) return 404;
if($order['user']) {
    $user = cRegister::getUser();
    $user->filterIsUser();
    if($order['user']!= $user->getId()) {
        return 404;
    }

    cmfMenu::add('Личные кабинет', cUrl::get('/user/'));
    cmfMenu::add('История заказов', cUrl::get('/user/order/'));
    cmfMenu::add('Заказ № '. $orderId);
} else {
    cLoader::library('user/cmfUserOrderView');
    if($isView = !cmfUserOrderView::isView($orderId)) {
        $this->assing('isView', $isView);
        $content = cRegister::sql()->placeholder("SELECT content FROM ?t WHERE name='Личный кабинет: Заказы: конфиденциальные данные'", db_content_static)
                            ->fetchRow(0);
        $this->assing('content', $content);

        $orderView = new cmfUserOrderView($orderId);
        $this->assing('orderView',	$orderView);
        $this->assing('form',			$orderView->form()->get());
    }
}


list($header, $_basket) = unserialize($order['product']);
$this->assing('header', $header);
$this->assing('_basket', $_basket);

list(, , $userData, $userAdress, $userSubscribe) = unserialize($order['data']);
$this->assing('userData', $userData);
$this->assing('userAdress', $userAdress);
if(!empty($order['EMS'])) {
    $this->assing('EMS', $order['EMS']);
}
$this->assing('userSubscribe', $userSubscribe);

list($countAll, $priceAll, $priceDiscount, $priceDelivery, $discount) = unserialize($order['price']);
$this->assing('countAll', $countAll);
$this->assing('priceAll', $priceAll);
$this->assing('priceDiscount', $priceDiscount);
$this->assing('discount', $discount);
if($order['isDelivery']==='yes')
    $this->assing('priceDelivery', $priceDelivery);

if(!empty($order['deliveryDesc']))
    $this->assing2('deliveryDesc', cConvert::unserialize($order['deliveryDesc']));

if(cGlobal::is('paymentMessage')) {
    $this->assing2('paymentMessage', cGlobal::get('paymentMessage'));
}
$this->assing('orderId', $orderId);
$this->assing2('orderUrl', cUrl::get('/user/order/one/', $orderId));
$this->assing('status', $status[$order['status']]);
$this->assing2('date', date('d.m.y H:i', strtotime($order['registerDate'])));



//$this->assing2('pay', $order['isPay']==='yes' ? 'оплачен' : 'не оплачен');
if($order['isPay']==='no') {
    $this->assing2('payUrl', cUrl::get('/user/order/pay/', $orderId));
}

?>