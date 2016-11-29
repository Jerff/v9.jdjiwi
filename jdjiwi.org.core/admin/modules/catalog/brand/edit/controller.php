<?php


class catalog_brand_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->initModul('main',	'catalog_brand_edit_modul');

		// url
		$this->url()->setSubmit('/admin/catalog/brand/edit/');
		$this->url()->setCatalog('/admin/catalog/brand/');
	}

	public function delete($id) {
		$id = parent::delete($id);
		cLoader::initModul('product_edit_controller')->deleteBrand($id);
		return $id;
	}

}

?>