<?php


class _seo_reklama_list_controller extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->initModul('main',	'_seo_reklama_list_modul');

		// url
		$this->url()->setSubmit('/admin/seo/reklama/');
		$this->url()->setEdit('/admin/seo/reklama/edit/');
	}

	public function delete($id) {
		$id = cLoader::initModul('_seo_reklama_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>