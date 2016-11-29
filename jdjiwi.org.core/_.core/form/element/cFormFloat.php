<?php

cLoader::library('form/template/{form}/cFormFloat{Form}');

class cFormFloat extends cFormNumber {

    public function init() {
        $this->settings()->replace('isNumber', 'isFloat');
        parent::init();
    }

}

?>