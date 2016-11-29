<?php


class dump_config_modul extends driver_modul_edit_param_of_record {


    protected function init() {
        parent::init();

        $this->setDb('dump_config_db');

		// формы
		$form = $this->form()->get();
		$form->add('run',	    new cmfFormCheckbox());
	}

}
class dump_config_controller extends driver_controller_edit_param_of_record {


	protected function init() {
		parent::init();
		$this->initModul('main',	'dump_config_modul');

		// url
		$this->url()->setSubmit(cPages::getMain());
	}

}
class dump_config_db extends driver_db_edit_param_of_record {

}

?>