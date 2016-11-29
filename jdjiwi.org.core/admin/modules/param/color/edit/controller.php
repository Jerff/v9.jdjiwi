<?php


class param_color_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->initModul('main',	'param_color_edit_modul');

		// url
		$this->url()->setSubmit('/admin/param/color/edit/');
		$this->url()->setCatalog('/admin/param/color/');
	}

}

?>