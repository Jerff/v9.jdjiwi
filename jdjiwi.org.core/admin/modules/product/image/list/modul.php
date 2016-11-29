<?php


class product_image_list_modul extends driver_modul_gallery_list {

	protected function init() {
		parent::init();

		$this->setDb('product_image_list_db');
	}

}

?>