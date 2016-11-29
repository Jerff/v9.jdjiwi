<?php


cLoader::library('ajax/cControllerAjax');
class cSubscribeNo extends cControllerAjax {

	public function __construct() {
        $url = cAjaxUrl .'subscribe/subscribeNo/?';
        parent::__construct($url);
    }


    protected function init() {
        $form = $this->form(1);
        $form->add('email', 'email')
                    ->name('Email')->default('Ваш email')->isNoEmpty();
    }

    public function run() {
        $data = $this->processing();
        $email = $data['email'];

        $sql = cRegister::sql();
        if($sql->placeholder("SELECT 1 FROM ?t WHERE email=? AND subscribe='yes'", db_subscribe_mail, $email)
                                    ->numRows()) {

            $data = array();
            $data['command'] = 'subscribeNo';
            $data['cod'] = $cod = sha1(rand(1, time()) . cSalt);
            $send = $data;

            $send['subscribeNoUrl'] = cUrl::get('/subscribe/command/', "subscribeNo/email/". urlencode($email) ."/cod/$cod");
            $send['email'] = $email;
//            pre($send);

            $cmfMail = new cmfMail();
            $cmfMail->sendTemplates('Рассылка: Запрос на отписку', $send, $email);
            $sql->add(db_subscribe_mail, $data, array('email'=>$email));

            $this->success('Запрос на отписку отправлен');
        } else {
            $this->form(1)->error()->set('email', 'Вы не подписаны');
            $this->error();
        }
    }


    public function run1ok($email, $cod) {
        if(!$email or !$cod) {
            $this->runExit('error');
        }
        $sql = cRegister::sql();
        if($sql->placeholder("SELECT 1 FROM ?t WHERE email=? AND command='subscribeNo' AND cod=?", db_subscribe_mail, $email, $cod)
                                    ->numRows()) {
            $sql->del(db_subscribe_mail, array('email'=>$email));

            $send = array('email'=>$email);
            $cmfMail = new cmfMail();
            $cmfMail->sendTemplates('Рассылка: Отписка завершена', $send, $email);
            $this->runExit('ok');
        } else {
            $this->runExit('error');
        }
    }


    protected function runExit($command) {
        $url = cUrl::get('/subscribe/command/', "subscribeNo/action/{$command}");
        cRedirect($url);
    }

}

?>