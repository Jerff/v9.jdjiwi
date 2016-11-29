<?php

class _seo_sitemap_list_controller extends driver_controller_list_all {

    protected function init() {
        parent::init();
        $this->initModul('main', '_seo_sitemap_list_modul');

        // url
        $this->url()->setSubmit('/admin/seo/sitemap/');
        $this->access()->writeAdd('updateSitemap');
    }

    protected function updateSitemap() {
        cmfCronConfig::runModul('siteMap');
        $this->ajax()->alert('Обновление завершено');
    }

    public function delete($id) {
        parent::deleteList($id);
        return parent::delete($id);
    }

}

?>