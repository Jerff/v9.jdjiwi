<?php


class subscribe_mail_list_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('subscribe_mail_list_db');

		// формы
		$form = $this->form()->get();
		$form->add('subscribe',		new cmfFormCheckbox());
	}

}

?>