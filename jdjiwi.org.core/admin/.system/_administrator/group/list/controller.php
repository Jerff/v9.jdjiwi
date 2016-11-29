<?php


class _administrator_group_list_controller extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->initModul('main',	'_administrator_group_list_modul');

		// url
		$this->url()->setSubmit('/admin/administrator/group/');
		$this->url()->setEdit('/admin/administrator/group/edit/');

		$this->url()->set('user', '/admin/administrator/');
	}

	protected function getLimit() {
		return 1000;
	}

	public function getUserUrl() {
		$opt = array('admin'=>$this->getIndex());
		return $this->url()->get('user', $opt);
	}

	public function delete($id) {
		$id = cLoader::initModul('_administrator_group_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>