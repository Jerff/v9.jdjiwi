<?php

cLoader::library('form/template/{form}/cFormEmail{Form}');

class cFormEmail extends cFormText {

    public function init() {
        parent::init();
//        $this->setType('email');
        $this->settings()->isEmail();
    }

}

?>