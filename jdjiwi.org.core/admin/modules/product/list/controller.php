<?php

class product_list_controller extends driver_controller_list {

    protected function init() {
        parent::init();
        $this->initModul('main', 'product_list_modul');

        // url
        $this->url()->setSubmit('/admin/product/');
        $this->url()->setEdit('/admin/product/edit/');
        $this->url()->set('attach', '/admin/product/attach/');

        $this->siteUrl()->init(function($data, $filter) {
                    return array('/product/', $filter->item('section', $data->section)->isUri . '/' . $data->uri);
                });

        $this->access()->readAdd('changeFilter');
    }

    public function changeFilter() {
        $opt = array();
        $opt['articul'] = cInput::post()->get('articul');
        if (!empty($opt['articul']))
            $opt['articul'] = trim($opt['articul']);
        $opt['price1'] = cConvert::toFloat(cInput::post()->get('price1'));
        $opt['price2'] = cConvert::toFloat(cInput::post()->get('price2'));
        $this->ajax()->redirect($this->url()->getSubmit($opt));
    }

    public function initSection() {
        $mFilter = cLoader::initModul('catalog_section_list_tree')->getNameList(null, array('isUri'));
        $this->set('$mSection', array_keys($mFilter));
        $mFilter[-1]['name'] = 'Без разделов';
        $mFilter[0]['name'] = 'Все разделы';
        $this->filter()->start($mFilter, 'section', 'end');
    }

    public function initBrand() {
        $mFilter = cLoader::initModul('catalog_brand_list_db')->getNameList();
        $mFilter[0]['name'] = 'Все';
        $this->filter()->start($mFilter, 'brand', 'end');
    }

    public function initFilter() {
        $mFilter = array();
        $mFilter['dumpYes']['name'] = 'В наличие';
        $mFilter['dumpNo']['name'] = 'Отсуствуют в наличии';
        $mFilter['visibleYes']['name'] = 'Показываются на сайте';
        $mFilter['visibleNo']['name'] = 'Не показываются на сайте';
        $mFilter['new']['name'] = 'Новинки';
        $mFilter['sale']['name'] = 'Распродажа';
        $mFilter['all']['name'] = 'Все';
        $this->filter()->start($mFilter, 'filter', 'start');
    }

    public function delete($id) {
        $id = cLoader::initModul('product_edit_controller')->delete($id);
        return parent::delete($id);
    }

    public function getProductUrl($opt = null) {
        $opt['product1'] = $this->id()->get();
        $opt['page'] = 1;
        return $this->url()->get('attach', $opt);
    }

    public function attachProduct() {
        return $this->sql()->placeholder("SELECT product1, count(product2) AS count FROM ?t WHERE product1 ?@ GROUP BY product1", db_product_attach, $this->getDataRecord())
                        ->fetchRowAll(0, 1);
    }

}

?>