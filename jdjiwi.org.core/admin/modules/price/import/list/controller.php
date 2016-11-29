<?php


class price_import_list_controller extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->initModul('main',	'price_import_list_modul');

		// url
		$this->url()->setSubmit('/admin/price/import/');
		$this->url()->setEdit('/admin/price/import/edit/');
	}

}

?>