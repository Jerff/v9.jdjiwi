<?php

class news_list_controller extends driver_controller_list {

    protected function init() {
        parent::init();
        $this->initModul('main', 'news_list_modul');

        // url
        $this->url()->setSubmit('/admin/news/');
        $this->url()->setEdit('/admin/news/edit/');

        $this->siteUrl()->init(function($data, $filter) {
                    return array('/news/', $filter->item('section', $data->info)->isUri . '/' . $data->uri);
                });
    }

    public function initSection() {
        $mFilter = cLoader::initModul('catalog_section_list_tree')->getNameList(null, array('isUri'));
        $this->set('$mSection', array_keys($mFilter));
        $mFilter[-1]['name'] = 'Без разделов';
        $mFilter[0]['name'] = 'Все разделы';
        $this->filter()->start($mFilter, 'section', 'end');
    }

    public function delete($id) {
        $id = cLoader::initModul('news_edit_controller')->delete($id);
        return parent::delete($id);
    }

}

?>