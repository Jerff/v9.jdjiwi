<?php


class payment_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->initModul('main',	'payment_edit_modul');

		// url
		$this->url()->setSubmit('/admin/payment/edit/');
		$this->url()->setCatalog('/admin/payment/');
	}

}

?>