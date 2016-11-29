<?php

cLoader::library('form/template/{form}/cFormInt{Form}');

class cFormInt extends cFormNumber {

    public function init() {
        $this->settings()->replace('isNumber', 'isInt');
        parent::init();
    }

}

?>