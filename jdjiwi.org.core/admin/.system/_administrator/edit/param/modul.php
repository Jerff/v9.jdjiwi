<?php


class _administrator_edit_param_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('_administrator_edit_param_db');

		// формы
		$form = $this->form()->get();
	}

}

?>