<?php


abstract class driver_modul_gallery_list extends driver_modul_list {


	protected function init() {
		parent::init();

		// формы
		$form = $this->form()->get();
		$this->setNewPos();
		$form->add('visible',		new cmfFormCheckbox());
	}

	protected function saveStart(&$send) {
		if(isset($send['main']) or isset($send['pos'])) {
    		$this->command()->reloadView();
        }
		parent::saveStart($send);
	}

}

?>