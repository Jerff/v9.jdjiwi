<?php


class payment_param_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->initModul('main',	'payment_param_modul');
		$this->initModul('edit',	'payment_param_edit_modul');

		// url
		$this->url()->setSubmit('/admin/basket/pay/param/');
		$this->url()->setCatalog('/admin/basket/pay/edit/');

		$this->access()->readAdd('onchangeCountry');
	}

}

?>