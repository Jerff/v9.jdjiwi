<?php


class price_import_list_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('price_import_list_db');
	}

}

?>