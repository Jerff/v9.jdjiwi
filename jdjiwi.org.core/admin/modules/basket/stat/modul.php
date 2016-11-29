<?php


class basket_stat_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('basket_stat_db');
	}

}

?>