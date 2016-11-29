<?php


class product_param_modul extends driver_modul_edit_param {

	protected function init() {
		parent::init();

		$this->setDb('product_param_db');
	}

}

?>