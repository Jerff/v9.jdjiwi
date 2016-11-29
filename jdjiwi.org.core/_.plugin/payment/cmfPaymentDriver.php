<?php


class cmfPaymentDriver {


    static public function percentageAdd($price, $commission) {
        return number_format($price * (1+$commission/100), 2, '.', '');
    }
    static public function percentageRemove($price, $commission) {
        return number_format($price / (1-$commission/100), 2, '.', '');
    }

    static public function addLog() {
        $data = func_get_args();
        $type = array_shift($data);
        $signature = array_pop($data);
        return cRegister::sql()->add(db_payment_log, array('type'=>$type,
                                                                'date'=>date('Y-m-d H:i:s'),
                                                                'data'=>serialize($data),
                                                                'signature'=>$signature));
    }

    static public function isTransactions($type, $transaction) {
        $is = cRegister::sql()->placeholder("SELECT 1 FROM ?t WHERE `type`=? AND `transaction`=?", db_payment_transactions, $type, $transaction)
                                    ->numRows();
        if($is) exit;
    }

    static public function resultOk($type, $orderId, $transaction, $sum, $data) {
        $sql = cRegister::sql();
        $order = $sql->placeholder("SELECT id, pay, isPay, data, user FROM ?t WHERE id=? AND status ?@ AND `delete`='no'", db_basket, $orderId, cmfOrder::getKeyStatusList(1))
								->fetchAssoc();
		if(!$order) exit;
		if($order['isPay']==='yes') exit;
        if($order['pay']<$sum) exit;

        list($email, $userData) = unserialize($order['data']);
        $userData['orderId'] = $orderId;
        $userData['adminUrl'] = cBaseAdminUrl .'#/basket/edit/&id='. $orderId;
        $userData['orderUrl'] = cUrl::get('/user/order/one/', $orderId);

        $cmfMail = new cmfMail();
		$cmfMail->sendType('basket', 'Корзина заказа: Заказ оплачен: сообщение менеджеру', $userData);
		$cmfMail->sendTemplates('Корзина заказа: Заказ оплачен: Сообщение клиенту', $userData, $email);

        $sql->add(db_payment_transactions, array('type'=>$type,
                                                 'date'=>date('Y-m-d H:i:s'),
                                                 'user'=>$order['user'],
                                                 'transaction'=>$transaction,
                                                 'order'=>$orderId));
        cmfControllerOrder::addOrderPayment($order['id'], $type, $transaction);
		$payData = $type.'<br />'. print_r(cConvert::objectToArray($data), true);
		$sql->add(db_basket, array('isPay'=>'yes', 'payData'=>$payData), $orderId);
    }

    static public function run($data) {
    }

    static public function result($type, $data) {
        exit;
    }

    static public function success() {
        return false;
    }

    static public function fail() {
        return false;
    }

    static function runCurl($url, $page, $post) {
        $headers = array("POST ".$page." HTTP/1.0",
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Content-length: ".mb_strlen($post));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    }

?>