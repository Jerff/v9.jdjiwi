<?php

class cFormCore extends cFormRegister {

    public function error() {
        $this->register('cFormError');
    }

    public function config() {
        $this->register('cmfFormConfig');
    }

}

?>