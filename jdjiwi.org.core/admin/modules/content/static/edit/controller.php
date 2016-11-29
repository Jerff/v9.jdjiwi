<?php


class content_static_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->initModul('main',	'content_static_edit_modul');

		// url
		$this->url()->setSubmit('/admin/content/static/edit/');
		$this->url()->setCatalog('/admin/content/static/');
	}

}

?>