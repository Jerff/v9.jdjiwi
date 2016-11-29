<?php


class user_group_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->initModul('main',	'user_group_edit_modul');

		// url
		$this->url()->setSubmit('/admin/user/group/edit/');
		$this->url()->setCatalog('/admin/user/group/');
	}

	public function delete($id) {
		return $id;
	}

}

?>