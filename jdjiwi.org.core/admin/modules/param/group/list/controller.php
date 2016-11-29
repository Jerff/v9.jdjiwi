<?php


class param_group_list_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->initModul('main',	'param_group_list_modul');

		// url
		$this->url()->setSubmit('/admin/param/group/');
		$this->url()->setEdit('/admin/param/group/edit/');

		$this->url()->set('select', '/admin/param/group/select/');
		$this->url()->set('notice', '/admin/param/group/notice/');
	}

	public function getSelectUrl() {
		$opt = array('parentId'=>$this->getIndex());
		return $this->url()->get('select', $opt);
	}

	public function getNoticeUrl() {
		$opt = array('parentId'=>$this->getIndex());
		return $this->url()->get('notice', $opt);
	}

	public function delete($id) {
		$id = cLoader::initModul('param_group_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>