<?php

cLoader::library('ajax/cControllerAjax');
cLoader::library('basket/cmfBasket');

class cUserEnter extends cControllerAjax {

    function __construct($name = '') {
        if (!$name)
            $name = cInput::get()->get('name');
        switch ($name) {
            case 'leftUserEnter':
                $name = 'leftUserEnter';
                break;

            case 'basket':
                $name = 'basket';
                break;

            default:
                $name = 'UserEnter';
                break;
        }
        $this->settings()->name($name);
        $url = cAjaxUrl . 'user/enter/?name=' . $name;
        parent::__construct($url);
    }

    protected function init() {
        $form = $this->form(1);

        $form->add('login', 'email')
                ->name('Email')->default('Ваш email')->isNoEmpty();
        $form->add('password', 'password')
                ->name('Пароль')->default('Пароль')->isNoEmpty();
//        $form->add('password2', 'password')
//                ->name('Пароль')->default('Пароль')->isNoEmpty()->confirmName('confirm');
    }

    public function run() {
        $data = $this->processing();
        pre($data);
        exit;

        $is = cRegister::getUser()->select($data['login'], $data['password']);
        if ($is) {
            $url = cInput::post()->get('url');
            $index = cUrl::get('/index/');
            if (strpos($url, $index) === 0) {

            } else
                switch ($this->get('type')) {
                    case 'basket':
                        $url = cUrl::get('/basket/');
                        break;

                    default:
                        $url = cUrl::get('/user/');
                        break;
                }
            cAjax::get()->redirect($url);
        } else {
            $this->form()->get()->setError('login', 'Неверен логин или пароль');
            $this->runEnd(true);
        }
    }

}

?>