<?php


class _mail_var_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('_mail_var_db');

		// формы
		$form = $this->form()->get();
		$form->add('var',	new cFormText(array('max'=>255, '!empty')));
		$form->add('name',	new cFormText(array('max'=>255, '!empty')));
		$form->add('value',	new cFormText(array('max'=>255, '!empty')));
	}

}

?>