<?php


class _seo_counters_edit_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('_seo_counters_edit_db');

		// формы
		$form = $this->form()->get();
		$this->setNewPos();

		$form->add('name',		new cmfFormTextarea(array('max'=>255, '!empty')));
		$form->add('counters',	new cmfFormTextarea());
		$form->add('main',		new cmfFormCheckbox());
		$form->add('visible',		new cmfFormCheckbox());
	}

}

?>