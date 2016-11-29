<?php


class param_group_edit_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('param_group_edit_db');

		// формы
		$form = $this->form()->get();
		$form->add('name',		new cmfFormTextarea(array('max'=>100, '!empty')));
	}

}

?>