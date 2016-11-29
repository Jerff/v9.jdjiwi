<?php

cLoader::library('ajax/cControllerAjax');
cLoader::library('user/cmfUserRegister');
cLoader::library('basket/cmfOrder');
cLoader::library('subscribe/cmfSubscribe');

class cmfBasketSubscribe extends cControllerAjax {

    function __construct($url = null, $func = null) {
        if (empty($url))
            $url = cAjaxUrl . '/basket/subscribe/?';
        if (empty($func))
            $func = 'cmfAjaxSendForm';
        parent::__construct($url, $func);
    }

    public function get($n) {
        return parent::get($n);
    }

    public function get2($n, $n2) {
        return parent::get2($n, $n2);
    }

    public function get3($n, $n2, $n3) {
        return parent::get3($n, $n2, $n3);
    }

    protected function init() {
        $form = $this->form()->get();
        foreach (cmfSubscribe::typeList() as $k => $v) {
            $form->add($k, new cmfFormCheckbox(array('name' => $v)));
        }
    }

    public function loadData() {
        $basket = new cmfBasket();
        if ($basket->isStep(4)) {
            foreach ($basket->getStep(4) as $data)
                if (is_array($data)) {
                    $this->form()->get()->select($data);
                }
        } else {
            $user = cRegister::getUser();
            $this->form()->get()->select($user->all);
            if ($this->get('$isSubscribe'))
                cmfSubscribe::selectForm($this->form()->get(), $user->getId(), $user->email);
        }
    }

    public function run1() {
        $formSubscribe = $this->processing();

        $basket = new cmfBasket();
        if (!$basket->isOrder()) {
            cAjax::get()->redirect(cUrl::get('/basket/'));
        }

        $userSubscribe = $this->form()->get()->processingName($formSubscribe);

        $basket->setStep(4, array($formSubscribe, $userSubscribe));
        $basket->save();

        cAjax::get()->redirect(cUrl::get('/basket/pay/'));
    }

    protected function runEnd($error = false) {
        cAjax::get()->script("cmf.basket.scroll('#" . $this->getIdForm() . "');");
        parent::runEnd($error);
    }

}

?>