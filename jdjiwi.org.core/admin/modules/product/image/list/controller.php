<?php


class product_image_list_controller extends driver_controller_gallery_list {


	protected function init() {
		parent::init();
		$this->initModul('main',	'product_image_list_modul');

		// url
		$this->url()->setSubmit('/admin/product/image/');
		$this->url()->setEdit('/admin/product/image/');
	}

	public function delete($id) {
		$is = $this->id()->get()==$id;
		$id = cLoader::initModul('product_image_edit_controller')->delete($id);
		if($is) {
		    $this->ajax()->redirect($this->url()->getSubmit(array('id'=>null)));
		}
		return parent::delete($id);
	}

	public function getColor($color) {
		static $_color = array();
		if(!$_color) {
			$_color = cLoader::initModul('param_color_list_db')->getNameList(null, array('color'));
		}
		$new = array();
		foreach(cConvert::pathToArray($color) as $id) {
            if(isset($_color[$id])) {
                $new[] = $_color[$id]['color'];
            }
		}
		return $new;
	}

}

?>