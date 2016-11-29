<?php


class _pages_access_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->initModul('main',	'_pages_access_modul', false);
		$this->initModul('read',	'_pages_access_read_modul');
		$this->initModul('write','_pages_access_write_modul');
		$this->initModul('delete','_pages_access_delete_modul');

		// url
		$this->url()->setSubmit('/admin/pages/access/');

		$this->access()->writeAdd('updatePageAccess');
	}

	public function getFormRead() {
		return $this->modul('read')->form()->get()->getId();
	}

	public function getFormWrite() {
		return $this->modul('write')->form()->get()->getId();
	}

	public function getFormDelete() {
		return $this->modul('delete')->form()->get()->getId();
	}


	protected function updatePageAccess() {
		cmfUpdatePages::start();
	}

}

?>