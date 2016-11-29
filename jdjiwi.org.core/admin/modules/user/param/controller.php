<?php


class user_param_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->initModul('main',	'user_param_modul');
		$this->initModul('edit',	'user_param_edit_modul');

		// url
		$this->url()->setSubmit('/admin/user/param/');
		$this->url()->setCatalog('/admin/user/edit/');

		$this->access()->readAdd('onchangeCountry');
	}

	public function getUserStat() {
		return cLoader::initModul('user_edit_db')->getUserStat($this->id()->get());
	}

	protected function onchangeCountry() {
		$this->modul()->onchangeCountry();
	}

}

?>