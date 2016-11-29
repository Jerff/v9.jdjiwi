<?php


class param_group_list_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('param_group_list_db');
	}

}

?>