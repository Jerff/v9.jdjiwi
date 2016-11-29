<?php


class price_import_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->initModul('main',	'price_import_edit_modul');

		// url
		$this->url()->setSubmit('/admin/price/import/edit/');
		$this->url()->setCatalog('/admin/price/import/');
	}

	public function shop() {
		return cLoader::initModul('shop_edit_db')->getDataRecord($this->getFilter('shop'));
	}

	public function delete($id) {
		return $res;
	}

}

?>