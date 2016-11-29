<?php


class dump_log_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('dump_log_db');

		// формы
		$form = $this->form()->get();
	}

}

?>