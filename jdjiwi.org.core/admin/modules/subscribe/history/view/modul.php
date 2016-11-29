<?php


class subscribe_history_view_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('subscribe_history_view_db');

		// формы
		$form = $this->form()->get();
	}

}

?>