<?php


class _seo_reklama_list_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('_seo_reklama_list_db');

		// формы
		$form = $this->form()->get();
		$this->setNewPos();
		$form->add('visible',		new cmfFormCheckbox());
	}

}

?>