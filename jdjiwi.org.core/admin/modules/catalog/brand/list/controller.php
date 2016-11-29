<?php


class catalog_brand_list_controller extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->initModul('main',	'catalog_brand_list_modul');

		// url
		$this->url()->setSubmit('/admin/catalog/brand/');
		$this->url()->setEdit('/admin/catalog/brand/edit/');
	}

	public function delete($id) {
		$id = cLoader::initModul('catalog_brand_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>