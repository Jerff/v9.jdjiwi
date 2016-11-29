<?php


class param_group_filter_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->initModul('main',	'param_group_filter_modul');

		// url
		$this->url()->setSubmit('/admin/param/group/filter/');
		$this->url()->set('filter', '/admin/param/group/filter/');
	}

}

?>