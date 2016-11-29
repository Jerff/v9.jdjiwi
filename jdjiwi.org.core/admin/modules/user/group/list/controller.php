<?php


class user_group_list_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->initModul('main',	'user_group_list_modul');

		// url
		$this->url()->setSubmit('/admin/user/group/');
		$this->url()->setEdit('/admin/user/group/edit/');

		$this->url()->set('user', '/admin/user/');
	}

	public function getUserUrl() {
		$opt = array('main'=>$this->getIndex());
		return $this->url()->get('user', $opt);
	}

/*	public function delete($id) {
		$id = cmfModulLoad('user_group_edit_controller')->delete($id);
		return parent::delete($id);
	}*/

}

?>