<?php


class content_info_edit_controller extends driver_controller_edit_tree {

	protected function init() {
		parent::init();
		$this->initModul('main',	'content_info_edit_modul');

		// url
		$this->url()->setSubmit('/admin/content/info/edit/');
		$this->url()->setCatalog('/admin/content/info/');

        $this->siteUrl()->init(function($data) {
                    return array('/info/', $data->isUri);
                });
	}

}

?>