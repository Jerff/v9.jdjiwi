<?php


class basket_list_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('basket_list_db');
	}

}

?>