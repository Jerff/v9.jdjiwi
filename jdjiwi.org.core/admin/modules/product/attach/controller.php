<?php


class product_attach_controller extends product_list_controller {

    protected function init() {
        //parent::init();
        $this->initModul('main', 'product_attach_modul', false);
        $this->initModul('product', 'product_attach_product_modul');

        // url
        $this->url()->setSubmit('/admin/product/attach/');
        $func = function(&$opt) {
                    $opt['id'] = $this->getFilter('product1');
                    $opt['edit'] = null;
                };
        if ($this->getFilter('edit')) {
            $this->url()->setCatalog('/admin/product/edit/', $func);
        } else {
            $this->url()->setCatalog('/admin/product/', $func);
        }

        $this->access()->writeDel('delete');
        $this->access()->readAdd('changeFilter');
    }

    public function filterAttach() {
        $filter = array();
        $filter['all']['name'] = 'Все товары';
        $filter['attach']['name'] = 'Только привязанные';
        return parent::abstractFilter($filter, 'attach', 'reset');
    }

    public function filterDump() {
        $filter = array();
        $filter['all']['name'] = 'Все';
        $filter['yes']['name'] = 'В наличии';
        $filter['no']['name'] = 'Отсутсвующие';
        return parent::abstractFilter($filter, 'dump', 'yes');
    }

    public function nameLink() {
        $data = cLoader::initModul('product_edit_db')->getDataRecord($this->getFilter('product1'));
        return $data['name'];
    }

}

?>