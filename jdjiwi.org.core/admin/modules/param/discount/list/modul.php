<?php


class param_discount_list_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('param_discount_list_db');

		// формы
		$form = $this->form()->get();
		$form->add('visible',		new cmfFormCheckbox());
	}

}

?>