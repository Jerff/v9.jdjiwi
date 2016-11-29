<?php


class param_config_controller extends driver_controller_edit_param_of_record {


	protected function init() {
		parent::init();
		$this->initModul('main',	'param_config_modul');

		// url
		$this->url()->setSubmit(cPages::getMain());
	}

}

?>