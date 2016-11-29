<?php

class showcase_list_controller extends driver_controller_list {

    protected function init() {
        parent::init();
        $this->initModul('main', 'showcase_list_modul');

        // url
        $this->url()->setSubmit('/admin/showcase/');
        $this->url()->setEdit('/admin/showcase/edit/');

        $this->siteUrl()->init(function($data, $filter) {
                    return array('/showcase/', $data->uri);
                });
    }

    public function delete($id) {
        $id = cLoader::initModul('showcase_edit_controller')->delete($id);
        return parent::delete($id);
    }

}

?>