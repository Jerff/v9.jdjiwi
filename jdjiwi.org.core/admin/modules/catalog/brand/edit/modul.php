<?php


class catalog_brand_edit_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('catalog_brand_edit_db');

		// формы
		$form = $this->form()->get();
		$this->setNewPos();

		$form->add('uri',		new cFormText(array('uri'=>25)));
		$form->add('name',	    new cmfFormTextarea(array('!empty', 'max'=>100)));
		$form->add('visible',	new cmfFormCheckbox());


		$form->add('title',			new cmfFormTextarea(array('max'=>500)));
		$form->add('keywords',		new cmfFormTextarea(array('max'=>500)));
		$form->add('description',	new cmfFormTextarea(array('max'=>500)));
	}

	protected function updateIsErrorData($data, &$isError) {
		if(isset($data['uri'])) {
			$isUri = $data['uri'];
			if($this->getDb()->isUrlExistsWhere($data['uri'])) {
				$this->form()->get()->setError('uri', 'Адрес "/'. $isUri .'/" уже занят!');
				$isError = true;
			}
		}
	}

	protected function saveStart(&$send) {
		parent::saveStart($send);

		parent::saveStartUri($send, 'name', 25);
	}

}

?>