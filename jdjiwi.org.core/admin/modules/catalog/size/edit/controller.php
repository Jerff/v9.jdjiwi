<?php


class catalog_size_edit_controller extends driver_controller_edit_tree {

	protected function init() {
		parent::init();
		$this->initModul('main',	'catalog_size_edit_modul');

		// url
		$this->url()->setSubmit('/admin/catalog/size/edit/');
		$this->url()->setCatalog('/admin/catalog/size/');
	}

}

?>