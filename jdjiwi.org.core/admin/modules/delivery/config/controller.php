<?php


class delivery_config_modul extends driver_modul_edit_param_of_record {


    protected function init() {
        parent::init();

        $this->setDb('delivery_config_db');

		// формы
		$form = $this->form()->get();
		$form->add('basket',	    new cmfFormTextarea());
	}

}
class delivery_config_controller extends driver_controller_edit_param_of_record {


	protected function init() {
		parent::init();
		$this->initModul('main',	'delivery_config_modul');

		// url
		$this->url()->setSubmit(cPages::getMain());
	}

}
class delivery_config_db extends driver_db_edit_param_of_record {

}

?>