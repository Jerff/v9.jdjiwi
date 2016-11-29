<?php

class content_pages_edit_controller extends driver_controller_edit {

    protected function init() {
        parent::init();
        $this->initModul('main', 'content_pages_edit_modul');

        // url
        $this->url()->setSubmit('/admin/content/pages/edit/');
        $this->url()->setCatalog('/admin/content/pages/');
    }

}

?>