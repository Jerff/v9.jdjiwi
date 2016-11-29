<?php


class delivery_delivery_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->initModul('main',	'delivery_delivery_modul');

		// url
		$this->url()->setSubmit('/admin/delivery/');

		$this->access()->writeAdd('newLine');
	}

	public function delete($id) {
		parent::deleteList($id);
		return parent::delete($id);
	}

}

?>