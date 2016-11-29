<?php


class _profil_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->initModul('main',	'_profil_edit_modul');

		// url
		$this->url()->setSubmit('/admin/profil/');
		$this->url()->setCatalog('/admin/profil/');
	}

	public function delete($id) {
		return false;
	}

}

?>