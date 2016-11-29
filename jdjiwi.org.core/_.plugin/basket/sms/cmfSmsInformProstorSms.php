<?php


class cmfSmsInformProstorSms {

    public function send($config, $phone, $message) {
        //$message = cmfString::translate($message);
        //pre($config, $phone, $message);


        $url = "http://{$config['login']}:{$config['password']}@gate.prostor-sms.ru/send/?phone=%2B{$phone}&text=". urlencode($message) ."&sender={$config['sender']}";
        //$url = "http://{$config['login']}:{$config['password']}@gate.prostor-sms.ru/status/?id=214821349";
        $res = file_get_contents($url);
        $res = explode('=', $res);
        if(get($res, 1)==='not enough credits') {
            $send = array('service'=>$config['api']);
            $cmfMail = new cmfMail();
            $cmfMail->sendType('basket', 'SMS оповещение: Баланс пуст', $send);
        }
//        pre($phone, $url, $res, $message);

//        214785263=accepted;
//        http://api_login:api_password@gate.prostor-sms.ru/status/?id=A132571BC

    }

}

?>