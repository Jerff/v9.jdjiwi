<?php


class product_attach_product_modul extends driver_modul_list_product_attach {

	protected function init() {
		parent::init();

		$this->setDb('product_attach_product_db');
	}

}

?>