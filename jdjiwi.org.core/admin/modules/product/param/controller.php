<?php


class product_param_controller extends driver_controller_edit_param {

	protected function init() {
		parent::init();
		$this->initModul('main',	'product_param_modul');

		// url
		$this->url()->setSubmit('/admin/product/param/');
		$this->url()->setCatalog('/admin/product/edit/');

		$this->url()->set('select', '/admin/param/group/select/');
		$this->url()->set('notice', '/admin/param/group/notice/');
	}

	public function getSelectUrl() {
		$opt = array('parentId'=>cGlobal::get('$typeProduct'), 'page'=>null);
		return $this->url()->get('select', $opt);
	}

	public function getNoticeUrl() {
		$opt = array('parentId'=>cGlobal::get('$typeProduct'), 'page'=>null);
		return $this->url()->get('notice', $opt);
	}
}

?>