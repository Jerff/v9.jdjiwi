<?php


class basket_status_list_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('basket_status_list_db');

		// формы
		$form = $this->form()->get();
		$this->setNewPos();
	}

}

?>