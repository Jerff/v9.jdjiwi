<?php

cLoader::library('form/template/{form}/cFormRange{Form}');

class cFormRange extends cFormNumber {

    public function init() {
        parenet::init();
        $this->settings()->dataType('range');
    }

}

?>