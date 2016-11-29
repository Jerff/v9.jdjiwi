<?php


class _config_cron_config_modul extends driver_modul_edit_param_of_record {


	protected function init() {
		parent::init();

		$this->setDb('_config_cron_config_db');

		// формы
		$form = $this->form()->get();
		//$form->add('time',	new cmfFormSelect());
	}

	public function loadForm() {
		parent::loadForm();
	}

}

?>