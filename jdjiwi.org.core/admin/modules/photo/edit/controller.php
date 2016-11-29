<?php


class photo_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->initModul('main',	'photo_edit_modul');

		// url
		$this->url()->setSubmit('/admin/photo/edit/');
		$this->url()->setCatalog('/admin/photo/');

        $this->siteUrl()->init(function($data) {
                    return array('/photo/', $data->uri);
                });
	}

	public function delete($id) {
		cLoader::initModul('photo_image_edit_controller')->deleteProduct($id);
		return parent::delete($id);
	}

}

?>