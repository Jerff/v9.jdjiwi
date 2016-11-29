<?php


class cmfPaymentTeleMoney extends cmfPaymentDriver {

    static private $login = '100004456090';
    static private $key = '8zM1V3XDG5jY4YEo1LzQ0VXryTSwYGBXVps7gsSa';
    static public function run($data) {
        $view = cPages::param()->get(4);
        if($view!=='send') return true;

        $from = self::$login;
        $to = get($_POST, 'to');
        $com = urlencode(get($_POST, 'com'));
        $txn_id = $data['orderId'];
        $price = $data['price'];

        $url = "https://w.qiwi.ru/setInetBill_utf.do?from={$from}&to={$to}&summ={$price}&com={$com}&txn_id=$txn_id&check_agt=false&lifetime=0.25";

        cRedirect($url);
        // print URL if you need
        //echo "<a href='$url'>$url</a>";
        exit;
    }


    static public function result($type, $data) {
    	$time = get($_POST, 'TM_TIME');
    	$trans_id = get($_POST, 'TM_TRANSACTION');
    	$sum = get($_POST, 'TM_SUM');
    	$comment = get($_POST, 'TM_COMMENT');
    	$order_id = get($_POST, 'TM_EXTRA');
    	$status = get($_POST, 'TM_STATUS');
    	$hash = get($_POST, 'TM_HASH');
    	$is_test = get($_POST, 'TM_TEST');

    	$ref_hash = md5($time. ":". $trans_id .":". $sum .":". self::$key);
        if($hash!=$ref_hash) exit;

        if(!$is_test and $status == "confirmed") {
            $order = $sql->placeholder("SELECT id, pay, isPay FROM ?t WHERE id=? AND status ?@ AND `delete`='no'", db_basket, $orderId, array_keys($status))
    								->fetchAssoc();
    		if(!$order) exit;
    		if($order['isPay']==='yes') exit;
            if($order['pay']!=$sum) exit;

            if(cmfControllerOrder::addOrderPayment($order['id'], 'TeleMoney', $trans_id)) {
                $sql->add(db_basket, array('isPay'=>'yes'), $orderId);
            }
        }
    }

    static public function success() {
        return get($_POST, 'TM_EXTRA');
    }

    static public function fail() {
        return get($_POST, 'TM_EXTRA');
    }


    static public function view($data) {
        extract($data);
        $login = sprintf("%012d", self::$login);
        $price = number_format($price, 2, '.', '');

?>
<form id="transfer" method="POST" action="https://telemoney.ru/transfer">
	<input type="hidden" name="TM_TARGET" value="<?=$login ?>">
	<input type="hidden" name="TM_SUM" value="<?=$price ?>">
	<input type="hidden" name="TM_COMMENT" value="<?=cString::specialchars($desc) ?>">
	<input type="hidden" name="TM_EXTRA" value="<?=$orderId ?>">
	<input type="submit" value="Оплатить">
</form>
<script type="text/javascript">
cmf.getId('transfer').submit();
</script>
<?
    }

}

?>