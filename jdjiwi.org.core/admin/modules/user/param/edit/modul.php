<?php


class user_param_edit_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('user_param_edit_db');

		// формы
		$form = $this->form()->get();
	}

    public function loadForm() {
        parent::loadForm();
	}

}

?>