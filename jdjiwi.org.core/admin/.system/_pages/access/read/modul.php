<?php


class _pages_access_read_modul extends driver_modul_list_product_attach {

	protected function init() {
		parent::init();

		$this->setDb('_pages_access_read_db');
	}

}

?>