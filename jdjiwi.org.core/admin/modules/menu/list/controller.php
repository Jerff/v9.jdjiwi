<?php


class menu_list_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->initModul('main',	'menu_list_modul');

		// url
		$this->url()->setSubmit('/admin/menu/');

		$this->access()->writeAdd('newLine');
	}


	public function delete($id) {
		parent::deleteList($id);
		return parent::delete($id);
	}

}

?>