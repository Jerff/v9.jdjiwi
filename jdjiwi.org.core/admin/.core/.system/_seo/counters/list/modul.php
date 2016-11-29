<?php


class _seo_counters_list_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('_seo_counters_list_db');

		// формы
		$form = $this->form()->get();
		$this->setNewPos();
		$form->add('main',		new cmfFormCheckbox());
		$form->add('visible',		new cmfFormCheckbox());
	}

}

?>