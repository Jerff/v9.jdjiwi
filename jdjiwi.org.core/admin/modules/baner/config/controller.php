<?php


class baner_config_controller extends driver_controller_edit_param_of_record {


	protected function init() {
		parent::init();
		$this->initModul('main',	'baner_config_modul');

		// url
		$this->url()->setSubmit(cPages::getMain());
	}

}

?>