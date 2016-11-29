<?php


class _seo_counters_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->initModul('main',	'_seo_counters_edit_modul');

		// url
		$this->url()->setSubmit('/admin/seo/counters/edit/');
		$this->url()->setCatalog('/admin/seo/counters/');
	}

}

?>