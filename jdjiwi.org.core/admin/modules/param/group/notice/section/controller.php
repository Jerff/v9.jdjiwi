<?php


class param_group_notice_section_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->initModul('main',	'param_group_notice_section_modul');

		// url
		$this->url()->setSubmit('/admin/param/group/notice/');
		$this->url()->setCatalog('/admin/param/group/notice/');
	}

	public function delete($id) {
		return $id;
	}

}

?>