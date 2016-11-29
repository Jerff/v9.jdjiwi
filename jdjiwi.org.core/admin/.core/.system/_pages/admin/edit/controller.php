<?php

class _pages_admin_edit_controller extends driver_controller_edit_tree {

    protected function init() {
        parent::init();
        $this->initModul('main', '_pages_admin_edit_modul');

        // url
        $this->url()->setSubmit('/admin/pages/admin/');
        $this->url()->setCatalog(
                '/admin/pages/admin/', function(&$opt) {
                    $opt['id'] = $this->getFilter('parent');
                });
        $this->access()->readAdd('onchangeModul');
    }

    protected function onchangeModul() {
        $this->modul()->onchangeModul();
    }

}

?>