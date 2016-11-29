<?php


class content_content_list_modul extends driver_modul_list_tree {

	protected function init() {
		parent::init();

		$this->setDb('content_content_list_db');

		// формы
		$form = $this->form()->get();
		$this->setNewPos();
		$form->add('visible',		new cmfFormCheckbox());
	}

}

?>