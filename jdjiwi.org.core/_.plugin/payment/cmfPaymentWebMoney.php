<?php


class cmfPaymentWebMoney extends cmfPaymentDriver {


    static private $purse = 'R692553029014';
    static private $secretKey = 'r0h8r7h8er8h78rhrh9rh0rng8rn2432';

    static public function run($data) {
        return true;
    }

    static public function result($type, $data) {
        /*$data = base64_decode('YToxMzp7czo4OiJMTUlfTU9ERSI7czoxOiIxIjtzOjE4OiJMTUlfUEFZTUVOVF9BTU9VTlQiO3M6ODoiMTE3MjcuMDAiO3M6MTU6IkxNSV9QQVlFRV9QVVJTRSI7czoxMzoiUjY5MjU1MzAyOTAxNCI7czoxNDoiTE1JX1BBWU1FTlRfTk8iO3M6MToiNCI7czoxMjoiTE1JX1BBWUVSX1dNIjtzOjEyOiI2MzQ3OTcwMjk4OTYiO3M6MTU6IkxNSV9QQVlFUl9QVVJTRSI7czoxMzoiUjY5MjU1MzAyOTAxNCI7czoxNToiTE1JX1NZU19JTlZTX05PIjtzOjM6IjEwOSI7czoxNjoiTE1JX1NZU19UUkFOU19OTyI7czozOiI0MzYiO3M6MTg6IkxNSV9TWVNfVFJBTlNfREFURSI7czoxNzoiMjAxMTA0MjEgMDU6MTY6MjUiO3M6ODoiTE1JX0hBU0giO3M6MzI6IkU2NTlERTY5QTUzOUFEMDkzQ0MxQ0NEM0FEQjIxRDQ0IjtzOjE2OiJMTUlfUEFZTUVOVF9ERVNDIjtzOjQzOiLO7+vg8uAg5+Dq4OfgILk0IOTr/yDs4OPg5+jt4CBkZW1vLnRmLWsub3JnIjtzOjg6IkxNSV9MQU5HIjtzOjU6InJ1LVJVIjtzOjEwOiJMTUlfREJMQ0hLIjtzOjM6IlNNUyI7fQ==');
        $_POST = unserialize($data);
        pre($_POST);*/

        self::addLog($type, 1, base64_encode(serialize($_POST)));
        if(get($_POST, 'LMI_PREREQUEST')==1) {
            $order = cRegister::sql()->placeholder("SELECT isPay FROM ?t WHERE id=? AND status ?@ AND `delete`='no'", db_basket, $_POST['LMI_PAYMENT_NO'], cmfOrder::getKeyStatusList(1))
								            ->fetchAssoc();
            if($order and $order['isPay']=='no') {
                echo 'yes';
            }
            exit;
        }

        $purse = self::$purse;
        $secretKey = self::$secretKey;
        $orderId = (int)$_POST['LMI_PAYMENT_NO'];
        $amount = (int)$_POST['LMI_PAYMENT_AMOUNT'];
        if($purse!=$_POST['LMI_PAYEE_PURSE']) exit;
        //if($_POST['LMI_MODE']==1) exit;

        $chkstring =  strtoupper(md5($_POST['LMI_PAYEE_PURSE'] . $_POST['LMI_PAYMENT_AMOUNT'] . $_POST['LMI_PAYMENT_NO'].
    		        $_POST['LMI_MODE'] . $_POST['LMI_SYS_INVS_NO'].$_POST['LMI_SYS_TRANS_NO'].$_POST['LMI_SYS_TRANS_DATE'].
    	            self::$secretKey . $_POST['LMI_PAYER_PURSE'].$_POST['LMI_PAYER_WM']));
        if($chkstring!=$_POST['LMI_HASH']) exit;

        $sum = self::percentageRemove($amount, $data['commission']);
        self::resultOk($type, $orderId, $orderId, $sum, $_POST);
    }

    static public function success() {
        return (int)get($_POST, "LMI_PAYMENT_NO");
    }
    static public function fail() {
        return (int)get($_POST, "LMI_PAYMENT_NO");
    }

    static public function view($data) {
        extract($data);

        $purse = self::$purse;
        $secretKey = self::$secretKey;
        $price = self::percentageAdd($data['price'], $data['commission']);
        $orderId = $data['orderId'];
?>
<form id="pay" method="POST" action="https://merchant.webmoney.ru/lmi/payment.asp" accept-charset="windows-1251">
  <input type="hidden" name="LMI_PAYMENT_AMOUNT" value="<?=$price ?>">
  <input type="hidden" name="LMI_PAYMENT_DESC" value="<?=$desc ?>">
  <input type="hidden" name="LMI_PAYMENT_DESC_BASE64" value="<?=base64_encode($desc) ?>">
  <input type="hidden" name="LMI_PAYMENT_NO" value="<?=$orderId ?>">
  <input type="hidden" name="LMI_PAYEE_PURSE" value="<?=$purse ?>">
  <input type="hidden" name="LMI_SIM_MODE" value="0">
</form>
<center>Сейчас вы перейдете на страницу оплаты...</center>
<script type="text/javascript">
cmf.getId('pay').submit();
</script>
<?
    }

}

?>