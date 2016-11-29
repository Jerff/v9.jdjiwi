<?php


class showcase_edit_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('showcase_edit_db');

		// формы
		$form = $this->form()->get();
		$this->setNewPos();
		$form->add('name',		new cmfFormTextarea(array('max'=>255, '!empty')));
		$form->add('image',		new cmfFormFile(array('path'=>path_showcase, 'size'=>array(showcaseWidth, showcaseHeight))));
		$form->add('visible',	new cmfFormCheckbox());
	}

	protected function saveStart(&$send) {
		parent::saveStart($send);
		if(array_key_exists('image', $send)) {
		    $this->command()->reloadView();
		}
	}

}

?>