<?php


class user_group_list_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('user_group_list_db');

		// формы
		$form = $this->form()->get();
		$this->setNewPos();
		$form->add('visible',		new cmfFormCheckbox());
	}

}

?>