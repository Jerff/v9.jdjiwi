<?php


class cmfPaymentConfig {


    static function getList() {
        return array(''=>'Не выбрано',
                     'Cyberplat'=>'Cyberplat',
                     'Yandex'=>'Яндекс.Деньги',
                     'WebMoney'=>'WebMoney',
                     'TeleMoney'=>'TeleMoney',
                     'Sberbank'=>'Сбербанк',
                     'QIWI'=>'QIWI',
                     'LiqPAY'=>'LiqPAY',
                     'Robokassa'=>'Робокасса');
    }
    
}

?>