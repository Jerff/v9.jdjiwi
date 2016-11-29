<?php


class main_info_controller extends driver_controller_edit {

	function __construct($id=null) {
		$this->setIdName('main');
		parent::__construct($id);
	}

	protected function init() {
		parent::init();
		$this->initModul('main',		'main_info_modul');

		// url
		$this->url()->setSubmit(cPages::getMain());
	}

}

?>