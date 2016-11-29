<?php

class catalog_section_edit_controller extends driver_controller_edit_tree {

    protected function init() {
        parent::init();
        $this->initModul('main', 'catalog_section_edit_modul');

        // url
        $this->url()->setSubmit('/admin/catalog/section/edit/');
        $this->url()->setCatalog(
                '/admin/catalog/section/edit/', function(&$opt) {
                    $opt['id'] = $this->getFilter('parent');
                });

        $this->siteUrl()->init(function($data) {
                    return array('/section/', $data->isUri);
                });
    }

    public function delete($id) {
        cLoader::initModul('catalog_section_shop_controller')->deleteSection($id);
        $id = parent::delete($id);
        cLoader::initModul('product_edit_controller')->deleteSection($id);
        cLoader::initModul('param_group_notice_controller')->deleteSection($id);
        cLoader::initModul('param_group_select_controller')->deleteSection($id);
        return $id;
    }

    public function getNewUrl() {
        $opt = array('isList' => null);
        return parent::getNewUrl($opt);
    }

    public function &path() {
        $path = $this->modul()->path();
        $item_id = $this->id()->get();
        foreach ($path as $id => &$value)
            if ($item_id != $id)
                $value['url'] = $this->url()->getSubmit(array('id' => $id, 'isList' => 1, 'parentId' => null));

        $root = array('name' => 'Начало',
            'url' => $this->getRootUrl());
        array_unshift($path, $root);
        return $path;
    }

}

?>