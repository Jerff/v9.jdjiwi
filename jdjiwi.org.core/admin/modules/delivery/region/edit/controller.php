<?php


class delivery_region_edit_controller extends driver_controller_edit_tree {

	protected function init() {
		parent::init();
		$this->initModul('main',	'delivery_region_edit_modul');

		// url
		$this->url()->setSubmit('/admin/delivery/region/edit/');
		$this->url()->setCatalog('/admin/delivery/region/edit/');
	}

	public function &path() {
		$path = $this->modul()->path();
		$item_id = $this->id()->get();
		foreach($path as $id=>&$value)
			if($item_id!=$id) $value['url'] = $this->url()->getSubmit(array('id'=>$id, 'isList'=>1, 'parentId'=>null));

		$root = array('name'=>'Начало',
					  'url'=>$this->getRootUrl());
		array_unshift($path, $root);
		return $path;
	}

}

?>