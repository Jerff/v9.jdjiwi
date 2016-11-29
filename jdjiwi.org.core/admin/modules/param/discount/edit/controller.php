<?php


class param_discount_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->initModul('main',	'param_discount_edit_modul');

		// url
		$this->url()->setSubmit('/admin/param/discount/edit/');
		$this->url()->setCatalog('/admin/param/discount/');
	}

}

?>