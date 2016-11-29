<?php

class basket_edit_modul extends driver_modul_edit {

    protected function init() {
        parent::init();

        $this->setDb('basket_edit_db');
    }

    protected function deleteBasket() {

    }

    protected function isEdit() {

        return true;
        $data = $this->getData();
        if (!isset($data['status'])) {
            $data = $this->runData();
        }
        if ($data['isPay'] === 'yes')
            return false;
        return (bool) cLoader::initModul('basket_status_edit_db')->getDataWhere(array('id' => $data['status'], 'AND', 'stop' => 1));
    }

    public function loadForm() {
        $form = $this->form()->get();
        if ($this->isEdit()) {
            cCommand::set('$is');
            $form->add('deliveryType', new cmfFormSelect());
            $form->addElement('deliveryType', '', 'Не выбрано');
            $form->addElement('deliveryType', 'russian-post', 'Почта-россии');
            $form->addElement('deliveryType', 'ems', 'EMS');
            $form->select('deliveryType', 'ems');

            $form->add('deliveryCod', new cFormText());
            $form->add('EMS', new cFormText());

            $form->add('isDelivery', new cmfFormCheckbox());
            $form->add('deliveryPrice', new cmfFormTextInt());
        }
    }

    protected function selectForm($data) {
        parent::selectForm($data);
        if (isset($data['data'])) {
            list(, $email, $userData, $userAdress, $userSubscribe) = unserialize($data['data']);
        }
    }

    public function updatePrice($isNew) {
        if (!$this->isEdit())
            return;

        $newList = array();
        $productList = cInput::post()->get('productList');
        $deleteList = cInput::post()->get('deleteList');
        if ($isNew) {
            $newId = (int) cInput::post()->get('product');
            $newParam = cInput::post()->get('param');
            $param = $this->getParamProduct($newId);
            foreach ((array) $newParam as $k => $v) {
                if (!isset($param[$k]))
                    unset($newParam[$k]);
            }
            if (empty($newParam) and empty($param)) {
                $newParam = array(0 => 'on');
            }

            $newColor = cInput::post()->get('color');
            $color = $this->getColorProduct($newId);
            foreach ((array) $newColor as $k => $v) {
                if (!isset($color[$k]))
                    unset($newColor[$k]);
            }
            if (empty($newColor)) {
                $newColor = array(0 => 'on');
            }

            if ($newParam and $newColor) {
                foreach ($newParam as $pId => $v) {
                    foreach ($newColor as $cId => $v) {
                        $newList[$newId][$pId][$cId] = 1;
                    }
                }
            }
        }

        cmfControllerOrder::updatePrice($this->id()->get(), $productList, $newList, $deleteList);
        $this->command()->reloadView();
        cCommand::set('$isEdit');
    }

    public function changeSection() {
        $section = (int) cInput::post()->get('section');
        $brand = (int) cInput::post()->get('brand');
        if (!$section) {
            $this->ajax()->html('productId', '')
                    ->html('paramId', '')
                    ->html('colorId', '');
        }

        $section = cLoader::initModul('catalog_section_list_db')->getIdList(array("(id='{$section}' OR path LIKE '%[{$section}]%')", 'AND', 'visible' => 'yes'));
        $where = array('section' => $section);
        if ($brand) {
            $where[] = 'AND';
            $where['brand'] = $brand;
        }
        $where[] = 'AND';
        $where['visible'] = 'yes';

        $product = cLoader::initModul('product_list_db')->getDataList($where);
        $html = '<select name="product" style="width:99%" onchange="modul.postAjax(\'changeProduct\');">
        <option>Выберите</option>';
        foreach ($product as $k => $v) {
            $html .= '<option value="' . $k . '">' . $v['name'] . '</option>';
        }
        $html .= '</select>';
        $this->ajax()->html('productId', $html)
                ->html('paramId', '')
                ->html('colorId', '');
    }

