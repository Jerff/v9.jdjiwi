<?php


class _administrator_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->initModul('main',	'_administrator_edit_modul');
		$this->initModul('param',	'_administrator_edit_param_modul');

		// url
		$this->url()->setSubmit('/admin/administrator/edit/');
		$this->url()->setCatalog('/admin/administrator/');
	}

	public function delete($id) {
		cmfAdminModel::accesIs($id);
		return parent::delete($id);
	}

}

?>