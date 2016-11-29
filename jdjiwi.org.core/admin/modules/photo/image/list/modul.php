<?php


class photo_image_list_modul extends driver_modul_gallery_list {

	protected function init() {
		parent::init();

		$this->setDb('photo_image_list_db');
	}

}

?>