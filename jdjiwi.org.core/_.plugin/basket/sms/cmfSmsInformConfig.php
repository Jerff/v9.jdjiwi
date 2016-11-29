<?php


cLoader::library('basket/sms/cmfSmsInformProstorSms');
class cmfSmsInformConfig {


    static function getDriver($id) {
        $res = array('prostor-sms.ru'=>'cmfSmsInformProstorSms');
        if(isset($res[$id])) {
            return new $res[$id]();
        } else {
            return false;
        }
    }

}

?>