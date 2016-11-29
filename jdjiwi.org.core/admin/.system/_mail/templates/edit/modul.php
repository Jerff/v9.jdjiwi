<?php


class _mail_templates_edit_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('_mail_templates_edit_db');

		// формы
		$form = $this->form()->get();

		$form->add('name',	    new cmfFormTextarea(array('!empty', 'max'=>255)));
		$form->add('header',	new cmfFormTextarea(array('max'=>255)));
		$form->add('var',		new cmfFormTextarea());
		$form->add('content',	new cmfFormTextarea());
		$form->add('html',	    new cmfFormTextareaWysiwyng('mail/templates', $this->id()->get()));
	}

}

?>