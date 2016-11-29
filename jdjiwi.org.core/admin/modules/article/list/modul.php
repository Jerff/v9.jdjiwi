<?php


class article_list_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('article_list_db');

		// формы
		$form = $this->form()->get();
		$form->add('isMain',		new cmfFormCheckbox());
		$form->add('visible',		new cmfFormCheckbox());
	}

    protected function saveStart(&$send) {
		parent::saveStart($send);
		if(isset($send['isMain'])) {
			$this->command()->reloadView();
		}
	}

}

?>