<?php


class param_discount_edit_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('param_discount_edit_db');

		// формы
		$form = $this->form()->get();

		$form->add('name',	    new cmfFormTextInt(array('!empty', 'max'=>100)));
		$form->add('image',	    new cmfFormFile(array('path'=>path_discount)));
		$form->add('visible',	new cmfFormCheckbox());
	}

}

?>