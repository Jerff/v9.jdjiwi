<?php


class price_import_edit_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('price_import_edit_db');

		// формы
		$form = $this->form()->get();
	}

}

?>