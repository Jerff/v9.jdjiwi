<?php


cLoader::library('payment/cmfPaymentDriver');
cLoader::library('payment/cmfPaymentConfig');
cLoader::library('order/cmfControllerOrder');

cLoader::library('payment/cmfPaymentSberbank');
cLoader::library('payment/cmfPaymentRobokassa');
cLoader::library('payment/cmfPaymentLiqPAY');
cLoader::library('payment/cmfPaymentWebMoney');
cLoader::library('payment/cmfPaymentQIWI');
cLoader::library('payment/cmfPaymentTeleMoney');
class cmfPayment {


    static private $modul = null;
    static private $data = null;
    static private function modul(&$modul) {
        if(!empty($modul) and class_exists('cmfPayment'. $modul)) {
            $modul = 'cmfPayment'. $modul;
            return true;
        }
        return false;
    }

    static public function start($modul, $data) {
        if(!self::modul($modul)) {
            return false;
        }
        self::$modul = $modul;
        self::$data = $data;
        call_user_func(array(self::$modul, 'run'), $data);
        return true;
    }

    static public function view() {
        call_user_func(array(self::$modul, 'view'), self::$data);
    }


    static public function result($modul, $data) {
        $name = $modul;
        if(!self::modul($modul)) {
            return 404;
        }
        call_user_func(array($modul, 'result'), $name, $data);
        exit;
    }

    static public function success($modul) {
        if(!self::modul($modul)) {
            return 404;
        }
        return call_user_func(array($modul, 'success'));
    }

    static public function fail($modul) {
        if(!self::modul($modul)) {
            return 404;
        }
        return call_user_func(array($modul, 'fail'));
    }

}

?>