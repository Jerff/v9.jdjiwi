<?php


class _pages_access_delete_modul extends driver_modul_list_product_attach {

	protected function init() {
		parent::init();

		$this->setDb('_pages_access_delete_db');

		$form = $this->form()->get();
		//$form->add('object', new cmfFormText());
	}

}

?>