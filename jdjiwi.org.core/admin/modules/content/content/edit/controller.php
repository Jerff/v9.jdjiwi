<?php


class content_content_edit_controller extends driver_controller_edit_tree {

	protected function init() {
		parent::init();
		$this->initModul('main',	'content_content_edit_modul');

		// url
		$this->url()->setSubmit('/admin/content/content/edit/');
		$this->url()->setCatalog('/admin/content/content/');

        $this->siteUrl()->init(function($data) {
                    return array('/content/', $data->isUri);
                });
	}

}

?>