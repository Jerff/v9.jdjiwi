<?php


class news_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->initModul('main',	'news_edit_modul');

		// url
		$this->url()->setSubmit('/admin/news/edit/');
		$this->url()->setCatalog('/admin/news/');

        $this->siteUrl()->init(function($data) {
                    return array('/news/', $data->uri);
                });
	}

}

?>