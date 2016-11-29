<?php


class _pages_access_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('_pages_access_db');
	}

}

?>