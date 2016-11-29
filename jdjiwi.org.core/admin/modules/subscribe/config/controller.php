<?php


class subscribe_config_controller extends driver_controller_edit_param_of_record {

    protected function init() {
        parent::init();
        $this->initModul('main', 'subscribe_config_modul');

        // url
        $this->url()->setSubmit(cPages::getMain());
    }

}

class subscribe_config_modul extends driver_modul_edit_param_of_record {


	protected function init() {
		parent::init();

		$this->setDb('subscribe_config_db');

		// формы
		$form = $this->form()->get();
		$form->add('email',		    new cFormText(array('max'=>255, '!empty', 'email')));
		$form->add('mailMax',    	new cmfFormTextInt(array('min'=>25, 'max'=>50)));
	}

}


class subscribe_config_db extends driver_db_edit_param_of_record {

}

?>