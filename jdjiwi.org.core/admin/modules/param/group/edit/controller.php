<?php


class param_group_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->initModul('main',	'param_group_edit_modul');

		// url
		$this->url()->setSubmit('/admin/param/group/edit/');
		$this->url()->setCatalog('/admin/param/group/');
	}

	public function delete($id) {
		cLoader::initModul('param_group_notice_controller')->deleteGroup($id);
		cLoader::initModul('param_group_select_controller')->deleteGroup($id);
		return parent::delete($id);
	}

}

?>