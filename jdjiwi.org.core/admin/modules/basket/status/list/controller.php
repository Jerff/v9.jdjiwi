<?php


class basket_status_list_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->initModul('main',	'basket_status_list_modul');

		// url
		$this->url()->setSubmit('/admin/basket/status/');
		$this->url()->setEdit('/admin/basket/status/edit/');
	}

	public function delete($id) {
		$id = cLoader::initModul('basket_status_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>