<?php


class _seo_reklama_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->initModul('main',	'_seo_reklama_edit_modul');

		// url
		$this->url()->setSubmit('/admin/seo/reklama/edit/');
		$this->url()->setCatalog('/admin/seo/reklama/');
	}

}

?>