<?php


class cmfPaymentLiqPAY extends cmfPaymentDriver {


    static private $merchantId = 'i4042678229';
    static private $curr = 'RUR';
    static private $hach1 = '5yKNQHWcZTVcKZ3BM0NDEX3RxctD';
    static private $hach2 = 'iANqsiY8Rijgq5weSJtk83iQTURmHWIMSPCjN';

    static public function run($data) {
        return true;
    }


    static public function result($type, $data) {
        $merchantId = self::$merchantId;
        $hach1 = self::$hach1;
        $hach2 = self::$hach2;
        if(empty($hech2)) $hech2 = $hech1;

        $currency = $_POST['payment_currency'];
	    $operation_xml = base64_decode($_POST['operation_xml']);
        $signature = $_POST['signature'];
        $xml = new SimpleXMLElement($operation_xml);

        $lqsignature = base64_encode(sha1($hach1 . $operation_xml . $hach1, 1));
        self::addLog($type, $currency, $operation_xml, $signature);
        if($signature!=$lqsignature) exit;
        if((string)$xml->status!='success') exit;
        if((string)$xml->merchant_id!=$merchantId) exit;

        $amount = (float)$xml->amount;
	    $orderId = (string)$xml->order_id;
        $transaction = (string)$xml->transaction_id;
        self::isTransactions($type, $transaction);


        $str = "<request>
    <version>1.2</version>
    <action>view_transaction</action>
    <merchant_id>{$merchantId}</merchant_id>
    <transaction_id>{$transaction}</transaction_id>
    <transaction_order_id>{$orderId}</transaction_order_id>
</request>";
        $operation_xml = base64_encode($str);
        $signature = base64_encode(sha1($hech2 . $str . $hech2, 1));
        $operation_envelop = "<operation_envelope>
    <operation_xml>{$operation_xml}</operation_xml>
    <signature>{$signature}</signature>
</operation_envelope>";
        $post = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<request>
    <liqpay>{$operation_envelop}</liqpay>
</request>";
        $url = "https://www.liqpay.com/?do=api_xml";
        $page = "/?do=api_xml";
        $result = self::runCurl($url, $page, $post);

        $result = new SimpleXMLElement($result);
        $result = base64_decode($result->liqpay->operation_envelope->operation_xml);
        $result = new SimpleXMLElement($result);
        $result = (array)$result->transaction;

        if($amount!=$result['amount']) exit;
        if('usd'!=strtolower($result['currency'])) exit;
        if('success'!=strtolower($result['status'])) exit;

        $sum = self::percentageRemove($amount, $data['commission']);
        self::resultOk($type, $orderId, $transaction, $sum, $xml);
    }

    static public function success() {
        if(isset($_POST['operation_xml'])) {
            $operation_xml = base64_decode($_POST['operation_xml']);
            $xml = new SimpleXMLElement($operation_xml);
            switch((string)$xml->status) {
                case 'success':     cGlobal::set('paymentMessage', 'Платеж прошел успешно'); break;
                case 'failure':     cGlobal::set('paymentMessage', 'Платеж не прошел');; break;
                case 'wait_secure': cGlobal::set('paymentMessage', 'Платеж в обработке'); break;
            }
            return (int)$xml->order_id;
        } return false;
    }


    static public function view($data) {
        extract($data);

        $merchantId = self::$merchantId;
        $hach1 = self::$hach1;
        $curr = self::$curr;
        $price = self::percentageAdd($data['price'], $data['commission']);
        $desc = cConvert::translate($data['desc']);
        $orderId = $data['orderId'] .'_'. time();

        $xml="<request>
<version>1.2</version>
<result_url>{$data['successUrl']}</result_url>
<server_url>{$data['resulUrl']}</server_url>
<merchant_id>{$merchantId}</merchant_id>
<order_id>{$orderId}</order_id>
<amount>{$price}</amount>
<currency>{$curr}</currency>
<description>{$desc}</description>
<default_phone></default_phone>
<pay_way>card</pay_way>
</request>
";

        $xmlEncoded = base64_encode($xml);
        $lqsignature = base64_encode(sha1($hach1 . $xml . $hach1 ,1));
?>
<form id="pay" action="https://www.liqpay.com/?do=clickNbuy" method="POST" accept-charset="windows-1251"/>
    <input name="operation_xml" type="hidden" value="<?=$xmlEncoded ?>">
    <input name="signature" type="hidden" value="<?=$lqsignature ?>">
</form>
<center>Сейчас вы перейдете на страницу оплаты...</center>
<script type="text/javascript">
cmf.getId('pay').submit();
</script>
<?
    }

}

?>