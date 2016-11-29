<?php


class catalog_size_list_controller extends driver_controller_list_tree {

	protected function init() {
		parent::init();
		$this->initModul('main',	'catalog_size_list_modul');

		// url
		$this->url()->setSubmit('/admin/catalog/size/');
		$this->url()->setEdit('/admin/catalog/size/edit/');

	}

	public function delete($id) {
		$id = cLoader::initModul('catalog_size_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>