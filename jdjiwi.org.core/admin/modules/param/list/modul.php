<?php


class param_list_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('param_list_db');

		$form = $this->form()->get();
		$form->add('visible',	new cmfFormCheckbox());
	}

}

?>