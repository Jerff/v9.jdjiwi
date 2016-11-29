<?php


class sms_inform_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->initModul('main',	'sms_inform_modul');

		// url
		$this->url()->setSubmit('/admin/sms/inform/');

		$this->access()->writeAdd('newLine');
	}

	public function delete($id) {
		parent::deleteList($id);
		return parent::delete($id);
	}

}

?>