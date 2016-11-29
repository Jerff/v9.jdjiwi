<?php


class _mail_list_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('_mail_list_db');

		// формы
		$form = $this->form()->get();
		$form->add('userNew',		new cmfFormCheckbox());
		$form->add('basket',		new cmfFormCheckbox());
		$form->add('callback',		new cmfFormCheckbox());
		$form->add('name',			new cFormText(array('!empty', 'max'=>255)));
		$form->add('email',			new cFormText(array('!empty', 'max'=>255, 'email')));
	}

}

?>