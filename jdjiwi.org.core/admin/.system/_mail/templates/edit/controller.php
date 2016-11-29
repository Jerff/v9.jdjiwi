<?php


class _mail_templates_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->initModul('main',	'_mail_templates_edit_modul');

		// url
		$this->url()->setSubmit('/admin/mail/templates/edit/');
		$this->url()->setCatalog('/admin/mail/templates/');
	}

	public function getEmailVar() {
        return cLoader::initModul('_mail_var_db')->getNameList(null, array('var'));
	}

}

?>