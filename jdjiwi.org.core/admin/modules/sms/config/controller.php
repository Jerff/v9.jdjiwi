<?php


class sms_config_modul extends driver_modul_edit_param_of_record {


    protected function init() {
        parent::init();

        $this->setDb('sms_config_db');

		// формы
		$form = $this->form()->get();
		$form->add('visible',	    new cmfFormCheckbox());
		$form->add('api',           new cmfFormSelect(array('!empty')));
		$form->add('login',         new cFormText(array('!empty', 'max'=>150)));
		$form->add('password',	    new cmfFormPassword(array('confirmName'=>'apiPasssword')));
		$form->add('password2',	    new cmfFormPassword(array('confirmName'=>'apiPasssword')));
        $form->add('sender',	    new cFormText(array('!empty', 'max'=>150)));
        $form->add('smsLimit',      new cmfFormTextInt());

	}

	public function loadForm() {
		$form = $this->form()->get();
		$form->addElement('api', '',  'Выберите');
        $form->addElement('api', 'prostor-sms.ru',  'prostor-sms.ru');
	}

}
class sms_config_controller extends driver_controller_edit_param_of_record {


	protected function init() {
		parent::init();
		$this->initModul('main',	'sms_config_modul');

		// url
		$this->url()->setSubmit(cPages::getMain());
	}

}
class sms_config_db extends driver_db_edit_param_of_record {

}

?>