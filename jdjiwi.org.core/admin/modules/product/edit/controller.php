<?php

class product_edit_controller extends driver_controller_edit_param {

    protected function init() {
        parent::init();
        $this->initModul('edit', 'product_edit_modul');
        $this->initModul('main', 'product_param_modul');

        // url
        $this->url()->setSubmit('/admin/product/edit/');
        $this->url()->setCatalog('/admin/product/');
        $this->url()->set('attach', '/admin/product/attach/');

        $this->siteUrl()->init(function($data) {
                    $secUri = cLoader::initModul('catalog_section_edit_db')->getFeildRecord('isUri', $data->section);
                    return array('/product/', $secUri . '/' . $data->uri);
                });
    }

    public function getProductUrl($opt = null) {
        $opt['product1'] = $this->id()->get();
        $opt['edit'] = 1;
        $opt['page'] = 1;
        return $this->url()->get('attach', $opt);
    }

    public function attachProduct() {
        return $this->sql()->placeholder("SELECT count(product2) AS count FROM ?t WHERE product1=?", db_product_attach, $this->id()->get())
                        ->fetchRow(0);
    }

    public function deleteSection($id) {
        $this->delete($id);
    }

    public function deleteBrand($id) {
        $where = array('brand' => $id);
        $this->delete($this->getListId($where));
        if ($where) {
            $this->modul()->getDb()->saveId($this->getListId($where), array('brand' => 0));
        }
    }

    public function delete($id) {
        cLoader::initModul('product_image_edit_controller')->deleteProduct($id);
        cLoader::initModul('product_param_db')->deleteProduct($id);

        $modul = cLoader::initModul('product_attach_product_db');
        $modul->deleteProduct($id);
        $modul->deleteAttach($id);

        return parent::delete($id);
    }

}

?>