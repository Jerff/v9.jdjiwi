<?php


class payment_param_edit_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('payment_param_edit_db');

		// формы
		$form = $this->form()->get();
	}

}

?>