<?php


class _mail_var_config_controller extends driver_controller_edit {

	protected function init() {
		$this->setIdName('config');
		parent::init();
		$this->initModul('main',	'_mail_var_config_modul');

		// url
		$this->url()->setSubmit('/admin/mail/var/');
	}

}

?>