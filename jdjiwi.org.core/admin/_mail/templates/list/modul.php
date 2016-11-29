<?php


class _mail_templates_list_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('_mail_templates_list_db');

		// формы
		$form = $this->form()->get();
	}

}

?>