<?php


class _seo_counters_list_controller extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->initModul('main',	'_seo_counters_list_modul');

		// url
		$this->url()->setSubmit('/admin/seo/counters/');
		$this->url()->setEdit('/admin/seo/counters/edit/');
	}

	public function delete($id) {
		$id = cLoader::initModul('_seo_counters_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>