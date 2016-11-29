<?php


class _seo_title_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->initModul('main',	'_seo_title_modul');

		// url
		$this->url()->setSubmit('/admin/seo/');

		$this->access()->writeAdd('delete');
	}

	public function getChangeUrl() {
		$opt = array('id'=>null);
		return $this->url()->getSubmit($opt);
	}

	public function delete($id=null) {
		parent::delete($this->id()->get());

		$js = '';
		$js2 = '';
		foreach($this->modulAll() as $modul) {
			$modul->resetForm();
			$js .= $modul->getJsSetData();
		}
		$this->ajax()->script($js);
	}
}

?>