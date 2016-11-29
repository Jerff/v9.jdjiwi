<?php


class _mail_var_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->initModul('main',	'_mail_var_modul');

		// url
		$this->url()->setSubmit('/admin/mail/var/');

		$this->access()->writeAdd('newLine');
	}


	public function delete($id) {
		parent::deleteList($id);
		return parent::delete($id);
	}

}

?>