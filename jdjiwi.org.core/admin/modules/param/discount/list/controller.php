<?php


class param_discount_list_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->initModul('main',	'param_discount_list_modul');

		// url
		$this->url()->setSubmit('/admin/param/discount/');
		$this->url()->setEdit('/admin/param/discount/edit/');
	}

	public function delete($id) {
		$id = cLoader::initModul('param_discount_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>