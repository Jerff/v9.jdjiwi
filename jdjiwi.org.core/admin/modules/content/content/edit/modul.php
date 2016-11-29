<?php


class content_content_edit_modul extends driver_modul_edit_tree {

	protected function init() {
		parent::init();

		$this->setDb('content_content_edit_db');

		// формы
		$form = $this->form()->get();
        $this->setNewPos();
        $form->add('parent',    new cmfFormSelectInt());

        $form->add('uri',       new cFormText(array('uri'=>45)));
		$form->add('url',		new cFormText());
		$form->add('isUrl',	    new cmfFormCheckbox());

        $form->add('name',        new cmfFormTextarea(array('!empty', 'max'=>150)));
        $form->add('content',    new cmfFormTextareaWysiwyng('content', $this->id()->get()));
        $form->add('visible',    new cmfFormCheckbox());

		$form->add('title',			new cmfFormTextarea());
		$form->add('keywords',		new cmfFormTextarea());
		$form->add('description',	new cmfFormTextarea());
	}

	protected function updateIsErrorData($data, &$isError) {
		$parent = $this->form()->get()->handlerElement('parent');
		if(!$parent and isset($data['uri'])) {
			$isError |= $this->updateIsErrorDataUri('/content/', $data['uri'], 'name', 25, array('parent'=>$parent));
		}
	}

	protected function saveStart(&$send) {
		parent::saveStart($send);

		$parent = $this->form()->get()->handlerElement('parent');
		parent::saveStartUri($send, 'name', 25, array('parent'=>$parent));
	}

}

?>