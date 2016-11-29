<?php


class delivery_region_list_controller extends driver_controller_list_one {

	protected function init() {
		parent::init();
		$this->initModul('main',	'delivery_region_list_modul');

		// url
		$this->url()->setSubmit('/admin/delivery/region/edit/');
		$this->url()->setEdit('/admin/delivery/region/edit/');
	}

	public function getNewUrl($opt=null) {
		$opt['id'] = null;
		$opt['select'] = $this->getFilter('parent');
		$opt['parent'] = null;
		$opt['isList'] = null;
		return $this->url()->getEdit($opt);
	}

	public function getChild() {
		$listId = $this->getDataId();
		return $this->sql()->placeholder('SELECT parent, count(id) FROM ?t WHERE parent ?@ GROUP BY parent', db_delivery_region, $listId)
								->fetchRowAll(0, 1);
	}

	public function delete($id) {
		$id = cLoader::initModul('delivery_region_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>