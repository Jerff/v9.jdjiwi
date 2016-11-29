<?php


class _cache_template_controller extends driver_controller_edit {

	protected function init() {
		parent::init();

		// url
		$this->url()->setSubmit('/admin/cache/template/');

		$this->access()->writeAdd('clearFile');
	}

	protected function clearFile() {
        cDir::clear(cmfCompileModel);
        cDir::clear(cmfCompileController);
	}

}

?>