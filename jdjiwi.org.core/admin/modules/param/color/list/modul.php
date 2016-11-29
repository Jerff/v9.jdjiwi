<?php


class param_color_list_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('param_color_list_db');

		// формы
		$form = $this->form()->get();
		$form->add('visible',		new cmfFormCheckbox());
	}

}

?>