<?php

class modul_form_core {

    private $form = false;

    public function set($form, $lang) {
        $this->form = new cForm('', $form);
    }

    public function &get() {
        return $this->form;
    }

}

?>