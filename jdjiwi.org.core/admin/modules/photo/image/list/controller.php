<?php


class photo_image_list_controller extends driver_controller_gallery_list {


	protected function init() {
		parent::init();
		$this->initModul('main',	'photo_image_list_modul');

		// url
		$this->url()->setSubmit('/admin/photo/image/');
		$this->url()->setEdit('/admin/photo/image/');
	}

	public function delete($id) {
		$is = $this->id()->get()==$id;
		$id = cLoader::initModul('photo_image_edit_controller')->delete($id);
		if($is) {
		    $this->ajax()->redirect($this->url()->getSubmit(array('id'=>null)));
		}
		return parent::delete($id);
	}

}

?>