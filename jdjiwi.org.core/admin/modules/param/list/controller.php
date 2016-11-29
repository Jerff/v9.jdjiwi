<?php


class param_list_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->initModul('main',	'param_list_modul');

		// url
		$this->url()->setSubmit('/admin/param/');
		$this->url()->setEdit('/admin/param/edit/');
	}

	public function delete($id) {
		$id = cLoader::initModul('param_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>