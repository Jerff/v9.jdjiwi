<?php

cLoader::library('ajax/cControllerAjax');

class cSubscribeYes extends cControllerAjax {

    public function __construct($name = '') {
        if (!$name)
            $name = cInput::get()->get('name');
        switch ($name) {
            case 'leftSubscribeYes':
                $name = 'leftSubscribeYes';
                break;

            default:
                $name = 'SubscribeYes';
                break;
        }
        $this->settings()->name($name);
        $url = cAjaxUrl . 'subscribe/subscribeYes/?name=' . $name;
        parent::__construct($url);
    }

    protected function init() {
        $form = $this->form(1);
        if ($this->settings()->name === 'leftSubscribeYes') {
            $form->add('email', 'email')
                    ->name('Email')->default('Ваш email')->isNoEmpty()->isErrorHide();
        } else {
            $form->add('email', 'email')
                    ->name('Email')->default('Ваш email')->isNoEmpty();
        }

//        $form->add('email', 'float')
//                    ->name('Email')->default('Ваш email')->min(2)->max(5)->step(0.1);
//        cInput::post()->set('f993b98575fb0d92cc8212fc1f1afc44121393f90-1573270290-email', '32 432 5235,3535');
//        $form->get('email')->set('324325235.3535');
//        pre($form->get('email')->value());
//        $send = $form->processing()->result();
//        $form->update()->js();
//        pre($send, $form->get('email')->settings(), $form->get('email')->html(), cAjax::get());
//        exit;
//
//        $form->add('email', 'text')
//                    ->name('Email')->default('Ваш email')->range(2, 5);
//        cInput::post()->set('f993b98575fb0d92cc8212fc1f1afc44121393f90-1573270290-email', '32 432 5235,3535');
//        $form->get('email')->set('324325235.3535');
//        pre($form->get('email')->value());
//        $send = $form->processing()->result();
//        $form->update()->js();
//        pre($send, $form->get('email')->settings(), cAjax::get());
//        exit;
    }

    public function run() {
        $data = $this->processing();
        $email = $data['email'];

        $sql = cRegister::sql();
        if (!$sql->placeholder("SELECT 1 FROM ?t WHERE email=? AND subscribe='yes'", db_subscribe_mail, $email)
                        ->numRows()) {
            $data = array();
            $data['created'] = date('Y-m-d H:i:s');
            $data['type'] = '';
            $data['visible'] = 'no';
            $data['subscribe'] = 'no';
            $data['email'] = $email;
            $data['command'] = 'subscribeYes';
            $data['cod'] = $cod = cHashing::hash(rand(1, time()));
            $send = $data;

            $send['subscribeYesUrl'] = cUrl::get('/subscribe/command/', 'subscribeYes/email/' . urlencode($email) . '/cod/' . $cod);
            $sql->add(db_subscribe_mail, $data, array('email' => $email, 'AND', '1'));

            $cmfMail = new cmfMail();
            $cmfMail->sendTemplates('Рассылка: Запрос на подписку', $send, $email);

            $this->success('Запрос на подписку отправлен');
        } else {
            $this->form(1)->error()->set('email', 'Вы уже подписаны');
            $this->error();
        }
    }

    public function run1ok($email, $cod) {
        if (!$email or !$cod) {
            $this->runExit('error');
        }
        $sql = cRegister::sql();
        if ($sql->placeholder("SELECT 1 FROM ?t WHERE email=? AND command='subscribeYes' AND cod=?", db_subscribe_mail, $email, $cod)
                        ->numRows()) {
            $data = array();
            $data['visible'] = 'yes';
            $data['subscribe'] = 'yes';
            $data['command'] = '';
            $data['cod'] = '';
            $sql->add(db_subscribe_mail, $data, array('email' => $email));

            $send = array('email' => $email);
            $cmfMail = new cmfMail();
            $cmfMail->sendTemplates('Рассылка: Подписка завершена', $send, $email);
            $this->runExit('ok');
        } else {
            $this->runExit('error');
        }
    }

    protected function runExit($command) {
        $url = cUrl::get('/subscribe/command/', "subscribeYes/action/{$command}");
        cRedirect($url);
    }

}

?>