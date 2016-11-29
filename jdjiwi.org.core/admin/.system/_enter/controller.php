<?php

class _enter_controller extends driver_controller_edit {

    protected function init() {
        parent::init();
        $this->initModul('main', '_enter_modul');

        // url
        $this->url()->setSubmit('/admin/enter/');
    }

    public function run() {

    }

}

?>