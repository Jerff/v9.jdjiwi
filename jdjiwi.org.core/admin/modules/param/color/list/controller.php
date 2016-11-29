<?php


class param_color_list_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->initModul('main',	'param_color_list_modul');

		// url
		$this->url()->setSubmit('/admin/param/color/');
		$this->url()->setEdit('/admin/param/color/edit/');
	}

	public function delete($id) {
		$id = cLoader::initModul('param_color_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>