<?php


class _seo_copyright_controller extends driver_controller_edit_param_of_record {

	protected function init() {
		parent::init();
		$this->initModul('main',	'_seo_copyright_modul');

		// url
		$this->url()->setSubmit('/admin/seo/copyright/');
	}


}

?>