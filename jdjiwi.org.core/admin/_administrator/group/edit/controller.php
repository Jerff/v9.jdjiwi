<?php


class _administrator_group_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->initModul('main',	'_administrator_group_edit_modul');

		// url
		$this->url()->setSubmit('/admin/administrator/group/edit/');
		$this->url()->setCatalog('/admin/administrator/group/');
	}

	public function delete($id) {
		$id = parent::delete($id);

		cLoader::initModul('_pages_access_read_db')->deleteAttach($id);
		cLoader::initModul('_pages_access_write_db')->deleteAttach($id);
		cLoader::initModul('_pages_access_delete_db')->deleteAttach($id);

		return $id;
	}

}

?>