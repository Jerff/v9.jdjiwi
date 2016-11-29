<?php

cLoader::library('ajax/cControllerAjax');

class cmfFeedback extends cControllerAjax {

    function __construct($productId = 0, $name = '') {
        $this->set('$productId', $productId);
        if (!$name)
            $name = cInput::get()->get('userName');
        switch ($name) {
            case 'leftFeedback':
                $name = 'leftFeedback';
                break;

            default:
                $name = 'feedback';
                break;
        }
        $this->set('type', $name);
        $url = cAjaxUrl . '/form/feedback/?userName=' . $name . '&productId=' . $productId;
        $func = 'cmfAjaxSendForm';
        parent::__construct($url, $func);
    }

    public function getIdForm() {
        return $this->getName() . $this->get('type');
    }

    protected function init() {
        $form = $this->form()->get();
        $form->setOption('color', '#ff7a7a');
        $form->add('name', new cFormText(array('name' => 'Контактное лицо', '!empty', 'specialchars')));
        $form->add('email', new cFormText(array('name' => 'E-mail', '!empty', 'email', 'specialchars')));
        $form->add('notice', new cmfFormTextarea(array('name' => 'Вопрос', '!empty', 'specialchars', 'max' => 1000)));
        //$form->add('captcha',	new cmfFormKcaptcha());
    }

    public function run() {
        $data = $this->processing();

        $data2 = $this->form()->get()->processingName($data);
        $userMail = array();
        if ($this->get('$productId')) {
            $product = cRegister::sql()->placeholder("SELECT u.url, p.name FROM ?t p LEFT JOIN ?t u ON(u.product=p.id) WHERE u.product=p.id AND p.id=? AND visible='yes'", db_product, db_product_url, $this->get('$productId'))
                    ->fetchAssoc();
            $data2['======'] = ' ';
            $data2['Товар'] = $product['name'];
            $data2['Ссылка'] = cUrl::get('/product/', $product['url']);
        }

        $userMail['data'] = cConvert::arrayView($data2);


        $cmfMail = new cmfMail();
        $cmfMail->sendType('callback', 'Формы: Обратная Связь: Письмо менеджеру', $userMail);
        //$this->getForm()->get('captcha')->free();
        cAjax::get()->html($this->getIdForm(), '<b>Заявка&nbsp;отправлена</b>');
    }

    protected function runError($error = null) {
        parent::runError($error);
        cAjax::get()->script("cmf.main.fancybox.reView();");
    }

}

?>