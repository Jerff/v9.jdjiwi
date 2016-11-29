<?php


class basket_discount_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->initModul('main',	'basket_discount_modul');

		// url
		$this->url()->setSubmit('/admin/basket/discount/');

		$this->access()->writeAdd('newLine');
	}

}

?>