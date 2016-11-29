<?php

cLoader::library('form/template/{form}/cFormPassword{Form}');

class cFormPassword extends cFormText {

    public function init() {
        parent::init();
        $this->setType('password');
        $this->filter()->add('confirmName', 'confirmName');
    }

    // вернуть значения формы - при пустом показываем значение по умолчанию
    public function value() {
        return $this->reform()->view($this->get());
    }

}

?>