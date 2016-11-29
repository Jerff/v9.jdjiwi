<?php


class menu_list_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('menu_list_db');

		// формы
		$form = $this->form()->get();
		$this->setNewPos();
		$form->add('name',		new cFormText(array('max'=>150)));
		$form->add('url',		new cFormText(array('max'=>150)));
		$form->add('menu',		new cmfFormSelect());
		$form->add('visible',		new cmfFormCheckbox());
	}

	public function loadForm() {
		$form = $this->form()->get();
		model_menu::initMenu($form);
	}

}

?>