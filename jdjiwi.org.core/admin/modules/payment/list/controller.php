<?php


class payment_list_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->initModul('main',	'payment_list_modul');

		// url
		$this->url()->setSubmit('/admin/payment/');
		$this->url()->setEdit('/admin/payment/edit/');
	}

	public function delete($id) {
		$id = cLoader::initModul('payment_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>