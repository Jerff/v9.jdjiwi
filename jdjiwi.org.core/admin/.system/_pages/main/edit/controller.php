<?php

class _pages_main_edit_controller extends driver_controller_edit_tree {

    protected function init() {
        parent::init();
        $this->initModul('main', '_pages_main_edit_modul');

        // url
        $this->url()->setSubmit('/admin/pages/main/');
        $this->url()->setCatalog(
                '/admin/pages/main/', function(&$opt) {
                    $opt['id'] = $this->getFilter('parent');
                });
    }

}

?>