<?php


class cmfPaymentRobokassa extends cmfPaymentDriver {


    static private $login = 'tfk008';
    static private $curr = 'WMRM';
    static private $password1 = '8zM1V3XDG5jY4YEo1LzQ0VXryTSwYGBXVps7gsSa';
    static private $password2 = 'AnlWJj2zbcKsGjTHnVA2rrkrd56qUaO0bubHWPTQ';

    static public function run($data) {
        $mrh_login = self::$login;      // your login here
        $mrh_pass1 = self::$password1;   // merchant pass1 here

        // order properties
        $inv_id    = $data['orderId'];        // shop's invoice number
                               // (unique for shop's lifetime)
        $inv_desc  = urlencode($data['desc']);   // invoice desc
        $out_summ  = self::percentageAdd($data['price'], $data['commission']);   // invoice summ

        // build CRC value
        $crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1");

        $in_curr = self::$curr;

        // язык
        // language
        $culture = "ru";

        // e-mail пользователя
        $email = $data['orderEmail'];

        // build URL
        //$url = "https://merchant.roboxchange.com/Index.aspx?".
        $url = "http://test.robokassa.ru/Index.aspx?".
            "MrchLogin=$mrh_login&OutSum=$out_summ&InvId=$inv_id&Desc=$inv_desc&SignatureValue=$crc".
            "&IncCurrLabel=$in_curr&Email=$email&Culture=$culture";

        cRedirect($url);
        exit;
    }


    static public function result($type, $data) {
        cDebug::setError();
        cDebug::setSql();

        $out_summ = get($_POST, "OutSum");
        $transaction = $orderId = $inv_id = get($_POST, "InvId");
        $crc = get($_POST, "SignatureValue");

        $crc = strtoupper($crc);
        $mrh_pass2 = self::$password2;
        $my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass2"));
        self::addLog($type, $out_summ, $orderId, $crc);
        if ($my_crc !=$crc) {
            exit();
        }

        self::isTransactions($type, $transaction);
        $mrh_login = self::$login;
        $crc = md5("$mrh_login:$inv_id:$mrh_pass2");

        $url = "https://merchant.roboxchange.com/WebService/Service.asmx/OpState?MerchantLogin=$mrh_login&InvoiceID=$inv_id&Signature=$crc";
        $url = "http://test.robokassa.ru/Webservice/Service.asmx/OpState?MerchantLogin=$mrh_login&InvoiceID=$inv_id&Signature=$crc&StateCode=100";
        $res = file_get_contents($url);
        $xml = new SimpleXMLElement($res);

        $code = (int)$xml->Result->Code;
        if(!empty($code)) exit;
        $code = (int)$xml->State->Code;
        if($code!=100) exit;
        $OutCurrLabel = (string)$xml->Info->OutCurrLabel;
        if($OutCurrLabel!=self::$curr) exit;
        $sum = (int)$xml->Info->OutSum;
        //if($sum!=$out_summ) exit;

		$sum = self::percentageRemove($sum, $data['commission']);
		self::resultOk($type, $orderId, $orderId, $sum, $xml);
    }

    static public function success() {
        $mrh_pass1 = self::$password1;

        // чтение параметров
        // read parameters
        $out_summ = get($_POST, "OutSum");
        $inv_id = get($_POST, "InvId");
        $crc = get($_POST, "SignatureValue");

        $crc = strtoupper($crc);
        $my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass1"));

        // проверка корректности подписи
        // check signature
        if ($my_crc != $crc) {
            return 404;
        }
        return $inv_id;
    }

    static public function fail() {
        return get($_POST, "InvId");
    }

}

?>