<?php


class content_static_edit_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('content_static_edit_db');

		// формы
		$form = $this->form()->get();

		$form->add('name',	new cmfFormTextarea(array('!empty', 'max'=>150)));
		$form->add('notice',	new cmfFormTextarea(array('!empty', 'max'=>10000)));
		$form->add('content',	new cmfFormTextareaWysiwyng('static', $this->id()->get()));
	}

}

?>