<?php


class basket_status_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->initModul('main',	'basket_status_edit_modul');

		// url
		$this->url()->setSubmit('/admin/basket/status/edit/');
		$this->url()->setCatalog('/admin/basket/status/');
	}

}

?>