<?php

cLoader::library('ajax/cControllerAjax');
cLoader::library('user/cmfUserRegister');
cLoader::library('basket/cmfOrder');
cLoader::library('subscribe/cmfSubscribe');

class cmfBasketAdress extends cControllerAjax {

    function __construct($formUrl = null, $func = null) {
        if (empty($formUrl))
            $formUrl = cAjaxUrl . '/basket/adress/?';
        if (empty($func))
            $func = 'cmfAjaxSendForm';
        parent::__construct($formUrl, $func, 6);
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
        $form->add('name', new cFormText(array('!empty', 'name' => 'Имя', 'specialchars', 'max' => 250)));
        $form->add('family', new cFormText(array('!empty', 'name' => 'Фамилия', 'specialchars', 'max' => 250)));
        $form->add('email', new cFormText(array('!empty', 'name' => 'E-mail', 'email', 'min' => 4, 'max' => 100)));
        $form->add('cod', new cFormText(array('!empty', 'errorHide1', 'int', 'min' => 4, 'max' => 4)));
        $form->add('phone', new cFormText(array('!empty', 'errorHide1', 'name' => 'Телефон', 'int', 'min' => 7, 'max' => 7)));

        $form = $this->form()->get(2);
        $form->add('delivery', new cmfFormRadioInt(array('name' => 'Cпособ доставки', '!empty')));
        $res = cRegister::sql()->placeholder("SELECT id, name, isDelivery, isContact, notice, basket FROM ?t WHERE visible='yes' ORDER BY pos", db_delivery)
                ->fetchAssocAll('id');
        $delivery = $contact = $deliveryNotice = array();
        foreach ($res as $k => $v) {
            $form->addElement('delivery', $k, $v['name']);

            $delivery[$k] = $v['isDelivery'] === 'yes';
            $form->addOptions('delivery', $k, 'delivery', $delivery[$k] ? 'true' : 'false');

            $contact[$k] = $v['isContact'] === 'yes';
            $form->addOptions('delivery', $k, 'contact', $contact[$k] ? 'true' : 'false');

            $deliveryNotice[$k] = array('notice' => $v['notice'],
                'basket' => $v['basket']);
        }
        $this->set('$delivery', $delivery);
        $this->set('$contact', $contact);
        $this->set('$deliveryNotice', $deliveryNotice);

        $form = $this->form()->get(3);
        $form->add('adress', new cmfFormTextarea(array('!empty', 'name' => 'Адрес', 'specialchars', 'max' => 2000)));

        $form = $this->form()->get(4);
        $form->add('gorod', new cFormText(array('!empty', 'name' => 'Город', 'max' => 100)));
        $form->add('index', new cFormText(array('!empty', 'name' => 'Индекс', 'max' => 15)));

        $form = $this->form()->get(5);
        $form->add('notice', new cmfFormTextarea(array('name' => 'Пожелания', 'specialchars', 'max' => 1000)));

        $form = $this->form()->get(6);
        if ($this->set('$isSubscribe', !cRegister::getUser()->is()))
            foreach (cmfSubscribe::typeList() as $k => $v) {
                $form->add($k, new cmfFormCheckbox(array('name' => $v)));
            }
    }

    public function loadData() {
        $user = cRegister::getUser();
        $basket = new cmfBasket();
        if ($basket->isStep(2)) {
            foreach ($basket->getStep(2) as $data)
                if (is_array($data)) {
                    $this->form()->get(1)->select($data);
                    $this->form()->get(2)->select($data);
                    $this->form()->get(3)->select($data);
                    $this->form()->get(4)->select($data);
                    $this->form()->get(5)->select($data);
                    $this->form()->get(6)->select($data);
                    if (isset($data['delivery'])) {
                        $this->set('$isDelivery', $this->get2('$delivery', $data['delivery']));
                        $this->set('$isContact', $this->get2('$contact', $data['delivery']));
                    }
                }
        } else {
            $this->form()->get(1)->select($user->all);
            $this->form()->get(2)->select($user->all);
            $this->form()->get(3)->select($user->all);
            $this->form()->get(4)->select($user->all);
            $this->form()->get(5)->select($user->all);
            $this->form()->get(6)->select($user->all);
            if ($this->get('$isSubscribe'))
                cmfSubscribe::selectForm($this->form()->get(6), $user->getId(), $user->email);
        }
    }

    protected function processing() {
        $r = cRegister::request();

        $isError = $isUpdate = $isDelivery = $isContact = false;
        $data = array();
        foreach ($this->form()->all() as $i => $form) {
            if (!$isDelivery and ($i == 3 or $i == 4)) {
                $data[] = array();
                continue;
            } elseif (!$isContact and $i == 4) {
                $data[] = array();
                continue;
            }
            $form->setRequest($r);
            $send = $form->handler();
            $isUpdate |= count($send);
            $isError |= $form->isError();

            if ($i == 1) {
                if ($form->getErrorElement('cod') === $form->getErrorElement('phone')) {
                    $form->delError('cod');
                }
            }
            if ($i == 2) {
                if ($send['delivery']) {
                    $isDelivery = $this->get2('$delivery', (int) $send['delivery']);
                    $isContact = $this->get2('$contact', (int) $send['delivery']);
                } else {
                    $isDelivery = $isContact = true;
                }
                $send['isDelivery'] = $isDelivery;
                $send['isContact'] = $isContact;
                $send['dNotice'] = $this->get3('$deliveryNotice', (int) $send['delivery'], 'notice');
                $send['dBasket'] = $this->get3('$deliveryNotice', (int) $send['delivery'], 'basket');
            }
            $data[] = $send;
        }
        if (!$isError and $isUpdate) {
            return $data;
        }
        $this->runEnd(true);
    }

    public function run1() {
        list($userData, $form2, $form3, $form4, $form5, $form6) = $this->processing();

        $basket = new cmfBasket();
        if (!$basket->isOrder()) {
            cAjax::get()->redirect(cUrl::get('/basket/'));
        }

        $data = array_merge($userData, $form2, $form3, $form4, $form5, $form6);
        $data['phoneFull'] = $data['cod'] . $data['phone'];

        $delivery = array();
        if ($delivery['isDelivery'] = $data['isDelivery']) {
            $delivery['deliveryPrice'] = cRegister::sql()->placeholder("SELECT price FROM ?t WHERE id=? AND visible='yes'", db_delivery, $data['delivery'])
                    ->fetchRow(0);
            $basket->setStep('delivery', $delivery);
        } else {
            $basket->delStep('delivery');
        }
        $email = $userData['email'];
        $userData['phone'] = $userData['cod'] . '-' . $userData['phone'];
        $userData2 = $this->form()->get(1)->processingName($userData);
        $userAdress2 = array_merge($this->form()->get(2)->processingName($form2), $this->form()->get(4)->processingName($form4), $this->form()->get(3)->processingName($form3), $this->form()->get(5)->processingName($form5));
        $data['dName'] = $userAdress2['Cпособ доставки'];
        if (!empty($form2['dNotice'])) {
            $userAdress2['Cпособ доставки'] .= ' <small>(' . $form2['dNotice'] . ')</small>';
        }
        $userSubscribe2 = $this->form()->get(6)->processingName($form5);


        $basket->setStep(2, array($data, $email, $userData2, $userAdress2, $userSubscribe2));
        $basket->save();
        cAjax::get()->redirect(cUrl::get('/basket/pay/'));
    }

    protected function runEnd($error = false) {
        cAjax::get()->script("cmf.basket.scroll('#" . $this->getIdForm() . "');");
        parent::runEnd($error);
    }

}

?>