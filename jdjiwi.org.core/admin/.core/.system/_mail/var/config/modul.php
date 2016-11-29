<?php


class _mail_var_config_modul extends driver_modul_edit {


	protected function init() {
		parent::init();

		$this->setDb('_mail_var_config_db');

		// формы
		$form = $this->form()->get();

		$form->add('login',		new cFormText(array('max'=>255, '!empty')));
		$form->add('password',	new cmfFormPassword(array('confirmName'=>'passsword')));
		$form->add('password2',	new cmfFormPassword(array('confirmName'=>'passsword')));
		$form->add('secure',	new cmfFormSelect(array('max'=>255)));
		$form->add('host',		new cFormText(array('max'=>255, '!empty')));
		$form->add('port',		new cFormText(array('max'=>255, '!empty')));
	}

	public function loadForm() {
		parent::loadForm();

		$form = $this->form()->get();
		$form->addElement('secure', '', 'Обычное');
		$form->addElement('secure', 'ssl', 'ssl');
		$form->addElement('secure', 'tls', 'tls');
	}

}

?>