    public function changeProduct() {
        $product = (int) cInput::post()->get('product');
        if (!$product) {
            $this->ajax()->html('paramId', '')
                    ->html('colorId', '');
        }

        $html = '';
        foreach ($this->getParamProduct($product) as $k => $v) {
            $html .= '<label><input name="param[' . $k . ']" type="checkbox"> ' . $v . '</label>';
        }
        $this->ajax()->html('paramId', $html);

        $html = '';
        foreach ($this->getColorProduct($product) as $k => $v) {
            $html .= '<label><input name="color[' . $k . ']" type="checkbox"> ' . $v . '</label>';
        }
        $this->ajax()->html('colorId', $html);
    }

    public function getParamProduct($product) {
        $product = cLoader::initModul('product_list_db')->getDataRecord($product);
        $section = $product['section'];
        $basket = 0;
        while ($section) {
            list($section, $basket) = $this->sql()->placeholder("SELECT parent, basket FROM ?t WHERE id=?", db_section, $section)
                    ->fetchRow();
        }
        $param = $this->sql()->placeholder("SELECT value FROM ?t WHERE id=?", db_param, $basket)
                ->fetchRow(0);
        $param = cConvert::unserialize($param);
        $value = cConvert::unserialize($product['param']);

        $isPrice = !$product['price1'];
        $productPrice = cConvert::unserialize($product['paramPrice']);
        foreach ($param as $k => $v) {
            if (!isset($value[$basket][$k]) or
                    ($isPrice and empty($productPrice[$k]))) {
                unset($param[$k]);
            }
        }
        return $param;
    }

    public function getColorProduct($product) {
        $product = cLoader::initModul('product_list_db')->getDataRecord($product);
        $color = cConvert::pathToArray($product['colorAll']);
        return $this->sql()->placeholder("SELECT id, name FROM ?t WHERE id ?@ ORDER BY name", db_color, cConvert::pathToArray($product['colorAll']))
                        ->fetchRowAll(0, 1);
    }

    public function sendDeliveryStatusMail() {
        $data = $this->runData();
        list($userData, $email) = unserialize($data['data']);

        $userData['orderUrl'] = cUrl::get('/user/order/one/', $this->id()->get());
        $userData['orderId'] = $this->id()->get();
        $userData['EMS'] = $data['deliveryCod'];
        $cmfMail = new cmfMail();
        $cmfMail->sendTemplates('Корзина заказа: Cообщение клиенту о EMS номере заказа', $userData, $email);

        $this->getDb()->save(array('deliverySend' => 'yes'));
        $this->command()->reloadView();
    }

    public function sendEMSMail() {
        $data = $this->runData();
        list($userData, $email) = unserialize($data['data']);

        $userData['orderUrl'] = cUrl::get('/user/order/one/', $this->id()->get());
        $userData['orderId'] = $this->id()->get();
        $userData['EMS'] = $data['EMS'];
        $cmfMail = new cmfMail();
        $cmfMail->sendTemplates('Корзина заказа: Cообщение клиенту о EMS номере заказа', $userData, $email);

        $this->getDb()->save(array('EMSSend' => 'yes'));
        $this->command()->reloadView();
    }

    protected function processingForm($id) {
        $send = $this->form()->get()->processing($id);
        $data = $this->runData();
        $userRes = cConvert::unserialize($data['data']);
        $text = (array) cInput::post()->get('text');

        $isUpdate = false;
        $i = 0;
        for ($id = 2; $id <= 3; $id++)
            if (isset($userRes[$id]) and is_array($userRes[$id]))
                foreach ($userRes[$id] as $k => $v)
                    if (isset($text[$i])) {
                        if ($text[$i] != $v) {
                            $isUpdate = true;
                            $userRes[$id][$k] = $text[$i];
                        }
                        $i++;
                    }
        $send['data'] = cConvert::serialize($userRes);
        return $send;
    }

    protected function saveStart(&$send) {
        if (isset($send['EMS'])) {
            $send['EMSSend'] = 'no';
        }
        parent::saveStart($send);
    }

}

?>