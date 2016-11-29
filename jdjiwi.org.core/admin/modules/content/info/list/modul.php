<?php


class content_info_list_modul extends driver_modul_list_tree {

	protected function init() {
		parent::init();

		$this->setDb('content_info_list_db');

		// формы
		$form = $this->form()->get();
		$this->setNewPos();
		$form->add('visible',		new cmfFormCheckbox());
	}

}

?>