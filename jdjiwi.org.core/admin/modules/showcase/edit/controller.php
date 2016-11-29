<?php


class showcase_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->initModul('main',	'showcase_edit_modul');

		// url
		$this->url()->setSubmit('/admin/showcase/edit/');
		$this->url()->setCatalog('/admin/showcase/');
	}

}

?>