<?php


class product_image_edit_controller extends driver_controller_gallery_edit {

	protected function init() {
		parent::init();
		$this->initModul('main',	'product_image_edit_modul');

		// url
		$this->url()->setSubmit('/admin/product/image/');
		$this->url()->setCatalog('/admin/product/image/');
	}

	public function deleteProduct($id) {
		$old = cAdmin::menu()->sub()->getId();
		cAdmin::menu()->sub()->setId($id);
		$this->delete($this->getListId(array('product'=>$id)));
		cAdmin::menu()->sub()->setId($old);
	}

}

?>