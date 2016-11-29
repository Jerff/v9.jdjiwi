<?php


class product_remains_modul extends product_list_modul {

	protected function init() {
		parent::init();

		$this->setDb('product_remains_db');
	}

}

?>