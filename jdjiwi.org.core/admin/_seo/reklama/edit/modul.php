<?php


class _seo_reklama_edit_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('_seo_reklama_edit_db');

		// формы
		$form = $this->form()->get();
		$this->setNewPos();
		$form->add('name',	new cmfFormTextarea());
		$form->add('html',	new cmfFormTextarea());
		$form->add('uri',		new cmfFormTextarea());
		$form->add('type',	new cmfFormSelect());
		$form->add('visible',	new cmfFormCheckbox());
	}

	public function loadForm() {
		$form = $this->form()->get();
		$form->addElement('type', '',			'');
		$form->addElement('type', 'Реклама на странице',	'Реклама на странице');
	}

	protected function saveStart(&$send) {
		parent::saveStart($send);
		if(isset($send['uri'])) {
			$uri = cConvert::trim(explode("\n", trim($send['uri'])));
			$send['uri'] = cConvert::arrayToPath($uri);
		}
	}

}

?>