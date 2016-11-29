<?php


class catalog_brand_list_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('catalog_brand_list_db');

		// формы
		$form = $this->form()->get();
		$this->setNewPos();
		$form->add('visible',		new cmfFormCheckbox());
	}

}

?>